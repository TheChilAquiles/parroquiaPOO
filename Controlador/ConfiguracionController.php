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
     * Muestra el panel principal de administración
     */
    public function index()
    {
        // Verificar que sea administrador
        $this->requiereAdmin();

        // Obtener estadísticas básicas
        $stats = $this->obtenerEstadisticas();

        include __DIR__ . '/../Vista/admin/dashboard.php';
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

    /**
     * Gestiona los precios de certificados
     */
    public function precios()
    {
        // Verificar que sea administrador
        $this->requiereAdmin();

        // Procesar actualización si viene por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarActualizacionPrecios();
            return;
        }

        // Obtener configuraciones de precios
        $precios = $this->modeloConfiguracion->obtenerPorCategoria('certificados');

        include __DIR__ . '/../Vista/admin/precios.php';
    }

    /**
     * Procesa la actualización de precios
     */
    private function procesarActualizacionPrecios()
    {
        try {
            $precios = $_POST['precio'] ?? [];

            if (empty($precios)) {
                $_SESSION['error'] = 'No se recibieron precios para actualizar.';
                header('Location: ?route=admin/precios');
                exit;
            }

            // Preparar datos de configuración
            $configuraciones = [];
            foreach ($precios as $clave => $valor) {
                // Validar que sea numérico
                if (!is_numeric($valor) || $valor < 0) {
                    $_SESSION['error'] = "El precio para {$clave} debe ser un número válido.";
                    header('Location: ?route=admin/precios');
                    exit;
                }
                $configuraciones['cert_precio_' . $clave] = $valor;
            }

            // Actualizar
            $usuarioId = $_SESSION['user-id'] ?? null;
            $resultado = $this->modeloConfiguracion->actualizarMultiples($configuraciones, $usuarioId);

            if ($resultado['exito']) {
                $_SESSION['success'] = 'Precios actualizados correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar precios.';
            }

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            Logger::error("Error al actualizar precios", ['error' => $e->getMessage()]);
        }

        header('Location: ?route=admin/precios');
        exit;
    }

    /**
     * Configuración de la pasarela de pagos
     */
    public function pasarelaPagos()
    {
        // Verificar que sea administrador
        $this->requiereAdmin();

        // Procesar actualización si viene por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarActualizacionPasarela();
            return;
        }

        // Obtener configuraciones de pagos
        $configPagos = $this->modeloConfiguracion->obtenerPorCategoria('pagos');
        $proveedores = PaymentGatewayFactory::getAvailableProviders();

        include __DIR__ . '/../Vista/admin/pasarela-pagos.php';
    }

    /**
     * Procesa la actualización de la pasarela de pagos
     */
    private function procesarActualizacionPasarela()
    {
        try {
            $configuraciones = [
                'pago_gateway' => $_POST['pago_gateway'] ?? 'mock',
                'pago_modo' => $_POST['pago_modo'] ?? 'sandbox',
                'pago_moneda' => $_POST['pago_moneda'] ?? 'COP'
            ];

            $usuarioId = $_SESSION['user-id'] ?? null;
            $resultado = $this->modeloConfiguracion->actualizarMultiples($configuraciones, $usuarioId);

            if ($resultado['exito']) {
                // También actualizar variables de entorno si es posible
                $this->actualizarEnv([
                    'PAYMENT_GATEWAY_PROVIDER' => $configuraciones['pago_gateway'],
                    'PAYMENT_GATEWAY_MODE' => $configuraciones['pago_modo'],
                    'PAYMENT_DEFAULT_CURRENCY' => $configuraciones['pago_moneda']
                ]);

                $_SESSION['success'] = 'Configuración de pasarela actualizada. Recuerde actualizar las credenciales en el archivo .env';
            } else {
                $_SESSION['error'] = 'Error al actualizar configuración.';
            }

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            Logger::error("Error al actualizar pasarela", ['error' => $e->getMessage()]);
        }

        header('Location: ?route=admin/pasarela-pagos');
        exit;
    }

    /**
     * Actualiza variables en el archivo .env
     */
    private function actualizarEnv($variables)
    {
        $envPath = __DIR__ . '/../.env';

        if (!file_exists($envPath)) {
            Logger::warning("Archivo .env no encontrado");
            return false;
        }

        try {
            $envContent = file_get_contents($envPath);

            foreach ($variables as $key => $value) {
                // Buscar y reemplazar la variable
                $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';
                $replacement = $key . '=' . $value;

                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, $replacement, $envContent);
                } else {
                    // Si no existe, agregar al final
                    $envContent .= "\n" . $replacement;
                }
            }

            file_put_contents($envPath, $envContent);
            return true;

        } catch (Exception $e) {
            Logger::error("Error al actualizar .env", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Obtiene estadísticas básicas del sistema
     */
    private function obtenerEstadisticas()
    {
        try {
            $conexion = Conexion::conectar();

            // Total de feligreses
            $stmt = $conexion->query("SELECT COUNT(*) FROM feligreses WHERE estado_registro IS NULL");
            $totalFeligreses = $stmt->fetchColumn();

            // Total de usuarios
            $stmt = $conexion->query("SELECT COUNT(*) FROM usuarios");
            $totalUsuarios = $stmt->fetchColumn();

            // Total de certificados
            $stmt = $conexion->query("SELECT COUNT(*) FROM certificados");
            $totalCertificados = $stmt->fetchColumn();

            // Total de pagos
            $stmt = $conexion->query("SELECT COUNT(*) FROM pagos WHERE estado = 'pagado'");
            $totalPagos = $stmt->fetchColumn();

            // Ingresos totales
            $stmt = $conexion->query("SELECT SUM(valor) FROM pagos WHERE estado = 'pagado'");
            $ingresosTotales = $stmt->fetchColumn() ?: 0;

            return [
                'total_feligreses' => $totalFeligreses,
                'total_usuarios' => $totalUsuarios,
                'total_certificados' => $totalCertificados,
                'total_pagos' => $totalPagos,
                'ingresos_totales' => $ingresosTotales
            ];

        } catch (PDOException $e) {
            Logger::error("Error al obtener estadísticas", ['error' => $e->getMessage()]);
            return [
                'total_feligreses' => 0,
                'total_usuarios' => 0,
                'total_certificados' => 0,
                'total_pagos' => 0,
                'ingresos_totales' => 0
            ];
        }
    }
}
