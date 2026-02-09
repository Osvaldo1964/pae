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

    echo "--- RATION TYPES ---\n";
    $stmt = $db->query("SELECT id, name FROM pae_ration_types");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " | Name: " . $row['name'] . "\n";
    }

    echo "\n--- BENEFICIARIES MISSING RATION_TYPE_ID ---\n";
    $sql = "SELECT branch_id, ration_type, COUNT(*) as count 
            FROM beneficiaries 
            WHERE (ration_type_id IS NULL OR ration_type_id = 0) AND status = 'ACTIVO'
            GROUP BY branch_id, ration_type";
    $stmt = $db->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Branch: " . $row['branch_id'] . " | Original String: '" . $row['ration_type'] . "' | Count: " . $row['count'] . "\n";
    }

    echo "\n--- AGE GROUPS IN RECIPES ---\n";
    $stmt = $db->query("SELECT DISTINCT age_group FROM recipe_items");
    echo "Found in DB: " . implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN)) . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
