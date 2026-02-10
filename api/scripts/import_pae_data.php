<?php
/**
 * Script de Carga Masiva PAE 2026 - Versión Actualizada
 * Importa escuelas, sedes y beneficiarios desde carga_pae.csv con nuevas columnas
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';

use Config\Database;

// Configuración de límites y opciones
$options = getopt("", ["limit:", "dry-run", "clear"]);
$limit = isset($options['limit']) ? (int) $options['limit'] : 0;
$dryRun = isset($options['dry-run']);
$clearOld = isset($options['clear']);

$csvFile = __DIR__ . '/../../carga_pae.csv';

if (!file_exists($csvFile)) {
    die("Error: Archivo no encontrado en $csvFile\n");
}

$db = Database::getInstance()->getConnection();

// 1. Obtener PAE_ID (Solicitado ID: 4 - PAE CIENAGA)
$paeId = 4;
$stmt = $db->prepare("SELECT id, name FROM pae_programs WHERE id = ?");
$stmt->execute([$paeId]);
$pae = $stmt->fetch();

if (!$pae) {
    die("Error: No se encontró el Programa PAE con ID: $paeId\n");
}

echo "Usando Programa PAE ID: $paeId - " . $pae['name'] . "\n";

// 2. Tablas Maestras
$docTypeMap = [];
$stmt = $db->query("SELECT id, code FROM document_types");
while ($row = $stmt->fetch()) {
    $docTypeMap[$row['code']] = $row['id'];
}

$ethnicGroupMap = [];
$stmt = $db->query("SELECT id, name FROM ethnic_groups");
while ($row = $stmt->fetch()) {
    $ethnicGroupMap[trim(strtoupper($row['name']))] = $row['id'];
}
$defaultEthnicId = $ethnicGroupMap['SIN PERTENENCIA ÉTNICA'] ?? 1;

// Raciones (Caché raciones para ID 4)
$rationsMap = [];
$stmt = $db->prepare("SELECT id, name FROM pae_ration_types WHERE pae_id = ?");
$stmt->execute([$paeId]);
while ($row = $stmt->fetch()) {
    $rationsMap[trim(strtoupper($row['name']))] = $row['id'];
}
$defaultRationId = $rationsMap['ALMUERZO'] ?? ($rationsMap['REFRIGERIO'] ?? 1);

// 3. Caché de Beneficiarios (Evita SELECTs repetitivos)
$beneficiariesMap = [];
$stmt = $db->prepare("SELECT id, document_number FROM beneficiaries WHERE pae_id = ?");
$stmt->execute([$paeId]);
while ($row = $stmt->fetch()) {
    $beneficiariesMap[$row['document_number']] = $row['id'];
}

// 4. Limpiar datos previos si se solicita
if ($clearOld && !$dryRun) {
    echo "Limpiando beneficiarios previos para el Programa ID: $paeId...\n";
    $db->prepare("DELETE FROM beneficiaries WHERE pae_id = ?")->execute([$paeId]);
    // Reiniciar caché de beneficiarios ya que acabamos de borrarlos
    $beneficiariesMap = [];
}

// Mapeos de CSV
$handle = fopen($csvFile, "r");
$header = fgetcsv($handle, 0, ";");

$count = 0;
$beneficiariesCreated = 0;
$beneficiariesUpdated = 0;

$branchesCache = [];

echo "Iniciando procesamiento de registros (Modo Optimizado)...\n";

if (!$dryRun)
    $db->beginTransaction();

try {
    while (($row = fgetcsv($handle, 0, ";")) !== FALSE) {
        if ($limit > 0 && $count >= $limit)
            break;
        $count++;

        // Mapeo por nombres de columnas (Usando array_combine)
        if (count($header) !== count($row)) {
            echo "Error en línea $count: La cantidad de columnas no coincide.\n";
            continue;
        }

        $data = array_combine($header, $row);

        // 1. Vincular con Sede por DANE
        $daneBranch = trim($data['dane_code_sede']);
        if (empty($daneBranch))
            continue;

        if (!isset($branchesCache[$daneBranch])) {
            $stmt = $db->prepare("SELECT id, school_id FROM school_branches WHERE dane_code = ? AND pae_id = ?");
            $stmt->execute([$daneBranch, $paeId]);
            $branch = $stmt->fetch();

            if (!$branch) {
                echo "Advertencia en línea $count: No se encontró sede con DANE $daneBranch. Saltando registro.\n";
                continue;
            }
            $branchesCache[$daneBranch] = $branch;
        }

        $branchId = $branchesCache[$daneBranch]['id'];

        // 2. Beneficiario (Beneficiary)
        $docNumber = trim($data['document_number']);
        if (empty($docNumber))
            continue;

        // Tipo de Documento
        $rawDocType = trim($data['document_type_ide'] ?? '');
        $docTypeId = $docTypeMap[$rawDocType] ?? $docTypeMap['RC'] ?? 1;

        // Etnia (Default si no viene en CSV)
        $etniaId = $defaultEthnicId;

        // Fecha Nacimiento
        $rawBirthDate = trim($data['birth_date']);
        $birthDate = null;
        if (!empty($rawBirthDate)) {
            if (strpos($rawBirthDate, '/') !== false) {
                $parts = explode("/", $rawBirthDate);
                if (count($parts) == 3)
                    $birthDate = "{$parts[2]}-{$parts[1]}-{$parts[0]}";
            } else {
                $birthDate = $rawBirthDate;
            }
        }

        // Genero
        $genderRaw = trim(strtoupper($data['gender']));
        $gender = ($genderRaw == 'FEMENINO' || $genderRaw == 'F') ? 'Femenino' : 'Masculino';

        // Discapacidad
        $disRaw = trim(strtoupper($data['disability_type']));
        $isDisabled = ($disRaw != 'NO APLICA' && !empty($disRaw) && $disRaw != 'NO') ? 1 : 0;
        $disabilityType = $isDisabled ? $disRaw : 'NINGUNA';

        // Ración (Determinación automática o por defecto)
        $rationId = $defaultRationId;

        // Insertar/Actualizar Beneficiario
        if (!$dryRun) {
            $existingId = $beneficiariesMap[$docNumber] ?? null;

            if (!$existingId) {
                $stmtIns = $db->prepare("INSERT INTO beneficiaries 
                    (pae_id, branch_id, document_type_id, document_number, first_name, second_name, last_name1, last_name2, 
                     birth_date, gender, shift, grade, group_name, ethnic_group_id, sisben_category, disability_type, 
                     is_disabled, ration_type_id, status, address, email, phone) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVO', ?, ?, ?)");

                $stmtIns->execute([
                    $paeId,
                    $branchId,
                    $docTypeId,
                    $docNumber,
                    trim($data['first_name']),
                    trim($data['second_name']),
                    trim($data['last_name1']),
                    trim($data['last_name2']),
                    $birthDate,
                    $gender,
                    'MAÑANA',
                    trim($data['grade']),
                    trim($data['group_name']),
                    $etniaId,
                    trim($data['sisben_category']),
                    $disabilityType,
                    $isDisabled,
                    $rationId,
                    trim($data['address']),
                    trim($data['email']),
                    trim($data['phone'])
                ]);
                $beneficiariesCreated++;
            } else {
                $stmtUpd = $db->prepare("UPDATE beneficiaries SET 
                    branch_id = ?, first_name = ?, second_name = ?, last_name1 = ?, last_name2 = ?, 
                    birth_date = ?, gender = ?, shift = ?, grade = ?, group_name = ?, ethnic_group_id = ?, 
                    sisben_category = ?, disability_type = ?, is_disabled = ?, address = ?, email = ?, phone = ?
                    WHERE id = ?");
                $stmtUpd->execute([
                    $branchId,
                    trim($data['first_name']),
                    trim($data['second_name']),
                    trim($data['last_name1']),
                    trim($data['last_name2']),
                    $birthDate,
                    $gender,
                    'MAÑANA',
                    trim($data['grade']),
                    trim($data['group_name']),
                    $etniaId,
                    trim($data['sisben_category']),
                    $disabilityType,
                    $isDisabled,
                    trim($data['address']),
                    trim($data['email']),
                    trim($data['phone']),
                    $existingId
                ]);
                $beneficiariesUpdated++;
            }
        }

        if ($count % 500 == 0)
            echo "Procesados $count registros...\n";
    }

    if (!$dryRun)
        $db->commit();
} catch (Exception $e) {
    if (!$dryRun)
        $db->rollBack();
    die("Error durante la importación: " . $e->getMessage() . "\n");
}

fclose($handle);

echo "\n--- Resumen de Carga ---\n";
echo "Registros procesados: $count\n";
echo "Beneficiarios creados: $beneficiariesCreated\n";
echo "Beneficiarios actualizados: $beneficiariesUpdated\n";
if ($dryRun)
    echo "[MODO SIMULACIÓN - No se realizaron cambios en la BD]\n";
echo "------------------------\n";
