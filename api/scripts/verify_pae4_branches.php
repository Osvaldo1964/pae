<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT dane_code, name FROM school_branches WHERE pae_id = 4 LIMIT 5");
while ($row = $stmt->fetch()) {
    echo "DANE: " . $row['dane_code'] . " -> " . $row['name'] . "\n";
}
