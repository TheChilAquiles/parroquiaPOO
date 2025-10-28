<?php
// ============================================================================
// ModeloUsuario.php - REFACTORIZADO Y SEGURO
// ============================================================================

class ModeloUsuario
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Registra un nuevo usuario en el sistema (CON HASH SEGURO)
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

            // ¡CORRECCIÓN DE SEGURIDAD! Usar password_hash() en lugar de md5()
            $hashedPassword = password_hash($usuario['password'], PASSWORD_DEFAULT);
            
            $stmt->execute([1, $usuario['email'], $hashedPassword]);

            return ['status' => 'success', 'message' => 'Usuario registrado correctamente'];
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error al registrar usuario'];
        }
    }

    /**
     * Verifica el login de un usuario (NUEVO MÉTODO REQUERIDO)
     */
    public function mdlVerificarLogin($email, $password)
    {
        $usuario = $this->consultarUsuario($email);

        if ($usuario && password_verify($password, $usuario['contraseña'])) {
            // La contraseña es correcta
            unset($usuario['contraseña']); // No guardar la contraseña en la sesión
            return $usuario;
        } else {
            // Email no existe o contraseña incorrecta
            return null;
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
        // (Tu código original está bien)
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
        // (Tu código original está bien)
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

    // ========================================================================
    // MÉTODOS NUEVOS PARA RECUPERAR CONTRASEÑA
    // ========================================================================

    /**
     * Guarda el token de reseteo y su expiración en la BD
     */
    public function mdlGuardarTokenReset($email, $token, $expires)
    {
        try {
            $sql = "UPDATE usuarios 
                    SET reset_token = ?, reset_token_expires = ?
                    WHERE email = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$token, $expires, $email]);
        } catch (PDOException $e) {
            error_log("Error al guardar token de reseteo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca un usuario por un token de reseteo VÁLIDO (no expirado)
     */
    public function mdlBuscarUsuarioPorToken($token)
    {
        try {
            $sql = "SELECT * FROM usuarios 
                    WHERE reset_token = ? AND reset_token_expires > NOW()";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar por token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza la contraseña del usuario y limpia el token
     */
    public function mdlActualizarContrasenaPorToken($token, $nuevaPassword)
    {
        try {
            // Hashear la nueva contraseña
            $hashedPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios 
                    SET contraseña = ?, reset_token = NULL, reset_token_expires = NULL 
                    WHERE reset_token = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$hashedPassword, $token]);
        } catch (PDOException $e) {
            error_log("Error al actualizar contraseña: " . $e->getMessage());
            return false;
        }
    }
}