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



    public function mdlRegistrarUsuario($usuario)
{
    $conexion = Conexion::conectar(); // Usamos tu clase PDO

    if (!$conexion) {
        return false; // No se pudo conectar a la base de datos
    }

    // Verificar si el email ya existe usando tu método actual
    $exist = $this->existEmail($usuario['email']);

    if ($exist) {
        return ['status' => 'error', 'message' => "El email ya existe"];
    } else {
        // Insertar usuario con PDO
        $sql = "INSERT INTO `usuarios`(`usuario_rol_id`, `email`, `contraseña`) 
                VALUES (1, :email, :password)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':email', $usuario['email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        // Usar md5 como en tu código original
        $hashedPassword = md5($usuario['password']);

        $stmt->execute();

        if ($stmt->rowCount() <= 0) {
            return ['status' => 'error', 'message' => "No se pudo registrar el usuario"];
        }

        return ['status' => 'success', 'message' => "Usuario registrado correctamente"];
    }

    // Este bloque nunca se ejecuta por la lógica anterior, pero lo dejamos como en el original
    $this->usuarios[] = $usuario;
    return false;
}



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



    //     public function consultarUsuario($email)
    // {
    //     try {
    //         $conexion = Conexion::conectar(); // Llama al método estático para obtener la conexión PDO

    //         $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = :email");
    //         $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    //         $stmt->execute();

    //         $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve un array asociativo o false si no encuentra nada

    //         return $usuario ?: null; // Si no hay resultados, devuelve null
    //     } catch (PDOException $e) {
    //         error_log("Error en consultarUsuario: " . $e->getMessage());
    //         return false; // Manejo de error general
    //     }
    // }





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
