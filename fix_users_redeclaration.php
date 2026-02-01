<?php
$file = 'app/assets/js/views/users.js';
$content = file_get_contents($file);

// Replace const with var to allow dynamic reloading without SyntaxError
$content = str_replace('const UsersView = {', 'var UsersView = {', $content);

file_put_contents($file, $content);
echo "SUCCESS: users.js fixed (var used).\n";

// Bump version to 1.1.9
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.9'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.9.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.9\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.9.\n";
