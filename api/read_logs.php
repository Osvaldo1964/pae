<?php
$file = 'c:/xampp/htdocs/pae/api/php_errors.log';
if (file_exists($file)) {
    $lines = file($file);
    $last_lines = array_slice($lines, -20);
    echo implode("", $last_lines);
} else {
    echo "Error log file not found at $file";
}
