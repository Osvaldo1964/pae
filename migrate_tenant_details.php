<?php
/**
 * Migration Script: Add contract details to pae_programs table
 * Features: Start/End dates, Contract Number, Value, and Periodicity
 */

// Basic error reporting for visibility on Hostinger
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/api/config/Database.php';
require_once __DIR__ . '/api/utils/Env.php';

use Config\Database;

header('Content-Type: text/plain');

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Starting migration...\n";
    
    // Debug info
    $dbName = $db->query("SELECT DATABASE()")->fetchColumn();
    echo "Connected to database: $dbName\n";

    // Show ALL columns for debugging
    echo "Current columns in pae_programs:\n";
    $cols = $db->query("SHOW COLUMNS FROM `pae_programs`")->fetchAll(PDO::FETCH_COLUMN);
    echo implode(", ", $cols) . "\n\n";

    // Check if contract_number exists
    $exists = in_array('contract_number', $cols);

    if ($exists) {
        die("Migration skipped: Columns already exist in pae_programs table.\n");
    }

    $sql = "ALTER TABLE `pae_programs` 
            ADD COLUMN `start_date` DATE DEFAULT NULL AFTER `department`,
            ADD COLUMN `end_date` DATE DEFAULT NULL AFTER `start_date`,
            ADD COLUMN `contract_number` VARCHAR(50) DEFAULT NULL AFTER `end_date`,
            ADD COLUMN `contract_value` DECIMAL(15,2) DEFAULT 0.00 AFTER `contract_number`,
            ADD COLUMN `reporting_periodicity` ENUM('Mensual', 'Bimensual', 'Semestral', 'Anual', 'Ejecución Total') DEFAULT 'Mensual' AFTER `contract_value`";
            
    $db->exec($sql);
    
    echo "SUCCESS: Added contract_number, start_date, end_date, contract_value, and reporting_periodicity to pae_programs.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    http_response_code(500);
}
