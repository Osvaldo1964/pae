<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

spl_autoload_register(function ($class_name) {
    $base_dir = __DIR__ . '/';
    $prefix_map = [
        'Config\\' => 'config/',
        'Controllers\\' => 'controllers/',
        'Models\\' => 'models/',
        'Utils\\' => 'utils/',
        'Middleware\\' => 'middleware/'
    ];

    foreach ($prefix_map as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class_name, $len) === 0) {
            $relative_class = substr($class_name, $len);
            $file = $base_dir . $dir . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$resource = isset($uri[3]) ? $uri[3] : null;
$action = isset($uri[4]) ? $uri[4] : null;

if ($resource === 'auth') {
    $controller = new \Controllers\AuthController();
    if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->login();
    } elseif ($action === 'menu' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->getMenu();
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Auth Action Not Found"]);
    }
} elseif ($resource === 'public') {
    $controller = new \Controllers\PublicController();
    if ($action === 'pqr' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->submitPQR();
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Public Action Not Found"]);
    }
} else {
    echo json_encode(["message" => "PAE API v1.0 Running", "resource" => $resource]);
}
