<?php
$file = 'app/assets/js/views/schools.js';
$content = file_get_contents($file);

// 1. Fix openSchoolModal to handle ID
$old_open_school = "openSchoolModal(school = null) {
        const isEdit = !!school;";
$new_open_school = "openSchoolModal(schoolOrId = null) {
        let school = (typeof schoolOrId === 'number' || (typeof schoolOrId === 'string' && schoolOrId !== '')) 
            ? this.schools.find(s => s.id == schoolOrId) 
            : schoolOrId;
        const isEdit = !!school;";
$content = str_replace($old_open_school, $new_open_school, $content);

// 2. Fix openBranchModal to handle ID
$old_open_branch = "openBranchModal(branch = null) {
        const isEdit = !!branch;";
$new_open_branch = "openBranchModal(branchOrId = null) {
        let branch = (typeof branchOrId === 'number' || (typeof branchOrId === 'string' && branchOrId !== '')) 
            ? this.branches.find(b => b.id == branchOrId) 
            : branchOrId;
        const isEdit = !!branch;";
$content = str_replace($old_open_branch, $new_open_branch, $content);

// 3. Update call in renderSchoolsTable
$old_call_school = 'onclick="SchoolsView.openSchoolModal(${JSON.stringify(s).replace(/\'/g, "&apos;")})">';
$new_call_school = 'onclick="SchoolsView.openSchoolModal(${s.id})">';
$content = str_replace($old_call_school, $new_call_school, $content);

// 4. Update call in renderBranches
$old_call_branch = 'onclick="SchoolsView.openBranchModal(${JSON.stringify(b).replace(/\'/g, "&apos;")})">';
$new_call_branch = 'onclick="SchoolsView.openBranchModal(${b.id})">';
$content = str_replace($old_call_branch, $new_call_branch, $content);

file_put_contents($file, $content);
echo "SUCCESS: schools.js edit buttons fixed.\n";

// 5. Bump version to 1.0.9
$config_js = 'app/assets/js/core/config.js';
$c = file_get_contents($config_js);
$c = preg_replace("/VERSION: '.*'/", "VERSION: '1.0.9'", $c);
file_put_contents($config_js, $c);
echo "SUCCESS: config.js bumped to 1.0.9.\n";

$app_index = 'app/index.php';
$ai = file_get_contents($app_index);
$ai = preg_replace("/\\\$version = \".*\"/", "\$version = \"1.0.9\"", $ai);
file_put_contents($app_index, $ai);
echo "SUCCESS: app/index.php bumped to 1.0.9.\n";
