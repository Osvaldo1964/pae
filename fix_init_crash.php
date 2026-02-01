<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// 1. Remove the injected logic from init()
$target_init = '/async init\(\) \{(\s+)await this\.loadSchools\(\);\s+if \(id\) \{.*?\}\s+\},/s';
$clean_init = "async init() {\n        await this.loadSchools();\n    },";

$content = preg_replace($target_init, $clean_init, $content);

file_put_contents($file, $content);
echo "SUCCESS: schools.js init fixed.\n";

// 2. Bump version to 1.0.7
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.0.7'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.0.7.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.0.7\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.0.7.\n";
