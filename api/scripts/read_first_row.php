<?php
$f = fopen('carga_pae.csv', 'r');
$header = fgetcsv($f, 0, ';');
$row = fgetcsv($f, 0, ';');
if ($header && $row) {
    $data = array_combine($header, $row);
    foreach ($data as $k => $v) {
        echo "$k => $v\n";
    }
}
fclose($f);
