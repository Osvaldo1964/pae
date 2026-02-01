<?php
$file = 'app/assets/js/core/app.js';
$content = file_get_contents($file);
$old = "'sedes_educativas': 'schools',";
$new = "'sedes': 'schools',\n                    'sedes_educativas': 'schools',";

if (strpos($content, $old) !== false) {
    $content = str_replace($old, $new, $content);
    file_put_contents($file, $content);
    echo "SUCCESS: app.js patched.\n";
} else {
    // Try without the comma or with different spacing
    $old2 = "'sedes_educativas': 'schools'";
    if (strpos($content, $old2) !== false) {
        $content = str_replace($old2, "'sedes': 'schools',\n                    'sedes_educativas': 'schools'", $content);
        file_put_contents($file, $content);
        echo "SUCCESS: app.js patched (fallback).\n";
    } else {
        echo "ERROR: Target string not found.\n";
    }
}
