<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$stmt = $db->query("SELECT * FROM module_groups WHERE id = 5");
echo "--- GROUP 5 ---\n";
print_r($stmt->fetch());

$stmt = $db->query("SELECT * FROM modules WHERE route_key = 'consumos'");
echo "\n--- MODULE 'consumos' ---\n";
print_r($stmt->fetch());
