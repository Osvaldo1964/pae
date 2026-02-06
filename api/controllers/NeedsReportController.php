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

            if (!$cycle) throw new Exception("Ciclo no encontrado");

            // 2. Get Active Beneficiaries and Classify them by Branch and Age Group
            $sqlBen = "SELECT id, branch_id, birth_date FROM beneficiaries WHERE status = 'ACTIVO'";
            $beneficiaries = $this->conn->query($sqlBen)->fetchAll(PDO::FETCH_ASSOC);

            // Structure: $census[branch_id][age_group] = count
            $census = [];
            $cycleStart = new \DateTime($cycle['start_date']);

            foreach ($beneficiaries as $b) {
                if (!$b['birth_date']) continue;

                $dob = new \DateTime($b['birth_date']);
                $age = $cycleStart->diff($dob)->y;

                $group = $this->classifyAgeGroup($age);

                if (!isset($census[$b['branch_id']])) {
                    $census[$b['branch_id']] = [
                        'PREESCOLAR' => 0,
                        'PRIMARIAA' => 0,
                        'PRIMARIAB' => 0,
                        'SECUNDARIA' => 0
                    ];
                }

                if ($group) {
                    $census[$b['branch_id']][$group]++;
                }
            }

            // 3. Get Menus and Recipes with Quantities per Age Group
            $sqlMenus = "SELECT 
                            m.day_number,
                            mr.recipe_id,
                            ri.item_id,
                            i.name as item_name,
                            mu.name as unit,
                            ri.age_group,
                            ri.quantity
                         FROM menus m
                         JOIN menu_recipes mr ON m.id = mr.menu_id
                         JOIN recipe_items ri ON mr.recipe_id = ri.recipe_id
                         JOIN items i ON ri.item_id = i.id
                         LEFT JOIN measurement_units mu ON i.measurement_unit_id = mu.id
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
                        'unit' => $row['unit'],
                        'branches' => [],
                        'grand_total' => 0
                    ];
                    // Initialize all branches to 0
                    foreach ($branches as $bid => $bname) {
                        $demand[$itemId]['branches'][$bid] = 0;
                    }
                }

                // For each branch, calculate raw need: (Census[Group] * Qty)
                foreach ($census as $branchId => $groups) {
                    if (isset($groups[$ageGroup]) && $groups[$ageGroup] > 0) {
                        $totalForBranch = $groups[$ageGroup] * $qtyPerPerson;

                        if (!isset($demand[$itemId]['branches'][$branchId])) {
                            $demand[$itemId]['branches'][$branchId] = 0;
                        }

                        $demand[$itemId]['branches'][$branchId] += $totalForBranch;
                        $demand[$itemId]['grand_total'] += $totalForBranch;
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
        // Must match normalizeAgeGroup output
        if ($age >= 4 && $age <= 5) return 'PREESCOLAR';
        if ($age >= 6 && $age <= 7) return 'PRIMARIAA';
        if ($age >= 8 && $age <= 10) return 'PRIMARIAB';
        if ($age >= 11 && $age <= 17) return 'SECUNDARIA';
        return null;
    }

    private function normalizeAgeGroup($dbString)
    {
        $clean = preg_replace('/[^a-zA-Z0-9]/', '', $dbString);
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
