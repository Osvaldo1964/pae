<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$PAE_ID = 5;

echo "Checking beneficiary_ration_rights for PAE $PAE_ID...\n";

$stmt = $db->prepare("SELECT ration_type_id, COUNT(*) as count FROM beneficiary_ration_rights WHERE pae_id = ? GROUP BY ration_type_id");
$stmt->execute([$PAE_ID]);
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

$stmtTotal = $db->prepare("SELECT COUNT(DISTINCT beneficiary_id) as total_users FROM beneficiary_ration_rights WHERE pae_id = ?");
$stmtTotal->execute([$PAE_ID]);
echo "Total distinct beneficiaries with rights: " . $stmtTotal->fetchColumn() . "\n";
