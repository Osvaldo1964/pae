<?php
$db = new PDO('mysql:host=localhost;dbname=db-pae', 'root', '');
$stmt = $db->query("SHOW TRIGGERS");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
file_put_contents('schema_dump.txt', print_r($rows, true));
