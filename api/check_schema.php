<?php
require_once __DIR__ . '/config/Database.php';
$db = \Config\Database::getInstance()->getConnection();
echo "\n--- MENUS ---\n";
echo $db->query("SHOW CREATE TABLE menus")->fetch(PDO::FETCH_NUM)[1];
echo "\n--- MENU_ITEMS ---\n";
echo $db->query("SHOW CREATE TABLE menu_items")->fetch(PDO::FETCH_NUM)[1];
