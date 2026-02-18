<?php
require 'c:/xampp/htdocs/pae/api/utils/Env.php';
require 'c:/xampp/htdocs/pae/api/config/Database.php';

$c = \Config\Database::getInstance()->getConnection();
$tables = [
    'hr_payroll_config',
    'hr_payroll_periods',
    'hr_payroll_concepts',
    'hr_payroll_novelties',
    'hr_payrolls',
    'hr_payroll_details',
    'recipe_items',
    'recipe_nutrition'
];

$output = "";
foreach ($tables as $t) {
    try {
        $s = $c->query("SHOW CREATE TABLE $t")->fetch(PDO::FETCH_NUM);
        $output .= "\n-- TABLE: $t\n" . $s[1] . ";\n";
    } catch (Exception $e) {
        $output .= "\n-- ERROR TABLE $t: " . $e->getMessage() . "\n";
    }
}
file_put_contents('c:/xampp/htdocs/pae/api/schema_output.txt', $output);
echo "SUCCESS";
