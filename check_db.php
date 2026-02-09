<?php
require_once __DIR__ . '/api/config/Database.php';
try {
    $db = Config\Database::getInstance()->getConnection();
    echo "CONNECTION: SUCCESS\n";
    $stmt = $db->query('SHOW TABLES');
    echo "TABLES:\n";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
