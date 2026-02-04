<?php
require_once __DIR__ . '/config/Database.php';
try {
    $db = \Config\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT name FROM items WHERE name LIKE '%ó%' OR name LIKE '%í%' OR name LIKE '%á%' OR name LIKE '%é%' OR name LIKE '%ú%' LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['name'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
