<?php
require_once __DIR__ . '/utils/Env.php';
require_once __DIR__ . '/config/Database.php';

use Config\Database;

try {
    $conn = Database::getInstance()->getConnection();

    // Add arl_risk_percent
    $sql1 = "ALTER TABLE `hr_positions` ADD COLUMN `arl_risk_percent` DECIMAL(5,3) NOT NULL DEFAULT 0.522 AFTER `status`";
    try {
        $conn->exec($sql1);
        echo "Table hr_positions altered successfully.\n";
    } catch (Exception $e) {
        echo "Error on hr_positions (might exist): " . $e->getMessage() . "\n";
    }

    // Add is_exonerated
    $sql2 = "ALTER TABLE `hr_payroll_config` ADD COLUMN `is_exonerated` TINYINT(1) NOT NULL DEFAULT 0 AFTER `aux_transporte`";
    try {
        $conn->exec($sql2);
        echo "Table hr_payroll_config altered successfully.\n";
    } catch (Exception $e) {
        echo "Error on hr_payroll_config (might exist): " . $e->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
