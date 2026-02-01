<?php
require_once __DIR__ . '/api/config/Database.php';

use Config\Database;

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT id, name, entity_logo_path, operator_logo_path FROM pae_programs");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
