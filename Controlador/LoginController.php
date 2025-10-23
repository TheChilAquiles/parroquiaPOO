<?php
// ============================================================================
// LoginController.php
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

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email y contraseña son requeridos.';
            $this->mostrarFormulario();
            return;
        }

        $usuario = $this->modelo->consultarUsuario($email);

        if (!$usuario || $usuario['contraseña'] !== md5($password)) {
            $_SESSION['error'] = 'Email o contraseña incorrectos.';
            $this->mostrarFormulario();
            return;
        }

        // Login exitoso
        $_SESSION['logged'] = true;
        $_SESSION['user-id'] = $usuario['id'];
        $_SESSION['user-datos'] = $usuario['datos_completos'];
        $_SESSION['user-rol'] = $usuario['rol'];

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