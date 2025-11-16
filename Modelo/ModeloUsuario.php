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
            $result = $stmt->execute([$token, $expires, $email]);

            if ($result) {
                Logger::info("Token de reseteo de contraseña guardado", [
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                    'token_prefix' => substr($token, 0, 8) . '...',
                    'expires' => $expires
                ]);
            } else {
                Logger::warning("No se pudo guardar token de reseteo - email no encontrado", [
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1)
                ]);
            }

            return $result;
        } catch (PDOException $e) {
            Logger::error("Error al guardar token de reseteo:", [
                'error' => $e->getMessage(),
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1)
            ]);
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
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                Logger::info("Usuario encontrado por token válido", [
                    'user_id' => $usuario['id'],
                    'email' => substr($usuario['email'], 0, 3) . '***@' . substr(strstr($usuario['email'], '@'), 1),
                    'token_prefix' => substr($token, 0, 8) . '...'
                ]);
            } else {
                Logger::warning("Token de reseteo inválido o expirado", [
                    'token_prefix' => substr($token, 0, 8) . '...'
                ]);
            }

            return $usuario;
        } catch (PDOException $e) {
            Logger::error("Error al buscar por token:", [
                'error' => $e->getMessage(),
                'token_prefix' => substr($token, 0, 8) . '...'
            ]);
            return null;
        }
    }

    /**
     * Actualiza la contraseña del usuario y limpia el token
     */
    public function mdlActualizarContrasenaPorToken($token, $nuevaPassword)
    {
        try {
            // Primero obtenemos el usuario para logging
            $usuario = $this->mdlBuscarUsuarioPorToken($token);

            if (!$usuario) {
                Logger::warning("Intento de actualizar contraseña con token inválido", [
                    'token_prefix' => substr($token, 0, 8) . '...'
                ]);
                return false;
            }

            // Hashear la nueva contraseña
            $hashedPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios
                    SET contraseña = ?, reset_token = NULL, reset_token_expires = NULL
                    WHERE reset_token = ?";
            $stmt = $this->conexion->prepare($sql);
            $result = $stmt->execute([$hashedPassword, $token]);

            if ($result) {
                Logger::info("Contraseña actualizada exitosamente", [
                    'user_id' => $usuario['id'],
                    'email' => substr($usuario['email'], 0, 3) . '***@' . substr(strstr($usuario['email'], '@'), 1)
                ]);
            }

            return $result;
        } catch (PDOException $e) {
            Logger::error("Error al actualizar contraseña:", [
                'error' => $e->getMessage(),
                'token_prefix' => substr($token, 0, 8) . '...'
            ]);
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