<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';

use Config\Database;

$db = Database::getInstance()->getConnection();

$r = $db->query("SELECT name, route_key FROM modules")->fetchAll(PDO::FETCH_ASSOC);
foreach ($r as $row) {
    echo "Module: {$row['name']} -> Route: {$row['route_key']}\n";
}
