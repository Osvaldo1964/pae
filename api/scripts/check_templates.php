<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

echo "--- Table: cycle_templates ---\n";
$stmt = $db->query("DESCRIBE cycle_templates");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "  {$row['Field']} ({$row['Type']})\n";
}

echo "\n--- Table: cycle_template_days ---\n";
$stmt = $db->query("DESCRIBE cycle_template_days");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "  {$row['Field']} ({$row['Type']})\n";
}
