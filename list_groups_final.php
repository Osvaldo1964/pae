<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$stmt = $db->query("SELECT id, name FROM module_groups ORDER BY id");
foreach ($stmt->fetchAll() as $r) {
    echo "G_ID: {$r['id']} | G_NAME: {$r['name']}\n";
}
