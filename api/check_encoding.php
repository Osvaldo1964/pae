<?php
require_once __DIR__ . '/config/Database.php';
try {
    $db = \Config\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT id, name FROM pae_programs");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']} | Name: {$row['name']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
