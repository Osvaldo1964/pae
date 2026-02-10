<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';

use Config\Database;

$db = Database::getInstance()->getConnection();

$pae_id = 4;

$results = $db->query("SELECT * FROM beneficiaries WHERE pae_id = $pae_id")->fetchAll(PDO::FETCH_ASSOC);

echo "Total records: " . count($results) . "\n";

foreach ($results as $index => $row) {
    if (json_encode($row) === false) {
        echo "JSON Error at index $index (ID: {$row['id']}): " . json_last_error_msg() . "\n";
        print_r($row);
        break;
    }
}

echo "Check finished.\n";
