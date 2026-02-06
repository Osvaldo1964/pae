<?php
require_once __DIR__ . '/api/config/Database.php';

try {
    $db = \Config\Database::getInstance()->getConnection();

    // Get column names first to avoid errors
    $stmt = $db->query("DESCRIBE beneficiaries");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Columns: " . implode(", ", $columns) . "\n\n";

    $stmt = $db->query("SELECT * FROM beneficiaries LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "First Row:\n";
    print_r($row);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
