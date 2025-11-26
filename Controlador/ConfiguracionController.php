<?php

/**
 * ConfiguracionController
 * Controlador para el panel de administración del sistema
 */
class ConfiguracionController extends BaseController
{
    private $modeloConfiguracion;

    public function __construct()
    {
        $this->modeloConfiguracion = new ModeloConfiguracion();
    }

    /**
     * Muestra y gestiona las configuraciones del sistema
     */
    public function configuraciones()
    {
        // Verificar que sea administrador
        $this->requiereAdmin();

        // Procesar actualización si viene por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarActualizacionConfiguraciones();
            return;
        }

        // Obtener todas las configuraciones agrupadas por categoría
        $configuraciones = $this->modeloConfiguracion->obtenerTodas();
        $categorias = $this->modeloConfiguracion->obtenerCategorias();

        // Obtener proveedores de pago disponibles
        $proveedoresPago = PaymentGatewayFactory::getAvailableProviders();

        include __DIR__ . '/../Vista/admin/configuraciones.php';
    }

    /**
     * Procesa la actualización de configuraciones
     */
    private function procesarActualizacionConfiguraciones()
    {
        try {
            // Obtener datos del formulario
            $configuraciones = $_POST['config'] ?? [];

            if (empty($configuraciones)) {
                $_SESSION['error'] = 'No se recibieron configuraciones para actualizar.';
                header('Location: ?route=admin/configuraciones');
                exit;
            }

            // Actualizar configuraciones
            $usuarioId = $_SESSION['user-id'] ?? null;
            $resultado = $this->modeloConfiguracion->actualizarMultiples($configuraciones, $usuarioId);

            if ($resultado['exito']) {
                $_SESSION['success'] = $resultado['mensaje'];
                Logger::info("Configuraciones actualizadas", [
                    'usuario_id' => $usuarioId,
                    'cantidad' => $resultado['actualizadas']
                ]);
            } else {
                $_SESSION['error'] = $resultado['mensaje'];
            }

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar configuraciones: ' . $e->getMessage();
            Logger::error("Error al actualizar configuraciones", [
                'error' => $e->getMessage()
            ]);
        }

        header('Location: ?route=admin/configuraciones');
        exit;
    }
}
