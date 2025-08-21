<?php


class Conexion {


    static public function conectar() {
        try {
            $link = new PDO(
                "mysql:host=localhost;dbname=parroquia",
                "root", // ✅ Usuario de la base de datos
                "",     // ✅ Contraseña de la base de datos (vacía para XAMPP por defecto)
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Para manejar errores PDO
            return $link;
        } catch (PDOException $e) {
            // ✅ Es buena práctica loguear el error para depuración
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            // ✅ En desarrollo, puedes mostrar el error; en producción, un mensaje genérico.
            die("Error de conexión a la base de datos: " . $e->getMessage()); // Muestra el mensaje de error en desarrollo
        }
    }


}


?>
