<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$stmt = $db->query("SELECT id, name, icon FROM module_groups WHERE id = 5");
print_r($stmt->fetch());
