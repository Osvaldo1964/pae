<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT DISTINCT grade FROM beneficiaries WHERE pae_id = 4");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$row['grade']}\n";
}
