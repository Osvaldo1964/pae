<?php
$dir = new RecursiveDirectoryIterator('app');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'js') {
        $content = file_get_contents($file->getPathname());
        if (strpos($content, 'beneficiaries') !== false || strpos($content, 'estudiantes') !== false) {
            echo "PATH: " . $file->getPathname() . "\n";
        }
    }
}
