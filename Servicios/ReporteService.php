<?php
// Servicios/ReporteService.php

require_once __DIR__ . '/../Modelo/ReporteModelo.php';

class ReporteService
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ReporteModelo();
    }

    /**
     * Obtiene estadísticas generales para el dashboard
     * @return array
     */
    public function obtenerEstadisticasDashboard()
    {
        $anioActual = date('Y');
        $mesActual = date('m');
        $fechaInicioMes = date('Y-m-01');
        $fechaFinMes = date('Y-m-t');

        return [
            'sacramentos_total' => $this->modelo->contarSacramentosTotal(),
            'feligreses_total' => $this->modelo->contarFeligresesTotal(),
            'ingresos_mes' => $this->modelo->sumarIngresos($fechaInicioMes, $fechaFinMes),
            'certificados_mes' => $this->modelo->contarCertificados($fechaInicioMes, $fechaFinMes),
            'desglose_sacramentos' => $this->modelo->obtenerDesgloseSacramentos($anioActual),
            'ingresos_por_concepto' => $this->modelo->obtenerIngresosPorConcepto($anioActual)
        ];
    }

    /**
     * Obtiene datos para el reporte de sacramentos
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return array
     */
    public function obtenerReporteSacramentos($fechaInicio, $fechaFin)
    {
        return $this->modelo->obtenerReporteSacramentos($fechaInicio, $fechaFin);
    }

    /**
     * Obtiene datos para el reporte financiero
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return array
     */
    public function obtenerReporteFinanciero($fechaInicio, $fechaFin)
    {
        return $this->modelo->obtenerReporteFinanciero($fechaInicio, $fechaFin);
    }
}
