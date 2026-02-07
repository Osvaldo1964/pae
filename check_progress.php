<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();

echo "--- Ration Types ---\n";
$stmt = $conn->query("SELECT * FROM pae_ration_types");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

$tables = ['beneficiaries', 'recipes', 'daily_consumptions', 'menus', 'cycle_template_days', 'menu_recipes', 'nutritional_parameters'];
foreach ($tables as $t) {
    $stmt = $conn->query("SHOW COLUMNS FROM $t LIKE 'ration_type_id'");
    $res = $stmt->fetch();
    echo "Table: $t | Has ration_type_id: " . ($res ? "YES" : "NO") . "\n";
}
