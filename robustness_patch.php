<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// 1. Make init() auto-select if schools found
$old_init = "async init() {\n        await this.loadSchools();\n    },";
$new_init = "async init() {\n        await this.loadSchools();\n        if (this.schools.length > 0) {\n            console.log(\"Auto-selecting first school:\", this.schools[0].id);\n            this.selectSchool(this.schools[0].id);\n        }\n    },";
$content = str_replace($old_init, $new_init, $content);

// 2. Make selectSchool robust and add logs
$old_select = "    async selectSchool(id) {
        this.selectedSchoolId = id;
        const school = this.schools.find(s => s.id == id);

        document.getElementById('branches-title').innerText = `Sedes: \${school.name}`;
        document.getElementById('btn-new-branch').style.display = 'block';

        // Highlight row
        const rows = document.querySelectorAll('.school-row');
        rows.forEach(r => r.classList.remove('table-primary'));
        const activeRow = document.querySelector(`.school-row[data-id=\"\${id}\"]`);
        if (activeRow) activeRow.classList.add('table-primary');

        await this.loadBranches(id);
    },";

$new_select = "    async selectSchool(id) {
        if (!id) return;
        console.log(\"Selecting School ID:\", id);
        this.selectedSchoolId = id;
        const school = this.schools.find(s => s.id == id);
        
        if (!school) {
            console.warn(\"School not found in local list for ID:\", id);
            return;
        }

        const titleEl = document.getElementById('branches-title');
        if (titleEl) titleEl.innerText = `Sedes: \${school.name}`;
        
        const btnNew = document.getElementById('btn-new-branch');
        if (btnNew) btnNew.style.display = 'block';

        // Highlight row
        const rows = document.querySelectorAll('.school-row');
        rows.forEach(r => r.classList.remove('table-primary'));
        const activeRow = document.querySelector(`.school-row[data-id=\"\${id}\"]`);
        if (activeRow) activeRow.classList.add('table-primary');

        await this.loadBranches(id);
    },";
$content = str_replace($old_select, $new_select, $content);

file_put_contents($file, $content);
echo "SUCCESS: schools.js robustness fixed.\n";

// 3. Bump version to 1.0.8
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.0.8'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.0.8.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.0.8\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.0.8.\n";
