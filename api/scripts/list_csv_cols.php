<?php
$f = fopen('carga_pae.csv', 'r');
$h = fgetcsv($f, 0, ';');
if ($h) {
    foreach ($h as $index => $col) {
        echo "[$index] => $col\n";
    }
}
fclose($f);
