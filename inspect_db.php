<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();

$tables = [
    'beneficiaries' => 'ration_type',
    'recipes' => 'meal_type',
    'daily_consumptions' => 'meal_type',
    'menus' => 'meal_type',
    'menu_recipes' => 'meal_type',
    'nutritional_parameters' => 'meal_type',
    'cycle_template_days' => 'meal_type'
];

foreach ($tables as $table => $column) {
    echo "Table: $table | Column: $column\n";
    $stmt = $conn->query("DESC $table");
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c) {
        if ($c['Field'] == $column) {
            print_r($c);
        }
    }
    echo "-------------------\n";
}
