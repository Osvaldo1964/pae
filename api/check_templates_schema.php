<?php
require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

use Config\Database;

header('Content-Type: text/plain');

try {
    $conn = Database::getInstance()->getConnection();

    // Dump DB Name
    $stmt = $conn->query("SELECT DATABASE()");
    echo "DB NAME: " . $stmt->fetchColumn() . "\n";

    /*
    echo "=== TABLES ===\n";
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    print_r($tables);
    */

    echo "\n=== SCHEMA: cycle_template_days ===\n";
    try {
        $stmt = $conn->query("SHOW CREATE TABLE cycle_template_days");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $row['Create Table'] . "\n\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }

    echo "\n=== DATA: pae_ration_types ===\n";
    try {
        $stmt = $conn->query("SELECT * FROM pae_ration_types");
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($types);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage();
}
