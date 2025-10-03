<?php

/**
 * @file ModeloGrupo.php
 * @version 2.0
 * @author Samuel Bedoya
 * @brief Modelo de datos para gestión de grupos parroquiales
 * 
 * Implementa el patrón DAO para gestionar grupos parroquiales, sus miembros,
 * roles y relaciones. Incluye funcionalidades de auditoría con borrado lógico.
 * 
 * @architecture
 * - Patrón DAO (Data Access Object)
 * - Soft Delete para auditoría
 * - Validaciones robustas de datos
 * - Transacciones para operaciones complejas
 * 
 * @security
 * - Prepared Statements (prevención SQL Injection)
 * - Validación de tipos de datos
 * - Logging de errores sin exponer información sensible
 * - Verificaciones de integridad referencial
 * 
 * @package Modelo
 * @dependency Conexion.php - Clase que maneja la conexión PDO
 */

class ModeloGrupo
{
    /**
     * Conexión PDO a la base de datos.
     * 
     * @var PDO
     */
    private $conexion;

    /**
     * Constructor de la clase.
     * Inicializa la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    // ========================================================================
    // OPERACIONES CRUD DE GRUPOS
    // ========================================================================

    /**
     * Lista todos los grupos parroquiales activos con información estadística.
     * 
     * Obtiene los grupos no eliminados junto con el conteo de miembros activos.
     * Utiliza LEFT JOIN para incluir grupos sin miembros.
     * 
     * @return array Lista de grupos con estructura:
     *               - id: ID del grupo
     *               - nombre: Nombre del grupo
     *               - total_miembros: Cantidad de miembros activos
     * 
     * @example
     * [
     *   ['id' => 1, 'nombre' => 'Coro Juvenil', 'total_miembros' => 15],
     *   ['id' => 2, 'nombre' => 'Catequesis', 'total_miembros' => 30]
     * ]
     */
    public function mdlListarGrupos()
    {
        try {
            // Query optimizada con conteo condicional para solo miembros activos
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
     * Obtiene información detallada de un grupo específico.
     * 
     * @param int $grupo_id ID del grupo a consultar
     * 
     * @return array|null Información del grupo o null si no existe/está eliminado
     * 
     * @security Valida que el ID sea numérico positivo
     */
    public function mdlObtenerGrupoPorId($grupo_id)
    {
        try {
            // Validación de seguridad: prevenir IDs inválidos
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
     * Crea un nuevo grupo parroquial.
     * 
     * Valida unicidad del nombre entre grupos activos antes de crear.
     * 
     * @param string $nombre_grupo Nombre del nuevo grupo
     * 
     * @return bool|int ID del grupo creado si es exitoso, false en caso contrario
     * 
     * @validations
     * - Nombre no vacío
     * - Nombre único entre grupos activos
     */
    public function mdlCrearGrupo($nombre_grupo)
    {
        try {
            // Validar que el nombre no esté vacío
            if (empty(trim($nombre_grupo))) {
                return false;
            }

            $nombre_grupo = trim($nombre_grupo);

            // Verificar unicidad del nombre entre grupos activos
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
     * Actualiza el nombre de un grupo existente.
     * 
     * @param int $grupo_id ID del grupo a actualizar
     * @param string $nombre_grupo Nuevo nombre
     * 
     * @return bool true si se actualizó correctamente, false en caso contrario
     * 
     * @validations
     * - Grupo existe y está activo
     * - Nombre no vacío
     * - Nombre único (excepto el mismo grupo)
     */
    public function mdlActualizarGrupo($grupo_id, $nombre_grupo)
    {
        try {
            // Validaciones básicas
            if (!is_numeric($grupo_id) || $grupo_id <= 0 || empty(trim($nombre_grupo))) {
                return false;
            }

            $nombre_grupo = trim($nombre_grupo);

            // Verificar existencia del grupo
            if (!$this->mdlObtenerGrupoPorId($grupo_id)) {
                return false;
            }

            // Verificar unicidad: permitir el mismo nombre solo para el mismo grupo
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
     * Realiza borrado lógico de un grupo y sus relaciones.
     * 
     * Utiliza transacción para garantizar atomicidad:
     * 1. Elimina lógicamente las relaciones usuario-grupo
     * 2. Elimina lógicamente el grupo
     * 
     * @param int $grupo_id ID del grupo a eliminar
     * 
     * @return bool true si se eliminó correctamente, false en caso contrario
     * 
     * @pattern Soft Delete con transacción
     * @note Los datos no se borran físicamente, solo se marca fecha de eliminación
     */
    public function mdlEliminarGrupo($grupo_id)
    {
        try {
            // Validación de ID
            if (!is_numeric($grupo_id) || $grupo_id <= 0) {
                return false;
            }

            // Verificar que el grupo existe y está activo
            $grupo = $this->mdlObtenerGrupoPorId($grupo_id);
            if (!$grupo) {
                return false;
            }

            // Iniciar transacción para operación atómica
            $this->conexion->beginTransaction();

            try {
                // Paso 1: Eliminar lógicamente las relaciones usuario-grupo
                $sql1 = "UPDATE usuario_grupos 
                        SET estado_registro = NOW() 
                        WHERE grupo_parroquial_id = ? AND estado_registro IS NULL";
                $stmt1 = $this->conexion->prepare($sql1);
                $stmt1->execute([$grupo_id]);

                // Paso 2: Eliminar lógicamente el grupo
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

    // ========================================================================
    // GESTIÓN DE MIEMBROS
    // ========================================================================

    /**
     * Lista todos los miembros activos de un grupo con información completa.
     * 
     * Incluye datos del usuario, rol en el grupo, información del feligrés
     * y rol general del usuario en el sistema.
     * 
     * @param int $grupo_id ID del grupo
     * 
     * @return array Lista de miembros con estructura completa:
     *               - usuario_id
     *               - email
     *               - rol (en el grupo)
     *               - nombre_completo
     *               - telefono
     *               - rol_usuario (rol general en el sistema)
     */
    public function mdlListarMiembrosGrupo($grupo_id)
    {
        try {
            // Validación de seguridad
            if (!is_numeric($grupo_id) || $grupo_id <= 0) {
                return [];
            }

            // Query con múltiples JOINs para información completa
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
     * Agrega un miembro a un grupo con un rol específico.
     * 
     * Realiza múltiples validaciones antes de agregar:
     * - IDs válidos
     * - Usuario no está ya en el grupo
     * - Grupo existe y está activo
     * - Usuario existe
     * 
     * @param int $grupo_id ID del grupo
     * @param int $usuario_id ID del usuario
     * @param int $grupo_rol_id ID del rol en el grupo (ej: 1=Miembro, 2=Líder)
     * 
     * @return bool true si se agregó correctamente, false en caso contrario
     */
    public function mdlAgregarMiembro($grupo_id, $usuario_id, $grupo_rol_id)
    {
        try {
            // Validaciones de tipo y rango
            if (!is_numeric($grupo_id) || $grupo_id <= 0 ||
                !is_numeric($usuario_id) || $usuario_id <= 0 ||
                !is_numeric($grupo_rol_id) || $grupo_rol_id <= 0) {
                return false;
            }

            // Verificar que el usuario no esté ya en el grupo (activo)
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
     * Elimina lógicamente un miembro de un grupo.
     * 
     * @param int $grupo_id ID del grupo
     * @param int $usuario_id ID del usuario
     * 
     * @return bool true si se eliminó correctamente, false en caso contrario
     * 
     * @pattern Soft Delete
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
            
            // Log para depuración y auditoría
            error_log("Eliminando miembro - Grupo: $grupo_id, Usuario: $usuario_id, Filas afectadas: " . $stmt->rowCount());
            
            return $resultado && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar miembro: " . $e->getMessage());
            return false;
        }
    }

    // ========================================================================
    // GESTIÓN DE ROLES Y USUARIOS
    // ========================================================================

    /**
     * Lista todos los roles disponibles para asignar en grupos.
     * 
     * @return array Lista de roles con id y nombre
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
     * Lista todos los usuarios disponibles para agregar a grupos.
     * 
     * Incluye usuarios confirmados y con información de feligrés si existe.
     * 
     * @return array Lista de usuarios disponibles con:
     *               - id
     *               - email
     *               - nombre_completo
     *               - tipo_usuario (rol en el sistema)
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

    // ========================================================================
    // AUDITORÍA Y RECUPERACIÓN
    // ========================================================================

    /**
     * Lista todos los grupos eliminados lógicamente (historial).
     * 
     * Útil para auditoría y posible restauración.
     * 
     * @return array Lista de grupos eliminados con fecha de eliminación
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
     * Restaura un grupo eliminado lógicamente.
     * 
     * Valida que:
     * - El grupo existe y está eliminado
     * - No haya otro grupo activo con el mismo nombre
     * 
     * @param int $grupo_id ID del grupo a restaurar
     * 
     * @return bool true si se restauró correctamente, false en caso contrario
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
                return false; // No existe o no está eliminado
            }

            // Verificar unicidad del nombre entre grupos activos
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
     * MÉTODO DE DEPURACIÓN - Lista TODOS los miembros (activos e inactivos).
     * 
     * Útil para debugging y auditoría. Muestra el estado real de todos los registros.
     * 
     * @param int $grupo_id ID del grupo
     * 
     * @return array Lista completa de miembros con estado
     * 
     * @note Solo para uso interno/debugging
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

    // ========================================================================
    // MÉTODOS AUXILIARES PRIVADOS
    // Validaciones y verificaciones internas
    // ========================================================================

    /**
     * Verifica si un usuario ya está en un grupo (y activo).
     * 
     * @param int $grupo_id ID del grupo
     * @param int $usuario_id ID del usuario
     * 
     * @return bool true si está en el grupo, false en caso contrario
     * 
     * @access private
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
     * Verifica si un usuario existe en el sistema.
     * 
     * @param int $usuario_id ID del usuario
     * 
     * @return bool true si existe, false en caso contrario
     * 
     * @access private
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
     * Verifica si existe un grupo activo con un nombre específico.
     * 
     * La búsqueda es case-insensitive para evitar duplicados con diferentes mayúsculas.
     * 
     * @param string $nombre_grupo Nombre a verificar
     * 
     * @return array|false Datos del grupo si existe, false en caso contrario
     * 
     * @access private
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