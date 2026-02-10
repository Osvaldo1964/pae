<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';

use Config\Database;

$db = Database::getInstance()->getConnection();

$pae_id = 4;

$query = "SELECT b.*, br.name as branch_name, s.name as school_name, br.school_id as school_id, 
                 dt.name as document_type_name, eg.name as ethnic_group_name,
                 rt.name as ration_type_name
          FROM beneficiaries b
          LEFT JOIN school_branches br ON b.branch_id = br.id
          LEFT JOIN schools s ON br.school_id = s.id
          LEFT JOIN document_types dt ON b.document_type_id = dt.id
          LEFT JOIN ethnic_groups eg ON b.ethnic_group_id = eg.id
          LEFT JOIN pae_ration_types rt ON b.ration_type_id = rt.id
          WHERE b.pae_id = $pae_id 
          ORDER BY b.last_name1 ASC, b.first_name ASC";

$stmt = $db->query($query);
$beneficiaries = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total: " . count($beneficiaries) . "\n";
if (count($beneficiaries) > 0) {
    echo "First row keys:\n";
    print_r(array_keys($beneficiaries[0]));

    echo "\nFirst row sample:\n";
    print_r($beneficiaries[0]);
}

// Check for ration_type vs ration_type_id
$checkFields = $db->query("SHOW COLUMNS FROM beneficiaries")->fetchAll(PDO::FETCH_COLUMN);
echo "\nBeneficiaries Table Columns:\n";
print_r($checkFields);
