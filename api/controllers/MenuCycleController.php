<?php

namespace Controllers;

use Config\Database;
use PDO;
use Exception;

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
                if (is_object($decoded)) return $decoded->data->pae_id ?? null;
                if (is_array($decoded)) return $decoded['data']['pae_id'] ?? null;
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
     * Genera un ciclo de 20 días basado en una plantilla
     */
    public function generate()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $pae_id = $this->getPaeIdFromToken();
            $template_id = $data['template_id'];
            $start_date = $data['start_date'];
            $name = $data['name'];

            $this->conn->beginTransaction();

            // 1. Obtener la plantilla y sus días
            $stmtTemp = $this->conn->prepare("SELECT * FROM cycle_template_days WHERE template_id = ? ORDER BY day_number ASC");
            $stmtTemp->execute([$template_id]);
            $templateDays = $stmtTemp->fetchAll(PDO::FETCH_ASSOC);

            if (!$templateDays) throw new Exception("La plantilla seleccionada no tiene días configurados.");

            // 2. Crear el ciclo (menu_cycles)
            // Calculamos el end_date (20 días hábiles)
            $current_date = new \DateTime($start_date);
            $business_days_count = 0;
            $dates_mapping = []; // día_relativo => fecha_real

            while ($business_days_count < 20) {
                $day_of_week = $current_date->format('N'); // 1 (mon) to 7 (sun)
                if ($day_of_week < 6) { // Lunes a Viernes
                    $business_days_count++;
                    $dates_mapping[$business_days_count] = $current_date->format('Y-m-d');
                }
                if ($business_days_count < 20) {
                    $current_date->modify('+1 day');
                }
            }
            $end_date = $current_date->format('Y-m-d');

            $stmtCycle = $this->conn->prepare("INSERT INTO menu_cycles (pae_id, name, start_date, end_date, total_days, status) VALUES (?, ?, ?, ?, 20, 'BORRADOR')");
            $stmtCycle->execute([$pae_id, $name, $start_date, $end_date]);
            $cycle_id = $this->conn->lastInsertId();

            // 3. Crear los menús diarios (menus) y sus items (menu_items)
            // Agrupamos los días de la plantilla por número de día
            $days_data = [];
            foreach ($templateDays as $td) {
                $days_data[$td['day_number']][] = $td;
            }

            foreach ($dates_mapping as $rel_day => $real_date) {
                if (!isset($days_data[$rel_day])) continue;

                // Para cada día real, creamos un registro en 'menus'
                $stmtMenu = $this->conn->prepare("INSERT INTO menus (cycle_id, date, day_number) VALUES (?, ?, ?)");
                $stmtMenu->execute([$cycle_id, $real_date, $rel_day]);
                $menu_id = $this->conn->lastInsertId();

                // Insertamos las recetas asociadas a ese día
                foreach ($days_data[$rel_day] as $recipe_info) {
                    // Aquí vinculamos la receta al menú diario
                    // Nota: En un futuro aquí se haría la explosión de víveres hacia menu_items
                    $stmtMenuItem = $this->conn->prepare("INSERT INTO menu_items (menu_id, recipe_id, meal_type) VALUES (?, ?, ?)");
                    $stmtMenuItem->execute([$menu_id, $recipe_info['recipe_id'], $recipe_info['meal_type']]);
                }
            }

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Ciclo de 20 días generado correctamente', 'id' => $cycle_id]);
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * DELETE /api/menu-cycles/{id}
     */
    public function delete($id)
    {
        try {
            // Verificar estado antes de borrar
            $stmt = $this->conn->prepare("SELECT status, is_validated FROM menu_cycles WHERE id = ?");
            $stmt->execute([$id]);
            $cycle = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->conn->beginTransaction();

            // 1. Delete menu items (explosion of items)
            $stmt1 = $this->conn->prepare("DELETE FROM menu_items WHERE menu_id IN (SELECT id FROM menus WHERE cycle_id = ?)");
            $stmt1->execute([$id]);

            // 2. Delete menus
            $stmt2 = $this->conn->prepare("DELETE FROM menus WHERE cycle_id = ?");
            $stmt2->execute([$id]);

            // 3. Delete the cycle itself
            $stmtDel = $this->conn->prepare("DELETE FROM menu_cycles WHERE id = ?");
            $stmtDel->execute([$id]);

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Ciclo y toda su programación eliminados correctamente']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
