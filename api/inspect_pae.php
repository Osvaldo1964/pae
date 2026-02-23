<?php
require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

try {
    $conn = \Config\Database::getInstance()->getConnection();
    echo "COLUMNS IN pae_programs:\n";
    $stmt = $conn->query("DESCRIBE pae_programs");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }

    echo "\nCOLUMNS IN beneficiaries:\n";
    $stmt = $conn->query("DESCRIBE beneficiaries");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage();
}
