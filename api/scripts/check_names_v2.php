<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$cols = ['first_name', 'second_name', 'last_name1', 'last_name2'];
foreach ($cols as $col) {
    $stmt = $db->query("SHOW COLUMNS FROM beneficiaries LIKE '$col'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "$col: Null={$row['Null']}, Default={$row['Default']}\n";
    } else {
        echo "$col: NOT FOUND\n";
    }
}
