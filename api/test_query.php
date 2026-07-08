<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

try {
    $conn = \Config\Database::getInstance()->getConnection();

    echo "--- RECIPE ITEMS WITH ITEMS ---\n";
    $query = "SELECT ri.recipe_id, r.name as recipe_name, ri.item_id, i.name as item_name, 
                     ri.age_group, ri.quantity as recipe_qty, mu.code as item_unit, mu.conversion_factor
              FROM recipe_items ri
              JOIN recipes r ON ri.recipe_id = r.id
              JOIN items i ON ri.item_id = i.id
              JOIN measurement_units mu ON i.measurement_unit_id = mu.id
              LIMIT 15";
    $stmt = $conn->query($query);
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Throwable $e) {
    echo "QUERY FAILED: " . $e->getMessage() . "\n";
}
