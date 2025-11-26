<?php
/**
 * Router.php - v2.1
 * 
 * Gestiona el enrutamiento de la aplicación con soporte para:
 * - URLs amigables RESTful (/ruta/accion/id)
 * - Parámetros GET (?id=5, ?action=delete)
 * - Seguridad y autenticación
 * 
 * @version 2.1
 * @updated 2024
 */

class Router
{
    private $route;
    private $controllers = [];
    private $params = [];

    public function __construct()
    {
        // Obtener la ruta del .htaccess
        $this->route = $_GET['route'] ?? 'inicio';

        // Capturar parámetros GET adicionales
        $this->captureParams();

        // Inicializar mapa de rutas
        $this->initializeRoutes();
    }

    /**
     * Captura parámetros GET (especialmente 'id')
     */
    private function captureParams()
    {
        // Capturar ID si viene en la URL
        if (isset($_GET['id'])) {
            $this->params['id'] = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        }

        // Capturar otros parámetros comunes
        $commonParams = ['search', 'page', 'sort', 'order', 'filter', 'action'];
        foreach ($commonParams as $param) {
            if (isset($_GET[$param])) {
                $this->params[$param] = htmlspecialchars($_GET[$param], ENT_QUOTES, 'UTF-8');
            }
        }
    }

    /**
     * Obtiene un parámetro capturado
     * @param string $key Nombre del parámetro
     * @param mixed $default Valor por defecto
     * @return mixed El valor del parámetro o default
     */
    public function getParam($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Define el mapeo de rutas a controladores y acciones
     * Formato: 'ruta' => ['controlador' => 'Clase', 'accion' => 'metodo']
     */
    private function initializeRoutes()
    {
        $this->controllers = [
            // ================================================================
            // RUTAS PÚBLICAS
            // ================================================================
            'inicio' => ['controlador' => 'HomeController', 'accion' => 'index'],
            'noticias' => ['controlador' => 'NoticiasController', 'accion' => 'index'],
            'manual' => ['controlador' => 'ManualController', 'accion' => 'index'],
            'login' => ['controlador' => 'LoginController', 'accion' => 'mostrarFormulario'],
            'login/procesar' => ['controlador' => 'LoginController', 'accion' => 'procesar'],
            'salir' => ['controlador' => 'LoginController', 'accion' => 'salir'],
            'olvido' => ['controlador' => 'LoginController', 'accion' => 'mostrarFormularioOlvido'],
            'olvido/procesar' => ['controlador' => 'LoginController', 'accion' => 'procesarSolicitudOlvido'],
            'resetear' => ['controlador' => 'LoginController', 'accion' => 'mostrarFormularioReseteo'],
            'resetear/procesar' => ['controlador' => 'LoginController', 'accion' => 'procesarReseteo'],
            'registro' => ['controlador' => 'RegistroController', 'accion' => 'mostrarFormulario'],
            'registro/procesar' => ['controlador' => 'RegistroController', 'accion' => 'procesar'],
            'contacto' => ['controlador' => 'ContactoController', 'accion' => 'mostrar'],
            'informacion' => ['controlador' => 'InformacionController', 'accion' => 'mostrar'],

            // ================================================================
            // RUTAS AUTENTICADAS - PERFIL
            // ================================================================
            'perfil' => ['controlador' => 'PerfilController', 'accion' => 'mostrar'],
            'perfil/buscar' => ['controlador' => 'PerfilController', 'accion' => 'buscar'],
            'perfil/actualizar' => ['controlador' => 'PerfilController', 'accion' => 'actualizar'],

            // ================================================================
            // RUTAS AUTENTICADAS - DASHBOARD
            // ================================================================
            'dashboard' => ['controlador' => 'DashboardController', 'accion' => 'mostrar'],

            // ================================================================
            // RUTAS AUTENTICADAS - LIBROS
            // ================================================================
            'libros' => ['controlador' => 'LibrosController', 'accion' => 'index'],
            'libros/seleccionar-tipo' => ['controlador' => 'LibrosController', 'accion' => 'seleccionarTipo'],
            'libros/crear' => ['controlador' => 'LibrosController', 'accion' => 'crear'],

            // ================================================================
            // RUTAS AUTENTICADAS - SACRAMENTOS
            // ================================================================
            'sacramentos' => ['controlador' => 'SacramentosController', 'accion' => 'index'],
            'sacramentos/libro' => ['controlador' => 'SacramentosController', 'accion' => 'verLibro'],
            'sacramentos/listar' => ['controlador' => 'SacramentosController', 'accion' => 'listar'],
            'sacramentos/crear' => ['controlador' => 'SacramentosController', 'accion' => 'crear'],
            'sacramentos/obtener' => ['controlador' => 'SacramentosController', 'accion' => 'obtener'],
            'sacramentos/buscar-usuario' => ['controlador' => 'SacramentosController', 'accion' => 'buscarUsuario'],
            'sacramentos/participantes' => ['controlador' => 'SacramentosController', 'accion' => 'getParticipantes'],

            // ================================================================
            // RUTAS AUTENTICADAS - CERTIFICADOS
            // ================================================================
            'certificados' => ['controlador' => 'CertificadosController', 'accion' => 'mostrar'],
            'certificados/verificar' => ['controlador' => 'CertificadosController', 'accion' => 'verificar'],
            'certificados/generar' => ['controlador' => 'CertificadosController', 'accion' => 'generar'],
            'certificados/generar-simplificado' => ['controlador' => 'CertificadosController', 'accion' => 'generarSimplificado'],
            'certificados/listar-todos' => ['controlador' => 'CertificadosController', 'accion' => 'listarTodos'],
            'certificados/solicitar-desde-sacramento' => ['controlador' => 'CertificadosController', 'accion' => 'solicitarDesdeSacramento'],
            'certificados/obtener-familiares' => ['controlador' => 'CertificadosController', 'accion' => 'obtenerFamiliares'],

            // Solicitudes de certificados (Feligrés)
            'certificados/solicitar' => ['controlador' => 'SolicitudesCertificadosController', 'accion' => 'mostrarFormulario'],
            'certificados/crear' => ['controlador' => 'SolicitudesCertificadosController', 'accion' => 'crear'],
            'certificados/buscar-sacramentos-familiar' => ['controlador' => 'SolicitudesCertificadosController', 'accion' => 'buscarSacramentosFamiliar'],
            'certificados/mis-solicitudes' => ['controlador' => 'SolicitudesCertificadosController', 'accion' => 'misSolicitudes'],
            'certificados/descargar' => ['controlador' => 'SolicitudesCertificadosController', 'accion' => 'descargar'],

            // ================================================================
            // RUTAS AUTENTICADAS - FELIGRESES
            // ================================================================
            'feligreses' => ['controlador' => 'FeligresController', 'accion' => 'index'],
            'feligreses/listar' => ['controlador' => 'FeligresController', 'accion' => 'listar'],
            'feligreses/crear' => ['controlador' => 'FeligresController', 'accion' => 'crear'],
            'feligreses/editar' => ['controlador' => 'FeligresController', 'accion' => 'editar'],
            'feligreses/eliminar' => ['controlador' => 'FeligresController', 'accion' => 'eliminar'],

            // ================================================================
            // RUTAS AUTENTICADAS - NOTICIAS (ACTUALIZADO)
            // ================================================================

            'noticias/crear' => ['controlador' => 'NoticiasController', 'accion' => 'crear'],
            'noticias/actualizar' => ['controlador' => 'NoticiasController', 'accion' => 'actualizar'],
            'noticias/eliminar' => ['controlador' => 'NoticiasController', 'accion' => 'eliminar'],

            // ================================================================
            // RUTAS AUTENTICADAS - GRUPOS
            // ================================================================
            'grupos' => ['controlador' => 'GruposController', 'accion' => 'index'],
            'grupos/ver' => ['controlador' => 'GruposController', 'accion' => 'ver'],
            'grupos/crear' => ['controlador' => 'GruposController', 'accion' => 'crear'],
            'grupos/editar' => ['controlador' => 'GruposController', 'accion' => 'editar'],
            'grupos/eliminar' => ['controlador' => 'GruposController', 'accion' => 'eliminar'],
            'grupos/agregar-miembro' => ['controlador' => 'GruposController', 'accion' => 'agregarMiembro'],
            'grupos/eliminar-miembro' => ['controlador' => 'GruposController', 'accion' => 'eliminarMiembro'],
            'grupos/actualizar-rol' => ['controlador' => 'GruposController', 'accion' => 'actualizarRol'],
            'grupos/crear-rol' => ['controlador' => 'GruposController', 'accion' => 'crearRol'],

            // ================================================================
            // RUTAS AUTENTICADAS - PAGOS
            // ================================================================
            'pagos' => ['controlador' => 'PagosController', 'accion' => 'index'],
            'pagos/crear' => ['controlador' => 'PagosController', 'accion' => 'crear'],
            'pagos/actualizar' => ['controlador' => 'PagosController', 'accion' => 'actualizar'],
            'pagos/eliminar' => ['controlador' => 'PagosController', 'accion' => 'eliminar'],

            // Pagos de certificados
            'pagos/mis-pagos' => ['controlador' => 'PagosController', 'accion' => 'misPagos'],
            'pagos/pagar-certificado' => ['controlador' => 'PagosController', 'accion' => 'pagarCertificado'],
            'pagos/procesar-pago-online' => ['controlador' => 'PagosController', 'accion' => 'procesarPagoOnline'],
            'pagos/registrar-pago-efectivo' => ['controlador' => 'PagosController', 'accion' => 'registrarPagoEfectivo'],

            // Webhook para confirmación de pagos externos (PÚBLICA)
            'pagos/webhook-confirmacion' => ['controlador' => 'PagosController', 'accion' => 'webhookConfirmacion'],
            'pagos/respuesta' => ['controlador' => 'PagosController', 'accion' => 'respuestaPaymentsWay'], // Callback PaymentsWay

            // ================================================================
            // RUTAS AUTENTICADAS - REPORTES
            // ================================================================
            'reportes' => ['controlador' => 'ReportesController', 'accion' => 'index'],
            'reportes/crear' => ['controlador' => 'ReportesController', 'accion' => 'crear'],
            'reportes/guardar' => ['controlador' => 'ReportesController', 'accion' => 'guardar'],
            'reportes/editar' => ['controlador' => 'ReportesController', 'accion' => 'editar'],
            'reportes/actualizar' => ['controlador' => 'ReportesController', 'accion' => 'actualizar'],
            'reportes/eliminar' => ['controlador' => 'ReportesController', 'accion' => 'eliminar'],
            'reportes/exportarPDF' => ['controlador' => 'ReportesController', 'accion' => 'exportarPDF'],
            'reportes/filtrar' => ['controlador' => 'ReportesController', 'accion' => 'filtrar'],

            // ================================================================
            // RUTAS DE ADMINISTRACIÓN (Solo Administradores)
            // ================================================================
            'admin/configuraciones' => ['controlador' => 'ConfiguracionController', 'accion' => 'configuraciones'],
        ];
    }

    /**
     * Ejecuta la ruta solicitada
     */
    public function dispatch()
    {
        try {
            $route = trim($this->route, '/');

            Logger::info("Ruta solicitada", [
                'route' => $route,
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 100),
                'logged' => isset($_SESSION['logged']) ? 'yes' : 'no',
                'user_id' => $_SESSION['user-id'] ?? 'guest'
            ]);

            // Buscar si la ruta existe en el mapeo
            if (!isset($this->controllers[$route])) {
                Logger::warning("Ruta no encontrada - 404", [
                    'route' => $route,
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $this->notFound();
                return;
            }

            $controllerData = $this->controllers[$route];
            $controllerClass = $controllerData['controlador'];
            $action = $controllerData['accion'];

            // Verificar autenticación si es necesario
            if ($this->requiresAuth($route)) {
                if (!$this->isAuthenticated()) {
                    Logger::info("Acceso denegado - autenticación requerida", [
                        'route' => $route,
                        'redirect' => 'login',
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                    ]);
                    $_SESSION['redirect_after_login'] = $route;
                    redirect('login');
                    
                    exit();
                }
            }

            Logger::info("Ejecutando controlador", [
                'route' => $route,
                'controller' => $controllerClass,
                'action' => $action,
                'user_id' => $_SESSION['user-id'] ?? 'guest'
            ]);

            // Cargar y ejecutar el controlador
            $this->loadAndExecute($controllerClass, $action);

        } catch (Exception $e) {
            Logger::error("Error crítico en Router::dispatch", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'route' => $this->route ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            http_response_code(500);
            die("Error crítico en la aplicación. Por favor, contacta al administrador.");
        }
    }

    /**
     * Carga el archivo del controlador y ejecuta la acción
     */
    private function loadAndExecute($controllerClass, $action)
    {
        try {
            $controllerFile = __DIR__ . '/Controlador/' . $controllerClass . '.php';

            if (!file_exists($controllerFile)) {
                Logger::error("Archivo de controlador no encontrado", [
                    'controller' => $controllerClass,
                    'path' => $controllerFile,
                    'route' => $this->route
                ]);
                die("Error: Controlador no encontrado - $controllerClass en " . $controllerFile);
            }

            require_once $controllerFile;

            if (!class_exists($controllerClass)) {
                Logger::error("Clase de controlador no encontrada después de require", [
                    'controller' => $controllerClass,
                    'file' => $controllerFile,
                    'route' => $this->route
                ]);
                die("Error: Clase $controllerClass no encontrada en $controllerFile");
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $action)) {
                Logger::error("Método de acción no encontrado en controlador", [
                    'controller' => $controllerClass,
                    'action' => $action,
                    'route' => $this->route
                ]);
                die("Error: Acción $action no existe en $controllerClass");
            }

            $controller->$action();

            Logger::info("Controlador ejecutado exitosamente", [
                'controller' => $controllerClass,
                'action' => $action,
                'route' => $this->route,
                'user_id' => $_SESSION['user-id'] ?? 'guest'
            ]);

        } catch (Exception $e) {
            Logger::error("Excepción durante ejecución de controlador", [
                'controller' => $controllerClass,
                'action' => $action,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'route' => $this->route,
                'user_id' => $_SESSION['user-id'] ?? 'guest'
            ]);
            die("Error al ejecutar $controllerClass->$action(): " . $e->getMessage());
        }
    }

    /**
     * Determina si una ruta requiere autenticación
     */
    private function requiresAuth($route)
    {
        $publicRoutes = [
            'inicio',
            'noticias',
            'manual',
            'login',
            'login/procesar',
            'registro',
            'registro/procesar',
            'contacto',
            'informacion',
            'olvido',
            'olvido/procesar',
            'resetear',
            'resetear/procesar',
            'certificados/verificar', // Verificación pública de certificados por QR
            'pagos/webhook-confirmacion' // Webhook para pasarela de pago externa
        ];
        return !in_array($route, $publicRoutes);
    }

    /**
     * Verifica si el usuario está autenticado
     */
    private function isAuthenticated()
    {
        return isset($_SESSION['logged']) && $_SESSION['logged'] === true;
    }

    /**
     * Maneja rutas no encontradas
     */
    private function notFound()
    {
        http_response_code(404);
        include_once __DIR__ . '/Vista/404.php';
        exit();
    }
}
?>