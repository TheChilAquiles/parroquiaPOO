<?php
// ============================================================================
// LoginController.php - COMPLETO (Login + Reseteo con PHPMailer)
// ============================================================================


// Usar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class LoginController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloUsuario();
    }

    // ========================================================================
    // MÉTODOS DE LOGIN Y SALIR (LOS QUE YA TENÍAS)
    // ========================================================================

    public function mostrarFormulario()
    {
        include_once __DIR__ . '/../Vista/login.php';
    }

    public function procesar()
    {
        try {
            Logger::info("Intento de login iniciado", [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->mostrarFormulario();
                return;
            }

            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            // Validar que vengan los datos
            if (empty($email) || empty($password)) {
                Logger::warning("Login fallido: campos vacíos", ['ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
                $_SESSION['error_login'] = 'Email y contraseña son requeridos.';
                $this->mostrarFormulario();
                return;
            }

            // Validar formato de email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Logger::warning("Login fallido: email inválido", [
                    'email_provided' => substr($email, 0, 3) . '***',
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error_login'] = 'El formato del correo electrónico es inválido.';
                $this->mostrarFormulario();
                return;
            }

            // Usamos el método seguro del modelo
            $usuario = $this->modelo->mdlVerificarLogin($email, $password);

            // Verificar credenciales
            if (!$usuario) {
                Logger::warning("Login fallido: credenciales incorrectas", [
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
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

            Logger::info("Login exitoso", [
                'user_id' => $usuario['id'],
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                'rol' => $usuario['rol'],
                'datos_completos' => $usuario['datos_completos'] ?? false,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            redirect('dashboard');
            
            exit();

        } catch (Exception $e) {
            Logger::error("Error crítico en proceso de login", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $_SESSION['error_login'] = 'Error al procesar tu solicitud. Por favor, intenta de nuevo.';
            $this->mostrarFormulario();
        }
    }

    public function salir()
    {
        try {
            $userId = $_SESSION['user-id'] ?? 'unknown';
            $userRole = $_SESSION['user-rol'] ?? 'unknown';

            Logger::info("Logout exitoso", [
                'user_id' => $userId,
                'rol' => $userRole,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            session_destroy();
            redirect('inicio');
            
            exit();

        } catch (Exception $e) {
            Logger::error("Error durante logout", [
                'error' => $e->getMessage(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            session_destroy();
            redirect('inicio');
            exit();
        }
    }

    // ========================================================================
    // MÉTODOS NUEVOS PARA RECUPERAR CONTRASEÑA
    // ========================================================================

    /**
     * Muestra el formulario para pedir el email de reseteo
     */
    public function mostrarFormularioOlvido()
    {
        // CORREGIDO al nombre de tu vista
        include_once __DIR__ . '/../Vista/olvidoContraseña.php';
    }

    /**
     * Procesa la solicitud de reseteo (aquí se envía el email)
     */
    public function procesarSolicitudOlvido()
    {
        try {
            Logger::info("Solicitud de recuperación de contraseña iniciada", [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('olvido');
                
                exit();
            }

            $email = $_POST['email'] ?? null;

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Logger::warning("Recuperación fallida: email inválido", [
                    'email_provided' => substr($email ?? 'empty', 0, 3) . '***',
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error'] = 'Por favor, ingresa un email válido.';
                redirect('olvido');
                exit();
            }

            // 1. Verificar que el email exista
            $usuario = $this->modelo->consultarUsuario($email);

            if (!$usuario) {
                // No revelamos si el email existe o no por seguridad
                Logger::info("Recuperación solicitada para email no registrado", [
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['success'] = 'Si tu email está registrado, recibirás un enlace para recuperar tu contraseña.';
                redirect('olvido');
                exit();
            }

            // 2. Generar un token seguro
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600); // Expira en 1 hora

            Logger::info("Token de recuperación generado", [
                'user_id' => $usuario['id'],
                'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                'token_prefix' => substr($token, 0, 8) . '...',
                'expires_at' => $expires
            ]);

            // 3. Guardar el token en la BD
            if (!$this->modelo->mdlGuardarTokenReset($email, $token, $expires)) {
                Logger::error("Error al guardar token de recuperación en BD", [
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                    'user_id' => $usuario['id']
                ]);
                $_SESSION['error'] = 'Hubo un error al procesar tu solicitud. Intenta de nuevo.';
                redirect('olvido');
                exit();
            }

            // 4. Enviar el email con PHPMailer
            $urlReseteo = "http://localhost/parroquiaPOO/index.php?route=resetear&token=" . $token;

            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor desde config.php
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USERNAME;
                $mail->Password = SMTP_PASSWORD;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = SMTP_PORT;
                $mail->CharSet = 'UTF-8';

                // Remitente y destinatario
                $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
                $mail->addAddress($email);

                // Contenido del email
                $mail->isHTML(true);
                $mail->Subject = 'Recupera tu contraseña';
                $mail->Body = "Hola,<br><br>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace:<br><br>"
                    . "<a href='$urlReseteo' style='padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Restablecer Contraseña</a><br><br>"
                    . "Si no solicitaste esto, puedes ignorar este email. El enlace expira en 1 hora.<br><br>"
                    . "Si el botón no funciona, copia y pega esta URL: $urlReseteo";
                $mail->AltBody = "Para restablecer tu contraseña, copia y pega este enlace en tu navegador: $urlReseteo";

                $mail->send();

                Logger::info("Email de recuperación enviado exitosamente", [
                    'user_id' => $usuario['id'],
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1)
                ]);

                $_SESSION['success'] = 'Si tu email está registrado, recibirás un enlace para recuperar tu contraseña.';
                redirect('olvido');
                exit();

            } catch (Exception $e) {
                Logger::error("Error al enviar email de recuperación", [
                    'error' => $mail->ErrorInfo,
                    'exception' => $e->getMessage(),
                    'email' => substr($email, 0, 3) . '***@' . substr(strstr($email, '@'), 1),
                    'user_id' => $usuario['id']
                ]);
                $_SESSION['error'] = 'No se pudo enviar el email. Contacta al administrador.';
                redirect('olvido');
                exit();
            }

        } catch (Exception $e) {
            Logger::error("Error crítico en proceso de recuperación de contraseña", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $_SESSION['error'] = 'Error al procesar tu solicitud. Por favor, intenta de nuevo.';
            redirect('olvido');
            exit();
        }
    }

    /**
     * Muestra el formulario para ingresar la nueva contraseña
     */
    public function mostrarFormularioReseteo()
    {
        $token = $_GET['token'] ?? null;

        if (empty($token)) {
            $_SESSION['error'] = 'Token inválido o no proporcionado.';
            redirect('login');
            
            exit();
        }

        // Verificar si el token es válido y no ha expirado
        $usuario = $this->modelo->mdlBuscarUsuarioPorToken($token);

        if (!$usuario) {
            $_SESSION['error'] = 'El token es inválido o ha expirado. Por favor, solicita un nuevo reseteo.';
            redirect('olvido');
            
            exit();
        }

        // Pasamos el token a la vista
        // CORREGIDO al nombre de tu vista
        include_once __DIR__ . '/../Vista/resetearContraseña.php';
    }

    /**
     * Procesa y guarda la nueva contraseña
     */
    public function procesarReseteo()
    {
        try {
            Logger::info("Intento de reseteo de contraseña", [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('login');
                
                exit();
            }

            $token = $_POST['token'] ?? null;
            $password = $_POST['password'] ?? null;
            $password_confirm = $_POST['password_confirm'] ?? null;

            // 1. Validar que todo venga
            if (empty($token) || empty($password) || empty($password_confirm)) {
                Logger::warning("Reseteo fallido: campos vacíos", [
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error'] = 'Todos los campos son requeridos.';
                header('Location: ?route=resetear&token=' . urlencode($token));
                exit();
            }

            // 2. Validar que las contraseñas coincidan
            if ($password !== $password_confirm) {
                Logger::warning("Reseteo fallido: contraseñas no coinciden", [
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error'] = 'Las contraseñas no coinciden.';
                header('Location: ?route=resetear&token=' . urlencode($token));
                exit();
            }

            // 3. Validar fortaleza de la contraseña
            if (strlen($password) < 8) {
                Logger::warning("Reseteo fallido: contraseña débil", [
                    'length' => strlen($password),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres.';
                header('Location: ?route=resetear&token=' . urlencode($token));
                exit();
            }

            // 4. Verificar el token de nuevo
            $usuario = $this->modelo->mdlBuscarUsuarioPorToken($token);
            if (!$usuario) {
                Logger::warning("Reseteo fallido: token inválido o expirado", [
                    'token_prefix' => substr($token, 0, 8) . '...',
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error'] = 'El token es inválido o ha expirado.';
                redirect('olvido');
                
                exit();
            }

            // 5. Actualizar la contraseña
            if ($this->modelo->mdlActualizarContrasenaPorToken($token, $password)) {
                Logger::info("Contraseña actualizada exitosamente", [
                    'user_id' => $usuario['id'],
                    'email' => substr($usuario['email'], 0, 3) . '***@' . substr(strstr($usuario['email'], '@'), 1),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['success'] = '¡Tu contraseña ha sido actualizada! Ya puedes iniciar sesión.';
                redirect('login');
                
                exit();
            } else {
                Logger::error("Error al actualizar contraseña en BD", [
                    'user_id' => $usuario['id'],
                    'token_prefix' => substr($token, 0, 8) . '...'
                ]);
                $_SESSION['error'] = 'Hubo un error al actualizar tu contraseña. Intenta de nuevo.';
                header('Location: ?route=resetear&token=' . urlencode($token));
                exit();
            }

        } catch (Exception $e) {
            Logger::error("Error crítico en proceso de reseteo de contraseña", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $_SESSION['error'] = 'Error al procesar tu solicitud. Por favor, intenta de nuevo.';
            redirect('olvido');
            
            exit();
        }
    }
}