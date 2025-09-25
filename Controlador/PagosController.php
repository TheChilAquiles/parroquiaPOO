<?php
require_once(__DIR__ . '/../Modelo/ModeloPago.php');
require_once(__DIR__ . '/../Modelo/Conexion.php'); 

class PagosController {
    private $db;
    private $pago;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->pago = new CrearPagos($this->db);
    }

    // Método para crear un pago
    public function crearPago($data) {
        if (
            isset($data['certificado_id']) &&
            isset($data['valor']) &&
            isset($data['estado']) &&
            isset($data['metodo_de_pago'])
        ) {
            $this->pago->certificado_id = $data['certificado_id'];
            $this->pago->valor = $data['valor'];
            $this->pago->estado = $data['estado'];
            $this->pago->metodo_de_pago = $data['metodo_de_pago'];

            if ($this->pago->crear()) {
                $mensaje = "Pago creado con éxito.";
            } else {
                $mensaje = "Error al crear el pago.";
            }
        } else {
            $mensaje = "Datos incompletos.";
        }

        // Siempre carga la vista después de procesar
        include_once(__DIR__ . '/../Vista/agregar_pago.php');
    }
}
