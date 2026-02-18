<?php
require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

try {
    $conn = \Config\Database::getInstance()->getConnection();
    $row = $conn->query("SELECT * FROM hr_positions LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "REAL COLUMNS IN hr_positions:\n";
        print_r(array_keys($row));
    } else {
        echo "hr_positions is EMPTY. Checking schema again...\n";
        $stmt = $conn->query("DESCRIBE hr_positions");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- '{$row['Field']}'\n";
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
