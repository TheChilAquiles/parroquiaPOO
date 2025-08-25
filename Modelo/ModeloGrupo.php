<?php
// Incluir el archivo de conexión
require_once 'Conexion.php'; 

class ModeloGrupo
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * @return array Retorna todos los grupos parroquiales.
     */
    public function mdlListarGrupos()
    {
        try {
            $sql = "SELECT id, nombre FROM grupos";
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
     * @return array Retorna la información de un grupo específico.
     */
    public function mdlObtenerGrupoPorId($grupo_id)
    {
        try {
            $sql = "SELECT id, nombre FROM grupos WHERE id = ?";
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
     * @return array Retorna la lista de miembros de un grupo con su rol.
     */
    public function mdlListarMiembrosGrupo($grupo_id)
    {
        try {
            $sql = "SELECT u.email, gr.rol FROM usuario_grupos ug JOIN usuarios u ON ug.usuario_id = u.id JOIN grupo_roles gr ON ug.grupo_rol_id = gr.id WHERE ug.grupo_parroquial_id = ?";
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
            $sql = "INSERT INTO usuario_grupos (grupo_parroquial_id, usuario_id, grupo_rol_id) VALUES (?, ?, ?)";
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
            $sql = "DELETE FROM usuario_grupos WHERE grupo_parroquial_id = ? AND usuario_id = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$grupo_id, $usuario_id]);
        } catch (PDOException $e) {
            error_log("Error al eliminar miembro: " . $e->getMessage());
            return false;
        }
    }
    
    public function mdlCrearGrupo($nombre_grupo)
    {
        try {
            $sql = "INSERT INTO grupos (nombre) VALUES (?)";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$nombre_grupo]);
        } catch (PDOException $e) {
            error_log("Error al crear grupo: " . $e->getMessage());
            return false;
        }
    }
}


?>