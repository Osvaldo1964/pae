<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();

$tables = ['cycle_template_days', 'menu_recipes', 'nutritional_parameters'];
foreach ($tables as $t) {
    echo "--- $t ---\n";
    $stmt = $conn->query("DESC $t");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
