<?php

namespace Controllers;

use Config\Database;
use PDO;
use Exception;

class NeedsReportController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function generate($cycleId)
    {
        try {
            // 1. Get Cycle Details
            $stmt = $this->conn->prepare("SELECT * FROM menu_cycles WHERE id = ?");
            $stmt->execute([$cycleId]);
            $cycle = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cycle)
                throw new Exception("Ciclo no encontrado");

            // 2. Get Active Beneficiaries and Classify them by Branch, Ration Type and Age Group
            $pae_id = $cycle['pae_id'];
            $sqlBen = "SELECT id, branch_id, ration_type_id, birth_date FROM beneficiaries WHERE status = 'ACTIVO' AND pae_id = :pae_id";
            $stmtBen = $this->conn->prepare($sqlBen);
            $stmtBen->execute([':pae_id' => $pae_id]);
            $beneficiaries = $stmtBen->fetchAll(PDO::FETCH_ASSOC);

            // Structure: $census[branch_id][age_group] = count
            $census = [];
            $cycleStart = new \DateTime($cycle['start_date']);

            foreach ($beneficiaries as $b) {
                if (!$b['birth_date'])
                    continue;

                $dob = new \DateTime($b['birth_date']);
                $age = $cycleStart->diff($dob)->y;

                $group = $this->classifyAgeGroup($age);

                if (!isset($census[$b['branch_id']])) {
                    $census[$b['branch_id']] = [
                        'PREESCOLAR' => 0,
                        'PRIMARIA_A' => 0,
                        'PRIMARIA_B' => 0,
                        'SECUNDARIA' => 0
                    ];
                }

                if ($group) {
                    $rtid = $b['ration_type_id'];
                    if (!isset($census[$b['branch_id']][$rtid])) {
                        $census[$b['branch_id']][$rtid] = [
                            'PREESCOLAR' => 0,
                            'PRIMARIA_A' => 0,
                            'PRIMARIA_B' => 0,
                            'SECUNDARIA' => 0
                        ];
                    }
                    $census[$b['branch_id']][$rtid][$group]++;
                }
            }

            // 3. Get Menus and Recipes with Quantities per Age Group
            $sqlMenus = "SELECT 
                            m.day_number,
                            mr.recipe_id,
                            mr.ration_type_id,
                            ri.item_id,
                            i.name as item_name,
                            mu.name as unit,
                            ri.age_group,
                            ri.quantity,
                            mu.conversion_factor,
                            mu.abbreviation as unit_abbr
                         FROM menus m
                         JOIN menu_recipes mr ON m.id = mr.menu_id
                         JOIN recipe_items ri ON mr.recipe_id = ri.recipe_id
                         JOIN items i ON ri.item_id = i.id
                         JOIN measurement_units mu ON i.measurement_unit_id = mu.id
                         WHERE m.cycle_id = ?";

            $stmt = $this->conn->prepare($sqlMenus);
            $stmt->execute([$cycleId]);
            $recipeDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 4. Calculate Demand
            $demand = [];
            $branches = $this->getBranches(); // Map id => name

            foreach ($recipeDetails as $row) {
                $itemId = $row['item_id'];
                // Normalize Key
                $ageGroup = $this->normalizeAgeGroup($row['age_group']);
                $qtyPerPerson = floatval($row['quantity']);

                if (!isset($demand[$itemId])) {
                    $demand[$itemId] = [
                        'name' => $row['item_name'],
                        'unit' => $row['unit_abbr'],
                        'branches' => [],
                        'grand_total' => 0
                    ];
                    // Initialize all branches to 0
                    foreach ($branches as $bid => $bname) {
                        $demand[$itemId]['branches'][$bid] = 0;
                    }
                }

                // For each branch, calculate raw need: (Census[Ration][Group] * Qty) / ConversionFactor
                $factor = (isset($row['conversion_factor']) && $row['conversion_factor'] > 0) ? floatval($row['conversion_factor']) : 1;

                foreach ($census as $branchId => $rations) {
                    $targetRtId = $row['ration_type_id'];
                    if (isset($rations[$targetRtId])) {
                        $groups = $rations[$targetRtId];
                        if (isset($groups[$ageGroup]) && $groups[$ageGroup] > 0) {
                            $totalForBranch = ($groups[$ageGroup] * $qtyPerPerson) / $factor;

                            if (!isset($demand[$itemId]['branches'][$branchId])) {
                                $demand[$itemId]['branches'][$branchId] = 0;
                            }

                            $demand[$itemId]['branches'][$branchId] += $totalForBranch;
                            $demand[$itemId]['grand_total'] += $totalForBranch;
                        }
                    }
                }
            }

            // 5. Format for Response
            $finalReport = [];
            foreach ($demand as $itemId => $data) {
                // Show all for confirmation
                $finalReport[] = $data;
            }

            usort($finalReport, function ($a, $b) {
                return strcmp($a['name'], $b['name']);
            });

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'cycle' => $cycle,
                'branches' => $branches,
                'data' => $finalReport
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function classifyAgeGroup($age)
    {
        // Must match nomenclature in recipe_items table
        if ($age >= 4 && $age <= 5)
            return 'PREESCOLAR';
        if ($age >= 6 && $age <= 7)
            return 'PRIMARIA_A';
        if ($age >= 8 && $age <= 10)
            return 'PRIMARIA_B';
        if ($age >= 11 && $age <= 17)
            return 'SECUNDARIA';
        return null;
    }

    private function normalizeAgeGroup($dbString)
    {
        $clean = preg_replace('/[^a-zA-Z0-9_]/', '', $dbString);
        return strtoupper(trim($clean));
    }

    private function getBranches()
    {
        $sql = "SELECT sb.id, CONCAT(s.name, ' - ', sb.name) as full_name 
                FROM school_branches sb
                JOIN schools s ON sb.school_id = s.id
                WHERE sb.status IN ('ACTIVO', 'active')
                ORDER BY s.name, sb.name";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
