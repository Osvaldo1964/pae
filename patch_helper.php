<?php
$file = 'app/assets/js/core/helper.js';
$content = file_get_contents($file);

$target = "const settings = {\n            ...options,\n            headers: {\n                ...defaultHeaders,\n                ...(options.headers || {})\n            }\n        };";

$replacement = "const settings = {
            ...options,
            headers: {
                ...defaultHeaders,
                ...(options.headers || {})
            }
        };

        // If body is FormData, do NOT set Content-Type (let the browser do it with boundary)
        if (options.body instanceof FormData) {
            delete settings.headers['Content-Type'];
        }";

if (strpos($content, "...options,") !== false && strpos($content, "headers: {") !== false) {
    // Use a more flexible search if exact match fails
    $content = preg_replace(
        '/const settings = \{[^}]+\};/s',
        $replacement,
        $content,
        1
    );
    file_put_contents($file, $content);
    echo "SUCCESS: helper.js patched.\n";
} else {
    echo "ERROR: Target pattern not found in helper.js.\n";
}

// Bump version to 1.0.4
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.0.4'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.0.4.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.0.4\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.0.4.\n";
