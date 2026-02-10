<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';

use Config\Database;

$db = Database::getInstance()->getConnection();

$pae_id = 4;

echo "--- Diagnóstico de Beneficiarios (PAE 4) ---\n";

// 1. Verificar si existen beneficiarios
$count = $db->query("SELECT COUNT(*) FROM beneficiaries WHERE pae_id = $pae_id")->fetchColumn();
echo "Total Beneficiarios PAE 4: $count\n";

if ($count == 0) {
    echo "ERROR: No hay beneficiarios para el PAE 4.\n";
    exit;
}

// 2. Probar la consulta exacta del controlador
$query = "SELECT b.*, br.name as branch_name, s.name as school_name, br.school_id as school_id, 
                 dt.name as document_type_name, eg.name as ethnic_group_name,
                 rt.name as ration_type_name
          FROM beneficiaries b
          LEFT JOIN school_branches br ON b.branch_id = br.id
          LEFT JOIN schools s ON br.school_id = s.id
          LEFT JOIN document_types dt ON b.document_type_id = dt.id
          LEFT JOIN ethnic_groups eg ON b.ethnic_group_id = eg.id
          LEFT JOIN pae_ration_types rt ON b.ration_type_id = rt.id
          WHERE b.pae_id = $pae_id 
          LIMIT 5";

try {
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Consulta exitosa. Primeros 5 registros:\n";
    print_r($results);
} catch (Exception $e) {
    echo "ERROR EN CONSULTA SQL: " . $e->getMessage() . "\n";
}

// 3. Verificar si hay nulos críticos
$nullBranches = $db->query("SELECT COUNT(*) FROM beneficiaries WHERE pae_id = $pae_id AND branch_id IS NULL")->fetchColumn();
echo "Beneficiarios sin Sede: $nullBranches\n";

$nullRations = $db->query("SELECT COUNT(*) FROM beneficiaries WHERE pae_id = $pae_id AND (ration_type_id IS NULL OR ration_type_id = 0)")->fetchColumn();
echo "Beneficiarios sin Tipo de Ración ID: $nullRations\n";

// 4. Verificar qué PAE ID tiene el usuario 'admin'
$adminPae = $db->query("SELECT pae_id FROM users WHERE username = 'admin'")->fetchColumn();
echo "PAE ID del usuario 'admin': " . ($adminPae ?? 'NULL') . "\n";
