<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';

use Config\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    // Find cycle ID
    $stmt = $db->query("SELECT id, name FROM menu_cycles ORDER BY id DESC LIMIT 1");
    $cycle = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cycle) {
        die("No se encontró ciclo de 7 días.\n");
    }
    $cycleId = $cycle['id'];
    echo "Ciclo encontrado: " . $cycle['name'] . " (ID: $cycleId)\n\n";

    $branchId = 13;

    // 1. Check Beneficiaries count for this branch
    $sqlBen = "SELECT brr.ration_type_id, b.grade, b.birth_date, b.beneficiary_type, COUNT(*) as count 
               FROM beneficiaries b
               JOIN beneficiary_ration_rights brr ON b.id = brr.beneficiary_id
               WHERE b.status = 'ACTIVO' AND b.branch_id = $branchId
               GROUP BY brr.ration_type_id, b.grade, b.birth_date, b.beneficiary_type";
    $stmtBen = $db->query($sqlBen);
    $bens = $stmtBen->fetchAll(PDO::FETCH_ASSOC);

    $totalGeneral = 0;
    foreach ($bens as $b) {
        // Assume all are GENERAL for simplicity if the user said so, but let's just sum them
        $totalGeneral += $b['count'];
    }
    echo "Total Beneficiarios Activos en sede 13: $totalGeneral\n\n";

    // 2. Look at menu_recipes for this cycle
    $sqlMenus = "SELECT m.day_number, r.name as recipe_name, rt.name as ration_name, mr.ration_type_id, ri.age_group, ri.quantity, ri.item_id
                 FROM menus m
                 JOIN menu_recipes mr ON m.id = mr.menu_id
                 JOIN recipes r ON mr.recipe_id = r.id
                 JOIN pae_ration_types rt ON mr.ration_type_id = rt.id
                 JOIN recipe_items ri ON mr.recipe_id = ri.recipe_id
                 WHERE m.cycle_id = $cycleId AND ri.item_id = 363";
    
    $stmtMenus = $db->query($sqlMenus);
    $details = $stmtMenus->fetchAll(PDO::FETCH_ASSOC);

    echo "--- DETALLE DE ENDULZANTE EN EL CICLO (ID: $cycleId) ---\n";
    $sumQty = 0;
    foreach ($details as $d) {
        if ($d['age_group'] === 'GENERAL' || $d['age_group'] === 'SECUNDARIA') {
            echo "Dia: " . $d['day_number'] . " | Racion: " . $d['ration_name'] . " | Receta: " . $d['recipe_name'] . " | Qty: " . $d['quantity'] . "\n";
            $sumQty += $d['quantity'];
        }
    }
    
    echo "\nTotal Unidades de Endulzante por 1 Beneficiario en todo el ciclo: $sumQty\n";
    echo "Calculo para 70 beneficiarios: " . ($sumQty * 70) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
