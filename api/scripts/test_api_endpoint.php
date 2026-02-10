<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../utils/JWT.php';

use Config\Database;
use Utils\JWT;

// Simulate Login and Get Token for Program 4
$payload = [
    "data" => [
        "pae_id" => 4
    ]
];
$token = JWT::encode($payload);

echo "Token: $token\n\n";

// Now simulate the index method call
$_SERVER['Authorization'] = "Bearer $token";

require_once __DIR__ . '/../controllers/BaseController.php';
require_once __DIR__ . '/../controllers/BeneficiaryController.php';

$controller = new Controllers\BeneficiaryController();

ob_start();
$controller->index();
$output = ob_get_clean();

echo "Response length: " . strlen($output) . " bytes\n";
$decoded = json_decode($output, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "JSON DECODE OK. Count: " . count($decoded) . "\n";
    if (count($decoded) > 0) {
        echo "Example item keys: " . implode(", ", array_keys($decoded[0])) . "\n";
    }
} else {
    echo "JSON DECODE ERROR: " . json_last_error_msg() . "\n";
    echo "Output sample: " . substr($output, 0, 500) . "...\n";
}
