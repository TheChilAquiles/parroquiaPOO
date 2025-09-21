<?php
// Incluir el archivo de conexión
class ModeloGrupo
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * @return array Retorna todos los grupos parroquiales activos con información adicional.
     */
    public function mdlListarGrupos()
    {
        try {
            $sql = "SELECT 
                        g.id, 
                        g.nombre,
                        COUNT(CASE WHEN ug.estado_registro IS NULL THEN 1 END) as total_miembros
                    FROM grupos g 
                    LEFT JOIN usuario_grupos ug ON g.id = ug.grupo_parroquial_id
                    WHERE g.estado_registro IS NULL
                    GROUP BY g.id, g.nombre
                    ORDER BY g.nombre ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar grupos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param int $grupo_id
     * @return array|null Retorna la información de un grupo específico activo con total de miembros.
     */
    public function mdlObtenerGrupoPorId($grupo_id)
    {
        try {
            // Validar que el ID sea un número válido
            if (!is_numeric($grupo_id) || $grupo_id <= 0) {
                return null;
            }

            $sql = "SELECT 
                        g.id, 
                        g.nombre,
                        COUNT(CASE WHEN ug.estado_registro IS NULL THEN 1 END) as total_miembros
                    FROM grupos g 
                    LEFT JOIN usuario_grupos ug ON g.id = ug.grupo_parroquial_id
                    WHERE g.id = ? AND g.estado_registro IS NULL
                    GROUP BY g.id, g.nombre";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$grupo_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener grupo: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @param int $grupo_id
     * @return array Retorna la lista de miembros activos de un grupo con información completa.
     */
    public function mdlListarMiembrosGrupo($grupo_id)
    {
        try {
            // Validar que el ID sea un número válido
            if (!is_numeric($grupo_id) || $grupo_id <= 0) {
                return [];
            }

            $sql = "SELECT 
                        u.id as usuario_id,
                        u.email, 
                        COALESCE(gr.rol, 'Sin rol') as rol,
                        COALESCE(
                            CONCAT(f.primer_nombre, ' ', 
                                    CASE WHEN f.segundo_nombre != '' THEN CONCAT(f.segundo_nombre, ' ') ELSE '' END,
                                    f.primer_apellido, ' ', f.segundo_apellido), 
                            u.email
                        ) as nombre_completo,
                        f.telefono,
                        ur.rol as rol_usuario
                    FROM usuario_grupos ug 
                    JOIN usuarios u ON ug.usuario_id = u.id 
                    LEFT JOIN grupo_roles gr ON ug.grupo_rol_id = gr.id 
                    LEFT JOIN feligreses f ON u.id = f.usuario_id
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id
                    WHERE ug.grupo_parroquial_id = ? AND ug.estado_registro IS NULL
                    ORDER BY gr.rol DESC, f.primer_nombre ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$grupo_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar miembros del grupo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param int $grupo_id
     * @param int $usuario_id
     * @param int $grupo_rol_id El ID del rol en el grupo (ej. 1 para Miembro, 2 para Líder).
     * @return bool Retorna true si se añadió el miembro, false en caso contrario.
     */
    public function mdlAgregarMiembro($grupo_id, $usuario_id, $grupo_rol_id)
    {
        try {
            // Validaciones
            if (!is_numeric($grupo_id) || $grupo_id <= 0 ||
                !is_numeric($usuario_id) || $usuario_id <= 0 ||
                !is_numeric($grupo_rol_id) || $grupo_rol_id <= 0) {
                return false;
            }

            // Verificar si el usuario ya está en el grupo (y activo)
            if ($this->mdlUsuarioEnGrupo($grupo_id, $usuario_id)) {
                error_log("El usuario ya está en el grupo");
                return false;
            }

            // Verificar que el grupo existe y está activo
            if (!$this->mdlObtenerGrupoPorId($grupo_id)) {
                error_log("El grupo no existe o está eliminado");
                return false;
            }

            // Verificar que el usuario existe
            if (!$this->mdlUsuarioExiste($usuario_id)) {
                error_log("El usuario no existe");
                return false;
            }

            $sql = "INSERT INTO usuario_grupos (grupo_parroquial_id, usuario_id, grupo_rol_id, estado_registro) 
                    VALUES (?, ?, ?, NULL)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$grupo_id, $usuario_id, $grupo_rol_id]);
        } catch (PDOException $e) {
            error_log("Error al agregar miembro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param int $grupo_id
     * @param int $usuario_id
     * @return bool Retorna true si se eliminó el miembro, false en caso contrario.
     */
    public function mdlEliminarMiembro($grupo_id, $usuario_id)
    {
        try {
            // Validaciones
            if (!is_numeric($grupo_id) || $grupo_id <= 0 ||
                !is_numeric($usuario_id) || $usuario_id <= 0) {
                return false;
            }

            $sql = "UPDATE usuario_grupos 
                    SET estado_registro = NOW() 
                    WHERE grupo_parroquial_id = ? AND usuario_id = ? AND estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $resultado = $stmt->execute([$grupo_id, $usuario_id]);
            
            // Log para depuración
            error_log("Eliminando miembro - Grupo: $grupo_id, Usuario: $usuario_id, Filas afectadas: " . $stmt->rowCount());
            
            return $resultado && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar miembro: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * @param string $nombre_grupo
     * @return bool|int Retorna el ID del grupo creado si es exitoso, false en caso contrario.
     */
    public function mdlCrearGrupo($nombre_grupo)
    {
        try {
            // Validar que el nombre no esté vacío
            if (empty(trim($nombre_grupo))) {
                return false;
            }

            $nombre_grupo = trim($nombre_grupo);

            // Verificar que no existe un grupo activo con el mismo nombre
            if ($this->mdlGrupoExistePorNombre($nombre_grupo)) {
                error_log("Ya existe un grupo activo con ese nombre");
                return false;
            }

            $sql = "INSERT INTO grupos (nombre, estado_registro) VALUES (?, NULL)";
            $stmt = $this->conexion->prepare($sql);
            
            if ($stmt->execute([$nombre_grupo])) {
                return $this->conexion->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al crear grupo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param int $grupo_id
     * @param string $nombre_grupo
     * @return bool Retorna true si se actualizó el grupo, false en caso contrario.
     */
    public function mdlActualizarGrupo($grupo_id, $nombre_grupo)
    {
        try {
            // Validaciones
            if (!is_numeric($grupo_id) || $grupo_id <= 0 || empty(trim($nombre_grupo))) {
                return false;
            }

            $nombre_grupo = trim($nombre_grupo);

            // Verificar que el grupo existe
            if (!$this->mdlObtenerGrupoPorId($grupo_id)) {
                return false;
            }

            // Verificar que no existe otro grupo con el mismo nombre
            $grupoExistente = $this->mdlGrupoExistePorNombre($nombre_grupo);
            if ($grupoExistente && $grupoExistente['id'] != $grupo_id) {
                error_log("Ya existe otro grupo con ese nombre");
                return false;
            }

            $sql = "UPDATE grupos SET nombre = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$nombre_grupo, $grupo_id]);
        } catch (PDOException $e) {
            error_log("Error al actualizar grupo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param int $grupo_id
     * @return bool Retorna true si se eliminó el grupo lógicamente, false en caso contrario.
     */
    public function mdlEliminarGrupo($grupo_id)
    {
        try {
            // Validar que el ID sea un número válido
            if (!is_numeric($grupo_id) || $grupo_id <= 0) {
                return false;
            }

            // Verificar que el grupo existe y está activo
            $grupo = $this->mdlObtenerGrupoPorId($grupo_id);
            if (!$grupo) {
                return false;
            }

            // Iniciar transacción para borrado lógico
            $this->conexion->beginTransaction();

            try {
                // Primero eliminar logicamente las relaciones usuario_grupos
                $sql1 = "UPDATE usuario_grupos 
                        SET estado_registro = NOW() 
                        WHERE grupo_parroquial_id = ? AND estado_registro IS NULL";
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->execute([$grupo_id]);

                // Luego realizar borrado lógico del grupo (poner fecha de eliminación)
                $sql2 = "UPDATE grupos SET estado_registro = NOW() WHERE id = ?";
                $stmt2 = $this->conexion->prepare($sql2);
                $result = $stmt2->execute([$grupo_id]);

                if ($result) {
                    $this->conexion->commit();
                    return true;
                } else {
                    $this->conexion->rollback();
                    return false;
                }
            } catch (PDOException $e) {
                $this->conexion->rollback();
                throw $e;
            }
        } catch (PDOException $e) {
            error_log("Error al eliminar grupo lógicamente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @return array Retorna todos los roles disponibles para grupos.
     */
    public function mdlListarRolesGrupo()
    {
        try {
            $sql = "SELECT id, rol FROM grupo_roles WHERE estado_registro IS NULL ORDER BY id ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar roles de grupo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @return array Retorna todos los usuarios disponibles para agregar a grupos.
     */
    public function mdlListarUsuariosDisponibles()
    {
        try {
            $sql = "SELECT 
                        u.id,
                        u.email,
                        COALESCE(
                            CONCAT(f.primer_nombre, ' ', f.primer_apellido), 
                            u.email
                        ) as nombre_completo,
                        ur.rol as tipo_usuario
                    FROM usuarios u
                    LEFT JOIN feligreses f ON u.id = f.usuario_id
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id
                    WHERE u.email_confirmed = 1 OR u.email_confirmed IS NULL
                    ORDER BY nombre_completo ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar usuarios disponibles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @return array Retorna el historial de grupos eliminados
     */
    public function mdlListarGruposEliminados()
    {
        try {
            $sql = "SELECT 
                        g.id, 
                        g.nombre,
                        g.estado_registro as fecha_eliminacion
                    FROM grupos g 
                    WHERE g.estado_registro IS NOT NULL
                    ORDER BY g.estado_registro DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar grupos eliminados: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param int $grupo_id
     * @return bool Restaurar un grupo eliminado lógicamente
     */
    public function mdlRestaurarGrupo($grupo_id)
    {
        try {
            if (!is_numeric($grupo_id) || $grupo_id <= 0) {
                return false;
            }

            // Verificar que el grupo existe y está eliminado
            $sql_verificar = "SELECT nombre FROM grupos WHERE id = ? AND estado_registro IS NOT NULL";
            $stmt_verificar = $this->conexion->prepare($sql_verificar);
            $stmt_verificar->execute([$grupo_id]);
            $grupo_eliminado = $stmt_verificar->fetch(PDO::FETCH_ASSOC);
            
            if (!$grupo_eliminado) {
                return false; // El grupo no existe o no está eliminado
            }

            // Verificar que no haya otro grupo activo con el mismo nombre
            if ($this->mdlGrupoExistePorNombre($grupo_eliminado['nombre'])) {
                error_log("Ya existe un grupo activo con ese nombre, no se puede restaurar");
                return false;
            }

            // Restaurar el grupo (cambiar estado_registro a NULL)
            $sql = "UPDATE grupos SET estado_registro = NULL WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$grupo_id]);
        } catch (PDOException $e) {
            error_log("Error al restaurar grupo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * MÉTODO DE DEPURACIÓN - Retorna TODOS los miembros de un grupo (activos e inactivos)
     * @param int $grupo_id
     * @return array Para debugging - muestra el estado de todos los registros
     */
    public function mdlListarTodosMiembrosGrupo($grupo_id)
    {
        try {
            if (!is_numeric($grupo_id) || $grupo_id <= 0) {
                return [];
            }

            $sql = "SELECT 
                        u.id as usuario_id,
                        u.email, 
                        COALESCE(gr.rol, 'Sin rol') as rol,
                        COALESCE(
                            CONCAT(f.primer_nombre, ' ', f.primer_apellido), 
                            u.email
                        ) as nombre_completo,
                        ug.estado_registro,
                        CASE 
                            WHEN ug.estado_registro IS NULL THEN 'ACTIVO' 
                            ELSE 'ELIMINADO' 
                        END as estado
                    FROM usuario_grupos ug 
                    JOIN usuarios u ON ug.usuario_id = u.id 
                    LEFT JOIN grupo_roles gr ON ug.grupo_rol_id = gr.id 
                    LEFT JOIN feligreses f ON u.id = f.usuario_id
                    WHERE ug.grupo_parroquial_id = ?
                    ORDER BY ug.estado_registro ASC, f.primer_nombre ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$grupo_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar todos los miembros: " . $e->getMessage());
            return [];
        }
    }

    // Métodos auxiliares privados

    /**
     * @param int $grupo_id
     * @param int $usuario_id
     * @return bool Verifica si un usuario ya está en un grupo (y activo).
     */
    private function mdlUsuarioEnGrupo($grupo_id, $usuario_id)
    {
        try {
            $sql = "SELECT COUNT(*) FROM usuario_grupos 
                    WHERE grupo_parroquial_id = ? AND usuario_id = ? AND estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$grupo_id, $usuario_id]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar usuario en grupo: " . $e->getMessage());
            return true; // Por seguridad, asumimos que está en el grupo
        }
    }

    /**
     * @param int $usuario_id
     * @return bool Verifica si un usuario existe.
     */
    private function mdlUsuarioExiste($usuario_id)
    {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$usuario_id]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar existencia de usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $nombre_grupo
     * @return array|false Verifica si existe un grupo activo con ese nombre.
     */
    private function mdlGrupoExistePorNombre($nombre_grupo)
    {
        try {
            $sql = "SELECT id, nombre FROM grupos WHERE LOWER(nombre) = LOWER(?) AND estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$nombre_grupo]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al verificar existencia de grupo por nombre: " . $e->getMessage());
            return false;
        }
    }
}
?>