<?php
// 1. Fix api/index.php for branches redirect
$file = 'api/index.php';
$content = file_get_contents($file);
$old = "\$school_id = isset(\$uri[4]) ? \$uri[4] : null;";
$new = "\$school_id = isset(\$uri[4]) ? \$uri[4] : (\$_GET['school_id'] ?? null);";
$content = str_replace($old, $new, $content);
file_put_contents($file, $content);
echo "SUCCESS: api/index.php updated for branch queries.\n";

// 2. Fix schools.js for auto-refresh and auto-select
$file_js = 'app/assets/js/views/schools.js';
$js = file_get_contents($file_js);
// In saveSchool, after loadSchools, try to select the new/edited school
$js = str_replace(
    "await this.loadSchools();",
    "await this.loadSchools();\n                if (id) {\n                    this.selectSchool(id);\n                } else if (this.schools.length > 0) {\n                    // If new, select the first one (usually the one just added if sorted by ID desc, but it's ASC. Maybe select by max ID)\n                    const maxId = Math.max(...this.schools.map(s => s.id));\n                    this.selectSchool(maxId);\n                }",
    $js
);
file_put_contents($file_js, $js);
echo "SUCCESS: schools.js updated for auto-selection.\n";

// 3. Bump version to 1.0.6
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.0.6'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.0.6.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.0.6\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.0.6.\n";
