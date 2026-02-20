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

    $pae_id = 3;

    echo "--- PAE ID: $pae_id ---\n";

    // Sum of items (planned/master)
    $stmt = $db->prepare("SELECT SUM(valor_total_oficial) FROM presupuesto_items WHERE pae_id = ? AND padre_id IS NULL AND estado = 1");
    $stmt->execute([$pae_id]);
    $total_items = $stmt->fetchColumn();
    echo "Sum(presupuesto_items where padre_id IS NULL): " . number_format($total_items, 2) . "\n";

    // Sum of assignments (assigned to branches/operators)
    $stmt = $db->prepare("SELECT SUM(valor_inicial + valor_adiciones - valor_reducciones) as total, SUM(valor_ejecutado) as ejecutado FROM presupuesto_asignacion WHERE pae_id = ?");
    $stmt->execute([$pae_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Sum(presupuesto_asignacion total): " . number_format($row['total'], 2) . "\n";
    echo "Sum(presupuesto_asignacion ejecutado): " . number_format($row['ejecutado'], 2) . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
