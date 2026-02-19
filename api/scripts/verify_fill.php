<?php
// Script to verify beneficiary counts for PAE ID 5
require_once __DIR__ . '/../utils/Env.php';
require_once __DIR__ . '/../config/Database.php';

use Config\Database;

try {
    $db = Database::getInstance()->getConnection();

    $PAE_ID = 5;

    echo "Verifying PAE ID $PAE_ID...\n";

    $stmt = $db->prepare("SELECT id, name, total_beneficiaries FROM school_branches WHERE pae_id = ?");
    $stmt->execute([$PAE_ID]);
    $branches = $stmt->fetchAll();

    $allMatch = true;

    foreach ($branches as $branch) {
        $branchId = $branch['id'];
        $name = $branch['name'];
        $target = $branch['total_beneficiaries'];

        $stmtCount = $db->prepare("SELECT COUNT(*) FROM beneficiaries WHERE branch_id = ?");
        $stmtCount->execute([$branchId]);
        $current = $stmtCount->fetchColumn();

        $status = ($current == $target) ? "[OK]" : "[FAIL]";
        if ($current != $target)
            $allMatch = false;

        echo sprintf("%-6s | %-30s | Target: %4d | Current: %4d\n", $status, substr($name, 0, 30), $target, $current);
    }

    if ($allMatch) {
        echo "\nSUCCESS: All branches have correct beneficiary counts.\n";
    } else {
        echo "\nWARNING: Some branches do not match match targets.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
