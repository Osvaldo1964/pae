<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// 1. Fix renderSchoolsTable to be more aggressive
$old_render = "        if ($.fn.DataTable.isDataTable('#schoolsTable')) {
            $('#schoolsTable').DataTable().destroy();
        }
        Helper.initDataTable('#schoolsTable');";

$new_render = "        if ($.fn.DataTable.isDataTable('#schoolsTable')) {
            $('#schoolsTable').DataTable().destroy();
            $('#schoolsTable tbody').empty(); // Physical clear
        }
        Helper.initDataTable('#schoolsTable');";
$content = str_replace($old_render, $new_render, $content);

// 2. Fix saveSchool logic (lines 432-440 approx)
$bad_save_refresh = "await this.loadSchools();
                if (id) {
                    this.selectSchool(id);
                } else if (this.schools.length > 0) {
                    // If new, select the first one (usually the one just added if sorted by ID desc, but it's ASC. Maybe select by max ID)
                    const maxId = Math.max(...this.schools.map(s => s.id));
                    this.selectSchool(maxId);
                }";

$good_save_refresh = "await this.loadSchools();
                setTimeout(() => {
                    if (id) {
                        this.selectSchool(id);
                    } else if (this.schools.length > 0) {
                        const maxId = Math.max(...this.schools.map(s => s.id));
                        this.selectSchool(maxId);
                    }
                }, 100);";
$content = str_replace($bad_save_refresh, $good_save_refresh, $content);

// 3. Fix deleteSchool logic (the duplicate of bad logic)
$content = str_replace("if (res.message) {
                Helper.alert('success', res.message);
                await this.loadSchools();
                if (id) {
                    this.selectSchool(id);
                } else if (this.schools.length > 0) {
                    // If new, select the first one (usually the one just added if sorted by ID desc, but it's ASC. Maybe select by max ID)
                    const maxId = Math.max(...this.schools.map(s => s.id));
                    this.selectSchool(maxId);
                }", "if (res.message) {
                Helper.alert('success', res.message);
                await this.loadSchools();
                setTimeout(() => {
                    if (this.schools.length > 0) {
                        this.selectSchool(this.schools[0].id);
                    }
                }, 100);", $content);

file_put_contents($file, $content);
echo "SUCCESS: schools.js standardized once and for all.\n";

// 4. Bump version to 1.1.5
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.5'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.5.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.5\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.5.\n";
