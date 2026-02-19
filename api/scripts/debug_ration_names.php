<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, name FROM pae_ration_types");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . " Name: '" . $row['name'] . "' Hex: " . bin2hex($row['name']) . "\n";
}
