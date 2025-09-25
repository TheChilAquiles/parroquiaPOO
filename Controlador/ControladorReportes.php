<?php
require_once '../modelo/Conexion.php';
require_once '../modelo/ModeloReporte.php';

$conexion = Conexion::conectar();
$modelo = new ModeloReporte($conexion);

$accion = $_GET['accion'] ?? '';

switch ($accion) {
    case 'listar':
        header('Content-Type: application/json');
        echo json_encode($modelo->listar(), JSON_UNESCAPED_UNICODE);
        break;

    case 'guardar':
        $data = json_decode(file_get_contents("php://input"), true);
        $nuevoId = $modelo->guardar(
            $data['titulo'],
            $data['descripcion'],
            $data['fecha'],
            $data['categoria'],
            $data['id_pagos']
        );
        echo json_encode(["success" => true, "id" => $nuevoId]);
        break;

    default:
        echo json_encode(["error" => "Acción no válida"]);
}
