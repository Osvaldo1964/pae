<?php
require_once __DIR__ . '/api/config/Database.php';

use Config\Database;

try {
    $db = Database::getInstance()->getConnection();
    $pass = password_hash('test1234', PASSWORD_DEFAULT);
    // Insert test user linked to PAE ID 1 (assuming it exists, otherwise we need to check PAEs)
    // First, check for a PAE
    $stmtPae = $db->query("SELECT id FROM pae_programs LIMIT 1");
    $pae = $stmtPae->fetch(PDO::FETCH_ASSOC);
    $pae_id = $pae ? $pae['id'] : 1;

    // Check for a Role
    $stmtRole = $db->query("SELECT id FROM roles LIMIT 1");
    $role = $stmtRole->fetch(PDO::FETCH_ASSOC);
    $role_id = $role ? $role['id'] : 1;

    $sql = "INSERT INTO users (username, password_hash, full_name, email, role_id, pae_id, status) 
            VALUES ('testuser', '$pass', 'Test User', 'test@test.com', $role_id, $pae_id, 'active')
            ON DUPLICATE KEY UPDATE password_hash = '$pass'";

    $db->exec($sql);
    echo "User 'testuser' (pass: test1234) created/updated in PAE $pae_id.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
