<?php
// Modelo/ReporteModelo.php
require_once __DIR__ . '/Conexion.php';

// ============================================================================
// ReporteModelo.php - REFACTORIZADO
// ============================================================================

class ReporteModelo
{
    private $db;

    public function __construct()
    {
        $this->db = Conexion::conectar();
    }

    /**
     * Obtiene todos los reportes con su pago asociado
     */
    public function obtenerReportes()
    {
        try {
            $sql = "SELECT 
                        r.id AS id_reporte,
                        r.titulo,
                        r.descripcion,
                        r.categoria,
                        r.fecha AS fecha_reporte,
                        p.id AS id_pago,
                        p.valor,
                        p.estado AS estado_pago,
                        p.fecha_pago
                    FROM reportes r
                    INNER JOIN pagos p ON r.id_pagos = p.id
                    ORDER BY r.id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener reportes:", ['error' => $e->getMessage()]);
            return [];
        }
    }
}
