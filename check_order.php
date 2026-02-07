<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();
$stmt = $conn->query("SELECT id, name, order_index FROM module_groups ORDER BY order_index ASC");
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($groups, JSON_PRETTY_PRINT);
