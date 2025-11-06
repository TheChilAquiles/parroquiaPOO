<?php
/**
 * Script para leer logs desde línea de comandos
 * Uso: php leer-logs.php [error|info|debug] [cantidad_lineas]
 */

require_once __DIR__ . '/Modelo/Logger.php';

$type = isset($argv[1]) ? $argv[1] : 'error';
$lines = isset($argv[2]) ? (int)$argv[2] : 50;

echo "=================================================\n";
echo "LOGS DE " . strtoupper($type) . " (Últimas {$lines} líneas)\n";
echo "=================================================\n\n";

$logs = Logger::read($type, $lines);

if (empty($logs)) {
    echo "No hay registros en el log de {$type}.\n";
} else {
    foreach ($logs as $log) {
        echo $log;
    }
}

echo "\n=================================================\n";
echo "Total de líneas: " . count($logs) . "\n";
echo "=================================================\n";
