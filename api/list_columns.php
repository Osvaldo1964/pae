<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

try {
    $conn = \Config\Database::getInstance()->getConnection();

    echo "COLUMNS IN hr_employees:\n";
    $stmt = $conn->query("DESCRIBE hr_employees");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }

    echo "\nCOLUMNS IN hr_positions:\n";
    $stmt = $conn->query("DESCRIBE hr_positions");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine();
}
