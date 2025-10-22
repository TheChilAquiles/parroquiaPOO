<?php
/**
 * index.php
 * 
 * Punto de entrada único de la aplicación.
 * Se encarga únicamente de:
 * 1. Inicializar sesiones
 * 2. Cargar dependencias básicas
 * 3. Ejecutar el router
 * 4. Mostrar la plantilla HTML
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

// Conexión a base de datos
require_once __DIR__ . '/Modelo/Conexion.php';

// Router
require_once __DIR__ . '/Router.php';

// ============================================================================
// INICIALIZAR PLANTILLA Y ROUTER
// ============================================================================

// Mostrar cabecera HTML
include_once __DIR__ . '/Vista/componentes/plantillaTop.php';

// Crear instancia del router y ejecutar
$router = new Router();
$router->dispatch();

// Mostrar pie de página HTML
include_once __DIR__ . '/Vista/componentes/plantillaBottom.php';

// Enviar buffer de salida
ob_end_flush();
?>
