<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$sql = "SELECT mg.id as gid, mg.name as gname, m.id as mid, m.name as mname, m.route_key 
        FROM module_groups mg 
        LEFT JOIN modules m ON m.group_id = mg.id 
        ORDER BY mg.id, m.id";
$stmt = $db->query($sql);
$rows = $stmt->fetchAll();

foreach ($rows as $r) {
    echo "Group [ID: {$r['gid']}] {$r['gname']} -> Module [ID: {$r['mid']}] {$r['mname']} ({$r['route_key']})\n";
}
