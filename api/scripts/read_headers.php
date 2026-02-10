<?php
$f = fopen('carga_pae.csv', 'r');
$h = fgetcsv($f, 0, ';');
print_r($h);
fclose($f);
