<?php
require_once "Conexion.php";

class ModeloPago {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // ✅ Se llama igual en el controlador
    public function obtenerPagos() {
        $query = $this->db->query("SELECT * FROM pagos");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Se llama igual en el controlador
    public function insertarPago($certificado_id, $valor, $estado, $estado_registro, $tipo_pago_id) {
        $sql = "INSERT INTO pagos (certificado_id, valor, estado, estado_registro, tipo_pago_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$certificado_id, $valor, $estado, $estado_registro, $tipo_pago_id]);
    }
}
