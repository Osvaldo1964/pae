<?php
require_once 'api/config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();

try {
    // 1. Find "Operación" Group in module_groups
    $stmt = $db->query("SELECT id FROM module_groups WHERE name LIKE '%Operación%' OR name LIKE '%Operacion%' OR name LIKE '%Operativo%'");
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    $groupId = $group ? $group['id'] : null;

    if (!$groupId) {
        // Fallback to "Reportes" if exist, else create "Operación"
        $stmtRep = $db->query("SELECT id FROM module_groups WHERE name LIKE '%Reporte%'");
        $repGroup = $stmtRep->fetch(PDO::FETCH_ASSOC);
        $groupId = $repGroup ? $repGroup['id'] : null;
    }

    if (!$groupId) {
        $db->query("INSERT INTO module_groups (name, icon, order_index) VALUES ('Operación', 'fas fa-tasks', 5)");
        $groupId = $db->lastInsertId();
    }

    // 2. Check if module exists in modules
    $stmt = $db->prepare("SELECT id FROM modules WHERE route_key = 'consumos'");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $db->prepare("INSERT INTO modules (group_id, name, route_key, icon, description, order_index) VALUES (?, 'Reporte de Asistencia (QR)', 'consumos', 'fas fa-id-card-alt', 'Registro de entregas capturadas por escáner', 10)")
            ->execute([$groupId]);
        echo "Module inserted successfully in group $groupId\n";
    } else {
        echo "Module already exists\n";
    }

    // Permissions check - if role_module_permissions or similar exists
    // Let's check for permission tables
    $stmtTables = $db->query("SHOW TABLES LIKE '%permission%'");
    $permTable = $stmtTables->fetch(PDO::FETCH_COLUMN);

    if ($permTable) {
        echo "Found permission table: $permTable\n";
        // Logic depends on schema, but usually it involves role_id and module_id
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
