<?php
require_once '../modelo/Conexion.php';
require_once '../modelo/ModeloReporte.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $conexion = Conexion::conectar();
    $modelo = new ModeloReporte($conexion);

    if ($_GET['accion'] === 'listar') {
        $data = $modelo->obtenerReportes();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error" => "Acción no válida"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
