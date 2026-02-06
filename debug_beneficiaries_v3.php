<?php
require_once __DIR__ . '/api/config/Database.php';

try {
    $db = \Config\Database::getInstance()->getConnection();

    $stmt = $db->query("SELECT id, name, pae_id, status FROM beneficiaries LIMIT 5");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
