<?php
require_once __DIR__ . '/../config/Database.php';

use Config\Database;

try {
    $conn = Database::getInstance()->getConnection();
    echo "Connection successful\n";

    // Test a single change
    $table = 'beneficiaries';
    echo "Testing $table update...\n";
    $conn->exec("ALTER TABLE beneficiaries MODIFY COLUMN ration_type VARCHAR(100)");
    echo "Step 1 OK\n";
    $conn->exec("UPDATE beneficiaries SET ration_type = 'DESAYUNO' WHERE ration_type = 'COMPLEMENTO MAÑANA'");
    echo "Step 2 OK\n";
} catch (Exception $e) {
    echo "DEBUG ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . " LINE: " . $e->getLine() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
}
