<?php
require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

try {
    $conn = \Config\Database::getInstance()->getConnection();
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "TABLES:\n" . implode("\n", $tables) . "\n\n";

    foreach ($tables as $table) {
        if (strpos($table, 'hr_') === 0) {
            echo "--- $table ---\n";
            $stmt = $conn->query("DESCRIBE $table");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "  {$row['Field']} ({$row['Type']})\n";
            }
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
