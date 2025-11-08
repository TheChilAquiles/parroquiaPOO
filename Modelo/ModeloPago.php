<?php

/**
 * ModeloPago.php - REFACTORIZADO
 * 
 * Modelo para gestión de pagos
 */

class ModeloPago
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Obtiene todos los pagos con información relacionada (certificados y feligreses)
     */
    public function mdlObtenerTodos()
    {
        try {
            $sql = "SELECT
                        p.id,
                        p.certificado_id,
                        p.valor,
                        p.estado,
                        tp.descripcion AS metodo_de_pago,
                        p.fecha_pago,
                        c.tipo_certificado,
                        CONCAT(f.primer_nombre, ' ', f.primer_apellido) AS feligres_nombre,
                        f.numero_documento,
                        CONCAT(sol.primer_nombre, ' ', sol.primer_apellido) AS solicitante_nombre
                    FROM pagos p
                    LEFT JOIN certificados c ON p.certificado_id = c.id
                    LEFT JOIN feligreses f ON c.feligres_certificado_id = f.id
                    LEFT JOIN feligreses sol ON c.solicitante_id = sol.id
                    LEFT JOIN tipos_pago tp ON p.tipo_pago_id = tp.id
                    WHERE p.estado_registro IS NULL
                    ORDER BY p.fecha_pago DESC, p.id DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener pagos:", ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Obtiene un pago por ID
     */
    public function mdlObtenerPorId($id)
    {
        try {
            $sql = "SELECT * FROM pagos WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener pago:", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Crea un nuevo pago
     */
    public function mdlCrear($data)
    {
        try {
            // Convertir método de pago a ID si viene como texto
            $tipoPagoId = $this->obtenerTipoPagoId($data['metodo_de_pago'] ?? null);

            $sql = "INSERT INTO pagos (certificado_id, valor, estado, tipo_pago_id, fecha_pago)
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $data['certificado_id'],
                $data['valor'],
                $data['estado'],
                $tipoPagoId
            ]);

            if ($stmt->rowCount() > 0) {
                return ['exito' => true, 'mensaje' => 'Pago creado correctamente'];
            }
            return ['exito' => false, 'mensaje' => 'No se pudo crear el pago'];
        } catch (PDOException $e) {
            Logger::error("Error al crear pago:", ['error' => $e->getMessage()]);
            return ['exito' => false, 'mensaje' => 'Error al crear pago'];
        }
    }

    /**
     * Actualiza un pago
     */
    public function mdlActualizar($id, $data)
    {
        try {
            // Convertir método de pago a ID si viene como texto
            $tipoPagoId = $this->obtenerTipoPagoId($data['metodo_de_pago'] ?? null);

            $sql = "UPDATE pagos SET estado = ?, tipo_pago_id = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $data['estado'],
                $tipoPagoId,
                $id
            ]);

            if ($stmt->rowCount() > 0) {
                return ['exito' => true, 'mensaje' => 'Pago actualizado correctamente'];
            }
            return ['exito' => false, 'mensaje' => 'No se pudo actualizar el pago'];
        } catch (PDOException $e) {
            Logger::error("Error al actualizar pago:", ['error' => $e->getMessage()]);
            return ['exito' => false, 'mensaje' => 'Error al actualizar pago'];
        }
    }

    /**
     * Elimina un pago (pero verifica dependencias primero)
     */
    public function mdlEliminar($id)
    {
        try {
            // Verificar si hay reportes asociados
            $sql_check = "SELECT COUNT(*) FROM reportes WHERE id_pagos = ?";
            $stmt_check = $this->conexion->prepare($sql_check);
            $stmt_check->execute([$id]);
            $tiene_reportes = $stmt_check->fetchColumn();

            if ($tiene_reportes > 0) {
                return ['exito' => false, 'mensaje' => 'No se puede eliminar: hay reportes asociados'];
            }

            // Eliminar si no hay dependencias
            $sql = "DELETE FROM pagos WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return ['exito' => true, 'mensaje' => 'Pago eliminado correctamente'];
            }
            return ['exito' => false, 'mensaje' => 'No se pudo eliminar el pago'];
        } catch (PDOException $e) {
            Logger::error("Error al eliminar pago:", ['error' => $e->getMessage()]);
            return ['exito' => false, 'mensaje' => 'Error al eliminar pago'];
        }
    }

    /**
     * Obtiene estadísticas de pagos
     */
    public function mdlObtenerEstadisticas()
    {
        try {
            $sql = "SELECT
                        COUNT(*) as total,
                        SUM(CASE WHEN LOWER(estado) = 'pagado' THEN 1 ELSE 0 END) as completados,
                        SUM(valor) as valor_total,
                        SUM(CASE WHEN LOWER(estado) = 'pagado' THEN valor ELSE 0 END) as valor_completado
                    FROM pagos";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener estadísticas:", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Obtiene lista de certificados para select
     */
    public function mdlObtenerCertificados()
    {
        try {
            $sql = "SELECT id FROM certificados ORDER BY id DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener certificados:", ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Convierte método de pago (texto o ID) a ID de tipos_pago
     * @param mixed $metodoPago Puede ser ID numérico o texto ("efectivo", "tarjeta", etc)
     * @return int|null ID del tipo de pago o null
     */
    private function obtenerTipoPagoId($metodoPago)
    {
        // Si ya es numérico y válido, devolverlo
        if (is_numeric($metodoPago) && $metodoPago > 0) {
            return (int)$metodoPago;
        }

        // Si es texto, convertir a ID
        if (is_string($metodoPago)) {
            $mapeo = [
                'efectivo' => 3,
                'tarjeta credito' => 1,
                'tarjeta debito' => 2,
                'transferencia' => 4,
                'paypal' => 5
            ];

            $metodoPago = strtolower(trim($metodoPago));
            return $mapeo[$metodoPago] ?? 3; // Default: efectivo
        }

        // Default: efectivo
        return 3;
    }
}
