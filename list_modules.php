<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();
$stmt = $db->query('SELECT g.name as group_name, m.name as module_name, m.route FROM modules m JOIN groups g ON m.group_id = g.id ORDER BY g.id, m.id');
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($res, JSON_PRETTY_PRINT);
