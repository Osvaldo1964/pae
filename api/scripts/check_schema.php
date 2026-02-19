<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

header('Content-Type: application/json');
$db = Database::getInstance()->getConnection();

$tables = [];
$stmt = $db->query("SHOW TABLES");
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    $tables[] = $row[0];
}

$columns = [];
foreach ($tables as $table) {
    if ($table === 'beneficiaries') {
        $stmt = $db->query("DESCRIBE beneficiaries");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

echo json_encode(['tables' => $tables, 'beneficiaries_columns' => $columns], JSON_PRETTY_PRINT);
