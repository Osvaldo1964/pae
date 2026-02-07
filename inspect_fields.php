<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();

$stmt = $conn->query("DESC menus");
echo "--- menus ---\n";
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['Field'] . "\n";
}

$stmt = $conn->query("DESC cycle_templates");
echo "--- cycle_templates ---\n";
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['Field'] . "\n";
}
