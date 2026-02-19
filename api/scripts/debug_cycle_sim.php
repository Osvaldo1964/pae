<?php
// Simulate Cycle Calculation Logic
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$PAE_ID = 5;

echo "Simulating Cycle Calculation for PAE $PAE_ID...\n";

// 3. Obtener población por Sede, Tipo de Ración, Grado y Fecha de Nacimiento
$stmtPop = $db->prepare("SELECT 
                            b.branch_id, 
                            brr.ration_type_id, 
                            b.grade, 
                            b.birth_date, 
                            b.beneficiary_type, 
                            COUNT(*) as total 
                        FROM beneficiaries b
                        JOIN beneficiary_ration_rights brr ON b.id = brr.beneficiary_id
                        WHERE b.pae_id = ? AND b.status IN ('ACTIVO', 'ASIGNADO') AND brr.pae_id = ?
                        GROUP BY b.branch_id, brr.ration_type_id, b.grade, b.birth_date, b.beneficiary_type");
$stmtPop->execute([$PAE_ID, $PAE_ID]);
$populations = $stmtPop->fetchAll(PDO::FETCH_ASSOC);

if (!$populations) {
    echo "No matching beneficiaries found!\n";
    exit;
}

echo "Found " . count($populations) . " population groups.\n";
$totalItems = 0;
foreach ($populations as $pop) {
    $totalItems += $pop['total'];
}
echo "Total Ration Rights: $totalItems (Expected ~1500)\n";

// Check breakdown by Ration Type
$stmtStats = $db->prepare("SELECT ration_type_id, COUNT(*) as cnt FROM beneficiary_ration_rights WHERE pae_id = ? GROUP BY ration_type_id");
$stmtStats->execute([$PAE_ID]);
print_r($stmtStats->fetchAll(PDO::FETCH_ASSOC));
