<?php
class ModeloReporte {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    // Listar con JOIN hacia pagos
    public function listar() {
        $sql = "SELECT r.id, r.titulo, r.descripcion, r.fecha, r.categoria, r.id_pagos,
                       p.valor, p.estado, p.fecha_pago
                FROM reportes r
                LEFT JOIN pagos p ON r.id_pagos = p.id
                ORDER BY r.fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Guardar
    public function guardar($titulo, $descripcion, $fecha, $categoria, $id_pagos) {
        $sql = "INSERT INTO reportes (titulo, descripcion, fecha, categoria, id_pagos) 
                VALUES (:titulo, :descripcion, :fecha, :categoria, :id_pagos)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha' => $fecha,
            ':categoria' => $categoria,
            ':id_pagos' => $id_pagos
        ]);
        return $this->db->lastInsertId();
    }
}
