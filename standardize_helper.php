<?php
$file = 'app/assets/js/core/helper.js';
$content = file_get_contents($file);

// 1. Correct initDataTable (reset to original)
$bad_init = '/initDataTable: \(selector, options = \{\}\) => \{.*?const settings = \{[^}]+\};.*?if \(options\.body instanceof FormData\) \{[^}]+\}.*?return \$\(selector\)\.DataTable\(settings\);/s';

// Simplified check and replace for initDataTable
$start_init = "initDataTable: (selector, options = {}) => {";
$end_init = "return $(selector).DataTable(settings);";
$pos_start = strpos($content, $start_init);
$pos_end = strpos($content, $end_init, $pos_start);

if ($pos_start !== false && $pos_end !== false) {
    $before = substr($content, 0, $pos_start);
    $after = substr($content, $pos_end + strlen($end_init));

    $clean_init = "initDataTable: (selector, options = {}) => {
        // Destroy if exists to prevent errors
        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        }

        const defaults = {
            language: {
                url: '/pae/app/assets/plugins/datatables/es-ES.json' // Load from local
            },
            responsive: true,
            // Bootstrap 5 Friendly Layout: Search (f) top, Table (t) middle, Info (i) & Pagination (p) bottom
            dom: '<\"d-flex justify-content-between align-items-center mb-3\"f>t<\"d-flex justify-content-between align-items-center mt-3\"ip>',
            pageLength: 5,
            lengthChange: false
        };

        // Merge defaults with user options (User options take precedence)
        const settings = { ...defaults, ...options };

        return $(selector).DataTable(settings);";

    $content = $before . $clean_init . $after;
}

// 2. Correct fetchAPI (ensure FormData fix is ONLY there)
// First, strip any existing FormData fix from fetchAPI to start clean if multi-applied
$content = str_replace("// If body is FormData, do NOT set Content-Type (let the browser do it with boundary)\n        if (options.body instanceof FormData) {\n            delete settings.headers['Content-Type'];\n        }", "", $content);

$fetch_marker = "const settings = {
            ...options,
            headers: {
                ...defaultHeaders,
                ...(options.headers || {})
            }
        };";

$fetch_fix = $fetch_marker . "\n\n        // If body is FormData, do NOT set Content-Type (let the browser do it with boundary)
        if (options.body instanceof FormData) {
            delete settings.headers['Content-Type'];
        }";

if (strpos($content, $fetch_marker) !== false) {
    $content = str_replace($fetch_marker, $fetch_fix, $content);
}

file_put_contents($file, $content);
echo "SUCCESS: helper.js standardized.\n";

// Bump version to 1.0.5
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.0.5'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.0.5.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.0.5\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.0.5.\n";
