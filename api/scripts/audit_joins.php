<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';

use Config\Database;

$db = Database::getInstance()->getConnection();

$pae_id = 4;

$query = "SELECT b.id, b.document_number, b.branch_id, b.document_type_id, b.ethnic_group_id, b.ration_type_id,
                 br.id as br_id, dt.id as dt_id, eg.id as eg_id, rt.id as rt_id
          FROM beneficiaries b
          LEFT JOIN school_branches br ON b.branch_id = br.id
          LEFT JOIN document_types dt ON b.document_type_id = dt.id
          LEFT JOIN ethnic_groups eg ON b.ethnic_group_id = eg.id
          LEFT JOIN pae_ration_types rt ON b.ration_type_id = rt.id
          WHERE b.pae_id = $pae_id";

$results = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

$missingBranch = 0;
$missingDocType = 0;
$missingEthnic = 0;
$missingRation = 0;

foreach ($results as $row) {
    if (!$row['br_id'])
        $missingBranch++;
    if (!$row['dt_id'])
        $missingDocType++;
    if (!$row['eg_id'])
        $missingEthnic++;
    if (!$row['rt_id'])
        $missingRation++;
}

echo "Total: " . count($results) . "\n";
echo "Missing Branch Join: $missingBranch\n";
echo "Missing DocType Join: $missingDocType\n";
echo "Missing Ethnic Join: $missingEthnic\n";
echo "Missing Ration Join: $missingRation\n";
