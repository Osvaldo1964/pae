<?php
require_once 'api/config/Database.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$db->query("UPDATE modules SET group_id = 3 WHERE route_key = 'consumos'");
echo "Module moved to Beneficiarios (Group 3)";
