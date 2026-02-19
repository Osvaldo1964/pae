<?php
require_once __DIR__ . '/../config/Config.php';
require_once __DIR__ . '/../utils/Env.php'; // Required by Database
require_once __DIR__ . '/../config/Database.php';

use Config\Database;
use Utils\Env;

try {
    // Load Env if not loaded by Database constructor
    Env::load(__DIR__ . '/../.env');

    $db = Database::getInstance();
    $conn = $db->getConnection();

    $query = "SELECT id, name FROM pae_ration_types ORDER BY name ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Found " . count($types) . " types:\n";
    foreach ($types as $row) {
        echo "[{$row['id']}] {$row['name']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>