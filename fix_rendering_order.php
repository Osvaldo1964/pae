<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// 1. Correct the renderSchoolsTable logic
// We need to destroy DataTable BEFORE writing to the tbody, and remove the extra .empty()

$new_render_method = "    renderSchoolsTable() {
        if ($.fn.DataTable.isDataTable('#schoolsTable')) {
            $('#schoolsTable').DataTable().destroy();
        }

        const tbody = document.getElementById('schools-table-body');
        if (!tbody) return;

        tbody.innerHTML = '';
        this.schools.forEach(s => {
            const logo = s.logo_path ? `\${Config.BASE_URL}\${s.logo_path}` : `\${Config.BASE_URL}assets/img/logos/logo_ovc.png`;
            tbody.innerHTML += `
                <tr class=\"school-row \${this.selectedSchoolId == s.id ? 'table-primary' : ''}\" style=\"cursor:pointer\" data-id=\"\${s.id}\" onclick=\"SchoolsView.selectSchool(\${s.id})\">
                    <td>
                        <div class=\"d-flex align-items-center\">
                            <img src=\"\${logo}\" class=\"rounded-circle me-3\" style=\"width: 40px; height: 40px; object-fit: cover;\" onerror=\"this.src='\${Config.BASE_URL}assets/img/logos/logo_ovc.png'\">
                            <div>
                                <h6 class=\"mb-0\">\${s.name}</h6>
                                <small class=\"badge bg-secondary\">\${s.school_type}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <small class=\"d-block text-dark\">\${s.rector || '-'}</small>
                        <small class=\"text-muted\"><i class=\"fas fa-map-marker-alt me-1\"></i>\${s.municipality}, \${s.area_type}</small>
                    </td>
                    <td class=\"text-end\" onclick=\"event.stopPropagation()\">
                        <button class=\"btn btn-sm btn-outline-primary\" onclick=\"SchoolsView.openSchoolModal(\${s.id})\">
                            <i class=\"fas fa-edit\"></i>
                        </button>
                        <button class=\"btn btn-sm btn-outline-danger\" onclick=\"SchoolsView.deleteSchool(\${s.id})\">
                            <i class=\"fas fa-trash\"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        Helper.initDataTable('#schoolsTable');
    },";

// Use regex to replace the entire old renderSchoolsTable method
$pattern = '/renderSchoolsTable\(\) \{.*?Helper\.initDataTable\(\'#schoolsTable\'\);\s+\},/s';
$content = preg_replace($pattern, $new_render_method, $content);

file_put_contents($file, $content);
echo "SUCCESS: schools.js rendering order fixed.\n";

// 2. Bump version to 1.1.6
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.6'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.6.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.6\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.6.\n";
