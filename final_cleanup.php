<?php
// 1. Bump version to 1.1.8
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.8'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.8.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.8\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.8.\n";

// 2. Clean up temporary scripts
unlink('apply_refinement_1.php');
unlink('apply_refinement_2.php');
echo "SUCCESS: Cleaned up temporary scripts.\n";
