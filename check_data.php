<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();

$tables = [
    'beneficiaries' => 'ration_type',
    'recipes' => 'meal_type',
    'nutritional_parameters' => 'meal_type'
];

foreach ($tables as $table => $col) {
    echo "--- $table ---\n";
    $stmt = $conn->query("SELECT $col, ration_type_id FROM $table WHERE ration_type_id IS NOT NULL LIMIT 3");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
