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

    echo "--- Search by sums ---\n";

    $stmt = $db->query("SELECT pae_id, SUM(valor_total_oficial) as total FROM presupuesto_items WHERE padre_id IS NULL AND estado = 1 GROUP BY pae_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "PAE {$row['pae_id']} - Items Total: " . number_format($row['total'], 2) . "\n";
    }

    $stmt = $db->query("SELECT pae_id, SUM(valor_inicial + valor_adiciones - valor_reducciones) as total FROM presupuesto_asignacion GROUP BY pae_id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "PAE {$row['pae_id']} - Asignacion Total: " . number_format($row['total'], 2) . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
