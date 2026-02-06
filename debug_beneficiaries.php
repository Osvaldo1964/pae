<?php
require_once __DIR__ . '/api/config/Database.php';

try {
    $db = \Config\Database::getInstance()->getConnection();

    echo "Checking beneficiaries table...\n";

    $stmt = $db->query("SELECT COUNT(*) as total FROM beneficiaries");
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "Total beneficiaries in table: $total\n";

    $stmt = $db->query("SELECT status, COUNT(*) as count FROM beneficiaries GROUP BY status");
    $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Beneficiaries by status:\n";
    print_r($statuses);

    $stmt = $db->query("SELECT pae_id, COUNT(*) as count FROM beneficiaries GROUP BY pae_id");
    $paes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Beneficiaries by PAE_ID:\n";
    print_r($paes);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
