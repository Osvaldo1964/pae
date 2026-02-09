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

    echo "RATION TYPES PER BRANCH (ACTIVE BENEFICIARIES):\n";
    $sql = "SELECT b.branch_id, sb.name as branch_name, b.ration_type_id, rt.name as ration_name, COUNT(*) as count 
            FROM beneficiaries b 
            JOIN school_branches sb ON b.branch_id = sb.id 
            LEFT JOIN pae_ration_types rt ON b.ration_type_id = rt.id
            WHERE b.status = 'ACTIVO'
            GROUP BY b.branch_id, b.ration_type_id";
    $stmt = $db->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - Branch " . $row['branch_id'] . " (" . $row['branch_name'] . ") | Ration " . $row['ration_type_id'] . " (" . $row['ration_name'] . "): " . $row['count'] . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
