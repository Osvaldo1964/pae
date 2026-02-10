<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;

// Mock environment for Controller
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['HTTP_AUTHORIZATION'] = 'Bearer ' . generateTestToken();

function generateTestToken()
{
    // Generate a dummy valid token for PAE 4 (assuming JWT logic or mocking JWT::decode)
    // Since we can't easily generate a signed token without the secret key here (it's in Config),
    // we will mock the getAuthData method or use a simpler integration test.
    // Actually, let's just use the Database directly to test the LOGIC, mirroring the controller specific query.
    return "dummy";
}

$db = Database::getInstance()->getConnection();
$paeId = 4;

// 1. Pick a beneficiary
$stmt = $db->query("SELECT id, first_name, modality_id FROM beneficiaries WHERE pae_id = $paeId LIMIT 1");
$ben = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ben)
    die("No beneficiaries found.\n");

echo "Beneficiario: {$ben['first_name']} (ID: {$ben['id']}, Modality: {$ben['modality_id']})\n";

if (!$ben['modality_id'])
    die("Beneficiary has no modality.\n");

// 2. Test Allowed Ration
$stmt = $db->prepare("SELECT ration_type_id FROM pae_modality_rations WHERE modality_id = ? LIMIT 1");
$stmt->execute([$ben['modality_id']]);
$allowedRation = $stmt->fetchColumn();

if ($allowedRation) {
    echo "Testing Allowed Ration ($allowedRation): ";
    $stmtMod = $db->prepare("SELECT 1 FROM pae_modality_rations WHERE modality_id = ? AND ration_type_id = ?");
    $stmtMod->execute([$ben['modality_id'], $allowedRation]);
    if ($stmtMod->rowCount() > 0)
        echo "PERMITIDO (OK)\n";
    else
        echo "BLOQUEADO (FAIL)\n";
}

// 3. Test Blocked Ration (Dummy ID 99999)
echo "Testing Blocked Ration (99999): ";
$stmtMod = $db->prepare("SELECT 1 FROM pae_modality_rations WHERE modality_id = ? AND ration_type_id = ?");
$stmtMod->execute([$ben['modality_id'], 99999]);
if ($stmtMod->rowCount() > 0)
    echo "PERMITIDO (FAIL)\n";
else
    echo "BLOQUEADO (OK)\n";
