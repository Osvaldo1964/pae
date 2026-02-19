<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$PAE_ID = 5;
$RATIONS = [
    8,  // DESAYUNO GENERAL
    11, // ALMUERZO GENERAL
    12, // CENA GENERAL
    13, // MEDIA MAÑANA GENERAL
    14  // MEDIA TARDE GENERAL
];

echo "Populating rights for PAE $PAE_ID...\n";

// 1. Get all beneficiaries for PAE 5
$stmt = $db->prepare("SELECT id FROM beneficiaries WHERE pae_id = ?");
$stmt->execute([$PAE_ID]);
$beneficiaries = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "Found " . count($beneficiaries) . " beneficiaries.\n";

$db->beginTransaction();

try {
    // Optional: Clear existing rights for clean slate?
    // $db->prepare("DELETE FROM beneficiary_ration_rights WHERE pae_id = ?")->execute([$PAE_ID]);

    $insert = $db->prepare("INSERT IGNORE INTO beneficiary_ration_rights (pae_id, beneficiary_id, ration_type_id, created_at) VALUES (?, ?, ?, NOW())");

    $count = 0;
    foreach ($beneficiaries as $benId) {
        foreach ($RATIONS as $rId) {
            $insert->execute([$PAE_ID, $benId, $rId]);
            $count++;
        }
    }

    $db->commit();
    echo "Inserted $count rights records.\n";
} catch (Exception $e) {
    $db->rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
