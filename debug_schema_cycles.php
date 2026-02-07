<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();
$tables = ['menu_cycles', 'cycle_templates', 'cycle_template_days', 'menus', 'menu_recipes', 'nutritional_parameters'];
foreach ($tables as $t) {
    echo "\nTable: $t\n";
    $stmt = $conn->query("DESCRIBE $t");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
