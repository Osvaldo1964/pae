<?php
spl_autoload_register(function ($class_name) {
    $base_dir = __DIR__ . '/';
    $prefix_map = [
        'Config\\' => 'config/',
        'Controllers\\' => 'controllers/',
        'Models\\' => 'models/',
        'Utils\\' => 'utils/',
        'Middleware\\' => 'middleware/'
    ];
    foreach ($prefix_map as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class_name, $len) === 0) {
            $relative_class = substr($class_name, $len);
            $file = $base_dir . $dir . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

use Config\Database;

try {
    $db = Database::getInstance()->getConnection();

    echo "COLUMNS FOR 'school_branches':\n";
    $cols = $db->query("DESCRIBE school_branches")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c)
        echo $c['Field'] . " (" . $c['Type'] . ")\n";

    echo "\nDISTINCT STATUS IN 'school_branches':\n";
    $stmt = $db->query("SELECT DISTINCT status FROM school_branches");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . ($row['status'] ?? 'NULL') . "\n";
    }

    echo "\nDISTINCT STATUS IN 'beneficiaries':\n";
    $stmt = $db->query("SELECT DISTINCT status FROM beneficiaries");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . ($row['status'] ?? 'NULL') . "\n";
    }

    echo "\nDISTINCT STATUS IN 'menu_cycles':\n";
    $stmt = $db->query("SELECT DISTINCT status FROM menu_cycles");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . ($row['status'] ?? 'NULL') . "\n";
    }

    echo "\nSAMPLE DATA FROM 'school_branches':\n";
    $stmt = $db->query("SELECT id, name, status, pae_id FROM school_branches LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode($row) . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
