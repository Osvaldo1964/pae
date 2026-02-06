<?php
require_once __DIR__ . '/api/config/Database.php';

try {
    $db = \Config\Database::getInstance()->getConnection();

    echo "--- CHECKING STATUS VALUES ---\n";
    $stmt = $db->query("SELECT status, COUNT(*) as count FROM beneficiaries GROUP BY status");
    $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($statuses);

    echo "\n--- CHECKING PAE_ID VALUES ---\n";
    $stmt = $db->query("SELECT pae_id, COUNT(*) as count FROM beneficiaries GROUP BY pae_id");
    $paes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($paes);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
