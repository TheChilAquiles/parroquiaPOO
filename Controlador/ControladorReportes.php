<?php
require_once __DIR__ . "/../Modelo/Conexion.php";

header("Content-Type: application/json; charset=UTF-8");

try {
    $conexion = Conexion::conectar();

    $accion = $_GET['accion'] ?? '';

    switch ($accion) {
        case 'listar':
            $sql = "SELECT id, titulo, descripcion, fecha, categoria, id_pagos 
                    FROM reportes 
                    ORDER BY fecha DESC";
            $stmt = $conexion->query($sql);
            $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($reportes);
            break;

        case 'detalle':
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(["error" => "Falta el parámetro ID"]);
                exit;
            }
            $sql = "SELECT id, titulo, descripcion, fecha, categoria, id_pagos 
                    FROM reportes WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$_GET['id']]);
            $reporte = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($reporte ?: []);
            break;

        case 'crear':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                http_response_code(400);
                echo json_encode(["error" => "Datos inválidos"]);
                exit;
            }

            $sql = "INSERT INTO reportes (titulo, descripcion, fecha, categoria, id_pagos) 
                    VALUES (?, ?, NOW(), ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                $data['titulo'],
                $data['descripcion'],
                $data['categoria'],
                $data['id_pagos']
            ]);

            echo json_encode(["success" => true, "id" => $conexion->lastInsertId()]);
            break;

        case 'actualizar':
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data || !isset($data['id'])) {
                http_response_code(400);
                echo json_encode(["error" => "Datos inválidos"]);
                exit;
            }

            $sql = "UPDATE reportes 
                    SET titulo = ?, descripcion = ?, categoria = ?, id_pagos = ? 
                    WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                $data['titulo'],
                $data['descripcion'],
                $data['categoria'],
                $data['id_pagos'],
                $data['id']
            ]);

            echo json_encode(["success" => true]);
            break;

        case 'eliminar':
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(["error" => "Falta el parámetro ID"]);
                exit;
            }
            $sql = "DELETE FROM reportes WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$_GET['id']]);

            echo json_encode(["success" => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(["error" => "Acción no válida"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}



