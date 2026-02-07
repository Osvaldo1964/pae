<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();

$tables = ['daily_consumptions', 'menu_recipes', 'nutritional_parameters', 'cycle_template_days'];

foreach ($tables as $t) {
    echo "Table: $t\n";
    $stmt = $conn->query("SHOW COLUMNS FROM $t LIKE 'pae_id'");
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
    echo "-------------------\n";
}
