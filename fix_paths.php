<?php
require_once __DIR__ . '/api/config/Database.php';

use Config\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Update entity_logo_path
    $db->exec("UPDATE pae_programs SET entity_logo_path = REPLACE(entity_logo_path, 'uploads/logos/', 'assets/img/logos/') WHERE entity_logo_path LIKE 'uploads/logos/%'");
    $db->exec("UPDATE pae_programs SET entity_logo_path = REPLACE(entity_logo_path, 'assets/uploads/logos/', 'assets/img/logos/') WHERE entity_logo_path LIKE 'assets/uploads/logos/%'");

    // Update operator_logo_path
    $db->exec("UPDATE pae_programs SET operator_logo_path = REPLACE(operator_logo_path, 'uploads/logos/', 'assets/img/logos/') WHERE operator_logo_path LIKE 'uploads/logos/%'");
    $db->exec("UPDATE pae_programs SET operator_logo_path = REPLACE(operator_logo_path, 'assets/uploads/logos/', 'assets/img/logos/') WHERE operator_logo_path LIKE 'assets/uploads/logos/%'");

    echo "Database paths updated successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
