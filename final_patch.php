<?php
// Patch api/index.php
$api_index = 'api/index.php';
$content = file_get_contents($api_index);
$marker = "} else {\n    http_response_code(404);\n    echo json_encode([\"message\" => \"Permission Action Not Found\"]);\n}";
$new_routes = "} else {\n        http_response_code(404);\n        echo json_encode([\"message\" => \"Permission Action Not Found\"]);\n    }\n} elseif (\$resource === 'schools') {\n    \$controller = new \\Controllers\\SchoolController();\n    if (\$_SERVER['REQUEST_METHOD'] === 'GET') {\n        \$controller->index();\n    } elseif (\$_SERVER['REQUEST_METHOD'] === 'POST') {\n        \$controller->create();\n    } elseif (\$_SERVER['REQUEST_METHOD'] === 'PUT' || (\$_SERVER['REQUEST_METHOD'] === 'POST' && \$action && \$_POST['_method'] === 'PUT')) {\n        \$id = \$action ?: (\$_POST['id'] ?? null);\n        \$controller->update(\$id);\n    } elseif (\$_SERVER['REQUEST_METHOD'] === 'DELETE' && \$action) {\n        \$controller->delete(\$action);\n    } else {\n        http_response_code(405);\n        echo json_encode([\"message\" => \"Method Not Allowed\"]);\n    }\n} elseif (\$resource === 'branches') {\n    \$controller = new \\Controllers\\BranchController();\n    if (\$_SERVER['REQUEST_METHOD'] === 'GET') {\n        \$school_id = isset(\$uri[4]) ? \$uri[4] : null;\n        \$controller->index(\$school_id);\n    } elseif (\$_SERVER['REQUEST_METHOD'] === 'POST') {\n        \$controller->create();\n    } elseif (\$_SERVER['REQUEST_METHOD'] === 'PUT' && \$action) {\n        \$controller->update(\$action);\n    } elseif (\$_SERVER['REQUEST_METHOD'] === 'DELETE' && \$action) {\n        \$controller->delete(\$action);\n    } else {\n        http_response_code(405);\n        echo json_encode([\"message\" => \"Method Not Allowed\"]);\n    }\n}";

if (strpos($content, 'elseif ($resource === \'schools\')') === false) {
    if (strpos($content, $marker) !== false) {
        $content = str_replace($marker, $new_routes, $content);
        file_put_contents($api_index, $content);
        echo "SUCCESS: api/index.php patched.\n";
    } else {
        echo "ERROR: Marker not found in api/index.php.\n";
    }
} else {
    echo "NOTE: api/index.php already patched.\n";
}

// Patch config.js
$config_js = 'app/assets/js/core/config.js';
$content = file_get_contents($config_js);
$content = preg_replace("/VERSION: '.*'/", "VERSION: '1.0.3'", $content);
file_put_contents($config_js, $content);
echo "SUCCESS: config.js updated to 1.0.3.\n";

// Patch app/index.php
$app_index = 'app/index.php';
$content = file_get_contents($app_index);
$content = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.0.3\"", $content);
file_put_contents($app_index, $content);
echo "SUCCESS: app/index.php updated to 1.0.3.\n";
