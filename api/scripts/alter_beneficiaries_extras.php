<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Config.php';

use Config\Database;

try {
    $database = Database::getInstance();
    $conn = $database->getConnection();
    
    // Add sizes columns
    $sql1 = "ALTER TABLE beneficiaries 
             ADD COLUMN talla_zapato VARCHAR(10) DEFAULT NULL,
             ADD COLUMN talla_camisa VARCHAR(10) DEFAULT NULL,
             ADD COLUMN talla_pantalon VARCHAR(10) DEFAULT NULL,
             ADD COLUMN doc_identidad_path VARCHAR(255) DEFAULT NULL,
             ADD COLUMN doc_sisben_path VARCHAR(255) DEFAULT NULL,
             ADD COLUMN historia_clinica_path VARCHAR(255) DEFAULT NULL,
             ADD COLUMN fotografia_path VARCHAR(255) DEFAULT NULL;";
             
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute();
    echo "Columnas de tallas y documentos agregadas con éxito a la tabla beneficiaries.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    // Check if error is "Duplicate column name"
}
?>
