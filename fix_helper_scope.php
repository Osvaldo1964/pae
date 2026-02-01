<?php
$file = 'app/assets/js/core/helper.js';
$content = file_get_contents($file);

// 1. Fix initDataTable (Lines 25-31 in current file)
// Revert to original: const settings = { ...defaults, ...options };
$bad_pattern = '/\/\/ Merge defaults with user options \(User options take precedence\)\s+const settings = \{[^}]+\};/s';
$good_init = "// Merge defaults with user options (User options take precedence)\n        const settings = { ...defaults, ...options };";

$content = preg_replace($bad_pattern, $good_init, $content);

// 2. Apply FormData fix to fetchAPI (correctly this time)
$fetch_pattern = '/(fetchAPI: async \(endpoint, options = \{\}\) => \{.*?const settings = \{[^}]+\};)/s';
$fetch_replacement = '$1

        // If body is FormData, do NOT set Content-Type (let the browser do it with boundary)
        if (options.body instanceof FormData) {
            delete settings.headers[\'Content-Type\'];
        }';

// We must be careful not to double-apply. 
// Check if it's already in fetchAPI scope
if (strpos($content, "if (options.body instanceof FormData)") === false) {
    $content = preg_replace($fetch_pattern, $fetch_replacement, $content);
}

file_put_contents($file, $content);
echo "SUCCESS: helper.js fixed correctly.\n";
