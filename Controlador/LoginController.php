<?php
// ============================================================================
// LoginController.php - REFACTORIZADO
// ============================================================================

class LoginController
{
    private $modelo;

    public function __construct()
    {
        require_once __DIR__ . '/../Modelo/ModeloUsuario.php';
        $this->modelo = new ModeloUsuario();
    }

    public function mostrarFormulario()
    {
        include_once __DIR__ . '/../Vista/login.php';
    }

    public function procesar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->mostrarFormulario();
            return;
        }

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        // Validar que vengan los datos
        if (empty($email) || empty($password)) {
            $_SESSION['error_login'] = 'Email y contraseña son requeridos.';
            $this->mostrarFormulario();
            return;
        }

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_login'] = 'El formato del correo electrónico es inválido.';
            $this->mostrarFormulario();
            return;
        }

        // Consultar usuario
        $usuario = $this->modelo->consultarUsuario($email);

        // Verificar credenciales
        if (!$usuario || $usuario['contraseña'] !== md5($password)) {
            $_SESSION['error_login'] = 'Email o contraseña incorrectos.';
            $this->mostrarFormulario();
            return;
        }

        // Login exitoso
        $_SESSION['logged'] = true;
        $_SESSION['user-id'] = $usuario['id'];
        $_SESSION['user-datos'] = $usuario['datos_completos'] ?? false;
        $_SESSION['user-rol'] = $usuario['rol'];
        $_SESSION['success'] = '¡Bienvenido!';

        header('Location: ?route=dashboard');
        exit();
    }

    public function salir()
    {
        session_destroy();
        header('Location: ?route=inicio');
        exit();
    }
}