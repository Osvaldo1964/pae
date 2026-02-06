<?php
require_once __DIR__ . '/api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();
$stmt = $db->query("SHOW COLUMNS FROM menu_items");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
