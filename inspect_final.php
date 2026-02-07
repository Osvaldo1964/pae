<?php
require_once __DIR__ . '/api/config/Database.php';
$conn = \Config\Database::getInstance()->getConnection();

echo "Tables:\n";
$stmt = $conn->query("SHOW TABLES");
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));

$stmt = $conn->query("DESC beneficiaries");
echo "--- beneficiaries ---\n";
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['Field'] . "\n";
}
