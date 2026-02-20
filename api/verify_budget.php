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

    $pae_id = 4;
    echo "--- PAE 4 Verification ---\n";

    // Sum from presupuesto_items (top-level, active)
    $stmt = $db->prepare("SELECT SUM(valor_total_oficial) FROM presupuesto_items WHERE pae_id = ? AND padre_id IS NULL AND estado = 1");
    $stmt->execute([$pae_id]);
    $items_total = $stmt->fetchColumn();
    echo "Items Total (planned): " . number_format($items_total, 2) . "\n";

    // Current Dashboard logic (sums all assignments)
    $stmt = $db->prepare("SELECT SUM(valor_inicial + valor_adiciones - valor_reducciones) FROM presupuesto_asignacion WHERE pae_id = ?");
    $stmt->execute([$pae_id]);
    $dashboard_total = $stmt->fetchColumn();
    echo "Current Dashboard Total: " . number_format($dashboard_total, 2) . "\n";

    // Proposed Dashboard logic (joins items and filters by active)
    $stmt = $db->prepare("
        SELECT SUM(a.valor_inicial + a.valor_adiciones - a.valor_reducciones) 
        FROM presupuesto_asignacion a
        JOIN presupuesto_items i ON a.item_id = i.id_item
        WHERE a.pae_id = ? AND i.estado = 1
    ");
    $stmt->execute([$pae_id]);
    $filtered_total = $stmt->fetchColumn();
    echo "Filtered Dashboard Total (item.estado=1): " . number_format($filtered_total, 2) . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
