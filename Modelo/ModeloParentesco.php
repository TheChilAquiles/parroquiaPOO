<?php

// ============================================================================
// ModeloParentesco.php
// Gestiona relaciones familiares entre feligreses
// ============================================================================

class ModeloParentesco
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Obtiene todos los parentescos de un feligrés
     * @param int $feligresId ID del feligrés
     * @return array Lista de familiares
     */
    public function mdlObtenerPorFeligres($feligresId)
    {
        try {
            // Obtener tanto cuando es sujeto como cuando es pariente
            $sql = "SELECT
                        p.id,
                        CASE
                            WHEN p.feligres_sujeto_id = ? THEN p.feligres_pariente_id
                            ELSE p.feligres_sujeto_id
                        END AS familiar_id,
                        CONCAT(f.primer_nombre, ' ', COALESCE(f.segundo_nombre, ''), ' ',
                               f.primer_apellido, ' ', COALESCE(f.segundo_apellido, '')) AS nombre_completo,
                        f.numero_documento,
                        dt.tipo AS tipo_documento,
                        pa.parentesco,
                        p.parentesco_id
                    FROM parientes p
                    JOIN feligreses f ON (
                        CASE
                            WHEN p.feligres_sujeto_id = ? THEN p.feligres_pariente_id = f.id
                            ELSE p.feligres_sujeto_id = f.id
                        END
                    )
                    JOIN parentescos pa ON p.parentesco_id = pa.id
                    JOIN documento_tipos dt ON f.tipo_documento_id = dt.id
                    WHERE (p.feligres_sujeto_id = ? OR p.feligres_pariente_id = ?)
                    AND p.estado_registro IS NULL
                    ORDER BY pa.parentesco, f.primer_nombre";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$feligresId, $feligresId, $feligresId, $feligresId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener parentescos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lista todos los parentescos registrados (para Admin/Secretario)
     * @return array Lista completa de parentescos
     */
    public function mdlListarTodos()
    {
        try {
            $sql = "SELECT
                        p.id,
                        CONCAT(f1.primer_nombre, ' ', f1.primer_apellido) AS feligres_sujeto,
                        CONCAT(f2.primer_nombre, ' ', f2.primer_apellido) AS feligres_pariente,
                        pa.parentesco
                    FROM parientes p
                    JOIN feligreses f1 ON p.feligres_sujeto_id = f1.id
                    JOIN feligreses f2 ON p.feligres_pariente_id = f2.id
                    JOIN parentescos pa ON p.parentesco_id = pa.id
                    WHERE p.estado_registro IS NULL
                    ORDER BY f1.primer_nombre, f2.primer_nombre";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar parentescos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crea una nueva relación de parentesco
     * @param array $datos [feligres_sujeto_id, feligres_pariente_id, parentesco_id]
     * @return array ['status' => 'success'|'error', 'message' => string]
     */
    public function mdlCrear($datos)
    {
        try {
            // Validar que no sea el mismo feligrés
            if ($datos['feligres_sujeto_id'] == $datos['feligres_pariente_id']) {
                return [
                    'status' => 'error',
                    'message' => 'Un feligrés no puede ser pariente de sí mismo'
                ];
            }

            // Validar que no exista ya la relación
            if ($this->mdlVerificarRelacion($datos['feligres_sujeto_id'], $datos['feligres_pariente_id'])) {
                return [
                    'status' => 'error',
                    'message' => 'Esta relación de parentesco ya existe'
                ];
            }

            // Insertar parentesco
            $sql = "INSERT INTO parientes (feligres_sujeto_id, feligres_pariente_id, parentesco_id)
                    VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $datos['feligres_sujeto_id'],
                $datos['feligres_pariente_id'],
                $datos['parentesco_id']
            ]);

            return [
                'status' => 'success',
                'message' => 'Parentesco registrado exitosamente'
            ];
        } catch (PDOException $e) {
            error_log("Error al crear parentesco: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al registrar parentesco'
            ];
        }
    }

    /**
     * Verifica si existe una relación entre dos feligreses (bidireccional)
     * @param int $feligres1 ID del primer feligrés
     * @param int $feligres2 ID del segundo feligrés
     * @return bool
     */
    public function mdlVerificarRelacion($feligres1, $feligres2)
    {
        try {
            $sql = "SELECT COUNT(*) AS existe
                    FROM parientes
                    WHERE ((feligres_sujeto_id = ? AND feligres_pariente_id = ?)
                       OR (feligres_sujeto_id = ? AND feligres_pariente_id = ?))
                    AND estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$feligres1, $feligres2, $feligres2, $feligres1]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['existe'] > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar relación: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los tipos de parentesco disponibles
     * @return array Lista de parentescos
     */
    public function mdlObtenerTiposParentesco()
    {
        try {
            $sql = "SELECT id, parentesco FROM parentescos WHERE estado_registro IS NULL ORDER BY parentesco";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener tipos de parentesco: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Edita un parentesco existente
     * @param int $id ID del parentesco
     * @param array $datos [parentesco_id]
     * @return array ['status' => 'success'|'error', 'message' => string]
     */
    public function mdlEditar($id, $datos)
    {
        try {
            $sql = "UPDATE parientes SET parentesco_id = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$datos['parentesco_id'], $id]);

            if ($stmt->rowCount() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Parentesco actualizado exitosamente'
                ];
            }
            return [
                'status' => 'error',
                'message' => 'No se pudo actualizar el parentesco'
            ];
        } catch (PDOException $e) {
            error_log("Error al editar parentesco: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al actualizar parentesco'
            ];
        }
    }

    /**
     * Elimina un parentesco (soft delete)
     * @param int $id ID del parentesco
     * @return array ['status' => 'success'|'error', 'message' => string]
     */
    public function mdlEliminar($id)
    {
        try {
            $sql = "UPDATE parientes SET estado_registro = NOW() WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Parentesco eliminado exitosamente'
                ];
            }
            return [
                'status' => 'error',
                'message' => 'No se pudo eliminar el parentesco'
            ];
        } catch (PDOException $e) {
            error_log("Error al eliminar parentesco: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al eliminar parentesco'
            ];
        }
    }
}
