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
            // Incluir conteo de proyecciones para saber si está congelado
            $query = "SELECT c.*, 
                      (SELECT COUNT(*) FROM cycle_projections WHERE cycle_id = c.id) as projection_count
                      FROM menu_cycles c 
                      WHERE c.pae_id = :pae_id 
                      ORDER BY c.start_date DESC";
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
            $template_id = $data['template_id'];
            $start_date = $data['start_date'];
            $name = $data['name'];

            $pae_id = $this->getPaeIdFromToken();
            if (!$pae_id) {
                $pae_id = $data['pae_id'] ?? null;
                if (!$pae_id) throw new Exception("Debe especificar un programa PAE");
            }

            $this->conn->beginTransaction();

            // 1. Obtener la plantilla y sus días
            $stmtTemp = $this->conn->prepare("SELECT * FROM cycle_template_days WHERE template_id = ? ORDER BY day_number ASC");
            $stmtTemp->execute([$template_id]);
            $templateDays = $stmtTemp->fetchAll(PDO::FETCH_ASSOC);

            if (!$templateDays) throw new Exception("La plantilla seleccionada no tiene días configurados.");

            // 2. Definir el periodo de días hábiles entre las fechas
            $start = new \DateTime($start_date);
            $end = new \DateTime($data['end_date']);
            $end_display = $end->format('Y-m-d');

            $dates_mapping = [];
            $business_days_count = 0;

            $interval = new \DateInterval('P1D');
            $period = new \DatePeriod($start, $interval, $end->modify('+1 day'));

            foreach ($period as $date) {
                $day_of_week = $date->format('N'); // 1 (Mon) to 7 (Sun)
                if ($day_of_week < 6) {
                    $business_days_count++;
                    $dates_mapping[$business_days_count] = $date->format('Y-m-d');
                }
            }

            if ($business_days_count === 0) throw new Exception("No hay días hábiles en el rango seleccionado.");

            $stmtCycle = $this->conn->prepare("INSERT INTO menu_cycles (pae_id, name, start_date, end_date, total_days, status) VALUES (?, ?, ?, ?, ?, 'BORRADOR')");
            $stmtCycle->execute([$pae_id, $name, $start_date, $end_display, $business_days_count]);
            $cycle_id = $this->conn->lastInsertId();

            // 3. Crear los menús diarios (menus) y vincular recetas usando mapeo circular (módulo)
            $days_data = [];
            foreach ($templateDays as $td) {
                $days_data[$td['day_number']][] = $td;
            }
            // Obtener el número máximo de día en la plantilla para el módulo
            $max_template_day = max(array_keys($days_data));

            foreach ($dates_mapping as $rel_day => $real_date) {
                // Mapeo circular: Si el ciclo es más largo que la plantilla, vuelve al día 1
                $template_day_to_use = (($rel_day - 1) % $max_template_day) + 1;

                if (!isset($days_data[$template_day_to_use])) {
                    // Si ese día específico no existe en la plantilla (ej: saltos), buscamos el anterior más cercano
                    $found = false;
                    for ($d = $template_day_to_use; $d >= 1; $d--) {
                        if (isset($days_data[$d])) {
                            $template_day_to_use = $d;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) continue;
                }

                $stmtMenu = $this->conn->prepare("INSERT INTO menus (pae_id, cycle_id, name, day_number) VALUES (?, ?, ?, ?)");
                $menu_name = "Día " . $rel_day . " - " . $real_date;
                $stmtMenu->execute([$pae_id, $cycle_id, $menu_name, $rel_day]);
                $menu_id = $this->conn->lastInsertId();

                foreach ($days_data[$template_day_to_use] as $recipe_info) {
                    // Vinculamos la receta al menú diario en la tabla menu_recipes
                    $stmtMenuRec = $this->conn->prepare("INSERT INTO menu_recipes (menu_id, recipe_id, meal_type) VALUES (?, ?, ?)");
                    $stmtMenuRec->execute([$menu_id, $recipe_info['recipe_id'], $recipe_info['meal_type']]);
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
     * POST /api/menu-cycles/approve/{id}
     * Congela la demanda del ciclo calculando items por sede
     */
    public function approve($id)
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            if (!$pae_id) {
                $data = json_decode(file_get_contents("php://input"), true);
                $pae_id = $data['pae_id'] ?? null;
            }

            // 1. Verificar estado del ciclo
            $stmt = $this->conn->prepare("SELECT * FROM menu_cycles WHERE id = ? AND pae_id = ?");
            $stmt->execute([$id, $pae_id]);
            $cycle = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$cycle) throw new Exception("Ciclo no encontrado.");
            if ($cycle['status'] !== 'BORRADOR') throw new Exception("Solo se pueden aprobar ciclos en estado BORRADOR.");

            $this->conn->beginTransaction();

            // 2. Limpiar proyecciones previas si existen (por seguridad)
            $this->conn->prepare("DELETE FROM cycle_projections WHERE cycle_id = ?")->execute([$id]);

            // 3. Obtener población por Sede, Tipo de Ración y Grado (para mapear a Grupo Etario)
            $stmtPop = $this->conn->prepare("SELECT branch_id, ration_type, grade, COUNT(*) as total 
                                            FROM beneficiaries 
                                            WHERE pae_id = ? AND status = 'active' 
                                            GROUP BY branch_id, ration_type, grade");
            $stmtPop->execute([$pae_id]);
            $populations = $stmtPop->fetchAll(PDO::FETCH_ASSOC);

            if (!$populations) throw new Exception("No hay beneficiarios activos en el programa para calcular la demanda.");

            // 4. Obtener la explosión de recetas vinculadas al ciclo
            // Buscamos: Ciclo -> Menús -> Recetas -> ItemsReceta
            $queryExplosion = "SELECT mr.meal_type, ri.age_group, ri.item_id, ri.quantity 
                               FROM menu_recipes mr
                               JOIN recipe_items ri ON mr.recipe_id = ri.recipe_id
                               WHERE mr.menu_id IN (SELECT id FROM menus WHERE cycle_id = ?)";
            $stmtExp = $this->conn->prepare($queryExplosion);
            $stmtExp->execute([$id]);
            $recipeDetails = $stmtExp->fetchAll(PDO::FETCH_ASSOC);

            if (!$recipeDetails) throw new Exception("El ciclo no tiene recetas o ingredientes configurados.");

            // 5. Motor de Cálculo (Cruce Matriz)
            $projections = []; // [branch_id][item_id] => quantity
            $totalBeneficiaries = 0;

            // Mapeo de MealType a RationType
            $mealToRation = [
                'DESAYUNO' => 'COMPLEMENTO MAÑANA',
                'MEDIA MAÑANA' => 'COMPLEMENTO MAÑANA',
                'ALMUERZO' => 'ALMUERZO',
                'ONCES' => 'COMPLEMENTO TARDE',
                'CENA' => 'COMPLEMENTO TARDE'
            ];

            foreach ($populations as $pop) {
                $totalBeneficiaries += $pop['total'];
                $branch_id = $pop['branch_id'];
                $age_group = $this->getAgeGroupForGrade($pop['grade']);
                $ration_type = strtoupper($pop['ration_type']);

                foreach ($recipeDetails as $recipe) {
                    $targetRation = $mealToRation[$recipe['meal_type']] ?? 'ALMUERZO';

                    // Solo sumar si el tipo de ración del niño coincide con el plato
                    // Y el grupo etario coincide con el gramaje de la receta
                    if ($ration_type === $targetRation && $age_group === $recipe['age_group']) {
                        $item_id = $recipe['item_id'];
                        $quantity = $recipe['quantity'] * $pop['total'];

                        if (!isset($projections[$branch_id])) $projections[$branch_id] = [];
                        if (!isset($projections[$branch_id][$item_id])) $projections[$branch_id][$item_id] = 0;

                        $projections[$branch_id][$item_id] += $quantity;
                    }
                }
            }

            // 6. Guardar Proyecciones Congeladas
            $stmtInsert = $this->conn->prepare("INSERT INTO cycle_projections (cycle_id, branch_id, item_id, total_quantity, beneficiary_count) VALUES (?, ?, ?, ?, ?)");
            foreach ($projections as $branch_id => $items) {
                foreach ($items as $item_id => $qty) {
                    // Contamos beneficiarios aproximados para esta sede (opcional, para trazabilidad)
                    $stmtInsert->execute([$id, $branch_id, $item_id, $qty, $totalBeneficiaries]);
                }
            }

            // 7. Actualizar estado del ciclo
            $stmtUpdate = $this->conn->prepare("UPDATE menu_cycles SET status = 'ACTIVO', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmtUpdate->execute([$id]);

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Ciclo aprobado y demanda congelada correctamente.']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function getAgeGroupForGrade($grade)
    {
        $grade = trim(strtoupper($grade));
        if (in_array($grade, ['TRANSICIÓN', 'TRANSICION', 'JARDIN', 'JARDÍN', 'PRE-JARDIN', '0', '0°'])) return 'PREESCOLAR';
        if (in_array($grade, ['1', '1°', '2', '2°', '3', '3°'])) return 'PRIMARIA_A';
        if (in_array($grade, ['4', '4°', '5', '5°'])) return 'PRIMARIA_B';
        return 'SECUNDARIA'; // 6 a 11
    }

    /**
     * DELETE /api/menu-cycles/{id}
     */
    public function delete($id)
    {
        try {
            $pae_id = $this->getPaeIdFromToken();

            // Verificar estado antes de borrar
            $stmt = $this->conn->prepare("SELECT status FROM menu_cycles WHERE id = ? AND pae_id = ?");
            $stmt->execute([$id, $pae_id]);
            $cycle = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cycle) throw new Exception("Ciclo no encontrado.");
            if ($cycle['status'] === 'ACTIVO') throw new Exception("No se puede eliminar un ciclo que ya está ACTIVO (congelado).");

            $this->conn->beginTransaction();

            // 1. Delete menu recipes
            $stmt1 = $this->conn->prepare("DELETE FROM menu_recipes WHERE menu_id IN (SELECT id FROM menus WHERE cycle_id = ?)");
            $stmt1->execute([$id]);

            // 2. Delete menus
            $stmt2 = $this->conn->prepare("DELETE FROM menus WHERE cycle_id = ?");
            $stmt2->execute([$id]);

            // 3. Delete the cycle itself
            $stmtDel = $this->conn->prepare("DELETE FROM menu_cycles WHERE id = ?");
            $stmtDel->execute([$id]);

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Ciclo eliminado correctamente']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
