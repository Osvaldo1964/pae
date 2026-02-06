<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();
$stmt = $db->query('SHOW TABLES');
$res = $stmt->fetchAll(PDO::FETCH_COLUMN); // Column 0 only
foreach ($res as $table) {
    echo $table . "\n";
}
