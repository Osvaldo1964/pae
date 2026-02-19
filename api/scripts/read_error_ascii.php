<?php
$lines = file('fill_log_final_ascii.txt');
foreach ($lines as $line) {
    if (strpos($line, 'Error') !== false || strpos($line, 'SQLSTATE') !== false || strpos($line, 'Column') !== false) {
        echo trim($line) . "\n";
    }
}
