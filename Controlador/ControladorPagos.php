<?php
require_once __DIR__ . "/../Modelo/Conexion.php";

class ControladorPagos {

    public function index() {
        $conexion = Conexion::conectar();
        $mensaje = "";

        // Manejo de borrado
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            $id = intval($_POST['id'] ?? 0);

            if ($id > 0) {
                // Verificar si el pago existe
                $check = $conexion->prepare("SELECT id FROM pagos WHERE id = ?");
                $check->execute([$id]);

                if ($check->fetch()) {
                    // Verificar si el pago tiene reportes asociados
                    $checkReportes = $conexion->prepare("SELECT COUNT(*) FROM reportes WHERE id_pagos = ?");
                    $checkReportes->execute([$id]);
                    $tieneReportes = $checkReportes->fetchColumn();

                    if ($tieneReportes > 0) {
                        $mensaje = "❌ No se puede eliminar el pago con ID {$id} porque tiene reportes asociados.";
                    } else {
                        $del = $conexion->prepare("DELETE FROM pagos WHERE id = ?");
                        $del->execute([$id]);
                        $mensaje = "✅ Pago con ID {$id} eliminado correctamente.";
                    }
                } else {
                    $mensaje = "⚠️ No se encontró pago con ID {$id}.";
                }
            } else {
                $mensaje = "⚠️ ID inválido.";
            }
        }

        // Listado
        $sql = "SELECT * FROM pagos ORDER BY fecha_pago DESC, id DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Estadísticas
        $totalPagos = count($pagos);
        $pagosCompletados = count(array_filter($pagos, function($p) { 
            return strtolower($p['estado']) === 'pagado'; 
        }));
        $valorTotal = array_sum(array_column($pagos, 'valor'));

        // Pasar datos a la vista
        require __DIR__ . "/../Vista/pagos.php";
    }

    public function nuevo() {
        require __DIR__ . "/../Vista/agregar_pago.php";
    }
}
