<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

try {
    $conn = \Config\Database::getInstance()->getConnection();

    echo "TEST 1: SELECT FROM hr_employees\n";
    $res1 = $conn->query("SELECT id FROM hr_employees LIMIT 1")->fetch();
    echo "Result 1: " . ($res1 ? "Success" : "Empty") . "\n";

    echo "TEST 2: SELECT FROM hr_positions\n";
    $res2 = $conn->query("SELECT id FROM hr_positions LIMIT 1")->fetch();
    echo "Result 2: " . ($res2 ? "Success" : "Empty") . "\n";

    echo "TEST 3: SELECT p.name FROM hr_positions p\n";
    $res3 = $conn->query("SELECT name FROM hr_positions LIMIT 1")->fetch();
    echo "Result 3: " . ($res3 ? "Name: " . $res3['name'] : "Empty/Fail") . "\n";

} catch (Throwable $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}
