<?php

// ============================================================================
// DashboardController.php - Controlador del dashboard
// ============================================================================

class DashboardController extends BaseController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new DashboardModel();
    }

    /**
     * Muestra el dashboard con todas las estadísticas
     */
    public function mostrar()
    {
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        // Obtener estadísticas del modelo
        $estadisticas = $this->modelo->obtenerEstadisticas();

        // Calcular totales
        $totales = $this->modelo->calcularTotales($estadisticas);

        // Incluir vista (las variables $estadisticas y $totales están disponibles)
        include_once __DIR__ . '/../Vista/dashboard.php';
    }
}