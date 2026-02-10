<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../utils/Env.php';
use Config\Database;
$db = Database::getInstance()->getConnection();

$tables = ['pae_ration_types', 'menu_cycles', 'daily_consumptions', 'menus', 'beneficiaries'];
foreach ($tables as $table) {
    echo "--- Table: $table ---\n";
    try {
        $stmt = $db->query("DESCRIBE $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  {$row['Field']} ({$row['Type']})\n";
        }
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
}
