<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$PAE_ID = 5;
$DEFAULT_RATION_ID = 11; // ALMUERZO GENERAL

echo "Fixing NULL ration_type_id for PAE $PAE_ID...\n";

try {
    $stmt = $db->prepare("UPDATE beneficiaries SET ration_type_id = ? WHERE pae_id = ? AND ration_type_id IS NULL");
    $stmt->execute([$DEFAULT_RATION_ID, $PAE_ID]);
    echo "Updated " . $stmt->rowCount() . " records.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
