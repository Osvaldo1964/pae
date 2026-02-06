<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$sql = "SELECT mg.name as group_name, m.name as module_name, m.route_key 
        FROM module_groups mg 
        JOIN modules m ON m.group_id = mg.id 
        ORDER BY mg.id, m.id";
$stmt = $db->query($sql);
foreach ($stmt->fetchAll() as $r) {
    echo "[{$r['group_name']}] -> {$r['module_name']} ({$r['route_key']})\n";
}
