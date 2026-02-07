<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();

$tables = ['cycle_template_days', 'menu_recipes'];
foreach ($tables as $t) {
    echo "--- $t ---\n";
    $stmt = $conn->query("DESC $t");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo $row['Field'] . "\n";
    }
}
