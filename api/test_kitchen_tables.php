<?php
// Test script to verify kitchen tables exist
require_once __DIR__ . '/config/Database.php';

use Config\Database;

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    echo "✅ Database connection successful\n\n";

    // Check if tables exist
    $tables = ['food_groups', 'measurement_units', 'items'];

    foreach ($tables as $table) {
        $query = "SHOW TABLES LIKE '$table'";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "✅ Table '$table' exists\n";

            // Count rows
            $countQuery = "SELECT COUNT(*) as count FROM $table";
            $countStmt = $conn->prepare($countQuery);
            $countStmt->execute();
            $result = $countStmt->fetch(PDO::FETCH_ASSOC);
            echo "   → Rows: " . $result['count'] . "\n";
        } else {
            echo "❌ Table '$table' does NOT exist\n";
        }
    }

    echo "\n--- Testing food_groups query ---\n";
    $query = "SELECT * FROM food_groups ORDER BY name";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Found " . count($groups) . " food groups:\n";
    foreach ($groups as $group) {
        echo "  - {$group['name']}\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
