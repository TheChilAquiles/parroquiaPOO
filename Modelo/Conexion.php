<?php

class Conexion
{
    //Propiedad estática para almacenar la única instancia de conexión
    private static $conexion = null;

    //Constructor privado para evitar instanciación directa
    private function __construct() {}

    //Método público estático para acceder a la conexión
    public static function conectar()
    {
        // Si ya hay una conexión, la retornamos
        if (self::$conexion !== null) {
            return self::$conexion;
        }

        //  Si no existe, la creamos
        try {
            self::$conexion = new PDO(
                "mysql:host=localhost;dbname=parroquia",
                "root",
                "",
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }

        return self::$conexion;
    }
}
