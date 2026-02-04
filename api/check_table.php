<?php
require_once __DIR__ . '/config/Database.php';
try {
    $db = \Config\Database::getInstance()->getConnection();
    $stmt = $db->query("DESCRIBE suppliers");
    $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($fields as $row) {
        printf("%-15s | %-20s | %-4s | %-10s\n", $row['Field'], $row['Type'], $row['Null'], $row['Default']);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
