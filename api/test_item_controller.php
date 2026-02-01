<?php
// Direct test of ItemController
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/ItemController.php';

use Controllers\ItemController;

echo "Testing ItemController...\n\n";

try {
    $controller = new ItemController();
    echo "✅ ItemController instantiated successfully\n\n";

    echo "--- Testing getFoodGroups() ---\n";
    ob_start();
    $controller->getFoodGroups();
    $output = ob_get_clean();

    echo "Output: " . $output . "\n";

    $data = json_decode($output, true);
    if ($data && isset($data['success'])) {
        echo "✅ JSON valid\n";
        echo "Success: " . ($data['success'] ? 'true' : 'false') . "\n";
        if (isset($data['data'])) {
            echo "Data count: " . count($data['data']) . "\n";
        }
    } else {
        echo "❌ Invalid JSON or structure\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
