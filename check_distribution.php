<?php
spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'Config\\') === 0) {
        require_once 'api/config/' . str_replace('Config\\', '', $class_name) . '.php';
    }
    if (strpos($class_name, 'Utils\\') === 0) {
        require_once 'api/utils/' . str_replace('Utils\\', '', $class_name) . '.php';
    }
});

try {
    $db = Config\Database::getInstance()->getConnection();

    echo "BENEFICIARY STATUS DISTRIBUTION:\n";
    $stmt = $db->query("SELECT status, COUNT(*) as count FROM beneficiaries GROUP BY status");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - [" . $row['status'] . "]: " . $row['count'] . "\n";
    }

    echo "\nBRANCHES WITH ACTIVE BENEFICIARIES:\n";
    $stmt = $db->query("SELECT b.branch_id, sb.name, COUNT(*) as count 
                        FROM beneficiaries b 
                        JOIN school_branches sb ON b.branch_id = sb.id 
                        WHERE b.status = 'ACTIVO'
                        GROUP BY b.branch_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - Branch " . $row['branch_id'] . " (" . $row['name'] . "): " . $row['count'] . "\n";
    }

    echo "\nPAE_ID DISTRIBUTION FOR ACTIVE BENEFICIARIES:\n";
    $stmt = $db->query("SELECT pae_id, COUNT(*) as count FROM beneficiaries WHERE status = 'ACTIVO' GROUP BY pae_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - PAE ID " . $row['pae_id'] . ": " . $row['count'] . " active beneficiaries\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
