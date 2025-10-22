<?php

// ============================================================================
// ModeloParticipante.php - REFACTORIZADO
// ============================================================================

class ModeloParticipante
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Crea un nuevo participante
     */
    public function crearParticipante($data)
    {
        try {
            $sql = "INSERT INTO participantes 
                    (feligres_id, sacramento_id, rol_participante_id)
                    VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            
            $stmt->execute([
                $data['feligres-id'] ?? $data['feligres_id'],
                $data['sacramento-id'] ?? $data['sacramento_id'],
                $data['participante-id'] ?? $data['participante_id']
            ]);

            return ['success' => true, 'id' => $this->conexion->lastInsertId()];
        } catch (PDOException $e) {
            error_log("Error al crear participante: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Obtiene todos los participantes
     */
    public function obtenerParticipantes()
    {
        try {
            $sql = "SELECT * FROM participantes ORDER BY id DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener participantes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un participante por ID
     */
    public function obtenerPorId($id)
    {
        try {
            $sql = "SELECT * FROM participantes WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener participante: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza un participante
     */
    public function actualizar($id, $data)
    {
        try {
            $sql = "UPDATE participantes 
                    SET feligres_id = ?, sacramento_id = ?, rol_participante_id = ? 
                    WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            
            return $stmt->execute([
                $data['feligres_id'],
                $data['sacramento_id'],
                $data['rol_participante_id'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar participante: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un participante
     */
    public function eliminar($id)
    {
        try {
            $sql = "DELETE FROM participantes WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar participante: " . $e->getMessage());
            return false;
        }
    }
}

