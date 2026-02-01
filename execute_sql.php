<?php
require_once __DIR__ . '/api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();

$sql = file_get_contents(__DIR__ . '/sql/06_environment_schema.sql');

try {
    // PDO doesn't always support multiple queries in one exec() depending on driver/settings
    // We split by semicolon if needed, but usually it works if emulated prepares is on or specific driver settings.
    // However, it's safer to just execute the whole block if the driver supports it.
    $db->exec($sql);
    echo "SQL Executed successfully\n";
} catch (PDOException $e) {
    echo "Error executing SQL: " . $e->getMessage() . "\n";
}
