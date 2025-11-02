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
     * Obtiene todos los pagos
     */
    public function mdlObtenerTodos()
    {
        try {
            $sql = "SELECT * FROM pagos ORDER BY fecha_pago DESC, id DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pagos: " . $e->getMessage());
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
            error_log("Error al obtener pago: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crea un nuevo pago
     */
    public function mdlCrear($data)
    {
        try {
            $sql = "INSERT INTO pagos (certificado_id, valor, estado, metodo_de_pago, fecha_pago)
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $data['certificado_id'],
                $data['valor'],
                $data['estado'],
                $data['metodo_de_pago']
            ]);

            if ($stmt->rowCount() > 0) {
                return ['exito' => true, 'mensaje' => 'Pago creado correctamente'];
            }
            return ['exito' => false, 'mensaje' => 'No se pudo crear el pago'];
        } catch (PDOException $e) {
            error_log("Error al crear pago: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => 'Error al crear pago'];
        }
    }

    /**
     * Actualiza un pago
     */
    public function mdlActualizar($id, $data)
    {
        try {
            $sql = "UPDATE pagos SET estado = ?, metodo_de_pago = ? WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $data['estado'],
                $data['metodo_de_pago'],
                $id
            ]);

            if ($stmt->rowCount() > 0) {
                return ['exito' => true, 'mensaje' => 'Pago actualizado correctamente'];
            }
            return ['exito' => false, 'mensaje' => 'No se pudo actualizar el pago'];
        } catch (PDOException $e) {
            error_log("Error al actualizar pago: " . $e->getMessage());
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
            error_log("Error al eliminar pago: " . $e->getMessage());
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
            error_log("Error al obtener estadísticas: " . $e->getMessage());
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
            error_log("Error al obtener certificados: " . $e->getMessage());
            return [];
        }
    }
}
