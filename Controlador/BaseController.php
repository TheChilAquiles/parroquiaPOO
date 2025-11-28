<?php

// ============================================================================
// BaseController.php - Controlador base con métodos de autenticación
// ============================================================================

/**
 * Clase base para todos los controladores
 * Proporciona métodos comunes de autenticación y autorización
 */
abstract class BaseController
{
    /**
     * Verifica que el usuario tenga rol de Administrador o Secretario
     * Redirige al dashboard si no tiene permisos
     */
    protected function requiereAdmin()
    {
        // Primero verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        if (!isset($_SESSION['user-rol']) ||
            !in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            
            Logger::warning("Acceso denegado a ruta protegida (requiereAdmin)", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'rol_actual' => $_SESSION['user-rol'] ?? 'none',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            $_SESSION['error'] = 'No tienes permisos para esta acción.';
            redirect('dashboard');
            
            exit();
        }
    }

    /**
     * Verifica que el usuario esté autenticado
     * Redirige al login si no está autenticado
     * Redirige al perfil si no ha completado sus datos personales
     *
     * @param bool $permitirPerfilIncompleto Si es true, no redirige aunque falten datos
     */
    protected function requiereAutenticacion($permitirPerfilIncompleto = false)
    {
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
            Logger::info("Redirección a login por falta de sesión", [
                'route_attempted' => $_GET['route'] ?? 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            
            $_SESSION['error'] = 'Debes iniciar sesión.';
            redirect('login');
            
            exit();
        }

        // Verificar si el usuario ha completado sus datos personales
        // Solo si no estamos ya en las páginas permitidas
        if (!$permitirPerfilIncompleto) {
            $rutaActual = $_GET['route'] ?? 'inicio';

            // Rutas que pueden accederse sin tener el perfil completo
            $rutasPermitidas = [
                'perfil',
                'perfil/buscar',
                'perfil/actualizar',
                'salir'
            ];

            if (!in_array($rutaActual, $rutasPermitidas)) {
                if (!isset($_SESSION['user-datos']) || $_SESSION['user-datos'] === false) {
                    $_SESSION['info'] = 'Por favor, completa tus datos personales para continuar.';
                    redirect('perfil');
                    
                    exit();
                }
            }
        }
    }

    /**
     * Verifica que el usuario tenga un rol específico
     * @param string|array $roles Rol o array de roles permitidos
     */
    protected function requiereRol($roles)
    {
        $this->requiereAutenticacion();

        $rolesPermitidos = is_array($roles) ? $roles : [$roles];
        $rolUsuario = $_SESSION['user-rol'] ?? '';

        if (!in_array($rolUsuario, $rolesPermitidos)) {
            Logger::warning("Acceso denegado por rol insuficiente", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'rol_actual' => $rolUsuario,
                'roles_requeridos' => $rolesPermitidos,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección.';
            redirect('dashboard');
            
            exit();
        }
    }
}
