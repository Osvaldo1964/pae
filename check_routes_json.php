<?php
require_once __DIR__ . '/api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();
$res = $db->query("SELECT id, name, route_key FROM modules WHERE group_id = 2")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($res, JSON_PRETTY_PRINT);
