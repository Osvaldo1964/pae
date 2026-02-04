<?php
require_once __DIR__ . '/config/Database.php';
try {
    $db = \Config\Database::getInstance()->getConnection();
    // Use the connection which already has 'set names utf8mb4'

    $fixes = [
        ['cotizaciones', 'Cotizaciones', 'Gestión de precios de proveedores'],
        ['compras', 'Órdenes de Compra', 'Gestión de pedidos a proveedores'],
        ['remisiones', 'Remisiones', 'Control de entregas a sedes'],
        ['almacen', 'Almacén', 'Control de stock e inventario'],
        ['proveedores', 'Proveedores', 'Directorio de proveedores']
    ];

    foreach ($fixes as $f) {
        $stmt = $db->prepare("UPDATE modules SET name = ?, description = ? WHERE route_key = ?");
        $stmt->execute([$f[1], $f[2], $f[0]]);
        echo "Fixed {$f[0]}\n";
    }

    // Fix module groups
    $groupFixes = [
        [1, 'Configuración'],
        [2, 'Instituciones'],
        [3, 'Beneficiarios'],
        [4, 'Cocina'],
        [5, 'Reportes'],
        [6, 'Inventarios']
    ];

    foreach ($groupFixes as $g) {
        $stmt = $db->prepare("UPDATE module_groups SET name = ? WHERE id = ?");
        $stmt->execute([$g[1], $g[0]]);
        echo "Fixed group {$g[1]}\n";
    }

    echo "Done!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
