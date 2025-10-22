<?php
// ============================================================================
// ModeloUsuario.php - REFACTORIZADO
// ============================================================================

class ModeloUsuario
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Registra un nuevo usuario en el sistema
     */
    public function mdlRegistrarUsuario($usuario)
    {
        if (!isset($usuario['email']) || !isset($usuario['password'])) {
            return ['status' => 'error', 'message' => 'Email y contraseña son requeridos'];
        }

        // Verificar si el email ya existe
        if ($this->existEmail($usuario['email'])) {
            return ['status' => 'error', 'message' => 'El email ya está registrado'];
        }

        try {
            $sql = "INSERT INTO usuarios (usuario_rol_id, email, contraseña) 
                    VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $hashedPassword = md5($usuario['password']);
            
            $stmt->execute([1, $usuario['email'], $hashedPassword]);

            return ['status' => 'success', 'message' => 'Usuario registrado correctamente'];
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error al registrar usuario'];
        }
    }

    /**
     * Consulta un usuario por email
     */
    public function consultarUsuario($email)
    {
        try {
            $sql = "SELECT u.*, ur.rol 
                    FROM usuarios u
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id 
                    WHERE u.email = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$email]);
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $usuario ?: null;
        } catch (PDOException $e) {
            error_log("Error al consultar usuario: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Verifica si un email ya existe en la base de datos
     */
    private function existEmail($email)
    {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar email: " . $e->getMessage());
            return true; // Por seguridad, asumimos que existe
        }
    }

    /**
     * Obtiene un usuario por ID
     */
    public function obtenerUsuarioPorId($id)
    {
        try {
            $sql = "SELECT u.*, ur.rol 
                    FROM usuarios u
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id 
                    WHERE u.id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene todos los usuarios
     */
    public function obtenerUsuarios()
    {
        try {
            $sql = "SELECT u.*, ur.rol 
                    FROM usuarios u
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id 
                    ORDER BY u.email ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }
}












