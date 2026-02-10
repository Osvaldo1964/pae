<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$tables = [
    'nutritional_parameters',
    'recipe_nutrition',
    'recipes',
    'menu_items',
    'age_groups',
    'pae_groups'
];

foreach ($tables as $table) {
    echo "--- Table: $table ---\n";
    try {
        $stmt = $db->query("DESCRIBE $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  {$row['Field']} ({$row['Type']})\n";
        }
    } catch (Exception $e) {
        echo "  Table not found or error: " . $e->getMessage() . "\n";
    }
}

echo "\n--- Sample nutritional_parameters ---\n";
try {
    $stmt = $db->query("SELECT * FROM nutritional_parameters LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
