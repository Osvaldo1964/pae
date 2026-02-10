<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();
echo "Tables check:\n";
foreach (['ethnic_groups', 'document_types', 'pae_ration_types', 'schools', 'school_branches'] as $t) {
    try {
        $c = $db->query("SELECT COUNT(*) FROM $t")->fetchColumn();
        echo " - $t: $c\n";
    } catch (Exception $e) {
        echo " - $t: ERROR (" . $e->getMessage() . ")\n";
    }
}
