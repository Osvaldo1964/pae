<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

echo "--- menu_cycles sample ---\n";
$stmt = $db->query("SELECT * FROM menu_cycles LIMIT 5");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    print_r($row);

echo "\n--- cycle_templates sample ---\n";
$stmt = $db->query("SELECT * FROM cycle_templates LIMIT 5");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    print_r($row);

echo "\n--- daily_consumptions sample (distinct rations) ---\n";
$stmt = $db->query("SELECT DISTINCT ration_type_id FROM daily_consumptions");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    print_r($row);
