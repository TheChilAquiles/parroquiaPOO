<?php
class ModeloUsuario
{
    private $usuarios = [];

    public function __construct()
    {
        require_once('Modelo/Conexion.php');
        // Aquí podrías inicializar la conexión a la base de datos si es necesario
        // Por ejemplo, podrías crear una conexión a la base de datos
        // $this->conexion = new Conexion();
    }



    // public function mdlRegistrarUsuario($usuario)
    // {
    //     $conexion = new Conexion();

    //     if (!$conexion->abrir()) {
    //         return false; // No se pudo conectar a la base de datos
    //     }

    //     $exist = $this->existEmail($usuario['email']);

    //     if ($exist) {
    //         return ['status' => 'error', 'message' => "El email ya existe"]; // El email ya existe
    //     } else {


    //         $sql = "INSERT INTO `usuarios`(`usuario_rol_id`, `email`, `contraseña`) VALUES (1,'" . $usuario['email'] . "','" . md5($usuario['password']) . "')";
    //         $conexion->consulta($sql);
    //         if ($conexion->obtenerFilasAfectadas() <= 0) {
    //             return ['status' => 'error', 'message' => "Usuario registrado correctamente"]; // No se pudo registrar el usuario
    //         }

    //         return ['status' => 'success', 'message' => "Usuario registrado correctamente"]; // Usuario registrado correctamente
    //     }


    //     $this->usuarios[] = $usuario;
    //     return false; // Simula que el usuario se creó correctamente
    // }



    public function consultarUsuario($email)
    {
        try {
            $conexion = Conexion::conectar();

            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$email]);

            // ✅ CORRECTO para PDO: Usar fetch() para obtener el resultado
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // ✅ CORRECTO para PDO: Ya no necesitas num_rows.
            // Simplemente verificas si la variable $usuario tiene datos.
            if ($usuario) {
                // El usuario se encontró, retorna el array de datos
                return $usuario;
            } else {
                // El usuario no se encontró, retorna null
                return null;
            }
        } catch (PDOException $e) {
            // Manejo de errores en caso de que algo falle
            error_log("Error en consultarUsuario: " . $e->getMessage());
            return null;
        }
    }





    public function obtenerUsuarios()
    {
        return $this->usuarios;
    }

    public function obtenerUsuarioPorId($id)
    {
        return isset($this->usuarios[$id]) ? $this->usuarios[$id] : null;
    }

    public function existEmail()
    {

        $conexion = Conexion::conectar();

        $sql = "SELECT * FROM `usuarios` WHERE email = '" . $_POST['email'] . "'";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $cantidadRegistros = $stmt->fetchColumn();
        if ($cantidadRegistros > 0) {
            return true; // El email ya existe
        } else {
            return false; // El email no existe
        }
    }
}
