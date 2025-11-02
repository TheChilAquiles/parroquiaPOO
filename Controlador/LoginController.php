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

        // Usamos el método seguro del modelo
        $usuario = $this->modelo->mdlVerificarLogin($email, $password);

        // Verificar credenciales
        if (!$usuario) {
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?route=olvido');
            exit();
        }

        $email = $_POST['email'] ?? null;

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Por favor, ingresa un email válido.';
            header('Location: ?route=olvido');
            exit();
        }

        // 1. Verificar que el email exista
        $usuario = $this->modelo->consultarUsuario($email);

        if (!$usuario) {
            // No revelamos si el email existe o no por seguridad
            $_SESSION['success'] = 'Si tu email está registrado, recibirás un enlace para recuperar tu contraseña.';
            header('Location: ?route=olvido');
            exit();
        }

        // 2. Generar un token seguro
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // Expira en 1 hora

        // 3. Guardar el token en la BD
        if (!$this->modelo->mdlGuardarTokenReset($email, $token, $expires)) {
            $_SESSION['error'] = 'Hubo un error al procesar tu solicitud. Intenta de nuevo.';
            header('Location: ?route=olvido');
            exit();
        }

        // 4. Enviar el email con PHPMailer
        // ¡¡CAMBIA ESTA URL por la de tu sitio web!!
        // Y usa la ruta correcta (?route=resetear)
        // LA LÍNEA NUEVA Y CORRECTA (PARA LOCAL):
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
            $mail->addAddress($email); // Email del usuario

            // Contenido del email
            $mail->isHTML(true);
            $mail->Subject = 'Recupera tu contraseña';
            $mail->Body = "Hola,<br><br>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace:<br><br>"
                . "<a href='$urlReseteo' style='padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Restablecer Contraseña</a><br><br>"
                . "Si no solicitaste esto, puedes ignorar este email. El enlace expira en 1 hora.<br><br>"
                . "Si el botón no funciona, copia y pega esta URL: $urlReseteo";
            $mail->AltBody = "Para restablecer tu contraseña, copia y pega este enlace en tu navegador: $urlReseteo";

            $mail->send();

            $_SESSION['success'] = 'Si tu email está registrado, recibirás un enlace para recuperar tu contraseña.';
            header('Location: ?route=olvido');
            exit();

        } catch (Exception $e) {
            error_log("Error al enviar email: {$mail->ErrorInfo}");
            $_SESSION['error'] = 'No se pudo enviar el email. Contacta al administrador.';
            header('Location: ?route=olvido');
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
            header('Location: ?route=login');
            exit();
        }

        // Verificar si el token es válido y no ha expirado
        $usuario = $this->modelo->mdlBuscarUsuarioPorToken($token);

        if (!$usuario) {
            $_SESSION['error'] = 'El token es inválido o ha expirado. Por favor, solicita un nuevo reseteo.';
            header('Location: ?route=olvido');
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?route=login');
            exit();
        }

        $token = $_POST['token'] ?? null;
        $password = $_POST['password'] ?? null;
        $password_confirm = $_POST['password_confirm'] ?? null;

        // 1. Validar que todo venga
        if (empty($token) || empty($password) || empty($password_confirm)) {
            $_SESSION['error'] = 'Todos los campos son requeridos.';
            header('Location: ?route=resetear&token=' . urlencode($token));
            exit();
        }

        // 2. Validar que las contraseñas coincidan
        if ($password !== $password_confirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: ?route=resetear&token=' . urlencode($token));
            exit();
        }

        // 3. Validar fortaleza de la contraseña
        if (strlen($password) < 8) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres.';
            header('Location: ?route=resetear&token=' . urlencode($token));
            exit();
        }

        // 4. Verificar el token de nuevo
        $usuario = $this->modelo->mdlBuscarUsuarioPorToken($token);
        if (!$usuario) {
            $_SESSION['error'] = 'El token es inválido o ha expirado.';
            header('Location: ?route=olvido');
            exit();
        }

        // 5. Actualizar la contraseña
        if ($this->modelo->mdlActualizarContrasenaPorToken($token, $password)) {
            $_SESSION['success'] = '¡Tu contraseña ha sido actualizada! Ya puedes iniciar sesión.';
            header('Location: ?route=login');
            exit();
        } else {
            $_SESSION['error'] = 'Hubo un error al actualizar tu contraseña. Intenta de nuevo.';
            header('Location: ?route=resetear&token=' . urlencode($token));
            exit();
        }
    }
}