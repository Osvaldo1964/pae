<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, name FROM pae_ration_types WHERE id = 11");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "ID 11: " . ($row ? $row['name'] : 'Not Found') . "\n";

// Also list all ration types to pick the best one
$stmt = $db->query("SELECT id, name FROM pae_ration_types");
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$r['id']}: {$r['name']}\n";
}
