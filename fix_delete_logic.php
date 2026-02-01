<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// Fix deleteSchool: it shouldn't try to select the ID it just deleted
$bad_delete_logic = "await this.loadSchools();
                if (id) {
                    this.selectSchool(id);
                } else if (this.schools.length > 0) {
                    // If new, select the first one (usually the one just added if sorted by ID desc, but it's ASC. Maybe select by max ID)
                    const maxId = Math.max(...this.schools.map(s => s.id));
                    this.selectSchool(maxId);
                }";

// Only keep it for saveSchool, remove from deleteSchool
// To be safe, let's replace specifically inside deleteSchool function
$start_token = "async deleteSchool(id) {";
$end_token = "if (this.selectedSchoolId == id) {";

$pos_start = strpos($content, $start_token);
$pos_end = strpos($content, $end_token, $pos_start);

if ($pos_start !== false && $pos_end !== false) {
    $before = substr($content, 0, $pos_start);
    $inner = substr($content, $pos_start, $pos_end - $pos_start);
    $after = substr($content, $pos_end);

    // Clean the inner logic of deleteSchool
    $inner = str_replace($bad_delete_logic, "await this.loadSchools();\n                if (this.schools.length > 0) {\n                    this.selectSchool(this.schools[0].id);\n                }", $inner);

    $content = $before . $inner . $after;
}

file_put_contents($file, $content);
echo "SUCCESS: schools.js deleteSchool fixed.\n";

// Bump version to 1.1.4
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.4'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.4.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.4\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.4.\n";
