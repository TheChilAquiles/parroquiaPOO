<?php
/**
 * Router.php
 * 
 * Clase simple para gestionar el enrutamiento de la aplicación.
 * Centraliza toda la lógica de mapeo de rutas a controladores y acciones.
 */

class Router
{
    private $route;
    private $controllers = [];

    public function __construct()
    {
        // Obtener la ruta del .htaccess
        $this->route = $_GET['route'] ?? 'inicio';
        
        // Inicializar mapa de rutas
        $this->initializeRoutes();
    }

    /**
     * Define el mapeo de rutas a controladores y acciones
     * Formato: 'ruta' => ['controlador' => 'Clase', 'accion' => 'metodo']
     */
    private function initializeRoutes()
    {
        $this->controllers = [
            'inicio' => ['controlador' => 'HomeController', 'accion' => 'index'],
            'login' => ['controlador' => 'LoginController', 'accion' => 'mostrarFormulario'],
            'login/procesar' => ['controlador' => 'LoginController', 'accion' => 'procesar'],
            'salir' => ['controlador' => 'LoginController', 'accion' => 'salir'],
            'registro' => ['controlador' => 'RegistroController', 'accion' => 'mostrarFormulario'],
            'registro/procesar' => ['controlador' => 'RegistroController', 'accion' => 'procesar'],
            
            'perfil' => ['controlador' => 'PerfilController', 'accion' => 'mostrar'],
            'perfil/buscar' => ['controlador' => 'PerfilController', 'accion' => 'buscar'],
            'perfil/actualizar' => ['controlador' => 'PerfilController', 'accion' => 'actualizar'],
            
            'dashboard' => ['controlador' => 'DashboardController', 'accion' => 'mostrar'],
            
            'libros' => ['controlador' => 'LibrosController', 'accion' => 'index'],
            'libros/seleccionar-tipo' => ['controlador' => 'LibrosController', 'accion' => 'seleccionarTipo'],
            'libros/crear' => ['controlador' => 'LibrosController', 'accion' => 'crear'],
            
            'sacramentos' => ['controlador' => 'SacramentosController', 'accion' => 'index'],
            'sacramentos/crear' => ['controlador' => 'SacramentosController', 'accion' => 'crear'],
            'sacramentos/buscar-usuario' => ['controlador' => 'SacramentosController', 'accion' => 'buscarUsuario'],
            
            'certificados' => ['controlador' => 'CertificadosController', 'accion' => 'mostrar'],
            'certificados/generar' => ['controlador' => 'CertificadosController', 'accion' => 'generar'],
            
            'noticias' => ['controlador' => 'NoticiasController', 'accion' => 'index'],
            'noticias/guardar' => ['controlador' => 'NoticiasController', 'accion' => 'guardar'],
            'noticias/eliminar' => ['controlador' => 'NoticiasController', 'accion' => 'eliminar'],
            
            'grupos' => ['controlador' => 'GruposController', 'accion' => 'index'],
            'grupos/ver' => ['controlador' => 'GruposController', 'accion' => 'ver'],
            'grupos/crear' => ['controlador' => 'GruposController', 'accion' => 'crear'],
            'grupos/editar' => ['controlador' => 'GruposController', 'accion' => 'editar'],
            'grupos/eliminar' => ['controlador' => 'GruposController', 'accion' => 'eliminar'],
            'grupos/agregar-miembro' => ['controlador' => 'GruposController', 'accion' => 'agregarMiembro'],
            'grupos/eliminar-miembro' => ['controlador' => 'GruposController', 'accion' => 'eliminarMiembro'],
            'grupos/actualizar-rol' => ['controlador' => 'GruposController', 'accion' => 'actualizarRol'],
            
            'pagos' => ['controlador' => 'PagosController', 'accion' => 'index'],
            'pagos/crear' => ['controlador' => 'PagosController', 'accion' => 'crear'],
            
            'reportes' => ['controlador' => 'ReportesController', 'accion' => 'index'],
            
            'contacto' => ['controlador' => 'ContactoController', 'accion' => 'mostrar'],
            'informacion' => ['controlador' => 'InformacionController', 'accion' => 'mostrar'],
        ];
    }

    /**
     * Ejecuta la ruta solicitada
     */
    public function dispatch()
    {
        $route = trim($this->route, '/');
        
        // Buscar si la ruta existe en el mapeo
        if (!isset($this->controllers[$route])) {
            $this->notFound();
            return;
        }

        $controllerData = $this->controllers[$route];
        $controllerClass = $controllerData['controlador'];
        $action = $controllerData['accion'];

        // Verificar autenticación si es necesario
        if ($this->requiresAuth($route)) {
            if (!$this->isAuthenticated()) {
                $_SESSION['redirect_after_login'] = $route;
                header('Location: ?route=login');
                exit();
            }
        }

        // Cargar y ejecutar el controlador
        $this->loadAndExecute($controllerClass, $action);
    }

    /**
     * Carga el archivo del controlador y ejecuta la acción
     */
    private function loadAndExecute($controllerClass, $action)
    {
        $controllerFile = __DIR__ . '/Controlador/' . $controllerClass . '.php';

        if (!file_exists($controllerFile)) {
            die("Error: Controlador no encontrado - $controllerClass en " . $controllerFile);
        }

        require_once $controllerFile;

        if (!class_exists($controllerClass)) {
            die("Error: Clase $controllerClass no encontrada en $controllerFile");
        }

        try {
            $controller = new $controllerClass();

            if (!method_exists($controller, $action)) {
                die("Error: Acción $action no existe en $controllerClass");
            }

            $controller->$action();
        } catch (Exception $e) {
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
            'login', 
            'login/procesar', 
            'registro', 
            'registro/procesar', 
            'contacto', 
            'informacion'
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
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Página no encontrada</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-[#F8EDE3]">
            <div class="min-h-screen flex items-center justify-center">
                <div class="text-center">
                    <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
                    <p class="text-2xl text-gray-700 mb-6">Página no encontrada</p>
                    <p class="text-gray-600 mb-8">La ruta "<strong><?php echo htmlspecialchars($_GET['route'] ?? 'desconocida'); ?></strong>" no existe</p>
                    <a href="?route=inicio" class="px-6 py-3 bg-[#DFD3C3] text-gray-900 font-semibold rounded hover:bg-[#D0C3B3] transition-colors">
                        Volver al inicio
                    </a>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}