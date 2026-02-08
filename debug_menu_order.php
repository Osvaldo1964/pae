<?php
require_once __DIR__ . '/api/config/Database.php';
try {
    $db = \Config\Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT id, name, order_index FROM module_groups WHERE name IN ('Recurso Humano', 'Reportes')");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo "ID: {$row['id']} | Name: {$row['name']} | Order: {$row['order_index']}\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
