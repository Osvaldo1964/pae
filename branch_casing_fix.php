<?php
// 1. Update schools.js for branch casing
$file_js = 'app/assets/js/views/schools.js';
$js = file_get_contents($file_js);

// Update saveBranch inside schools.js
$old_branch_data = "name: document.getElementById('branch-name').value,
            manager_name: document.getElementById('branch-manager').value,";
$new_branch_data = "name: document.getElementById('branch-name').value.toUpperCase(),
            manager_name: document.getElementById('branch-manager').value.toUpperCase(),";

if (strpos($js, $old_branch_data) !== false) {
    $js = str_replace($old_branch_data, $new_branch_data, $js);
    echo "SUCCESS: schools.js branch casing handled.\n";
}

file_put_contents($file_js, $js);

// 2. Update BranchController.php for backend enforcement
$file_controller = 'api/controllers/BranchController.php';
$controller = file_get_contents($file_controller);

// Inject casing logic at the beginning of create and update methods
$casing_injection = "
        // Enforce casing
        if (isset(\$data->name)) \$data->name = mb_strtoupper(\$data->name, 'UTF-8');
        if (isset(\$data->manager_name)) \$data->manager_name = mb_strtoupper(\$data->manager_name, 'UTF-8');
";

// If not already present (check to avoid double injection)
if (strpos($controller, 'mb_strtoupper($data->name') === false) {
    $controller = str_replace('$data = json_decode(file_get_contents("php://input"));', '$data = json_decode(file_get_contents("php://input"));' . $casing_injection, $controller);
    echo "SUCCESS: BranchController.php branch casing handled.\n";
}

file_put_contents($file_controller, $controller);

// 3. Bump version to 1.1.7
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.7'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.7.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.7\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.7.\n";
