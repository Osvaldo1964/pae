<?php
require_once 'api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();

try {
    echo "Fixing ration_type ENUM...\n";
    $db->exec("ALTER TABLE beneficiaries 
               MODIFY COLUMN ration_type ENUM('COMPLEMENTO MAÑANA', 'COMPLEMENTO TARDE', 'ALMUERZO') 
               CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'ALMUERZO'");

    echo "Fixing modality ENUM...\n";
    $db->exec("ALTER TABLE beneficiaries 
               MODIFY COLUMN modality ENUM('RACION PREPARADA EN SITIO', 'RACION INDUSTRIALIZADA', 'BONO ALIMENTARIO') 
               CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'RACION PREPARADA EN SITIO'");

    echo "Fixing corrupted records...\n";
    $db->exec("UPDATE beneficiaries SET ration_type = 'COMPLEMENTO MAÑANA' WHERE ration_type = '' AND shift = 'MAÑANA'");
    $db->exec("UPDATE beneficiaries SET ration_type = 'COMPLEMENTO TARDE' WHERE ration_type = '' AND shift = 'TARDE'");
    $db->exec("UPDATE beneficiaries SET ration_type = 'ALMUERZO' WHERE ration_type = ''");

    echo "Done!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>