<?php
// Test Script for Consumption API
// Usage: php tests/test_consumption.php

// 1. Login to get Token
$url_base = "http://localhost/pae/api";
echo "1. AUTHENTICATING...\n";
$ch = curl_init($url_base . "/auth/login");
// WARNING: Adjust credentials to a known valid user for testing
$credentials = json_encode(["username" => "testuser", "password" => "test1234"]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $credentials);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$auth_data = json_decode($response, true);
if ($http_code !== 200 || !isset($auth_data['token'])) {
    echo "Auth Failed: " . $response . "\n";
    exit(1);
}
$token = $auth_data['token'];
echo "Token received.\n";

// 2. Mock Data
// We need a valid beneficiary ID and Branch ID. 
// For this test script to work generically, we might need to fetch them or assume ID=1.
// Let's try to fetch beneficiaries first to get a valid ID.
echo "2. FETCHING BENEFICIARIES...\n";
$ch = curl_init($url_base . "/beneficiarios");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
$response = curl_exec($ch);
$bens = json_decode($response, true);
curl_close($ch);

if (empty($bens) || !isset($bens[0]['id'])) {
    echo "Ben Response: " . $response . "\n";
    echo "No beneficiaries found to test with.\n";
    exit(1);
}

$ben_id = $bens[0]['id'];
$branch_id = $bens[0]['branch_id'];
$ben_name = $bens[0]['first_name'];
echo "Selected Beneficiary: $ben_name (ID: $ben_id) Branch: $branch_id\n";

// 3. Register Consumption
echo "3. REGISTERING CONSUMPTION (ALMUERZO)...\n";
$payload = json_encode([
    "beneficiary_id" => $ben_id,
    "branch_id" => $branch_id,
    "meal_type" => "ALMUERZO" // Ensure this matches ENUM
]);
$ch = curl_init($url_base . "/consumptions");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "Authorization: Bearer $token"
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Response Code: $http_code\n";
echo "Response Body: $response\n";

// 4. Test Duplicate Prevention
echo "4. TESTING DUPLICATE PREVENTION...\n";
$ch = curl_init($url_base . "/consumptions");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "Authorization: Bearer $token"
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Duplicate Test Code: $http_code (Expected 409)\n";
echo "Response Body: $response\n";

// 5. Get Stats
echo "5. GETTING STATS...\n";
$ch = curl_init($url_base . "/consumptions/stats?branch_id=$branch_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
$response = curl_exec($ch);
$stats = json_decode($response, true);
curl_close($ch);

print_r($stats);
