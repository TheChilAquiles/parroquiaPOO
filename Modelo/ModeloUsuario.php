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
        $email = $usuario['email'] ?? null;

        if (!isset($usuario['email']) || !isset($usuario['password'])) {
            Logger::warning("Intento de registro con campos faltantes", [
                'email_presente' => isset($usuario['email']),
                'password_presente' => isset($usuario['password'])
            ]);
            return ['status' => 'error', 'message' => 'Email y contraseña son requeridos'];
        }

        // Verificar si el email ya existe
        if ($this->existEmail($usuario['email'])) {
            Logger::warning("Intento de registro con email duplicado", [
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1)
            ]);
            return ['status' => 'error', 'message' => 'El email ya está registrado'];
        }

        try {
            $sql = "INSERT INTO usuarios (usuario_rol_id, email, contraseña)
                    VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);

            // ¡CORRECCIÓN DE SEGURIDAD! Usar password_hash() en lugar de md5()
            $hashedPassword = password_hash($usuario['password'], PASSWORD_DEFAULT);

            $stmt->execute([1, $usuario['email'], $hashedPassword]);
            $userId = $this->conexion->lastInsertId();

            Logger::info("Usuario registrado exitosamente en BD", [
                'user_id' => $userId,
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                'rol_id' => 1
            ]);

            return ['status' => 'success', 'message' => 'Usuario registrado correctamente'];
        } catch (PDOException $e) {
            Logger::error("Error al registrar usuario:", [
                'error' => $e->getMessage(),
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1)
            ]);
            return ['status' => 'error', 'message' => 'Error al registrar usuario'];
        }
    }

    /**
     * Verifica el login de un usuario (NUEVO MÉTODO REQUERIDO)
     */
    // En ModeloUsuario.php, método mdlVerificarLogin
    public function mdlVerificarLogin($email, $password)
    {
        $usuario = $this->consultarUsuario($email);
        $emailMasked = substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1);

        if (!$usuario) {
            Logger::warning("Intento de login con email no registrado", [
                'email' => $emailMasked
            ]);
            return null;
        }

        // 1. Verificar si es bcrypt (nuevo)
        if (password_verify($password, $usuario['contraseña'])) {
            Logger::info("Login exitoso con bcrypt", [
                'user_id' => $usuario['id'],
                'email' => $emailMasked,
                'rol' => $usuario['rol'] ?? 'N/A'
            ]);
            unset($usuario['contraseña']);
            return $usuario;
        }

        // 2. Verificar si es MD5 (antiguo) y migrar
        if (md5($password) === $usuario['contraseña']) {
            // Migrar a bcrypt
            $nuevoHash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET contraseña = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$nuevoHash, $usuario['id']]);

            Logger::info("Login exitoso - Password migrado de MD5 a bcrypt", [
                'user_id' => $usuario['id'],
                'email' => $emailMasked
            ]);

            unset($usuario['contraseña']);
            return $usuario;
        }

        Logger::warning("Intento de login con contraseña incorrecta", [
            'user_id' => $usuario['id'],
            'email' => $emailMasked
        ]);

        return null;
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

            if ($usuario) {
                Logger::info("Usuario consultado exitosamente por email", [
                    'user_id' => $usuario['id'],
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                    'rol' => $usuario['rol'] ?? 'N/A'
                ]);
            }

            return $usuario ?: null;
        } catch (PDOException $e) {
            Logger::error("Error al consultar usuario:", [
                'error' => $e->getMessage(),
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1)
            ]);
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
            $existe = $stmt->fetchColumn() > 0;

            Logger::info("Verificación de email existente", [
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                'existe' => $existe
            ]);

            return $existe;
        } catch (PDOException $e) {
            Logger::error("Error al verificar email:", [
                'error' => $e->getMessage(),
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1)
            ]);
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
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                Logger::info("Usuario obtenido por ID", [
                    'user_id' => $id,
                    'email' => substr($usuario['email'], 0, 3) . '***@' . substr(strstr($usuario['email'], '@'), 1),
                    'rol' => $usuario['rol'] ?? 'N/A'
                ]);
            } else {
                Logger::warning("Usuario no encontrado por ID", [
                    'user_id' => $id
                ]);
            }

            return $usuario;
        } catch (PDOException $e) {
            Logger::error("Error al obtener usuario:", [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);
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
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Logger::info("Listado de usuarios obtenido de BD", [
                'total_usuarios' => count($usuarios)
            ]);

            return $usuarios;
        } catch (PDOException $e) {
            Logger::error("Error al obtener usuarios:", [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    // ========================================================================
    // CORRECCIÓN DE RECUPERACIÓN DE CONTRASEÑA (SOLUCIÓN DEFINITIVA)
    // ========================================================================

    /**
     * Guarda el token usando la hora generada por PHP
     */
    public function mdlGuardarTokenReset($email, $token, $expiracion) 
    {
        try {
            $sql = "UPDATE usuarios 
                    SET reset_token = :token, 
                        reset_token_expires = :expiracion 
                    WHERE email = :email";
            
            $stmt = $this->conexion->prepare($sql);
            
            $stmt->bindParam(":token", $token, PDO::PARAM_STR);
            $stmt->bindParam(":expiracion", $expiracion, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            
            return $stmt->execute();

        } catch (PDOException $e) {
            Logger::error("Error al guardar token:", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Busca por token usando la hora actual de PHP
     */
    public function mdlBuscarUsuarioPorToken($token)
    {
        try {
            $ahora = date('Y-m-d H:i:s');
            
            $sql = "SELECT * FROM usuarios 
                    WHERE reset_token = :token 
                    AND reset_token_expires > :ahora"; 
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(":token", $token, PDO::PARAM_STR);
            $stmt->bindParam(":ahora", $ahora, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            Logger::error("Error al buscar token:", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Actualiza la contraseña. 
     * IMPORTANTE: La columna en tu tabla se llama `contraseña` (con ñ).
     */
    public function mdlActualizarContrasenaPorToken($token, $nuevaPassword)
    {
        try {
            // Hashear password
            $hashedPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios 
                    SET contraseña = :password, 
                        reset_token = NULL, 
                        reset_token_expires = NULL 
                    WHERE reset_token = :token";
            
            $stmt = $this->conexion->prepare($sql);
            
            $stmt->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(":token", $token, PDO::PARAM_STR);
            
            return $stmt->execute();

        } catch (PDOException $e) {
            Logger::error("Error al actualizar pass:", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Marca los datos del usuario como completos
     * Se llama después de que el usuario complete su perfil de feligrés
     *
     * @param int $usuarioId ID del usuario
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function mdlMarcarDatosCompletos($usuarioId)
    {
        try {
            $sql = "UPDATE usuarios SET datos_completos = 1 WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $result = $stmt->execute([$usuarioId]);

            if ($result) {
                Logger::info("Datos marcados como completos para usuario", ['usuario_id' => $usuarioId]);
            }

            return $result;
        } catch (PDOException $e) {
            Logger::error("Error al marcar datos completos:", [
                'usuario_id' => $usuarioId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}