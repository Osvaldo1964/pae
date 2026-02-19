<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SHOW COLUMNS FROM beneficiaries");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Check if column is NOT NULL and has NO default value
    if ($row['Null'] === 'NO' && $row['Default'] === null && $row['Extra'] !== 'auto_increment') {
        echo "{$row['Field']}\n";
    }
}
