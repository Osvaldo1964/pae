<?php
$lines = file('fill_log_birth_date.txt');
foreach ($lines as $line) {
    if (strpos($line, 'Error') !== false || strpos($line, 'sql') !== false || strpos($line, 'SQLSTATE') !== false) {
        echo $line;
    }
}
