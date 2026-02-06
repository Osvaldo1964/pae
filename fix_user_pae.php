<?php
require_once __DIR__ . '/api/config/Database.php';

use Config\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Find a PAE that has beneficiaries
    $stmt = $db->query("SELECT pae_id, COUNT(*) as c FROM beneficiaries GROUP BY pae_id ORDER BY c DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("No beneficiaries found in ANY PAE. Cannot test.\n");
    }

    $pae_id = $row['pae_id'];
    echo "Found PAE $pae_id with " . $row['c'] . " beneficiaries.\n";

    // Update test user
    $sql = "UPDATE users SET pae_id = $pae_id WHERE username = 'testuser'";
    $db->exec($sql);
    echo "Updated 'testuser' to use PAE $pae_id.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
