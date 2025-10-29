<?php
/**
 * index.php - PUNTO DE ENTRADA ÚNICO
 * 
 * Se encarga de:
 * 1. Inicializar sesiones
 * 2. Cargar dependencias básicas
 * 3. Crear instancia del router (disponible globalmente)
 * 4. Ejecutar el router
 * 5. Mostrar la plantilla HTML
 * 
 * @version 2.1
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
// CARGAR DEPENDENCIAS BÁSICAS
// ============================================================================
require_once __DIR__ . '/vendor/autoload.php';
// Conexión a base de datos
require_once __DIR__ . '/Modelo/Conexion.php';

// Router
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