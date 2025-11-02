
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
            self::$conexion = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos", 0, $e);
        }
        
        return self::$conexion;
    }
}