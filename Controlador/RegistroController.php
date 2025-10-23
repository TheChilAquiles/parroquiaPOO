<?php
// ============================================================================
// RegistroController.php
// ============================================================================

class RegistroController
{
    private $modelo;

    public function __construct()
    {
        require_once __DIR__ . '/../Modelo/ModeloUsuario.php';
        $this->modelo = new ModeloUsuario();
    }

    public function mostrarFormulario()
    {
        include_once __DIR__ . '/../Vista/register.php';
    }

    public function procesar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->mostrarFormulario();
            return;
        }

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $passwordConfirm = $_POST['password-confirm'] ?? null;

        if (empty($email) || empty($password) || empty($passwordConfirm)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            $this->mostrarFormulario();
            return;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            $this->mostrarFormulario();
            return;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres.';
            $this->mostrarFormulario();
            return;
        }

        $resultado = $this->modelo->mdlRegistrarUsuario([
            'email' => $email,
            'password' => $password,
            'password-confirm' => $passwordConfirm
        ]);

        if (isset($resultado['status']) && $resultado['status'] === 'error') {
            $_SESSION['error'] = $resultado['message'];
            $this->mostrarFormulario();
            return;
        }

        $_SESSION['success'] = 'Registro completado. Por favor inicia sesión.';
        include_once __DIR__ . '/../Vista/registroCompleto.php';
    }
}