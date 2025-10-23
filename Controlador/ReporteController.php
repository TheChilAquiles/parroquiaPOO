<?php

// ============================================================================
// ReportesController.php
// ============================================================================

class ReportesController
{
    private $modelo;

    public function __construct()
    {
        require_once __DIR__ . '/../Modelo/ReporteModelo.php';
        $this->modelo = new ReporteModelo();
    }

    public function index()
    {
        try {
            $reportes = $this->modelo->obtenerReportes();

            // Calcular totales
            $totalReportes = count($reportes);
            $totalValor = 0.0;
            $pagosCompletados = 0;

            foreach ($reportes as $r) {
                $valor = isset($r['valor']) ? floatval($r['valor']) : 0.0;
                $totalValor += $valor;

                $estadoPago = strtolower(trim($r['estado_pago'] ?? ''));
                if (in_array($estadoPago, ['completo', 'pagado', 'paid', 'complete'])) {
                    $pagosCompletados++;
                }
            }

            include __DIR__ . '/../Vista/reportes.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar reportes.';
            include __DIR__ . '/../Vista/reportes.php';
        }
    }
}
