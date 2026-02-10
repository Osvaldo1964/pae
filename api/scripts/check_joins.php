<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';

use Config\Database;

$db = Database::getInstance()->getConnection();

$pae_id = 4;

$query = "SELECT COUNT(*) FROM beneficiaries b
          LEFT JOIN school_branches br ON b.branch_id = br.id
          LEFT JOIN schools s ON br.school_id = s.id
          LEFT JOIN document_types dt ON b.document_type_id = dt.id
          LEFT JOIN ethnic_groups eg ON b.ethnic_group_id = eg.id
          LEFT JOIN pae_ration_types rt ON b.ration_type_id = rt.id
          WHERE b.pae_id = $pae_id";

$joinedCount = $db->query($query)->fetchColumn();

echo "Base Beneficiaries: 1966\n";
echo "Joined Count: $joinedCount\n";

if ($joinedCount > 1966) {
    echo "WARNING: Row multiplication detected! Investigating which JOIN is causing it...\n";

    $joins = [
        'school_branches' => 'branch_id',
        'schools' => 'br.school_id', // Needs manual check
        'document_types' => 'document_type_id',
        'ethnic_groups' => 'ethnic_group_id',
        'pae_ration_types' => 'ration_type_id'
    ];

    foreach ($joins as $table => $fk) {
        $q = "SELECT b.id, COUNT(*) as c FROM beneficiaries b LEFT JOIN $table t ON b.$fk = t.id WHERE b.pae_id = $pae_id GROUP BY b.id HAVING c > 1 LIMIT 5";
        // Special case for schools (it joins via branch)
        if ($table == 'schools') {
            $q = "SELECT b.id, COUNT(*) as c FROM beneficiaries b JOIN school_branches br ON b.branch_id = br.id LEFT JOIN schools s ON br.school_id = s.id WHERE b.pae_id = $pae_id GROUP BY b.id HAVING c > 1 LIMIT 5";
        }

        $res = $db->query($q)->fetchAll();
        if (count($res) > 0) {
            echo "  - Table $table is causing duplication!\n";
        }
    }
} else {
    echo "No row multiplication detected.\n";
}

// Check if document_types has exact duplicates
$dtCount = $db->query("SELECT code, COUNT(*) as c FROM document_types GROUP BY code HAVING c > 1")->fetchAll();
if (count($dtCount) > 0) {
    echo "WARNING: Duplicate Codes in document_types:\n";
    print_r($dtCount);
}

// Check if ethnic_groups has exact duplicates
$egCount = $db->query("SELECT name, COUNT(*) as c FROM ethnic_groups GROUP BY name HAVING c > 1")->fetchAll();
if (count($egCount) > 0) {
    echo "WARNING: Duplicate Names in ethnic_groups:\n";
    print_r($egCount);
}
