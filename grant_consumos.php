<?php
require_once 'api/config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();

try {
    $stmtMod = $db->query("SELECT id FROM modules WHERE route_key = 'consumos'");
    $mod = $stmtMod->fetch(PDO::FETCH_ASSOC);
    if (!$mod)
        die("Module not found");
    $modId = $mod['id'];

    $stmtRoles = $db->query("SELECT id FROM roles");
    $roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

    foreach ($roles as $role) {
        // Find existing PAE IDs to replicate permissions if needed, or just insert for all
        $db->prepare("INSERT INTO module_permissions (role_id, module_id, can_read, can_create, can_update, can_delete) 
                      SELECT ?, ?, 1, 1, 1, 1 FROM (SELECT 1) AS tmp 
                      WHERE NOT EXISTS (SELECT id FROM module_permissions WHERE role_id = ? AND module_id = ? AND pae_id IS NULL)")
            ->execute([$role['id'], $modId, $role['id'], $modId]);

        echo "Attempted permission for role {$role['id']}\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
