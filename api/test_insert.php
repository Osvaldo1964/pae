<?php
require_once __DIR__ . '/config/Database.php';
try {
    $db = \Config\Database::getInstance()->getConnection();
    $stmt = $db->prepare("INSERT INTO suppliers (pae_id, nit, name, type) VALUES (3, '123456789', 'PROVEEDOR TEST', 'JURIDICA')");
    $stmt->execute();
    echo "Inserted ID: " . $db->lastInsertId() . "\n";
    $db->exec("DELETE FROM suppliers WHERE nit = '123456789'");
    echo "Cleaned up.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
