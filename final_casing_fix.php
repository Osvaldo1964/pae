<?php
// 1. Fix api/index.php routing for schools
$file = 'api/index.php';
$content = file_get_contents($file);

$old_dispatch = "} elseif (\$resource === 'schools') {
    \$controller = new \\Controllers\\SchoolController();
    if (\$_SERVER['REQUEST_METHOD'] === 'GET') {
        \$controller->index();
    } elseif (\$_SERVER['REQUEST_METHOD'] === 'POST') {
        \$controller->create();
    } elseif (\$_SERVER['REQUEST_METHOD'] === 'PUT' || (\$_SERVER['REQUEST_METHOD'] === 'POST' && \$action && \$_POST['_method'] === 'PUT')) {
        \$id = \$action ?: (\$_POST['id'] ?? null);
        \$controller->update(\$id);";

$new_dispatch = "} elseif (\$resource === 'schools') {
    \$controller = new \\Controllers\\SchoolController();
    if (\$_SERVER['REQUEST_METHOD'] === 'GET') {
        \$controller->index();
    } elseif (\$_SERVER['REQUEST_METHOD'] === 'POST' && (!\$action || \$action === '')) {
        \$controller->create();
    } elseif (\$action === 'update' || \$_SERVER['REQUEST_METHOD'] === 'PUT' || (\$_SERVER['REQUEST_METHOD'] === 'POST' && \$_POST['_method'] === 'PUT')) {
        \$id = (\$action === 'update') ? (\$uri[5] ?? null) : (\$action ?: (\$_POST['id'] ?? null));
        \$controller->update(\$id);";

$content = str_replace($old_dispatch, $new_dispatch, $content);
file_put_contents($file, $content);
echo "SUCCESS: api/index.php routing fixed.\n";

// 2. Add casing logic to SchoolController.php
$file_school = 'api/controllers/SchoolController.php';
$school_content = file_get_contents($file_school);
// Inject casing logic at start of create and update
$casing_logic = "
        // Enforce casing
        if (isset(\$data['name'])) \$data['name'] = mb_strtoupper(\$data['name'], 'UTF-8');
        if (isset(\$data['rector'])) \$data['rector'] = mb_strtoupper(\$data['rector'], 'UTF-8');
        if (isset(\$data['email'])) \$data['email'] = strtolower(\$data['email']);
";
$school_content = str_replace("\$data = \$_POST;", "\$data = \$_POST;" . $casing_logic, $school_content);
file_put_contents($file_school, $school_content);
echo "SUCCESS: SchoolController.php casing enforced.\n";

// 3. Add casing logic to BranchController.php
$file_branch = 'api/controllers/BranchController.php';
$branch_content = file_get_contents($file_branch);
$casing_logic_branch = "
        // Enforce casing
        if (isset(\$data->name)) \$data->name = mb_strtoupper(\$data->name, 'UTF-8');
        if (isset(\$data->manager_name)) \$data->manager_name = mb_strtoupper(\$data->manager_name, 'UTF-8');
";
$branch_content = str_replace("\$data = json_decode(file_get_contents(\"php://input\"));", "\$data = json_decode(file_get_contents(\"php://input\"));" . $casing_logic_branch, $branch_content);
file_put_contents($file_branch, $branch_content);
echo "SUCCESS: BranchController.php casing enforced.\n";

// 4. Update schools.js to enforce casing in UI
$file_js = 'app/assets/js/views/schools.js';
$js = file_get_contents($file_js);
$js = str_replace("formData.append('name', document.getElementById('school-name').value);", "formData.append('name', document.getElementById('school-name').value.toUpperCase());", $js);
$js = str_replace("formData.append('rector', document.getElementById('school-rector').value);", "formData.append('rector', document.getElementById('school-rector').value.toUpperCase());", $js);
$js = str_replace("formData.append('email', document.getElementById('school-email').value);", "formData.append('email', document.getElementById('school-email').value.toLowerCase());", $js);
$js = str_replace("name: document.getElementById('branch-name').value,", "name: document.getElementById('branch-name').value.toUpperCase(),", $js);
$js = str_replace("manager_name: document.getElementById('branch-manager').value,", "manager_name: document.getElementById('branch-manager').value.toUpperCase(),", $js);
file_put_contents($file_js, $js);
echo "SUCCESS: schools.js UI casing enforced.\n";

// 5. Bump version to 1.1.3
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.3'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.3.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.3\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.3.\n";
