<?php
require_once __DIR__ . '/api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();
$groups = $db->query("SELECT id, name FROM module_groups")->fetchAll(PDO::FETCH_ASSOC);
foreach ($groups as $g) {
    echo "GROUP: [{$g['id']}] {$g['name']}\n";
    $modules = $db->prepare("SELECT id, name, route_key FROM modules WHERE group_id = :id");
    $modules->execute(['id' => $g['id']]);
    while ($m = $modules->fetch(PDO::FETCH_ASSOC)) {
        echo "  - MODULE: [{$m['id']}] {$m['name']} -> route_key: '{$m['route_key']}'\n";
    }
}
echo "--- END ---\n";
