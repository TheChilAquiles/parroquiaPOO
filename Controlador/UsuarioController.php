<?php


// ============================================================================
// UsuarioController.php
// ============================================================================

class UsuarioController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloUsuario();
    }

    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . '/../Vista/crear-usuario.php';
            return;
        }

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $passwordConfirm = $_POST['password-confirm'] ?? null;

        if (empty($email) || empty($password) || empty($passwordConfirm)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            include __DIR__ . '/../Vista/crear-usuario.php';
            return;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            include __DIR__ . '/../Vista/crear-usuario.php';
            return;
        }

        $resultado = $this->modelo->mdlRegistrarUsuario([
            'email' => $email,
            'password' => $password,
            'password-confirm' => $passwordConfirm
        ]);

        if (isset($resultado['status']) && $resultado['status'] === 'error') {
            $_SESSION['error'] = $resultado['message'];
            include __DIR__ . '/../Vista/crear-usuario.php';
            return;
        }

        $_SESSION['success'] = 'Usuario creado correctamente.';
        header('Location: ?route=dashboard');
        exit();
    }

    public function obtenerPorId()
    {
        $id = $_GET['id'] ?? null;

        if (empty($id) || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID inválido']);
            exit();
        }

        try {
            $usuario = $this->modelo->obtenerUsuarioPorId((int)$id);

            if (!$usuario) {
                http_response_code(404);
                echo json_encode(['error' => 'Usuario no encontrado']);
                exit();
            }

            header('Content-Type: application/json');
            echo json_encode($usuario);
            exit();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al obtener usuario']);
            exit();
        }
    }
}