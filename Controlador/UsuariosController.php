<?php

class UsuariosController extends BaseController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloAdminUsuario();
    }

    public function index()
    {
        $this->requiereAdmin(); 

        $mensaje = null;
        if (isset($_SESSION['mensaje'])) {
            $mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }

        $filtro = $_POST['buscar'] ?? '';
        
        $usuarios = $this->modelo->mdlObtenerUsuarios($filtro);
        $roles = $this->modelo->mdlObtenerRoles(); 

        include_once __DIR__ . '/../Vista/usuarios.php';
    }

    public function crear()
    {
        $this->requiereAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responderError('Método no permitido');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol_id = $_POST['usuario_rol_id'] ?? '';

        if (empty($email) || empty($password) || empty($rol_id)) {
            $this->responderError('Email, contraseña y rol son obligatorios.');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->responderError('Formato de email inválido.');
            return;
        }

        $datos = [
            'email' => $email,
            'contraseña' => password_hash($password, PASSWORD_DEFAULT),
            'usuario_rol_id' => $rol_id
        ];

        $respuesta = $this->modelo->mdlCrearUsuario($datos);

        if ($respuesta['exito']) {
            $this->responderExito($respuesta['mensaje']);
        } else {
            $this->responderError($respuesta['mensaje']);
        }
    }

    public function actualizar()
    {
        $this->requiereAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responderError('Método no permitido');
            return;
        }

        $id = $_POST['id'] ?? null;
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? ''; 
        $rol_id = $_POST['usuario_rol_id'] ?? '';

        if (empty($id) || !is_numeric($id) || empty($email) || empty($rol_id)) {
            $this->responderError('ID, Email y Rol son obligatorios.');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->responderError('Formato de email inválido.');
            return;
        }

        $datos = [
            'email' => $email,
            'usuario_rol_id' => $rol_id
        ];

        if (!empty($password)) {
            $datos['contraseña'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $respuesta = $this->modelo->mdlActualizarUsuario($id, $datos);

        if ($respuesta['exito']) {
            $this->responderExito($respuesta['mensaje']);
        } else {
            $this->responderError($respuesta['mensaje']);
        }
    }

    public function eliminar()
    {
        $this->requiereAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responderError('Método no permitido');
            return;
        }

        $id = $_POST['id'] ?? null;

        if (empty($id) || !is_numeric($id)) {
            $this->responderError('ID de usuario inválido.');
            return;
        }

        if ($id == $_SESSION['user-id']) {
            $this->responderError('No puedes eliminar tu propia cuenta.');
            return;
        }

        $respuesta = $this->modelo->mdlBorrarUsuario($id);

        if ($respuesta['exito']) {
            $this->responderExito($respuesta['mensaje']);
        } else {
            $this->responderError($respuesta['mensaje']);
        }
    }

    // ========================================================================
    // MÉTODOS PRIVADOS PARA RESPONDER A AJAX
    // ========================================================================

    private function esAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function responderExito($mensaje)
    {
        if ($this->esAjax()) {
            // Limpiamos el buffer para evitar que se imprima HTML basura antes del JSON
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'exito' => true,
                'status' => 'success',
                'mensaje' => $mensaje,
                'message' => $mensaje
            ], JSON_UNESCAPED_UNICODE);
            exit(); 
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => $mensaje
            ];
            header('Location: ?route=usuarios');
            exit();
        }
    }

    private function responderError($mensaje)
    {
        if ($this->esAjax()) {
            // Limpiamos el buffer para evitar HTML basura
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400); // Enviamos código de error HTTP
            echo json_encode([
                'exito' => false,
                'status' => 'error',
                'mensaje' => $mensaje,
                'message' => $mensaje
            ], JSON_UNESCAPED_UNICODE);
            exit(); 
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => $mensaje
            ];
            header('Location: ?route=usuarios');
            exit();
        }
    }
}