<?php

// Autoloader para scripts independientes
spl_autoload_register(function ($class_name) {
    $base_dir = __DIR__ . '/../';
    $prefix_map = [
        'Config\\' => 'config/',
        'Controllers\\' => 'controllers/',
        'Models\\' => 'models/',
        'Utils\\' => 'utils/',
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
    $db = Database::getInstance();
    $conn = $db->getConnection();

    echo "--- INICIANDO ACTUALIZACIÓN MASIVA DE BENEFICIARIOS (SOLO PAE ID 4) ---\n";

    // 1. Actualización de Habeas Data
    echo "1. Actualizando Habeas Data (data_authorization = 1) para PAE ID 4...\n";
    $qHabeas = "UPDATE beneficiaries SET data_authorization = 1 WHERE pae_id = 4";
    $stHabeas = $conn->prepare($qHabeas);
    $stHabeas->execute();
    echo "   - Beneficiarios actualizados: " . $stHabeas->rowCount() . "\n";

    // 2. Asignación de Raciones (ALMUERZO y DESAYUNO)
    echo "2. Asignando raciones (ALMUERZO y DESAYUNO) a beneficiarios de PAE ID 4...\n";

    // Obtener tipos de ración relevantes solo para PAE 4
    $qRations = "SELECT id, name FROM pae_ration_types WHERE name IN ('ALMUERZO', 'DESAYUNO') AND status = 'ACTIVO' AND pae_id = 4";
    $stRations = $conn->query($qRations);
    $rationsForPae4 = [];
    while ($row = $stRations->fetch(PDO::FETCH_ASSOC)) {
        $rationsForPae4[] = $row['id'];
        echo "   - Detectado ID ración '{$row['name']}': {$row['id']}\n";
    }

    if (empty($rationsForPae4)) {
        throw new Exception("No se encontraron tipos de ración ALMUERZO/DESAYUNO activos para el PAE ID 4.");
    }

    $totalInserted = 0;

    // Preparar inserción segura
    $qInsertRight = "INSERT IGNORE INTO beneficiary_ration_rights (pae_id, beneficiary_id, ration_type_id) VALUES (?, ?, ?)";
    $stInsert = $conn->prepare($qInsertRight);

    // Obtener beneficiarios solo de PAE 4
    $qBeneficiaries = "SELECT id FROM beneficiaries WHERE pae_id = 4";
    $stBeneficiaries = $conn->query($qBeneficiaries);

    while ($b = $stBeneficiaries->fetch(PDO::FETCH_ASSOC)) {
        foreach ($rationsForPae4 as $ration_type_id) {
            $stInsert->execute([4, $b['id'], $ration_type_id]);
            if ($stInsert->rowCount() > 0) {
                $totalInserted++;
            }
        }
    }

    echo "   - Nuevos derechos de ración asignados: $totalInserted\n";
    echo "--- PROCESO FINALIZADO CON ÉXITO ---\n";

} catch (Exception $e) {
    echo "\n!!! ERROR CRÍTICO: " . $e->getMessage() . "\n";
}
