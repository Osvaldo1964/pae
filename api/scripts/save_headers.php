<?php
$f = fopen('carga_pae.csv', 'r');
$h = fgetcsv($f, 0, ';');
file_put_contents('csv_headers.txt', implode("\n", $h));
fclose($f);
