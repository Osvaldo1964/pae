<?php
require_once __DIR__ . '/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();
$res = $db->query('SELECT id, name, status, is_validated FROM menu_cycles')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($res, JSON_PRETTY_PRINT);
