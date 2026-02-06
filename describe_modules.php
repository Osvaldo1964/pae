<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$stmt = $db->query("DESCRIBE modules");
foreach ($stmt->fetchAll() as $r) {
    echo "{$r['Field']} | {$r['Type']}\n";
}
