<?php

// ============================================================================
// PagosController.php
// ============================================================================

class PagosController
{
    private $conexion;

    public function __construct()
    {
        require_once __DIR__ . '/../Modelo/Conexion.php';
        $this->conexion = Conexion::conectar();
    }

    public function index()
    {
        $mensaje = "";

        // Manejo de eliminación
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            $id = intval($_POST['id'] ?? 0);

            if ($id > 0) {
                try {
                    $check = $this->conexion->prepare("SELECT id FROM pagos WHERE id = ?");
                    $check->execute([$id]);

                    if ($check->fetch()) {
                        // Verificar si el pago tiene reportes asociados
                        $checkReportes = $this->conexion->prepare("SELECT COUNT(*) FROM reportes WHERE id_pagos = ?");
                        $checkReportes->execute([$id]);
                        $tieneReportes = $checkReportes->fetchColumn();

                        if ($tieneReportes > 0) {
                            $_SESSION['error'] = "No se puede eliminar el pago con ID $id porque tiene reportes asociados.";
                        } else {
                            $del = $this->conexion->prepare("DELETE FROM pagos WHERE id = ?");
                            $del->execute([$id]);
                            $_SESSION['success'] = "Pago con ID $id eliminado correctamente.";
                        }
                    } else {
                        $_SESSION['error'] = "No se encontró pago con ID $id.";
                    }
                } catch (Exception $e) {
                    $_SESSION['error'] = "Error al eliminar pago.";
                }
            } else {
                $_SESSION['error'] = "ID inválido.";
            }
        }

        // Listado de pagos
        try {
            $sql = "SELECT * FROM pagos ORDER BY fecha_pago DESC, id DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Estadísticas
            $totalPagos = count($pagos);
            $pagosCompletados = count(array_filter($pagos, function ($p) {
                return strtolower($p['estado']) === 'pagado';
            }));
            $valorTotal = array_sum(array_column($pagos, 'valor'));

            include __DIR__ . '/../Vista/pagos.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al cargar pagos.";
            include __DIR__ . '/../Vista/pagos.php';
        }
    }

    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $certificado_id = $_POST['certificado_id'] ?? null;
            $valor = $_POST['valor'] ?? null;
            $estado = $_POST['estado'] ?? null;
            $metodo = $_POST['metodo_de_pago'] ?? null;

            if (empty($certificado_id) || empty($valor) || empty($estado) || empty($metodo)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                include __DIR__ . '/../Vista/agregar_pago.php';
                return;
            }

            if (!is_numeric($valor) || $valor <= 0) {
                $_SESSION['error'] = 'El valor debe ser un número positivo.';
                include __DIR__ . '/../Vista/agregar_pago.php';
                return;
            }

            try {
                $sql = "INSERT INTO pagos (certificado_id, valor, estado, metodo_de_pago, fecha_pago) 
                        VALUES (?, ?, ?, ?, NOW())";
                $stmt = $this->conexion->prepare($sql);
                $stmt->execute([(int)$certificado_id, (float)$valor, $estado, $metodo]);

                $_SESSION['success'] = 'Pago creado exitosamente.';
                header('Location: ?route=pagos');
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al crear pago.';
                include __DIR__ . '/../Vista/agregar_pago.php';
            }
        } else {
            include __DIR__ . '/../Vista/agregar_pago.php';
        }
    }
}
