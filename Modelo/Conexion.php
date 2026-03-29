<?php
require_once __DIR__ . '/../config.php';

class Conexion
{
    private static $conexion = null;
    
    private function __construct() {}
    
    public static function conectar()
{
    if (self::$conexion !== null) {
        return self::$conexion;
    }
    
    try {
        // Construimos el DSN incluyendo el puerto
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
        
        self::$conexion = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
        
        self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Esto te ayudará a ver el error real en los logs de Render
        Logger::error("Error de conexión:", ['error' => $e->getMessage()]);
        throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
    }
    
    return self::$conexion;
}
}