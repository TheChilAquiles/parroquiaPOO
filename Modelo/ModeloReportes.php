<?php
class ModeloReporte {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function obtenerReportes() {
        $sql = "SELECT id, id_pagos, titulo, descripcion, categoria, fecha FROM reportes ORDER BY fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}