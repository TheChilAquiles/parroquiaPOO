<?php

// ============================================================================
// NoticiasController.php
// ============================================================================

class NoticiasController
{
    private $modelo;

    public function __construct()
    {
        require_once __DIR__ . '/../Modelo/ModeloNoticia.php';
        $this->modelo = new ModeloNoticia();
    }

    public function index()
    {
        $rol = $_SESSION['user-rol'] ?? 'Feligres';
        $mensaje = $_SESSION['mensaje'] ?? null;
        unset($_SESSION['mensaje']);

        if ($rol === 'Feligres') {
            $noticias = $this->modelo->mdlObtenerNoticias();
            include_once __DIR__ . '/../Vista/noticiaUsuario.php';
        } else {
            $filtro = $_POST['buscar'] ?? '';
            $noticias = $this->modelo->mdlObtenerNoticias($filtro);
            include_once __DIR__ . '/../Vista/noticiaAdministrador.php';
        }
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->index();
            return;
        }

        $titulo = htmlspecialchars($_POST['titulo'] ?? '', ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($_POST['descripcion'] ?? '', ENT_QUOTES, 'UTF-8');
        $id = $_POST['id'] ?? null;

        if (empty($titulo) || empty($descripcion)) {
            $_SESSION['error'] = 'Título y descripción son obligatorios.';
            $this->index();
            return;
        }

        $imagen = $_POST['imagen_actual'] ?? null;

        try {
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->procesarImagen($_FILES['imagen']);
            }

            $datos = [
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'imagen' => $imagen
            ];

            if (empty($id)) {
                $datos['id_usuario'] = $_SESSION['user-id'] ?? null;
                if (empty($datos['id_usuario'])) {
                    $_SESSION['error'] = 'Error: Usuario no autenticado.';
                    $this->index();
                    return;
                }
                $respuesta = $this->modelo->mdlCrearNoticia($datos);
            } else {
                $respuesta = $this->modelo->mdlActualizarNoticia($id, $datos);
            }

            $_SESSION['mensaje'] = [
                'tipo' => $respuesta['exito'] ? 'success' : 'error',
                'texto' => $respuesta['mensaje']
            ];
        } catch (Exception $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Error: ' . $e->getMessage()
            ];
        }

        header('Location: ?route=noticias');
        exit();
    }

    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
            $_SESSION['error'] = 'ID de noticia no proporcionado.';
            header('Location: ?route=noticias');
            exit();
        }

        $id = $_POST['id'];
        $respuesta = $this->modelo->mdlBorrarNoticia($id);

        $_SESSION['mensaje'] = [
            'tipo' => $respuesta['exito'] ? 'success' : 'error',
            'texto' => $respuesta['mensaje']
        ];

        header('Location: ?route=noticias');
        exit();
    }

    private function procesarImagen($file)
    {
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        $tipoArchivo = mime_content_type($file['tmp_name']);

        if (!in_array($tipoArchivo, $tiposPermitidos)) {
            throw new Exception('Tipo de archivo no permitido.');
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('Archivo demasiado grande (máx 5MB).');
        }

        $nombre = uniqid() . '-' . basename($file['name']);
        $destino = __DIR__ . '/../assets/img/noticias/' . $nombre;

        if (!is_dir(dirname($destino))) {
            mkdir(dirname($destino), 0777, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            throw new Exception('Error al mover archivo.');
        }

        return 'assets/img/noticias/' . $nombre;
    }
}