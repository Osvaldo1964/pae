<?php
require_once __DIR__ . '/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();
$stmt = $db->query("DESCRIBE suppliers");
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($res, JSON_PRETTY_PRINT);
