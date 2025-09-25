<?php
// Controlador/ReporteController.php
require_once __DIR__ . '/../Modelo/ReporteModelo.php';

class ReporteController {
    private $modelo;

    public function __construct() {
        $this->modelo = new ReporteModelo();
    }

    /**
     * Carga datos y muestra la vista de reportes
     */
    public function index() {
        $reportes = $this->modelo->obtenerReportes();

        // Calcular totales y número de pagos completados (normalizando estados)
        $totalReportes = count($reportes);
        $totalValor = 0.0;
        $pagosCompletados = 0;

        foreach ($reportes as $r) {
            $valor = isset($r['valor']) ? floatval($r['valor']) : 0.0;
            $totalValor += $valor;

            $estadoPago = strtolower(trim($r['estado_pago'] ?? ''));
            // Consideramos varias variantes que representan "pagado"
            if (in_array($estadoPago, ['completo', 'pagado', 'paid', 'complete'])) {
                $pagosCompletados++;
            }
        }

        // Variables disponibles para la vista:
        // $reportes, $totalReportes, $totalValor, $pagosCompletados
        require __DIR__ . '/../Vista/reportes.php';
    }
}
