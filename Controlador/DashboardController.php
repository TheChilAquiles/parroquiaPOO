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
        // 1. INICIALIZAMOS LAS VARIABLES POR DEFECTO PARA EVITAR ERRORES EN LA VISTA
        $estadisticas = [
            'usuarios' => ['total' => 0, 'roles' => 0, 'feligreses' => 0],
            'libros' => ['total' => 0, 'tipos' => 0, 'registros' => 0],
            'documentos' => ['tipos' => 0, 'total' => 0],
            'reportes' => ['total' => 0, 'categorias' => 0],
            'pagos' => ['total' => 0, 'completos' => 0, 'cancelados' => 0, 'pendientes' => 0],
            'certificados' => ['aprobados' => 0, 'pendientes' => 0, 'rechazados' => 0],
            'contactos' => ['total' => 0]
        ];
        
        $totales = [
            'usuarios_sistema' => 0,
            'recursos' => 0,
            'actividad' => 0
        ];

        try {
            // Verificar autenticación y perfil completo
            $this->requiereAutenticacion();

            Logger::info("Acceso a dashboard", [
                'user_id' => $_SESSION['user-id'] ?? 'unknown',
                'rol' => $_SESSION['user-rol'] ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            // 2. SOBREESCRIBIMOS CON LOS DATOS REALES SI TODO VA BIEN
            $estadisticas = $this->modelo->obtenerEstadisticas();
            $totales = $this->modelo->calcularTotales($estadisticas);

            Logger::info("Dashboard cargado exitosamente", [
                'user_id' => $_SESSION['user-id'] ?? 'unknown',
                'total_usuarios' => $totales['usuarios_sistema'] ?? 0,
                'total_recursos' => $totales['recursos'] ?? 0
            ]);

        } catch (Exception $e) {
            Logger::error("Error al cargar dashboard", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $_SESSION['user-id'] ?? 'unknown'
            ]);
            $_SESSION['error'] = 'Error al procesar los datos. Mostrando información por defecto.';
        }

        // 3. LA VISTA SE INCLUYE UNA SOLA VEZ AL FINAL
        include_once __DIR__ . '/../Vista/dashboard.php';
    }
}