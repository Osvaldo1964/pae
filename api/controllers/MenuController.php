<?php

namespace Controllers;

use Config\Database;
use PDO;
use Exception;

class MenuController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Obtener el ID del PAE desde el token JWT
     */
    private function getPaeIdFromToken()
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        if (preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            $token = $matches[1];
            try {
                $decoded = \Utils\JWT::decode($token);
                // Si el objeto decodificado tiene una propiedad 'data'
                if (is_object($decoded) && isset($decoded->data->pae_id)) {
                    return $decoded->data->pae_id;
                }
                // Si el objeto decodificado es un array (JWT::decode devuelve array en la versión actual)
                if (is_array($decoded) && isset($decoded['data']['pae_id'])) {
                    return $decoded['data']['pae_id'];
                }
                return null;
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * GET /api/menu-cycles - Listar ciclos de menús
     */
    public function getCycles()
    {
        try {
            $pae_id = $this->getPaeIdFromToken();

            // Si es Super Admin (pae_id null), ve todos. Si es Admin PAE, solo los suyos.
            $query = "SELECT * FROM menu_cycles" . ($pae_id ? " WHERE pae_id = :pae_id" : "") . " ORDER BY start_date DESC";
            $stmt = $this->conn->prepare($query);
            if ($pae_id)
                $stmt->bindParam(':pae_id', $pae_id);
            $stmt->execute();

            echo json_encode([
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * GET /api/menu-cycles/{id}/days - Obtener los menus organizado por días
     */
    public function getCycleDays($cycle_id)
    {
        try {
            // Obtener todos los menus del ciclo
            $query = "SELECT * FROM menus WHERE cycle_id = :cycle_id ORDER BY day_number, meal_type";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cycle_id', $cycle_id);
            $stmt->execute();
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Organizar por día para el frontend
            $days = [];
            foreach ($menus as $menu) {
                $dayNum = $menu['day_number'];
                if (!isset($days[$dayNum])) {
                    $days[$dayNum] = [
                        'day' => $dayNum,
                        'meals' => []
                    ];
                }
                $days[$dayNum]['meals'][] = $menu;
            }

            echo json_encode([
                'success' => true,
                'data' => array_values($days)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * GET /api/menus/{id} - Detalle de un menú con sus ingredientes
     */
    public function getMenuDetail($menu_id)
    {
        try {
            // 1. Datos básicos del menú
            $query = "SELECT * FROM menus WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $menu_id);
            $stmt->execute();
            $menu = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$menu) {
                throw new Exception("Menú no encontrado");
            }

            // 2. Ingredientes (Explosión de víveres)
            $query = "SELECT 
                        mi.*, 
                        i.name as item_name, 
                        i.code as item_code,
                        mu.abbreviation as unit,
                        fg.name as food_group
                      FROM menu_items mi
                      JOIN items i ON mi.item_id = i.id
                      JOIN measurement_units mu ON i.measurement_unit_id = mu.id
                      JOIN food_groups fg ON i.food_group_id = fg.id
                      WHERE mi.menu_id = :menu_id
                      ORDER BY mi.display_order";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':menu_id', $menu_id);
            $stmt->execute();
            $menu['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $menu
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * PUT /api/menus/{id} - Actualizar datos básicos de una minuta
     */
    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            $query = "UPDATE menus SET 
                        name = :name, 
                        meal_type = :meal_type,
                        day_number = :day_number,
                        age_group = :age_group
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':meal_type', $data['meal_type']);
            $stmt->bindParam(':day_number', $data['day_number']);
            $stmt->bindParam(':age_group', $data['age_group']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Minuta actualizada']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * POST /api/menus/{id}/items - Gestionar ingredientes de la minuta
     */
    public function manageItems($menu_id)
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $items = $data['items'] ?? [];

            $this->conn->beginTransaction();

            // 1. Limpiar ingredientes actuales
            $query = "DELETE FROM menu_items WHERE menu_id = :menu_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':menu_id', $menu_id, PDO::PARAM_INT);
            $stmt->execute();

            // 2. Agrupar ingredientes duplicados antes de insertar
            $groupedItems = [];
            foreach ($items as $item) {
                $id = $item['item_id'];
                if (isset($groupedItems[$id])) {
                    $groupedItems[$id]['quantity'] += floatval($item['quantity']);
                    // Concatenar preparaciones si son diferentes
                    if ($item['preparation'] && strpos($groupedItems[$id]['preparation'], $item['preparation']) === false) {
                        $groupedItems[$id]['preparation'] .= ", " . $item['preparation'];
                    }
                } else {
                    $groupedItems[$id] = [
                        'item_id' => $id,
                        'quantity' => floatval($item['quantity']),
                        'preparation' => $item['preparation']
                    ];
                }
            }

            // 3. Insertar nuevos ingredientes (Explosión de víveres)
            $query = "INSERT INTO menu_items (menu_id, item_id, standard_quantity, preparation_method) 
                      VALUES (:menu_id, :item_id, :quantity, :preparation)";
            $stmt = $this->conn->prepare($query);

            foreach ($groupedItems as $item) {
                $stmt->bindValue(':menu_id', $menu_id, PDO::PARAM_INT);
                $stmt->bindValue(':item_id', $item['item_id'], PDO::PARAM_INT);
                $stmt->bindValue(':quantity', $item['quantity']);
                $stmt->bindValue(':preparation', $item['preparation']);
                $stmt->execute();
            }

            // 4. Recalcular Totales Nutricionales en la tabla menus
            // Obtenemos el grupo etario del menú primero
            $stmtGroup = $this->conn->prepare("SELECT age_group FROM menus WHERE id = ?");
            $stmtGroup->execute([$menu_id]);
            $age_group = $stmtGroup->fetchColumn();

            $queryTotal = "UPDATE menus m 
                           SET 
                            total_calories = (SELECT IFNULL(SUM(mi.standard_quantity * i.calories / 100), 0) FROM menu_items mi JOIN items i ON mi.item_id = i.id WHERE mi.menu_id = :m1),
                            total_proteins = (SELECT IFNULL(SUM(mi.standard_quantity * i.proteins / 100) , 0) FROM menu_items mi JOIN items i ON mi.item_id = i.id WHERE mi.menu_id = :m2),
                            total_carbohydrates = (SELECT IFNULL(SUM(mi.standard_quantity * i.carbohydrates / 100), 0) FROM menu_items mi JOIN items i ON mi.item_id = i.id WHERE mi.menu_id = :m3),
                            total_fats = (SELECT IFNULL(SUM(mi.standard_quantity * i.fats / 100), 0) FROM menu_items mi JOIN items i ON mi.item_id = i.id WHERE mi.menu_id = :m4)
                           WHERE id = :id";

            // Si el menú tiene un grupo específico, podrías querer usar los gramajes de la receta para ese grupo.
            // Pero 'menu_items' actualmente guarda 'standard_quantity'. 
            // Si estuviéramos importando desde receta, deberíamos haber traído el gramaje correcto.

            $stmtTotal = $this->conn->prepare($queryTotal);
            $stmtTotal->bindValue(':m1', $menu_id, PDO::PARAM_INT);
            $stmtTotal->bindValue(':m2', $menu_id, PDO::PARAM_INT);
            $stmtTotal->bindValue(':m3', $menu_id, PDO::PARAM_INT);
            $stmtTotal->bindValue(':m4', $menu_id, PDO::PARAM_INT);
            $stmtTotal->bindValue(':id', $menu_id, PDO::PARAM_INT);
            $stmtTotal->execute();

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Ingredientes actualizados exitosamente y nutrición recalculada']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction())
                $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar ingredientes: ' . $e->getMessage()]);
        }
    }

    /**
     * POST /api/menu-cycles - Crear un nuevo ciclo y generar sus 20 días
     */
    public function storeCycle()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $pae_id = $this->getPaeIdFromToken();

            if (!$pae_id) {
                // Si es SuperAdmin y no envió pae_id, error
                $pae_id = $data['pae_id'] ?? null;
                if (!$pae_id)
                    throw new Exception("Debe especificar un programa PAE");
            }

            $this->conn->beginTransaction();

            // 1. Crear el Ciclo
            $query = "INSERT INTO menu_cycles (pae_id, name, description, start_date, end_date, status) 
                      VALUES (:pae_id, :name, :description, :start_date, :end_date, 'ACTIVO')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':pae_id' => $pae_id,
                ':name' => $data['name'],
                ':description' => $data['description'] ?? '',
                ':start_date' => $data['start_date'],
                ':end_date' => $data['end_date']
            ]);
            $cycle_id = $this->conn->lastInsertId();

            // 2. Generar 20 días automáticos (Desayuno y Almuerzo)
            $queryMenu = "INSERT INTO menus (pae_id, cycle_id, day_number, meal_type, name) 
                          VALUES (:pae_id, :cycle_id, :day, :type, :name)";
            $stmtMenu = $this->conn->prepare($queryMenu);

            for ($i = 1; $i <= 20; $i++) {
                // Desayuno
                $stmtMenu->execute([
                    ':pae_id' => $pae_id,
                    ':cycle_id' => $cycle_id,
                    ':day' => $i,
                    ':type' => 'DESAYUNO',
                    ':name' => "Desayuno Día $i"
                ]);
                // Almuerzo
                $stmtMenu->execute([
                    ':pae_id' => $pae_id,
                    ':cycle_id' => $cycle_id,
                    ':day' => $i,
                    ':type' => 'ALMUERZO',
                    ':name' => "Almuerzo Día $i"
                ]);
            }

            $this->conn->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Ciclo creado correctamente con sus 20 días de planeación',
                'data' => ['id' => $cycle_id]
            ]);
        } catch (Exception $e) {
            if ($this->conn->inTransaction())
                $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
