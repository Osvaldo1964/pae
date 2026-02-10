<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

echo "--- school_branches schema ---\n";
$stmt = $db->query("DESCRIBE school_branches");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}

echo "\n--- schools schema ---\n";
$stmt = $db->query("DESCRIBE schools");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
