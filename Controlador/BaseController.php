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
        if (!isset($_SESSION['user-rol']) ||
            !in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            $_SESSION['error'] = 'No tienes permisos para esta acción.';
            header('Location: ?route=dashboard');
            exit();
        }
    }

    /**
     * Verifica que el usuario esté autenticado
     * Redirige al login si no está autenticado
     */
    protected function requiereAutenticacion()
    {
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
            $_SESSION['error'] = 'Debes iniciar sesión.';
            header('Location: ?route=login');
            exit();
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
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección.';
            header('Location: ?route=dashboard');
            exit();
        }
    }
}
