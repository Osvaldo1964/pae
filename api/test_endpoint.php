<?php
// Simple test to see the actual error
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing direct endpoint call...\n\n";

// Simulate the API call
$_SERVER['REQUEST_METHOD'] = 'GET';
$resource = 'items';
$action = 'food-groups';

require_once __DIR__ . '/index.php';
