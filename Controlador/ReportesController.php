<?php

// ============================================================================
// ReportesController.php - REFACTORIZADO CON SERVICIO
// ============================================================================

require_once __DIR__ . '/../Servicios/ReporteService.php';

class ReportesController extends BaseController
{
    private $service;

    public function __construct()
    {
        $this->service = new ReporteService();
    }

    /**
     * Muestra el dashboard de reportes
     */
    public function index()
    {
        $this->requiereAutenticacion();

        try {
            $stats = $this->service->obtenerEstadisticasDashboard();
            include __DIR__ . '/../Vista/reportes.php';
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::index:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al cargar el dashboard.';
            $stats = []; // Evitar error en vista
            include __DIR__ . '/../Vista/reportes.php';
        }
    }

    /**
     * Muestra reporte detallado de sacramentos
     */
    public function sacramentos()
    {
        $this->requiereAutenticacion();

        $fechaInicio = $_GET['inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fin'] ?? date('Y-m-t');

        try {
            $datos = $this->service->obtenerReporteSacramentos($fechaInicio, $fechaFin);
            include __DIR__ . '/../Vista/reportes_sacramentos.php';
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::sacramentos:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al cargar reporte de sacramentos.';
            header('Location: index.php?route=reportes');
        }
    }

    /**
     * Muestra reporte financiero detallado
     */
    public function finanzas()
    {
        $this->requiereAutenticacion();

        $fechaInicio = $_GET['inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fin'] ?? date('Y-m-t');

        try {
            $datos = $this->service->obtenerReporteFinanciero($fechaInicio, $fechaFin);
            include __DIR__ . '/../Vista/reportes_finanzas.php';
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::finanzas:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al cargar reporte financiero.';
            header('Location: index.php?route=reportes');
        }
    }
}
