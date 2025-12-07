<?php
// debug_pdf.php

$dir = __DIR__ . '/certificados_generados';
$files = scandir($dir);

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    $path = $dir . '/' . $file;
    echo "File: $file\n";
    echo "Size: " . filesize($path) . " bytes\n";
    echo "Mime: " . mime_content_type($path) . "\n";
    
    $content = file_get_contents($path, false, null, 0, 20);
    echo "First 20 chars: " . bin2hex($content) . "\n";
    echo "First 20 chars (text): " . substr($content, 0, 20) . "\n";
    echo "-------------------\n";
}
