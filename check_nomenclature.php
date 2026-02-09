<?php
spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'Config\\') === 0) {
        require_once 'api/config/' . str_replace('Config\\', '', $class_name) . '.php';
    }
    if (strpos($class_name, 'Utils\\') === 0) {
        require_once 'api/utils/' . str_replace('Utils\\', '', $class_name) . '.php';
    }
});

function getAgeGroupForGrade($grade)
{
    $grade = trim(strtoupper($grade));
    if (in_array($grade, ['TRANSICIÓN', 'TRANSICION', 'JARDIN', 'JARDÍN', 'PRE-JARDIN', '0', '0°']))
        return 'PREESCOLAR';
    if (in_array($grade, ['1', '1°', '2', '2°', '3', '3°']))
        return 'PRIMARIA_A';
    if (in_array($grade, ['4', '4°', '5', '5°']))
        return 'PRIMARIA_B';
    return 'SECUNDARIA';
}

try {
    $db = Config\Database::getInstance()->getConnection();

    $validGroups = [];
    $stmt = $db->query("SELECT DISTINCT age_group FROM recipe_items");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $validGroups[] = $row['age_group'];
    }
    echo "VALID AGE GROUPS IN RECIPE_ITEMS: " . implode(', ', $validGroups) . "\n\n";

    echo "GRADE -> MAPPED GROUP -> VALID?\n";
    $stmt = $db->query("SELECT DISTINCT grade FROM beneficiaries");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mapped = getAgeGroupForGrade($row['grade']);
        $isValid = in_array($mapped, $validGroups) ? "YES" : "NO (ERROR!)";
        echo "  - " . $row['grade'] . " -> " . $mapped . " -> " . $isValid . "\n";
    }

    echo "\nBRANCHES INVOLVED:\n";
    $stmt = $db->query("SELECT b.branch_id, sb.name, COUNT(*) as count 
                        FROM beneficiaries b 
                        JOIN school_branches sb ON b.branch_id = sb.id 
                        GROUP BY b.branch_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  - Branch " . $row['branch_id'] . " (" . $row['name'] . "): " . $row['count'] . " beneficiaries\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
