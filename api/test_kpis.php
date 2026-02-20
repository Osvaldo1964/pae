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
    $pae_id = 3; // From previous sample

    $stmt = $db->prepare("SELECT COUNT(*) FROM school_branches WHERE pae_id = ? AND status IN ('ACTIVA', 'active')");
    $stmt->execute([$pae_id]);
    echo "Sedes Activas (pae_id=$pae_id): " . $stmt->fetchColumn() . "\n";

    $stmt = $db->prepare("SELECT COUNT(*) FROM beneficiaries WHERE pae_id = ? AND status IN ('ACTIVO', 'active')");
    $stmt->execute([$pae_id]);
    echo "Beneficiarios (pae_id=$pae_id): " . $stmt->fetchColumn() . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
