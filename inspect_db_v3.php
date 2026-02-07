<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();

$tables = ['menus', 'beneficiaries', 'recipes', 'daily_consumptions'];
foreach ($tables as $t) {
    echo "--- $t ---\n";
    $stmt = $conn->query("DESC $t");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
