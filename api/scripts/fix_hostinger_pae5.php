<?php
// Script to fix PAE 5 ration rights on Hostinger
// Usage: Upload to api/scripts/ and access via browser: https://yourdomain.com/api/scripts/fix_hostinger_pae5.php

define('BASE_PATH', __DIR__ . '/../../');
require_once BASE_PATH . 'api/utils/Env.php';
require_once BASE_PATH . 'api/config/Database.php';

use Config\Database;
use Utils\Env;

header('Content-Type: text/plain; charset=utf-8');

try {
    $db = Database::getInstance()->getConnection();
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage() . "\n");
}

$PAE_ID = 5;
$REQUIRED_RATIONS = [
    'DESAYUNO GENERAL',
    'ALMUERZO GENERAL',
    'CENA GENERAL',
    'MEDIA MAÑANA GENERAL',
    'MEDIA TARDE GENERAL'
];

echo "=== STARTING FIX FOR PAE $PAE_ID ON HOSTINGER ===\n\n";

function normalize($str)
{
    return mb_strtoupper(trim($str), 'UTF-8');
}

try {
    // 1. Resolve Ration IDs Dynamically
    echo "1. Resolving Ration IDs...\n";
    $stmt = $db->prepare("SELECT name, id FROM pae_ration_types");
    $stmt->execute();
    $allRations = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $rationIdsToAssign = [];
    $missingRations = [];

    foreach ($REQUIRED_RATIONS as $reqName) {
        $found = false;
        $reqNorm = normalize($reqName);
        foreach ($allRations as $dbName => $dbId) {
            if (normalize($dbName) === $reqNorm) {
                $rationIdsToAssign[] = $dbId;
                echo " - Found '$reqName' => ID $dbId\n";
                $found = true;
                break;
            }
        }
        if (!$found)
            $missingRations[] = $reqName;
    }

    if (!empty($missingRations)) {
        throw new Exception("Missing ration types in DB: " . implode(', ', $missingRations));
    }

    // 2. Get Beneficiaries
    echo "\n2. Fetching Beneficiaries for PAE $PAE_ID...\n";
    $stmtBen = $db->prepare("SELECT id FROM beneficiaries WHERE pae_id = ?");
    $stmtBen->execute([$PAE_ID]);
    $beneficiaries = $stmtBen->fetchAll(PDO::FETCH_COLUMN);
    $totalBen = count($beneficiaries);

    echo " - Found $totalBen beneficiaries.\n";

    if ($totalBen === 0) {
        echo "No beneficiaries found. Nothing to do.\n";
        exit;
    }

    // 3. Populate Rights
    echo "\n3. Populating Ration Rights...\n";
    $db->beginTransaction();

    $insert = $db->prepare("INSERT IGNORE INTO beneficiary_ration_rights (pae_id, beneficiary_id, ration_type_id, created_at) VALUES (?, ?, ?, NOW())");

    $insertedCount = 0;
    $progress = 0;

    foreach ($beneficiaries as $benId) {
        foreach ($rationIdsToAssign as $rId) {
            $insert->execute([$PAE_ID, $benId, $rId]);
            if ($insert->rowCount() > 0)
                $insertedCount++;
        }
        $progress++;
        if ($progress % 50 === 0)
            echo " .";
        if ($progress % 1000 === 0)
            echo " ($progress/$totalBen)\n";
    }

    $db->commit();
    echo "\n\nSUCCESS! Inserted $insertedCount new ration rights records.\n";
    echo "Total expected rights: " . ($totalBen * count($rationIdsToAssign)) . "\n";

} catch (Exception $e) {
    if ($db->inTransaction())
        $db->rollBack();
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
