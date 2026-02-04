<?php

namespace Controllers;

use Config\Database;
use PDO;
use Exception;
use Throwable;

class MenuCycleController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    private function getPaeIdFromToken()
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        if (preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            try {
                $decoded = \Utils\JWT::decode($matches[1]);
                if (is_object($decoded))
                    return $decoded->data->pae_id ?? null;
                if (is_array($decoded))
                    return $decoded['data']['pae_id'] ?? null;
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function index()
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            $query = "SELECT * FROM menu_cycles WHERE pae_id = :pae_id ORDER BY start_date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':pae_id', $pae_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store()
    {
        // Redirigir a generate si se prefiere
        $this->generate();
    }

    /**
     * POST /api/menu-cycles/generate
     * Genera un ciclo basado en parámetros avanzados (Rango, Días OFF, Modo)
     */
    public function generate()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $pae_id = $this->getPaeIdFromToken();

            $start_date = $data['start_date'];
            $end_date = $data['end_date']; // Ahora requerimos fecha fin explícita
            $name = $data['name'];
            $mode = $data['mode'] ?? 'ROTATIVE'; // ROTATIVE, RANDOM, MANUAL
            $template_id = $data['template_id'] ?? null;
            $exclude_weekends = $data['exclude_weekends'] ?? false;

            if (($mode === 'ROTATIVE' || $mode === 'RANDOM') && !$template_id) {
                throw new Exception("Se requiere una plantilla para el modo seleccionado.");
            }

            $this->conn->beginTransaction();

            // 1. Generar lista de fechas válidas
            $valid_dates = [];
            $current = new \DateTime($start_date);
            $end = new \DateTime($end_date);

            while ($current <= $end) {
                $day_of_week = $current->format('N'); // 1 (mon) to 7 (sun)

                $is_weekend = ($day_of_week == 6 || $day_of_week == 7);

                if ($exclude_weekends && $is_weekend) {
                    $current->modify('+1 day');
                    continue;
                }

                $valid_dates[] = [
                    'date' => $current->format('Y-m-d'),
                    'day_name' => $current->format('l')
                ];
                $current->modify('+1 day');
            }

            if (empty($valid_dates))
                throw new Exception("No hay días válidos en el rango seleccionado.");
            $total_days = count($valid_dates);

            // 2. Crear el ciclo (menu_cycles)
            $stmtCycle = $this->conn->prepare("INSERT INTO menu_cycles (pae_id, name, start_date, end_date, total_days, status) VALUES (?, ?, ?, ?, ?, 'BORRADOR')");
            $stmtCycle->execute([$pae_id, $name, $start_date, $end_date, $total_days]);
            $cycle_id = $this->conn->lastInsertId();

            // 3. Preparar pool de menús si aplica
            $pool = [];
            if ($mode !== 'MANUAL') {
                $stmtTemp = $this->conn->prepare("SELECT * FROM cycle_template_days WHERE template_id = ? ORDER BY day_number ASC");
                $stmtTemp->execute([$template_id]);
                $templateRaw = $stmtTemp->fetchAll(PDO::FETCH_ASSOC);

                if (!$templateRaw)
                    throw new Exception("La plantilla seleccionada está vacía.");

                // Agrupar por día relativo de la plantilla
                foreach ($templateRaw as $row) {
                    $pool[$row['day_number']][] = $row;
                }
                // Reindexar pool keys para facilitar acceso (0, 1, 2...)
                $pool = array_values($pool);
            }

            // 4. Asignar menús a días según modo
            foreach ($valid_dates as $index => $dateInfo) {
                // Crear el día (menu)
                // Usamos formato de fecha internacional para el nombre por defecto
                $menuName = "Menú del " . $dateInfo['date'];

                // Asegurar que insertamos pae_id y name (NOT NULL en schema)
                // Y asumimos que la columna 'date' será añadida mediante migración
                $stmtMenu = $this->conn->prepare("INSERT INTO menus (pae_id, cycle_id, name, date, day_number) VALUES (?, ?, ?, ?, ?)");
                $stmtMenu->execute([$pae_id, $cycle_id, $menuName, $dateInfo['date'], $index + 1]);
                $menu_id = $this->conn->lastInsertId();

                if ($mode === 'MANUAL') {
                    // No insertamos items
                    continue;
                }

                $poolIndex = 0;
                $poolSize = count($pool);

                if ($mode === 'ROTATIVE') {
                    // Módulo simple: 0, 1, 2... 0, 1...
                    $poolIndex = $index % $poolSize;
                } elseif ($mode === 'RANDOM') {
                    // Aleatorio simple
                    $poolIndex = rand(0, $poolSize - 1);
                }

                $selectedDayRecipes = $pool[$poolIndex];

                // Insertar items
                foreach ($selectedDayRecipes as $recipe_info) {
                    $stmtMenuItem = $this->conn->prepare("INSERT INTO menu_items (menu_id, recipe_id, meal_type) VALUES (?, ?, ?)");
                    $stmtMenuItem->execute([$menu_id, $recipe_info['recipe_id'], $recipe_info['meal_type']]);
                }
            }

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Ciclo generado correctamente', 'id' => $cycle_id]);
        } catch (Exception $e) {
            if ($this->conn->inTransaction())
                $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * GET /api/menu-cycles/{id}
     * Obtiene detalle del ciclo con sus días y menús asignados
     */
    public function show($id)
    {
        try {
            // 1. Info del Ciclo
            $stmt = $this->conn->prepare("SELECT * FROM menu_cycles WHERE id = ?");
            $stmt->execute([$id]);
            $cycle = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$cycle)
                throw new Exception("Ciclo no encontrado");

            // 2. Días (Menus)
            $query = "SELECT m.id, m.date, m.day_number, 
                             mi.recipe_id, mi.meal_type, r.name as recipe_name
                      FROM menus m
                      LEFT JOIN menu_items mi ON m.id = mi.menu_id
                      LEFT JOIN recipes r ON mi.recipe_id = r.id
                      WHERE m.cycle_id = ?
                      ORDER BY m.date ASC";

            $stmtItems = $this->conn->prepare($query);
            $stmtItems->execute([$id]);
            $rows = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            // Estructurar respuesta: Menus -> Items
            $menus = [];
            foreach ($rows as $row) {
                $menuId = $row['id'];
                if (!isset($menus[$menuId])) {
                    // Calcular nombre día (ej: Lunes)
                    $dt = new \DateTime($row['date']);
                    // Array simple para traducción o dejar en inglés
                    $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    // Sin tildes para evitar problemas de JSON encoding si el charset no es perfecto
                    $esDays = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
                    $dayIndex = $dt->format('w'); // 0 (sun) - 6 (sat)

                    $menus[$menuId] = [
                        'id' => $menuId,
                        'date' => $row['date'],
                        'day_number' => $row['day_number'],
                        'day_name' => $esDays[$dayIndex],
                        'items' => []
                    ];
                }
                if ($row['recipe_id']) {
                    $menus[$menuId]['items'][] = [
                        'recipe_id' => $row['recipe_id'],
                        'meal_type' => $row['meal_type'],
                        'recipe_name' => $row['recipe_name']
                    ];
                }
            }

            $cycle['menus'] = array_values($menus);

            // Debug: Asegurar que cycle no sea false por encoding
            $json = json_encode(['success' => true, 'data' => $cycle], JSON_UNESCAPED_UNICODE);
            if ($json === false) {
                throw new Exception("Error encoding JSON: " . json_last_error_msg());
            }
            echo $json;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * PUT /api/menu-cycles/{id}/items
     * Actualización masiva de items del ciclo (Guardado manual)
     */
    public function updateItems($id)
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $days = $data['days'] ?? []; // Array de { menu_id, items: [ {recipe_id, meal_type} ] }

            $this->conn->beginTransaction();

            // Iterar sobre los días modificados
            $stmtDel = $this->conn->prepare("DELETE FROM menu_items WHERE menu_id = ?");
            $stmtIns = $this->conn->prepare("INSERT INTO menu_items (menu_id, recipe_id, meal_type) VALUES (?, ?, ?)");

            foreach ($days as $dayChange) {
                $menuId = $dayChange['menu_id'];
                $items = $dayChange['items'];

                // 1. Borrar items previos de ese día
                $stmtDel->execute([$menuId]);

                // 2. Insertar nuevos items
                foreach ($items as $item) {
                    $stmtIns->execute([$menuId, $item['recipe_id'], $item['meal_type']]);
                }
            }

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Cambios guardados correctamente']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction())
                $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * DELETE /api/menu-cycles/{id}
     */
    public function delete($id)
    {
        // Debug ACTIVADO
        file_put_contents(__DIR__ . '/../../debug_data.php', date('Y-m-d H:i:s') . " - DELETE Method Entered for ID: $id\n", FILE_APPEND);

        header('Content-Type: application/json');

        try {
            // Verificar estado antes de borrar
            $stmt = $this->conn->prepare("SELECT status, is_validated FROM menu_cycles WHERE id = ?");
            $stmt->execute([$id]);
            $cycle = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cycle) {
                file_put_contents(__DIR__ . '/../../debug_data.php', " - Cycle $id NOT FOUND or already deleted.\n", FILE_APPEND);
                echo json_encode(['success' => true, 'message' => 'El ciclo no existe o ya fue eliminado']);
                return;
            }

            file_put_contents(__DIR__ . '/../../debug_data.php', " - Target found. Starting Transaction.\n", FILE_APPEND);
            $this->conn->beginTransaction();

            // 1. Delete menu items
            $stmt1 = $this->conn->prepare("DELETE FROM menu_items WHERE menu_id IN (SELECT id FROM menus WHERE cycle_id = ?)");
            $stmt1->execute([$id]);
            $deletedItems = $stmt1->rowCount();
            file_put_contents(__DIR__ . '/../../debug_data.php', " - Deleted $deletedItems menu_items.\n", FILE_APPEND);

            // 2. Delete menus
            $stmt2 = $this->conn->prepare("DELETE FROM menus WHERE cycle_id = ?");
            $stmt2->execute([$id]);
            $deletedMenus = $stmt2->rowCount();
            file_put_contents(__DIR__ . '/../../debug_data.php', " - Deleted $deletedMenus menus.\n", FILE_APPEND);

            // 3. Delete the cycle itself
            $stmtDel = $this->conn->prepare("DELETE FROM menu_cycles WHERE id = ?");
            $stmtDel->execute([$id]);
            $deletedCycle = $stmtDel->rowCount();
            file_put_contents(__DIR__ . '/../../debug_data.php', " - Deleted $deletedCycle menu_cycles.\n", FILE_APPEND);

            if ($deletedCycle === 0) {
                file_put_contents(__DIR__ . '/../../debug_data.php', " - CRITICAL: Cycle delete rowCount is 0! Rolling back.\n", FILE_APPEND);
                throw new Exception("No se pudo eliminar el registro del ciclo (Posible bloqueo o ID incorrecto)");
            }

            $this->conn->commit();
            file_put_contents(__DIR__ . '/../../debug_data.php', " - Transaction Committed.\n", FILE_APPEND);

            $res = json_encode(['success' => true, 'message' => 'Ciclo eliminado correctamente']);
            echo $res;

        } catch (Throwable $e) {
            if ($this->conn->inTransaction())
                $this->conn->rollBack();
            file_put_contents(__DIR__ . '/../../debug_data.php', " - ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
