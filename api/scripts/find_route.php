<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT name, route_key FROM modules WHERE name LIKE '%Estudiantes%' OR name LIKE '%Beneficiarios%' OR name LIKE '%Alumno%'");
while ($row = $stmt->fetch()) {
    echo "MOD: " . $row['name'] . " -> " . $row['route_key'] . "\n";
}
