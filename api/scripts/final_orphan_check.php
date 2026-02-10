<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$nullBranch = $db->query("SELECT COUNT(*) FROM beneficiaries WHERE branch_id IS NULL AND pae_id = 4")->fetchColumn();
$total = $db->query("SELECT COUNT(*) FROM beneficiaries WHERE pae_id = 4")->fetchColumn();

echo "Audito PAE 4:\n";
echo " - Total Beneficiarios: $total\n";
echo " - Sin Sede (Null Branch): $nullBranch\n";

if ($total > 0 && $nullBranch == 0) {
    echo "VERIFICACIÓN EXITOSA: Todos los beneficiarios tienen sede asignada.\n";
} else {
    echo "VERIFICACIÓN FALLIDA: Se encontraron beneficiarios sin sede.\n";
}
