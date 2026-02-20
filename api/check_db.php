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
    echo "TABLES LIST:\n";
    $stmt = $db->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "\n";
    }

    echo "\nCOLUMNS FOR 'menu_cycles':\n";
    $cols = $db->query("DESCRIBE menu_cycles")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $c)
        echo $c['Field'] . " (" . $c['Type'] . ")\n";

    echo "\nCOLUMNS FOR 'menus':\n";
    $cols = $db->query("DESCRIBE menus");
    if ($cols) {
        foreach ($cols->fetchAll(PDO::FETCH_ASSOC) as $c)
            echo $c['Field'] . " (" . $c['Type'] . ")\n";
    } else {
        echo "Table 'menus' NOT FOUND\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
