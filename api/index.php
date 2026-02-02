<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
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

$resource = isset($uri[3]) ? $uri[3] : null; // pae/api/RESOURCE
$action = isset($uri[4]) ? $uri[4] : null; // pae/api/resource/ACTION

if ($resource === 'auth') {
    $controller = new \Controllers\AuthController();
    if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->login();
    } elseif ($action === 'select_tenant' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->selectTenant(); // New Endpoint
    } elseif ($action === 'menu' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->getMenu();
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Auth Action Not Found"]);
    }
} elseif ($resource === 'tenant') {
    $controller = new \Controllers\TenantController();
    if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->register();
    } elseif ($action === 'list' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        // Require JWT for list endpoint
        require_once __DIR__ . '/utils/JWT.php';
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
            exit;
        }

        $token = $matches[1];
        $decoded = \Utils\JWT::decode($token);

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token inválido']);
            exit;
        }

        require_once __DIR__ . '/controllers/TenantManagementController.php';
        $db = \Config\Database::getInstance()->getConnection();
        $mgmtController = new \Controllers\TenantManagementController($db);
        $mgmtController->listAll($decoded['data']);
    } elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update PAE program (Changed from PUT to POST to support multipart/form-data in PHP)
        require_once __DIR__ . '/utils/JWT.php';
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
            exit;
        }

        $token = $matches[1];
        $decoded = \Utils\JWT::decode($token);

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token inválido']);
            exit;
        }

        $paeId = isset($uri[5]) ? $uri[5] : null;
        if (!$paeId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de programa requerido']);
            exit;
        }

        require_once __DIR__ . '/controllers/TenantManagementController.php';
        $db = \Config\Database::getInstance()->getConnection();
        $mgmtController = new \Controllers\TenantManagementController($db);
        $mgmtController->update($paeId, $decoded['data']);
    } elseif ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Delete PAE program
        require_once __DIR__ . '/utils/JWT.php';
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
            exit;
        }

        $token = $matches[1];
        $decoded = \Utils\JWT::decode($token);

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Token inválido']);
            exit;
        }

        $paeId = isset($uri[5]) ? $uri[5] : null;
        if (!$paeId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de programa requerido']);
            exit;
        }

        require_once __DIR__ . '/controllers/TenantManagementController.php';
        $db = \Config\Database::getInstance()->getConnection();
        $mgmtController = new \Controllers\TenantManagementController($db);
        $mgmtController->delete($paeId, $decoded['data']);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Tenant Action Not Found"]);
    }
} elseif ($resource === 'public') {
    $controller = new \Controllers\PublicController();
    if ($action === 'pqr' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->submitPQR();
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Public Action Not Found"]);
    }
} elseif ($resource === 'users') {
    require_once __DIR__ . '/utils/JWT.php';
    $controller = new \Controllers\UserController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->index();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->create();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $action) {
        $controller->update($action);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action) {
        $controller->delete($action);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
    }
} elseif ($resource === 'team') {
    require_once __DIR__ . '/utils/JWT.php';
    $controller = new \Controllers\TeamController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->index();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->create();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $action) {
        $controller->update($action);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action) {
        $controller->delete($action);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
    }
} elseif ($resource === 'roles') {
    $controller = new \Controllers\RoleController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->index();
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
    }
} elseif ($resource === 'permissions') {
    require_once __DIR__ . '/controllers/PermissionController.php';
    require_once __DIR__ . '/utils/JWT.php';

    // Validate JWT for all permission endpoints
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
        exit;
    }

    $token = $matches[1];
    $decoded = \Utils\JWT::decode($token);

    if (!$decoded) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token inválido']);
        exit;
    }

    $db = \Config\Database::getInstance()->getConnection();
    $controller = new PermissionController($db);

    // GET /api/permissions/roles - List all roles
    if ($action === 'roles' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $result = $controller->getRoles($decoded['data']);
        echo json_encode($result);
    }
    // GET /api/permissions/modules - Get all modules
    elseif ($action === 'modules' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $result = $controller->getModules();
        echo json_encode($result);
    }
    // GET /api/permissions/matrix/{role_id} - Get permission matrix
    elseif ($action === 'matrix' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $roleId = isset($uri[5]) ? $uri[5] : null;
        if (!$roleId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'role_id requerido']);
            exit;
        }
        $result = $controller->getPermissionMatrix($roleId, $decoded['data']);
        echo json_encode($result);
    }
    // PUT /api/permissions/update - Update permissions
    elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $controller->updatePermissions($data, $decoded['data']);
        echo json_encode($result);
    }
    // POST /api/permissions/roles - Create role (Super Admin only)
    elseif ($action === 'roles' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $controller->createRole($data, $decoded['data']);
        echo json_encode($result);
    }
    // DELETE /api/permissions/roles/{id} - Delete role (Super Admin only)
    elseif ($action === 'roles' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $roleId = isset($uri[5]) ? $uri[5] : null;
        if (!$roleId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'role_id requerido']);
            exit;
        }
        $result = $controller->deleteRole($roleId, $decoded['data']);
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Permission Action Not Found"]);
    }
} elseif ($resource === 'schools') {
    $controller = new \Controllers\SchoolController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->index();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && (!$action || $action === '')) {
        $controller->create();
    } elseif ($action === 'update' || $_SERVER['REQUEST_METHOD'] === 'PUT' || ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['_method'] === 'PUT')) {
        $id = ($action === 'update') ? ($uri[5] ?? null) : ($action ?: ($_POST['id'] ?? null));
        $controller->update($id);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action) {
        $controller->delete($action);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
    }
} elseif ($resource === 'branches') {
    $controller = new \Controllers\BranchController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $school_id = isset($uri[4]) ? $uri[4] : ($_GET['school_id'] ?? null);
        $controller->index($school_id);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->create();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $action) {
        $controller->update($action);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action) {
        $controller->delete($action);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
    }
} elseif ($resource === 'proveedores') {
    $controller = new \Controllers\SupplierController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->index();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->create();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $action) {
        $controller->update($action);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action) {
        $controller->delete($action);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
    }
} elseif ($resource === 'beneficiarios') {
    $controller = new \Controllers\BeneficiaryController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action === 'document_types') {
            $controller->getDocumentTypes();
        } elseif ($action === 'ethnic_groups') {
            $controller->getEthnicGroups();
        } else {
            $controller->index();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->create();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $action) {
        $controller->update($action);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action) {
        $controller->delete($action);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
    }
} elseif ($resource === 'items') {
    $controller = new \Controllers\ItemController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action === 'food-groups') {
            $controller->getFoodGroups();
        } elseif ($action === 'measurement-units') {
            $controller->getMeasurementUnits();
        } elseif ($action && is_numeric($action)) {
            $controller->show($action);
        } else {
            $controller->index();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $action) {
        $controller->update($action);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $action) {
        $controller->destroy($action);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
    }
} elseif ($resource === 'menu-cycles') {
    $controller = new \Controllers\MenuController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action && is_numeric($action)) {
            $controller->getCycleDays($action);
        } else {
            $controller->getCycles();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->storeCycle();
    }
} elseif ($resource === 'menus') {
    $controller = new \Controllers\MenuController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action && is_numeric($action)) {
        $controller->getMenuDetail($action);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $action && is_numeric($action)) {
        $controller->update($action);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action && is_numeric($action) && strpos($_SERVER['REQUEST_URI'], '/items') !== false) {
        $controller->manageItems($action);
    }
} elseif ($resource === 'inventory') {
    $controller = new \Controllers\WarehouseController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->getStock();
    }
} elseif ($resource === 'suppliers') {
    $controller = new \Controllers\WarehouseController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->getSuppliers();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->saveSupplier();
    }
} elseif ($resource === 'movements') {
    $controller = new \Controllers\WarehouseController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->getMovements();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->registerMovement();
    }
} elseif ($resource === 'recipes') {
    $controller = new \Controllers\RecipeController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action && is_numeric($action)) {
            $controller->show($action);
        } else {
            $controller->index();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if ($action && is_numeric($action)) {
            $controller->update($action);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if ($action && is_numeric($action)) {
            $controller->delete($action);
        }
    }
} elseif ($resource === 'cycle-templates') {
    $controller = new \Controllers\CycleTemplateController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action && is_numeric($action)) {
            $controller->show($action);
        } else {
            $controller->index();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        if ($action && is_numeric($action)) {
            $controller->update($action);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if ($action && is_numeric($action)) {
            $controller->delete($action);
        }
    }
} elseif ($resource === 'menu-cycles') {
    $controller = new \Controllers\MenuCycleController();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller->index();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($action === 'generate') {
            $controller->generate();
        } else {
            $controller->store();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if ($action && is_numeric($action)) {
            $controller->delete($action);
        }
    }
} else {
    http_response_code(404);
    echo json_encode(["message" => "Resource Not Found", "resource" => $resource]);
}
