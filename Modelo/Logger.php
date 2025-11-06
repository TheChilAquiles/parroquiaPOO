<?php

/**
 * Logger.php - Sistema centralizado de logs
 * Registra errores, advertencias e información en archivos separados
 */

class Logger
{
    private static $logDir = __DIR__ . '/../logs';
    private static $errorLog = 'errors.log';
    private static $infoLog = 'info.log';
    private static $debugLog = 'debug.log';

    /**
     * Inicializa el directorio de logs
     */
    private static function init()
    {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
    }

    /**
     * Registra un error
     * @param string $message Mensaje de error
     * @param array $context Contexto adicional (archivo, línea, etc.)
     */
    public static function error($message, $context = [])
    {
        self::log('ERROR', $message, $context, self::$errorLog);
    }

    /**
     * Registra información general
     * @param string $message Mensaje informativo
     * @param array $context Contexto adicional
     */
    public static function info($message, $context = [])
    {
        self::log('INFO', $message, $context, self::$infoLog);
    }

    /**
     * Registra información de depuración
     * @param string $message Mensaje de debug
     * @param array $context Contexto adicional
     */
    public static function debug($message, $context = [])
    {
        self::log('DEBUG', $message, $context, self::$debugLog);
    }

    /**
     * Método principal de logging
     * @param string $level Nivel del log (ERROR, INFO, DEBUG)
     * @param string $message Mensaje
     * @param array $context Contexto
     * @param string $file Archivo de destino
     */
    private static function log($level, $message, $context, $file)
    {
        self::init();

        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';

        // Obtener información de la traza
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller = isset($trace[2]) ? basename($trace[2]['file']) . ':' . $trace[2]['line'] : 'unknown';

        $logMessage = "[{$timestamp}] [{$level}] [{$caller}] {$message}{$contextStr}" . PHP_EOL;

        $logPath = self::$logDir . '/' . $file;
        file_put_contents($logPath, $logMessage, FILE_APPEND);
    }

    /**
     * Obtiene los últimos N registros de un archivo de log
     * @param string $type Tipo de log (error, info, debug)
     * @param int $lines Cantidad de líneas a leer (default: 50)
     * @return array Array de líneas del log
     */
    public static function read($type = 'error', $lines = 50)
    {
        self::init();

        $files = [
            'error' => self::$errorLog,
            'info' => self::$infoLog,
            'debug' => self::$debugLog
        ];

        $file = isset($files[$type]) ? $files[$type] : self::$errorLog;
        $logPath = self::$logDir . '/' . $file;

        if (!file_exists($logPath)) {
            return [];
        }

        // Leer las últimas N líneas
        $content = file($logPath);
        return array_slice($content, -$lines);
    }

    /**
     * Limpia un archivo de log
     * @param string $type Tipo de log a limpiar
     */
    public static function clear($type = 'all')
    {
        self::init();

        $files = [
            'error' => self::$errorLog,
            'info' => self::$infoLog,
            'debug' => self::$debugLog
        ];

        if ($type === 'all') {
            foreach ($files as $file) {
                $logPath = self::$logDir . '/' . $file;
                if (file_exists($logPath)) {
                    file_put_contents($logPath, '');
                }
            }
        } else {
            $file = isset($files[$type]) ? $files[$type] : null;
            if ($file) {
                $logPath = self::$logDir . '/' . $file;
                if (file_exists($logPath)) {
                    file_put_contents($logPath, '');
                }
            }
        }
    }

    /**
     * Registra excepciones de PHP
     * @param Exception|Throwable $exception
     */
    public static function exception($exception)
    {
        self::error(
            $exception->getMessage(),
            [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]
        );
    }
}
