<?php

// ============================================================================
// ContactoController.php
// ============================================================================

class PqrsController
{
    public function mostrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $mensaje = htmlspecialchars($_POST['mensaje'] ?? '', ENT_QUOTES, 'UTF-8');

            if (empty($nombre) || empty($email) || empty($mensaje)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                include __DIR__ . '/../Vista/pqrs.php';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'El email no es válido.';
                include __DIR__ . '/../Vista/pqrs.php';
                return;
            }

            try {
                $destinatario = 'contacto@parroquia.com'; // Cambiar por email real
                $asunto = 'Nuevo mensaje de contacto';
                $contenido = "Nombre: $nombre\nEmail: $email\n\nMensaje:\n$mensaje";

                mail($destinatario, $asunto, $contenido);

                $_SESSION['success'] = 'Mensaje enviado exitosamente.';
                include __DIR__ . '/../Vista/pqrs.php';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al enviar mensaje.';
                include __DIR__ . '/../Vista/pqrs.php';
            }
        } else {
            include __DIR__ . '/../Vista/pqrs.php';
        }
    }
}