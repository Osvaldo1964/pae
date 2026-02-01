<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// 1. Update HTML in render() to hide real input and show custom label
$old_html = '<div class="col-md-6">
                                        <label class="form-label">Logo</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="file" class="form-control" id="school-logo" accept="image/*" onchange="SchoolsView.previewLogo(this)">
                                            <div id="school-logo-preview-container" class="border rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; overflow: hidden;">
                                                <img id="school-logo-preview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                                <i id="school-logo-placeholder" class="fas fa-image text-muted"></i>
                                            </div>
                                        </div>
                                    </div>';

$new_html = '<div class="col-md-6">
                                        <label class="form-label">Logo</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="flex-grow-1">
                                                <input type="file" class="d-none" id="school-logo" accept="image/*" onchange="SchoolsView.previewLogo(this)">
                                                <div class="input-group">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById(\'school-logo\').click()">
                                                        <i class="fas fa-upload me-1"></i> Seleccionar
                                                    </button>
                                                    <span class="form-control form-control-sm text-truncate" id="school-logo-label" style="background: #f8f9fa;">
                                                        Seleccione un archivo...
                                                    </span>
                                                </div>
                                            </div>
                                            <div id="school-logo-preview-container" class="border rounded bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px; overflow: hidden;">
                                                <img id="school-logo-preview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                                <i id="school-logo-placeholder" class="fas fa-image text-muted"></i>
                                            </div>
                                        </div>
                                    </div>';
$content = str_replace($old_html, $new_html, $content);

// 2. Update previewLogo to update label
$old_preview = "            previewImg.style.display = 'block';
                placeholder.style.display = 'none';";
$new_preview = "            previewImg.style.display = 'block';
                placeholder.style.display = 'none';
                document.getElementById('school-logo-label').innerText = input.files[0].name;";
$content = str_replace($old_preview, $new_preview, $content);

// 3. Update openSchoolModal to set label
$open_logic_find = "placeholder.style.display = 'none';";
$label_logic = "\n                document.getElementById('school-logo-label').innerText = 'Imagen cargada (click para cambiar)';";
$content = str_replace($open_logic_find, $open_logic_find . $label_logic, $content);

// Reset label on new school
$reset_logic_find = "document.getElementById('school-logo-placeholder').style.display = 'block';";
$reset_label = "\n            document.getElementById('school-logo-label').innerText = 'Ningún archivo seleccionado';";
$content = str_replace($reset_logic_find, $reset_logic_find . $reset_label, $content);

file_put_contents($file, $content);
echo "SUCCESS: schools.js custom file input implemented.\n";

// 4. Bump version to 1.1.1
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.1.1'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.1.1.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.1.1\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.1.1.\n";
