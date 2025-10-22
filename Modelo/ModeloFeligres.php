<?php
// ============================================================================
// ModeloFeligres.php - REFACTORIZADO
// ============================================================================

class ModeloFeligres
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Consulta un feligrés por tipo y número de documento
     */
    public function mdlConsultarFeligres($tipoDoc, $documento)
    {
        try {
            $sql = "SELECT * FROM feligreses 
                    WHERE tipo_documento_id = ? AND numero_documento = ? 
                    LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$tipoDoc, $documento]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ?: false;
        } catch (PDOException $e) {
            error_log("Error al consultar feligrés: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea un nuevo feligrés
     */
    public function mdlCrearFeligres($datos)
    {
        try {
            $sql = "INSERT INTO feligreses 
                    (usuario_id, tipo_documento_id, numero_documento, primer_nombre, 
                     segundo_nombre, primer_apellido, segundo_apellido, telefono, direccion) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $datos['idUser'] ?? null,
                $datos['tipo-doc'],
                $datos['documento'],
                $datos['primer-nombre'],
                $datos['segundo-nombre'] ?? '',
                $datos['primer-apellido'],
                $datos['segundo-apellido'] ?? '',
                $datos['telefono'] ?? '',
                $datos['direccion']
            ]);

            if ($stmt->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Feligrés registrado correctamente'];
            }
            return ['status' => 'error', 'message' => 'No se pudo registrar el feligrés'];
        } catch (PDOException $e) {
            error_log("Error al crear feligrés: " . $e->getMessage());
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'El feligrés ya existe'];
            }
            return ['status' => 'error', 'message' => 'Error al registrar feligrés'];
        }
    }

    /**
     * Actualiza los datos de un feligrés
     */
    public function mdlUpdateFeligres($datos)
    {
        try {
            $sql = "UPDATE feligreses 
                    SET tipo_documento_id = ?, numero_documento = ?, 
                        primer_nombre = ?, segundo_nombre = ?, 
                        primer_apellido = ?, segundo_apellido = ?, 
                        telefono = ?, direccion = ? 
                    WHERE numero_documento = ?";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $datos['tipo-doc'],
                $datos['documento'],
                $datos['primer-nombre'],
                $datos['segundo-nombre'] ?? '',
                $datos['primer-apellido'],
                $datos['segundo-apellido'] ?? '',
                $datos['telefono'] ?? '',
                $datos['direccion'],
                $datos['documento']
            ]);

            if ($stmt->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Feligrés actualizado correctamente'];
            }
            return ['status' => 'error', 'message' => 'No se pudo actualizar el feligrés'];
        } catch (PDOException $e) {
            error_log("Error al actualizar feligrés: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error al actualizar feligrés'];
        }
    }

    /**
     * Obtiene un feligrés por ID
     */
    public function mdlObtenerPorId($id)
    {
        try {
            $sql = "SELECT * FROM feligreses WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener feligrés: " . $e->getMessage());
            return null;
        }
    }
}

