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
        $conexion = new Conexion();
        if (!$conexion->abrir()) {
            return false; // No se pudo conectar a la base de datos
        }

        $exist = $this->existEmail($usuario['email']);

        if ($exist) {
            return ['status' => 'error', 'message' => "El email ya existe"]; // El email ya existe
        } else {


            $sql = "INSERT INTO `usuarios`(`usuario_rol_id`, `email`, `contraseña`) VALUES (1,'" . $usuario['email'] . "','" . md5($usuario['password']) . "')";
            $conexion->consulta($sql);
            if ($conexion->obtenerFilasAfectadas() <= 0) {
                return ['status' => 'error', 'message' => "Usuario registrado correctamente"]; // No se pudo registrar el usuario
            }

            return ['status' => 'success', 'message' => "Usuario registrado correctamente"]; // Usuario registrado correctamente
        }


        $this->usuarios[] = $usuario;
        return false; // Simula que el usuario se creó correctamente
    }

    public function consultarUsuario($email)
    {
        $conexion = new Conexion();

        if (!$conexion->abrir()) {
            return false;
        }

        // Usamos sentencia preparada para evitar inyección
        $mysqli = $conexion->obtenerMySQLI();
        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $stmt->close();
            return $usuario; // Devuelve un array asociativo con los datos del usuario
        } else {
            $stmt->close();
            return null; // Usuario no encontrado
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

        $conexion = new Conexion();
        if (!$conexion->abrir()) {
            return false; // No se pudo conectar a la base de datos
        }

        $sql = "SELECT * FROM `usuarios` WHERE email = '" . $_POST['email'] . "'";
        $conexion->consulta($sql);
        if ($conexion->obtenerFilasAfectadas() > 0) {
            return true; // El email ya existe
        } else {
            return false; // El email no existe
        }
    }
}
