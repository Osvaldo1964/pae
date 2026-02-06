<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

echo "--- GROUPS WITH ORDER ---\n";
$stmt = $db->query("SELECT id, name, order_index FROM module_groups ORDER BY order_index, id");
print_r($stmt->fetchAll());
