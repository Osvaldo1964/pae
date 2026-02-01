<?php
require_once __DIR__ . '/api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();

echo "--- USERS TABLE SCHEMA ---\n";
$stmt = $db->query("DESCRIBE users");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}

echo "\n--- USER ID 4 DATA ---\n";
$stmt = $db->prepare("SELECT * FROM users WHERE id = 4");
$stmt->execute();
print_r($stmt->fetch(PDO::FETCH_ASSOC));

echo "\n--- RECENT PHP ERRORS ---\n";
$log = 'api/php_errors.log';
if (file_exists($log)) {
    $lines = file($log);
    echo implode("", array_slice($lines, -20));
} else {
    echo "Log file not found at $log";
}
