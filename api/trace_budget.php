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
    echo "--- PAE 4 presupuesto_asignacion ---\n";
    $stmt = $db->prepare("SELECT * FROM presupuesto_asignacion WHERE pae_id = ?");
    $stmt->execute([$pae_id]);
    $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($asignaciones as $a) {
        $item_id = $a['item_id'];
        $stmt_item = $db->prepare("SELECT codigo, nombre, estado FROM presupuesto_items WHERE id_item = ?");
        $stmt_item->execute([$item_id]);
        $item = $stmt_item->fetch(PDO::FETCH_ASSOC);

        $a['item_name'] = $item['nombre'] ?? 'NOT FOUND';
        $a['item_status'] = $item['estado'] ?? 'N/A';
        echo json_encode($a) . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
