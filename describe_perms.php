<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();
$stmt = $db->query('DESCRIBE module_permissions');
$res = $stmt->fetchAll(PDO::FETCH_COLUMN); // Column 0 is Field
foreach ($res as $col) {
    echo $col . "\n";
}
