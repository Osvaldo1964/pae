<?php
require_once __DIR__ . '/api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();

$groups = $db->query("SELECT id, name FROM module_groups")->fetchAll(PDO::FETCH_ASSOC);
$found = false;
foreach ($groups as $g) {
    if (stripos($g['name'], 'ENTORNO') !== false) {
        $found = true;
        echo "FOUND GROUP: id={$g['id']}, name={$g['name']}\n";
        $id = $g['id'];
        $modules = $db->prepare("SELECT id, name, route_key FROM modules WHERE group_id = :id");
        $modules->execute(['id' => $id]);
        while ($m = $modules->fetch(PDO::FETCH_ASSOC)) {
            echo "  - MODULE: id={$m['id']}, name={$m['name']}, route={$m['route_key']}\n";
        }
    }
}

if (!$found) {
    echo "ENTORNO group not found. Listing all groups:\n";
    foreach ($groups as $g) {
        echo "id={$g['id']}, name={$g['name']}\n";
    }
}
