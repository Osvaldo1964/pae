<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();
$stmt = $db->query('SELECT id, name FROM module_groups');
foreach ($stmt->fetchAll() as $r) {
    echo "ID: {$r['id']} | Name: {$r['name']}\n";
}
