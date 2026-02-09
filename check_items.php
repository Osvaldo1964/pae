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
    $stmt = $db->query("DESCRIBE items");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
