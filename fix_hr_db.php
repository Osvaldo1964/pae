<?php
require_once __DIR__ . '/api/config/Database.php';
try {
    $conn = \Config\Database::getInstance()->getConnection();
    echo "Updating hr_positions table...\n";
    $conn->exec("ALTER TABLE hr_positions CHANGE name description VARCHAR(100) NOT NULL");
    echo "Success!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
