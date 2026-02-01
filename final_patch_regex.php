<?php
$file = 'api/index.php';
$content = file_get_contents($file);

if (strpos($content, 'elseif ($resource === \'schools\')') !== false) {
    die("ALREADY PATCHED\n");
}

$search = '/\}\s*else\s*\{\s*http_response_code\(404\);\s*echo\s*json_encode\(\["message"\s*=>\s*"Resource Not Found",\s*"resource"\s*=>\s*\$resource\]\);\s*\}/';

$replace = "} elseif (\$resource === 'schools') {
    \$controller = new \\Controllers\\SchoolController();
    if (\$_SERVER['REQUEST_METHOD'] === 'GET') {
        \$controller->index();
    } elseif (\$_SERVER['REQUEST_METHOD'] === 'POST') {
        \$controller->create();
    } elseif (\$_SERVER['REQUEST_METHOD'] === 'PUT' || (\$_SERVER['REQUEST_METHOD'] === 'POST' && \$action && \$_POST['_method'] === 'PUT')) {
        \$id = \$action ?: (\$_POST['id'] ?? null);
        \$controller->update(\$id);
    } elseif (\$_SERVER['REQUEST_METHOD'] === 'DELETE' && \$action) {
        \$controller->delete(\$action);
    } else {
        http_response_code(405);
        echo json_encode([\"message\" => \"Method Not Allowed\"]);
    }
} elseif (\$resource === 'branches') {
    \$controller = new \\Controllers\\BranchController();
    if (\$_SERVER['REQUEST_METHOD'] === 'GET') {
        \$school_id = isset(\$uri[4]) ? \$uri[4] : null;
        \$controller->index(\$school_id);
    } elseif (\$_SERVER['REQUEST_METHOD'] === 'POST') {
        \$controller->create();
    } elseif (\$_SERVER['REQUEST_METHOD'] === 'PUT' && \$action) {
        \$controller->update(\$action);
    } elseif (\$_SERVER['REQUEST_METHOD'] === 'DELETE' && \$action) {
        \$controller->delete(\$action);
    } else {
        http_response_code(405);
        echo json_encode([\"message\" => \"Method Not Allowed\"]);
    }
} else {
    http_response_code(404);
    echo json_encode([\"message\" => \"Resource Not Found\", \"resource\" => \$resource]);
}";

$newContent = preg_replace($search, $replace, $content);

if ($newContent !== $content) {
    file_put_contents($file, $newContent);
    echo "SUCCESS: api/index.php patched with regex.\n";
} else {
    echo "ERROR: Regex did not match.\n";
}
