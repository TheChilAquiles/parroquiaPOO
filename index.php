<?php
/**
 * index.php - PUNTO DE ENTRADA ÚNICO
 * * Se encarga de:
 * 1. Inicializar sesiones
 * 2. Cargar dependencias básicas
 * 3. Crear instancia del router (disponible globalmente)
 * 4. Ejecutar el router
 * 5. Mostrar la plantilla HTML
 * * @version 2.2 (Corregido)
 */

// ============================================================================
// CONFIGURACIÓN Y INICIALIZACIÓN
// ============================================================================

// Iniciar sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Errores en desarrollo (comentar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Buffer de salida para control limpio
ob_start();

// ============================================================================
// CARGAR DEPENDENCIAS BÁSICAS (ORDEN CORREGIDO)
// ============================================================================

// 1. Cargar autoloader de Composer
require_once __DIR__ . '/vendor/autoload.php';

// 2. [NUEVO] Cargar las variables de entorno del archivo .env
// Esto es gracias a la librería que instalaste (vlucas/phpdotenv)
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    die('Error: No se pudo encontrar el archivo .env. Asegúrate de que esté en la raíz.');
}

// 3. [NUEVO] Cargar tu archivo de configuración
// Ahora que .env está cargado, config.php puede usar getenv()
// para definir las constantes (SMTP_HOST, DB_HOST, etc.)
require_once __DIR__ . '/config.php';

// 4. Cargar el resto de tus archivos
// Ahora la conexión SÍ tiene las constantes DB_HOST, DB_NAME, etc.
require_once __DIR__ . '/Modelo/Conexion.php';

// Tu autoloader y el Router
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/Router.php';

// ============================================================================
// INICIALIZAR ROUTER Y HACERLO DISPONIBLE GLOBALMENTE
// ============================================================================

// Crear instancia del router
$router = new Router();

// Hacer el router disponible como variable global para los controladores
$GLOBALS['router'] = $router;

// ============================================================================
// INICIALIZAR PLANTILLA Y EJECUTAR
// ============================================================================

// Mostrar cabecera HTML
include_once __DIR__ . '/Vista/componentes/plantillaTop.php';

// Ejecutar el router
$router->dispatch();

// Mostrar pie de página HTML
include_once __DIR__ . '/Vista/componentes/plantillaBottom.php';

// Enviar buffer de salida
ob_end_flush();
?>