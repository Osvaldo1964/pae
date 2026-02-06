<?php
require_once 'api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();

$tables = ['menu_items', 'menus', 'menu_cycles'];
foreach ($tables as $table) {
    echo "--- Table: $table ---\n";
    $stmt = $db->query("DESCRIBE $table");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} | {$row['Type']} | {$row['Null']} | {$row['Key']} | {$row['Default']}\n";
    }
}
