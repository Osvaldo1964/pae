<?php
require_once __DIR__ . '/api/config/Database.php';

use Config\Database;

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT id, name, entity_logo_path, operator_logo_path FROM pae_programs");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']} | Name: {$row['name']}\n";
        echo "  Entity: {$row['entity_logo_path']}\n";
        echo "  Operator: {$row['operator_logo_path']}\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
