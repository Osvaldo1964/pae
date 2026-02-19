<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
// Check for NULL ration_type_id in PAE 5
$stmt = $db->query("SELECT COUNT(*) as count FROM beneficiaries WHERE pae_id = 5 AND ration_type_id IS NULL");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "NULL ration_type_id count: " . $row['count'] . "\n";

// Check if any have values
$stmt = $db->query("SELECT ration_type_id, COUNT(*) as count FROM beneficiaries WHERE pae_id = 5 GROUP BY ration_type_id");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
