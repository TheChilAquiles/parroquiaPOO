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

            // Asegúrate de que DB_NAME sea 'test' (o el nombre que ves en TiDB)
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";



            // Esta es la clave: Opciones para conexión segura SSL
            $options = [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];

            self::$conexion = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            Logger::error("Error de conexión:", ['error' => $e->getMessage()]);
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }

        return self::$conexion;
    }
}
