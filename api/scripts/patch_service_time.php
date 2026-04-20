<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
try {
    $db = Database::getInstance()->getConnection();
    $db->exec("ALTER TABLE pae_ration_types ADD COLUMN service_time TIME DEFAULT NULL AFTER name;");
    echo "Columna service_time agregada exitosamente.\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "La columna ya existe.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
