<?php
require_once __DIR__ . "/Conexion.php";

class PagosModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM pagos ORDER BY fecha_pago DESC, id DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id) {
        $del = $this->conexion->prepare("DELETE FROM pagos WHERE id = ?");
        return $del->execute([$id]);
    }
}
