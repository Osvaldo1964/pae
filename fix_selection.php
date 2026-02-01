<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// 1. Add data-id to the row in renderSchoolsTable
$old_row = '<tr class="school-row ${this.selectedSchoolId == s.id ? \'table-primary\' : \'\'}" style="cursor:pointer" onclick="SchoolsView.selectSchool(${s.id})">';
$new_row = '<tr class="school-row ${this.selectedSchoolId == s.id ? \'table-primary\' : \'\'}" style="cursor:pointer" data-id="${s.id}" onclick="SchoolsView.selectSchool(${s.id})">';
$content = str_replace($old_row, $new_row, $content);

// 2. Fix selectSchool to use data-id for highlighting
$old_select = "        // Highlight row
        const rows = document.querySelectorAll('.school-row');
        rows.forEach(r => r.classList.remove('table-primary'));
        event.currentTarget.classList.add('table-primary');";

$new_select = "        // Highlight row
        const rows = document.querySelectorAll('.school-row');
        rows.forEach(r => r.classList.remove('table-primary'));
        const activeRow = document.querySelector(`.school-row[data-id=\"\${id}\"]`);
        if (activeRow) activeRow.classList.add('table-primary');";

$content = str_replace($old_select, $new_select, $content);

file_put_contents($file, $content);
echo "SUCCESS: schools.js event dependency fixed.\n";
