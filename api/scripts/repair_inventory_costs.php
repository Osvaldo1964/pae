<?php

// Script para reparar costos unitarios de inventario ($0) basándose en historial
spl_autoload_register(function ($class_name) {
    $base_dir = __DIR__ . '/../';
    $prefix_map = ['Config\\' => 'config/', 'Utils\\' => 'utils/'];
    foreach ($prefix_map as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class_name, $len) === 0) {
            $file = $base_dir . $dir . str_replace('\\', '/', substr($class_name, $len)) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

use Config\Database;

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    echo "--- INICIANDO REPARACIÓN DE COSTOS DE INVENTARIO ---\n";

    // 1. Obtener items con costo 0 pero que tienen stock o han tenido movimientos
    $query = "SELECT id, name, pae_id FROM items WHERE unit_cost = 0 OR unit_cost IS NULL";
    $items = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

    echo "Analisando " . count($items) . " items con costo cero...\n";

    foreach ($items as $item) {
        $itemId = $item['id'];
        $paeId = $item['pae_id'];
        $newCost = 0;

        // Intentar buscar el último precio en Órdenes de Compra (más confiable)
        $qPO = "SELECT pod.unit_price 
                FROM purchase_order_details pod
                JOIN purchase_orders po ON pod.po_id = po.id
                WHERE pod.item_id = ? AND po.pae_id = ? AND po.status != 'CANCELADA'
                ORDER BY po.po_date DESC, po.id DESC LIMIT 1";
        $stPO = $conn->prepare($qPO);
        $stPO->execute([$itemId, $paeId]);
        $resPO = $stPO->fetch(PDO::FETCH_ASSOC);

        if ($resPO && $resPO['unit_price'] > 0) {
            $newCost = $resPO['unit_price'];
        } else {
            // Intentar buscar en Movimientos de Inventario manuales
            $qMov = "SELECT md.unit_price 
                     FROM inventory_movement_details md
                     JOIN inventory_movements m ON md.movement_id = m.id
                     WHERE md.item_id = ? AND m.pae_id = ? AND m.movement_type IN ('ENTRADA', 'ENTRADA_OC')
                     ORDER BY m.movement_date DESC, m.id DESC LIMIT 1";
            $stMov = $conn->prepare($qMov);
            $stMov->execute([$itemId, $paeId]);
            $resMov = $stMov->fetch(PDO::FETCH_ASSOC);
            if ($resMov && $resMov['unit_price'] > 0) {
                $newCost = $resMov['unit_price'];
            }
        }

        if ($newCost > 0) {
            $update = $conn->prepare("UPDATE items SET unit_cost = ? WHERE id = ?");
            $update->execute([$newCost, $itemId]);
            echo "   [FIXED] {$item['name']}: Nuevos costo sugerido \${$newCost}\n";
        }
    }

    echo "--- PROCESO FINALIZADO ---\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
