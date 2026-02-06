<?php
header('Content-Type: application/json');
echo json_encode([
    'SERVER' => $_SERVER,
    'HEADERS' => function_exists('apache_request_headers') ? apache_request_headers() : 'N/A'
]);
