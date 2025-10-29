
<?php
trait PermisosHelper
{
    protected function requiereAdmin()
    {
        if (!isset($_SESSION['user-rol']) || 
            !in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            $_SESSION['error'] = 'No tienes permisos para esta acción.';
            header('Location: ?route=dashboard');
            exit();
        }
    }
    
    protected function requiereAutenticacion()
    {
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
            $_SESSION['error'] = 'Debes iniciar sesión.';
            header('Location: ?route=login');
            exit();
        }
    }
}