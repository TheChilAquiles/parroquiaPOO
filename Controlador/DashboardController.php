<?php

// ============================================================================
// DashboardController.php - Controlador del dashboard
// ============================================================================

class DashboardController extends BaseController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloDashboard();
    }

    /**
     * Muestra el dashboard con todas las estadísticas
     */
    public function mostrar()
    {
        try {
            // Verificar autenticación y perfil completo
            $this->requiereAutenticacion();

            Logger::info("Acceso a dashboard", [
                'user_id' => $_SESSION['user-id'],
                'rol' => $_SESSION['user-rol'] ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            // Obtener estadísticas del modelo
            $estadisticas = $this->modelo->obtenerEstadisticas();

            // Calcular totales
            $totales = $this->modelo->calcularTotales($estadisticas);

            Logger::info("Dashboard cargado exitosamente", [
                'user_id' => $_SESSION['user-id'],
                'total_usuarios' => $totales['usuarios_sistema'] ?? 0,
                'total_recursos' => $totales['recursos'] ?? 0
            ]);

            // Incluir vista (las variables $estadisticas y $totales están disponibles)
            include_once __DIR__ . '/../Vista/dashboard.php';

        } catch (Exception $e) {
            Logger::error("Error al cargar dashboard", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $_SESSION['user-id'] ?? 'unknown'
            ]);
            $_SESSION['error'] = 'Error al cargar el dashboard. Por favor, intenta de nuevo.';
            include_once __DIR__ . '/../Vista/dashboard.php';
        }
    }
}