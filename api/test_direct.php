<?php
// Direct test bypassing routing
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Direct ItemController Test ===\n\n";

// Set up environment
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_AUTHORIZATION'] = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkYXRhIjp7ImlkIjoxLCJ1c2VybmFtZSI6ImFkbWluIiwicm9sZV9pZCI6MSwicGFlX2lkIjpudWxsfSwiaWF0IjoxNzM4NDQzNjAwLCJleHAiOjE3Mzg0NDcyMDB9.test';

try {
    require_once __DIR__ . '/config/Database.php';
    require_once __DIR__ . '/controllers/ItemController.php';

    $controller = new \Controllers\ItemController();

    echo "Controller instantiated successfully\n\n";
    echo "Calling getFoodGroups()...\n";

    ob_start();
    $controller->getFoodGroups();
    $output = ob_get_clean();

    echo "Output:\n";
    echo $output . "\n";

    $decoded = json_decode($output, true);
    if ($decoded) {
        echo "\nJSON is valid!\n";
        print_r($decoded);
    } else {
        echo "\nJSON decode error: " . json_last_error_msg() . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
