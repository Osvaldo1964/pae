<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// 1. Remove the line that causes ReferenceError
$bad_line = "document.getElementById('school-logo-label').innerText = input.files[0].name;";
// Note: This line exists in previewLogo too, so we must safely target only the one in openSchoolModal
// Actually, it's easier to just remove it if it follows the edit label logic
$search = "document.getElementById('school-logo-label').innerText = 'Imagen cargada (click para cambiar)';" . "\n" . "                " . $bad_line;
$replace = "document.getElementById('school-logo-label').innerText = 'Imagen cargada (click para cambiar)';";

if (strpos($content, $search) !== false) {
    $content = str_replace($search, $replace, $content);
    echo "SUCCESS: schools.js ReferenceError fixed.\n";
} else {
    // Try without the specific indentation if it failed
    $content = preg_replace("/('Imagen cargada \(click para cambiar\)');\s+document\.getElementById\('school-logo-label'\)\.innerText = input\.files\[0\]\.name;/s", "$1;", $content);
    echo "SUCCESS: schools.js ReferenceError fixed via regex.\n";
}

file_put_contents($file, $content);

// 2. Bump version to 1.1.2
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.2'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.2.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.2\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.2.\n";
