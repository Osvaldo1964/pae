<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

// Let's see what pae_ids exist
echo "--- PAE PROGRAMS ---\n";
$stmt = $db->query("SELECT id, name FROM pae_programs");
foreach ($stmt->fetchAll() as $r) {
    echo "ID: {$r['id']} | Name: {$r['name']}\n";
}

// Check permissions for 'consumos' module
echo "\n--- PERMISSIONS FOR 'consumos' ---\n";
$stmtMod = $db->query("SELECT id FROM modules WHERE route_key = 'consumos'");
$modId = $stmtMod->fetchColumn();

if ($modId) {
    $stmt = $db->prepare("SELECT role_id, pae_id, can_read FROM module_permissions WHERE module_id = ?");
    $stmt->execute([$modId]);
    foreach ($stmt->fetchAll() as $r) {
        $pae = $r['pae_id'] ?? 'NULL (Global/Default)';
        echo "Role: {$r['role_id']} | PAE: $pae | Read: {$r['can_read']}\n";
    }
} else {
    echo "Module 'consumos' not found.\n";
}
