<?php
require_once "../Modelo/ModeloPago.php";

class ControladorPago {
    private $modelo;

    public function __construct() {
        $this->modelo = new ModeloPago();
    }

    // Mostrar lista de pagos
    public function index() {
        $pagos = $this->modelo->obtenerPagos();  // ✅ método existe en ModeloPago
        include "../Vista/Pagos.php";            // se manda $pagos a la vista
    }

    // Guardar un nuevo pago
    public function guardar($data) {
        if (!empty($data)) {
            $this->modelo->insertarPago(         // ✅ método existe en ModeloPago
                $data['certificado_id'],
                $data['valor'],
                $data['estado'],
                $data['fecha_registro'],
                $data['tipo_pago_id']
            );
        }
        header("Location: ControladorPago.php?accion=index");
        exit;
    }
}

// -------- RUTEO --------
$controlador = new ControladorPago();
$accion = $_GET['accion'] ?? 'index';

if ($accion === 'guardar') {
    $controlador->guardar($_POST);
} else {
    $controlador->index();
}
