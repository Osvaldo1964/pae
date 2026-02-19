<?php
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';
use Config\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Check by grade name
    $sql = "SELECT COUNT(*) FROM beneficiaries WHERE grade = 'TEST_GRADE'";
    $stmt = $db->query($sql);

    if (!$stmt) {
        print_r($db->errorInfo());
        exit(1);
    }

    $count = $stmt->fetchColumn();
    echo "Test beneficiaries count (by grade): $count\n";

} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
