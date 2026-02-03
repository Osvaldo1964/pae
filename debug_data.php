<?php
require_once 'api/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id, ration_type, shift, modality FROM beneficiaries WHERE id = 31");
$stmt->execute();
$data = $stmt->fetch();
header('Content-Type: text/plain');
echo "ID: " . $data['id'] . "\n";
echo "Ration Type: [" . $data['ration_type'] . "]\n";
echo "Ration Type Hex: " . bin2hex($data['ration_type']) . "\n";
echo "Shift: [" . $data['shift'] . "]\n";
echo "Modality: [" . $data['modality'] . "]\n";
?>