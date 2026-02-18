<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

try {
    $conn = \Config\Database::getInstance()->getConnection();

    $query = "SELECT e.*, p.name as position_name 
              FROM hr_employees e
              LEFT JOIN hr_positions p ON e.position_id = p.id
              LIMIT 1";

    echo "Executing query: $query\n";
    $stmt = $conn->query($query);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Query executed successfully.\n";
    print_r($res);
} catch (Throwable $e) {
    echo "QUERY FAILED: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
}
