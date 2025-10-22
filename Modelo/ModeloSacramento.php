<?php

// ============================================================================
// ModeloSacramento.php - SIMPLIFICADO
// ============================================================================

class ModeloSacramento
{
    private $conexion;
    private $libroID;
    private $sacramentoTipo;
    private $numero;

    public function __construct($tipo = null, $numero = null)
    {
        $this->conexion = Conexion::conectar();
        if ($tipo && $numero) {
            $this->sacramentoTipo = $tipo;
            $this->numero = $numero;
            $this->setLibroID();
        }
    }

    /**
     * Obtiene los participantes de un sacramento
     */
    public function getParticipantes($sacramentoId)
    {
        try {
            $sql = "SELECT 
                        pr.rol,
                        CONCAT(f.primer_nombre, ' ', COALESCE(f.segundo_nombre, ''), ' ', 
                               f.primer_apellido, ' ', COALESCE(f.segundo_apellido, '')) AS nombre
                    FROM participantes p
                    JOIN feligreses f ON f.id = p.feligres_id
                    JOIN participantes_rol pr ON pr.id = p.rol_participante_id
                    WHERE p.sacramento_id = ?";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$sacramentoId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener participantes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Establece el ID del libro
     */
    private function setLibroID()
    {
        try {
            $sql = "SELECT id FROM libros 
                    WHERE libro_tipo_id = ? AND numero = ? 
                    LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$this->sacramentoTipo, $this->numero]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->libroID = $resultado['id'] ?? null;
        } catch (PDOException $e) {
            error_log("Error al obtener ID del libro: " . $e->getMessage());
            $this->libroID = null;
        }
    }

    /**
     * Crea un nuevo sacramento
     */
    public function CrearSacramento($data)
    {
        if (!$this->libroID) {
            return false;
        }

        try {
            $this->conexion->beginTransaction();

            // Crear sacramento
            $sql_sacramento = "INSERT INTO sacramentos 
                              (libro_id, tipo_sacramento_id, fecha_generacion)
                              VALUES (?, ?, NOW())";
            $stmt = $this->conexion->prepare($sql_sacramento);
            $stmt->execute([$this->libroID, $this->sacramentoTipo]);
            
            $sacramentoID = $this->conexion->lastInsertId();

            // Procesar integrantes
            if (isset($data['integrantes']) && is_array($data['integrantes'])) {
                foreach ($data['integrantes'] as $integrante) {
                    // Aquí iría lógica para crear/obtener feligrés y participante
                    // Por ahora solo retornamos el sacramentoID
                }
            }

            $this->conexion->commit();
            return $sacramentoID;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            error_log("Error al crear sacramento: " . $e->getMessage());
            return false;
        }
    }
}