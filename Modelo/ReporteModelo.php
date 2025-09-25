<?php
// Modelo/ReporteModelo.php
require_once __DIR__ . '/Conexion.php';

class ReporteModelo {
    private $db;

    public function __construct() {
        // Usamos tu método actual de conexión (Conexion::conectar())
        $this->db = Conexion::conectar();
    }

    /**
     * Devuelve todos los reportes con su pago asociado
     * @return array
     */
    public function obtenerReportes(): array {
        $sql = "SELECT
                    r.id AS id_reporte,
                    r.titulo,
                    r.descripcion,
                    r.categoria,
                    r.fecha AS fecha_reporte,
                    r.estado_registro,
                    p.id AS id_pago,
                    p.certificado_id,
                    p.valor,
                    p.estado AS estado_pago,
                    p.fecha_pago,
                    p.tipo_pago_id
                FROM reportes r
                INNER JOIN pagos p ON r.id_pagos = p.id
                ORDER BY r.id DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
