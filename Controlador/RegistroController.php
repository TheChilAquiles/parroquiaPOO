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
        try {
            Logger::info("Intento de registro iniciado", [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 100)
            ]);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->mostrarFormulario();
                return;
            }

            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;
            $passwordConfirm = $_POST['password-confirm'] ?? null;

            // Validar que vengan todos los datos
            if (empty($email) || empty($password) || empty($passwordConfirm)) {
                Logger::warning("Registro fallido: campos vacíos", [
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                    'email_provided' => !empty($email)
                ]);
                $_SESSION['error_email'] = 'Todos los campos son obligatorios.';
                $this->mostrarFormulario();
                return;
            }

            // Validar formato de email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Logger::warning("Registro fallido: email inválido", [
                    'email_provided' => substr($email, 0, 3) . '***',
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error_email'] = 'El formato del correo electrónico es inválido.';
                $this->mostrarFormulario();
                return;
            }

            // Validar que las contraseñas coincidan
            if ($password !== $passwordConfirm) {
                Logger::warning("Registro fallido: contraseñas no coinciden", [
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error_confirm'] = 'Las contraseñas no coinciden.';
                $this->mostrarFormulario();
                return;
            }

            // Validar longitud mínima de contraseña
            if (strlen($password) < 8) {
                Logger::warning("Registro fallido: contraseña débil", [
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                    'password_length' => strlen($password),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error_password'] = 'La contraseña debe tener al menos 8 caracteres.';
                $this->mostrarFormulario();
                return;
            }

            Logger::info("Validaciones completadas, intentando registro en BD", [
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            // Intentar registrar usuario
            $resultado = $this->modelo->mdlRegistrarUsuario([
                'email' => $email,
                'password' => $password,
                'password-confirm' => $passwordConfirm
            ]);

            // Verificar resultado
            if (isset($resultado['status']) && $resultado['status'] === 'error') {
                Logger::warning("Registro fallido en BD", [
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                    'error' => $resultado['message'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);

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
            Logger::info("Registro completado exitosamente", [
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                'user_id' => $resultado['id'] ?? 'unknown',
                'rol' => $resultado['rol'] ?? 'Feligrés',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            $_SESSION['success'] = 'Registro completado. Por favor inicia sesión.';
            header('Location: ?route=login');
            exit();

        } catch (Exception $e) {
            Logger::error("Error crítico en proceso de registro", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => isset($email) ? substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1) : 'unknown',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $_SESSION['error_email'] = 'Error al procesar tu registro. Por favor, intenta de nuevo.';
            $this->mostrarFormulario();
        }
    }
}