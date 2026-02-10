<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';

use Config\Database;

$db = Database::getInstance()->getConnection();

$pae_id = 4;

echo "--- Raciones Programa 4 ---\n";
$rations = $db->query("SELECT id, name FROM pae_ration_types WHERE pae_id = $pae_id")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rations as $r) {
    echo "ID: {$r['id']} - Name: {$r['name']}\n";
}

echo "\n--- Distribución de Raciones en Beneficiarios (PAE 4) ---\n";
$counts = $db->query("SELECT ration_type_id, COUNT(*) as c FROM beneficiaries WHERE pae_id = $pae_id GROUP BY ration_type_id")->fetchAll(PDO::FETCH_ASSOC);
foreach ($counts as $c) {
    $rName = "UNKNOWN";
    if ($c['ration_type_id']) {
        $stmt = $db->prepare("SELECT name FROM pae_ration_types WHERE id = ?");
        $stmt->execute([$c['ration_type_id']]);
        $rName = $stmt->fetchColumn() ?: "INVALID_ID";
    }
    echo "RationID: {$c['ration_type_id']} ($rName) - Count: {$c['c']}\n";
}
