<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT status, COUNT(*) as count FROM beneficiaries WHERE pae_id = 5 GROUP BY status");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
