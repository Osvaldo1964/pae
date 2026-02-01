<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// 1. Update HTML in render()
$old_html = '<div class="col-md-6">
                                        <label class="form-label">Logo</label>
                                        <input type="file" class="form-control" id="school-logo" accept="image/*">
                                    </div>';
$new_html = '<div class="col-md-6">
                                        <label class="form-label">Logo</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="file" class="form-control" id="school-logo" accept="image/*" onchange="SchoolsView.previewLogo(this)">
                                            <div id="school-logo-preview-container" class="border rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; overflow: hidden;">
                                                <img id="school-logo-preview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                                <i id="school-logo-placeholder" class="fas fa-image text-muted"></i>
                                            </div>
                                        </div>
                                    </div>';
$content = str_replace($old_html, $new_html, $content);

// 2. Add previewLogo method before openSchoolModal
$insertion_point = "openSchoolModal(schoolOrId = null) {";
$preview_method = "previewLogo(input) {
        const previewImg = document.getElementById('school-logo-preview');
        const placeholder = document.getElementById('school-logo-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    },\n\n    ";
$content = str_replace($insertion_point, $preview_method . $insertion_point, $content);

// 3. Update openSchoolModal to handle preview
$open_point = "document.getElementById('school-area').value = school.area_type;";
$preview_logic = "\n            
            const previewImg = document.getElementById('school-logo-preview');
            const placeholder = document.getElementById('school-logo-placeholder');
            if (school.logo_path) {
                previewImg.src = Config.BASE_URL + school.logo_path;
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
            } else {
                previewImg.src = '';
                previewImg.style.display = 'none';
                placeholder.style.display = 'block';
            }";
$content = str_replace($open_point, $open_point . $preview_logic, $content);

// Also reset preview on new school
$reset_point = "document.getElementById('modalSchoolTitle').innerText = isEdit ? 'Editar Colegio' : 'Nuevo Colegio';";
$reset_logic = "\n        
        if (!isEdit) {
            document.getElementById('school-logo-preview').style.display = 'none';
            document.getElementById('school-logo-placeholder').style.display = 'block';
        }";
$content = str_replace($reset_point, $reset_point . $reset_logic, $content);

file_put_contents($file, $content);
echo "SUCCESS: schools.js logo preview added.\n";

// 4. Bump version to 1.1.0
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.0'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.0.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.0\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.0.\n";
