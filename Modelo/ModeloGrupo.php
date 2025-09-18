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
     * @return array Retorna todos los grupos parroquiales con información adicional.
     */
    public function mdlListarGrupos()
    {
        try {
            $sql = "SELECT 
                        g.id, 
                        g.nombre,
                        COUNT(ug.usuario_id) as total_miembros,
                        g.estado_registro
                    FROM grupos g 
                    LEFT JOIN usuario_grupos ug ON g.id = ug.grupo_parroquial_id
                    GROUP BY g.id, g.nombre, g.estado_registro
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
     * @return array|null Retorna la información de un grupo específico con total de miembros.
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
                        COUNT(ug.usuario_id) as total_miembros,
                        g.estado_registro
                    FROM grupos g 
                    LEFT JOIN usuario_grupos ug ON g.id = ug.grupo_parroquial_id
                    WHERE g.id = ?
                    GROUP BY g.id, g.nombre, g.estado_registro";
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
     * @return array Retorna la lista de miembros de un grupo con información completa.
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
                        ur.rol as rol_usuario,
                        ug.estado_registro as fecha_ingreso
                    FROM usuario_grupos ug 
                    JOIN usuarios u ON ug.usuario_id = u.id 
                    LEFT JOIN grupo_roles gr ON ug.grupo_rol_id = gr.id 
                    LEFT JOIN feligreses f ON u.id = f.usuario_id
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id
                    WHERE ug.grupo_parroquial_id = ?
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

            // Verificar si el usuario ya está en el grupo
            if ($this->mdlUsuarioEnGrupo($grupo_id, $usuario_id)) {
                error_log("El usuario ya está en el grupo");
                return false;
            }

            // Verificar que el grupo existe
            if (!$this->mdlObtenerGrupoPorId($grupo_id)) {
                error_log("El grupo no existe");
                return false;
            }

            // Verificar que el usuario existe
            if (!$this->mdlUsuarioExiste($usuario_id)) {
                error_log("El usuario no existe");
                return false;
            }

            $sql = "INSERT INTO usuario_grupos (grupo_parroquial_id, usuario_id, grupo_rol_id, estado_registro) 
                    VALUES (?, ?, ?, NOW())";
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

            $sql = "DELETE FROM usuario_grupos WHERE grupo_parroquial_id = ? AND usuario_id = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$grupo_id, $usuario_id]);
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

            // Verificar que no existe un grupo con el mismo nombre
            if ($this->mdlGrupoExistePorNombre($nombre_grupo)) {
                error_log("Ya existe un grupo con ese nombre");
                return false;
            }

            $sql = "INSERT INTO grupos (nombre, estado_registro) VALUES (?, NOW())";
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
     * @return bool Retorna true si se eliminó el grupo, false en caso contrario.
     */
    public function mdlEliminarGrupo($grupo_id)
    {
        try {
            // Validar que el ID sea un número válido
            if (!is_numeric($grupo_id) || $grupo_id <= 0) {
                return false;
            }

            // Iniciar transacción para eliminar primero los miembros y luego el grupo
            $this->conexion->beginTransaction();

            try {
                // Primero eliminar todos los miembros del grupo
                $sql1 = "DELETE FROM usuario_grupos WHERE grupo_parroquial_id = ?";
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->execute([$grupo_id]);

                // Luego eliminar el grupo
                $sql2 = "DELETE FROM grupos WHERE id = ?";
                $stmt2 = $this->conexion->prepare($sql2);
                $stmt2->execute([$grupo_id]);

                $this->conexion->commit();
                return true;
            } catch (PDOException $e) {
                $this->conexion->rollback();
                throw $e;
            }
        } catch (PDOException $e) {
            error_log("Error al eliminar grupo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @return array Retorna todos los roles disponibles para grupos.
     */
    public function mdlListarRolesGrupo()
    {
        try {
            $sql = "SELECT id, rol FROM grupo_roles ORDER BY id ASC";
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

    // Métodos auxiliares privados

    /**
     * @param int $grupo_id
     * @param int $usuario_id
     * @return bool Verifica si un usuario ya está en un grupo.
     */
    private function mdlUsuarioEnGrupo($grupo_id, $usuario_id)
    {
        try {
            $sql = "SELECT COUNT(*) FROM usuario_grupos WHERE grupo_parroquial_id = ? AND usuario_id = ?";
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
     * @return array|false Verifica si existe un grupo con ese nombre.
     */
    private function mdlGrupoExistePorNombre($nombre_grupo)
    {
        try {
            $sql = "SELECT id, nombre FROM grupos WHERE LOWER(nombre) = LOWER(?)";
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