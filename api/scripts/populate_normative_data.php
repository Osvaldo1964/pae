<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$paeId = 4;

// 1. Obtener Raciones de PAE 4
$rations = $db->query("SELECT id, name FROM pae_ration_types WHERE pae_id = $paeId")->fetchAll(PDO::FETCH_ASSOC);
echo "Raciones encontradas: " . count($rations) . "\n";

// 2. Crear Modalidad Estándar si no existe
$stmt = $db->prepare("SELECT id FROM pae_modalities WHERE pae_id = ? AND name = 'ESTÁNDAR COMPLETO'");
$stmt->execute([$paeId]);
$modId = $stmt->fetchColumn();

if (!$modId) {
    $db->prepare("INSERT INTO pae_modalities (pae_id, name, description) VALUES (?, 'ESTÁNDAR COMPLETO', 'Incluye todas las raciones del ciclo activo')")
        ->execute([$paeId]);
    $modId = $db->lastInsertId();
    echo "Modalidad 'ESTÁNDAR COMPLETO' creada (ID: $modId).\n";
} else {
    echo "Modalidad 'ESTÁNDAR COMPLETO' ya existe (ID: $modId).\n";
}

// 3. Vincular raciones
foreach ($rations as $r) {
    $db->prepare("INSERT IGNORE INTO pae_modality_rations (modality_id, ration_type_id) VALUES (?, ?)")
        ->execute([$modId, $r['id']]);
}

// 4. Mapeos de Grados
// Obtener IDs de grupos
$stmt = $db->query("SELECT id, name FROM pae_nutritional_groups");
$groupsRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$groups = [];
foreach ($groupsRows as $row) {
    $groups[$row['name']] = $row['id'];
}
$preescolarId = $groups['PREESCOLAR'] ?? 0;
$primariaId = $groups['PRIMARIA'] ?? 0;
$secundariaId = $groups['SECUNDARIA Y MEDIA'] ?? 0;

if (!$preescolarId)
    die("Error: Grupos nutricionales no encontrados.\n");

// Insertar/Actualizar mappings
$grades = $db->query("SELECT DISTINCT grade FROM beneficiaries WHERE pae_id = $paeId")->fetchAll(PDO::FETCH_COLUMN);
foreach ($grades as $g) {
    $groupId = $primariaId;
    if ($g == 0)
        $groupId = $preescolarId;
    else if ($g >= 6)
        $groupId = $secundariaId;
    else if ($g == -1)
        $groupId = $primariaId;

    $db->prepare("INSERT IGNORE INTO pae_grade_mappings (pae_id, grade_name, nutritional_group_id) VALUES (?, ?, ?)")
        ->execute([$paeId, $g, $groupId]);
}

// 5. Actualizar Beneficiarios (Iterativo por Mapeo)
// Obtenemos todos los mapeos de este PAE
$mappings = $db->query("SELECT grade_name, nutritional_group_id FROM pae_grade_mappings WHERE pae_id = $paeId")->fetchAll(PDO::FETCH_ASSOC);

echo "Actualizando beneficiarios por grado...\n";
$totalUpdated = 0;
$stmtUpdate = $db->prepare("UPDATE beneficiaries SET modality_id = ?, nutritional_group_id = ? WHERE pae_id = ? AND grade = ?");

foreach ($mappings as $map) {
    $stmtUpdate->execute([$modId, $map['nutritional_group_id'], $paeId, $map['grade_name']]);
    $count = $stmtUpdate->rowCount();
    // echo " - Grado '{$map['grade_name']}': $count actualizados.\n";
    $totalUpdated += $count;
}

echo "Total beneficiarios actualizados: $totalUpdated\n";
