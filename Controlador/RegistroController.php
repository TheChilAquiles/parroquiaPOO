<?php
// ============================================================================
// RegistroController.php - REFACTORIZADO
// ============================================================================

class RegistroController
{
    private $modelo;

    public function __construct()
    {
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

        // Validar que vengan todos los datos
        if (empty($email) || empty($password) || empty($passwordConfirm)) {
            $_SESSION['error_email'] = 'Todos los campos son obligatorios.';
            $this->mostrarFormulario();
            return;
        }

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_email'] = 'El formato del correo electrónico es inválido.';
            $this->mostrarFormulario();
            return;
        }

        // Validar que las contraseñas coincidan
        if ($password !== $passwordConfirm) {
            $_SESSION['error_confirm'] = 'Las contraseñas no coinciden.';
            $this->mostrarFormulario();
            return;
        }

        // Validar longitud mínima de contraseña
        if (strlen($password) < 6) {
            $_SESSION['error_password'] = 'La contraseña debe tener al menos 6 caracteres.';
            $this->mostrarFormulario();
            return;
        }

        // Intentar registrar usuario
        $resultado = $this->modelo->mdlRegistrarUsuario([
            'email' => $email,
            'password' => $password,
            'password-confirm' => $passwordConfirm
        ]);

        // Verificar resultado
        if (isset($resultado['status']) && $resultado['status'] === 'error') {
            // El modelo ya validó si el email existe
            if (strpos($resultado['message'], 'registrado') !== false) {
                $_SESSION['error_email'] = 'Este correo electrónico ya está registrado.';
            } else {
                $_SESSION['error_email'] = $resultado['message'];
            }
            $this->mostrarFormulario();
            return;
        }

        // Registro exitoso
        $_SESSION['success'] = 'Registro completado. Por favor inicia sesión.';
        header('Location: ?route=login');
        exit();
    }
}