<?php
require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

try {
    $conn = \Config\Database::getInstance()->getConnection();

    echo "--- STRUCTURE OF hr_positions ---\n";
    $stmt = $conn->query("SHOW COLUMNS FROM hr_positions");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

    echo "\n--- DATA FROM hr_positions (1 row) ---\n";
    $stmt = $conn->query("SELECT * FROM hr_positions LIMIT 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        print_r($data);
    } else {
        echo "Table is empty.\n";
    }

    echo "\n--- STRUCTURE OF hr_employees ---\n";
    $stmt = $conn->query("SHOW COLUMNS FROM hr_employees");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
