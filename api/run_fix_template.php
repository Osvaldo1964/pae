<?php
require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

use Config\Database;

try {
    $conn = Database::getInstance()->getConnection();
    $sql = file_get_contents(__DIR__ . '/scripts/fix_template_unique_key.sql');

    // Split statements if multiple
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            try {
                echo "Executing: " . substr($stmt, 0, 50) . "...\n";
                $conn->exec($stmt);
                echo "OK\n";
            } catch (Exception $e) {
                echo "WARN: " . $e->getMessage() . "\n";
            }
        }
    }
    echo "Files Processed.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
