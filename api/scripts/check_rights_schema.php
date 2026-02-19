<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SHOW COLUMNS FROM beneficiary_ration_rights");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$row['Field']}\n";
}
