<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

echo "--- MODULE GROUPS ---\n";
$stmt = $db->query("SELECT id, name FROM module_groups");
$groups = $stmt->fetchAll();
foreach ($groups as $g) {
    echo "ID: {$g['id']} - Name: {$g['name']}\n";
}

echo "\n--- CONSUMOS MODULE ---\n";
$stmt = $db->query("SELECT group_id, name, route_key FROM modules WHERE route_key = 'consumos'");
$mod = $stmt->fetch();
if ($mod) {
    echo "Module '{$mod['name']}' belongs to Group ID: {$mod['group_id']}\n";
} else {
    echo "Module NOT FOUND\n";
}
