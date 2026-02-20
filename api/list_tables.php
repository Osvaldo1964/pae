<?php
spl_autoload_register(function ($class_name) {
    $base_dir = __DIR__ . '/';
    $prefix_map = ['Config\\' => 'config/', 'Controllers\\' => 'controllers/', 'Models\\' => 'models/', 'Utils\\' => 'utils/', 'Middleware\\' => 'middleware/'];
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
    $stmt = $db->query("SHOW TABLES");
    echo "---TABLES_START---\n";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo $row[0] . "\n";
    }
    echo "---TABLES_END---\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
