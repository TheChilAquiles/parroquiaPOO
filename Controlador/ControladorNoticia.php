<?php
// Usamos rutas absolutas para evitar el error de "file not found"
require_once __DIR__ . '/../Modelo/Conexion.php';
require_once __DIR__ . '/../Modelo/ModeloNoticia.php';

class NoticiaController
{
    private $modeloNoticia;
    private $conexion;

    public function __construct()
    {
        $this->modeloNoticia = new ModeloNoticia();
    }

    public function ctrlGestionarNoticias()
    {

        $accion = $_POST[md5('action')] ?? 'listar';
        $noticia = null;
        $noticias = [];
        $mensaje = null;

        switch ($accion) {
            case 'ver_form':
                $id = $_POST['id'] ?? null;
                if ($id) {
                    $noticia = $this->modeloNoticia->mdlLeerNoticiaPorId($id);
                    if (!$noticia) {
                        $mensaje = ['tipo' => 'error', 'texto' => 'Noticia no encontrada.'];
                        $noticia = null;
                    }
                }
                break;

            case 'guardar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = $_POST['id'] ?? null;
                    $titulo = $_POST['titulo'] ?? '';
                    $descripcion = $_POST['descripcion'] ?? '';
                    $imagen = '';

                    // Lógica para manejar la subida de la imagen
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                        $directorioDestino = __DIR__ . '/../assets/img/noticias/';
                        if (!is_dir($directorioDestino)) {
                            mkdir($directorioDestino, 0755, true);
                        }
                        $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
                        $rutaArchivo = $directorioDestino . $nombreArchivo;

                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaArchivo)) {
                            $imagen = 'assets/img/noticias/' . $nombreArchivo;
                        } else {
                            $mensaje = ['tipo' => 'error', 'texto' => 'Error al subir la imagen.'];
                            break;
                        }
                    } elseif ($id && empty($_FILES['imagen']['name'])) {
                        $noticiaExistente = $this->modeloNoticia->mdlLeerNoticiaPorId($id);
                        if ($noticiaExistente) {
                            $imagen = $noticiaExistente['imagen'];
                        }
                    }

                    if ($id) {
                        $resultado = $this->modeloNoticia->mdlActualizarNoticia($id, $titulo, $descripcion, $imagen);
                        $mensaje = $resultado ? ['tipo' => 'exito', 'texto' => 'Noticia actualizada con éxito.'] : ['tipo' => 'error', 'texto' => 'Error al actualizar la noticia.'];
                    } else {
                        $idUsuario = $_SESSION['user-id'] ?? null;
                        if ($idUsuario && !empty($imagen)) {
                            $resultado = $this->modeloNoticia->mdlCrearNoticia($titulo, $descripcion, $imagen, $idUsuario);
                            $mensaje = $resultado ? ['tipo' => 'exito', 'texto' => 'Noticia creada con éxito.'] : ['tipo' => 'error', 'texto' => 'Error al crear la noticia.'];
                        } else {
                            $mensaje = ['tipo' => 'error', 'texto' => 'No se puede crear la noticia. Usuario no logueado o no se subió una imagen.'];
                        }
                    }
                }
                $accion = 'listar';
                break;

            case 'eliminar':
                $id = $_POST['id'] ?? null;
                if ($id) {
                    $noticia = $this->modeloNoticia->mdlLeerNoticiaPorId($id);
                    if ($noticia) {
                        $rutaArchivo = __DIR__ . '/../' . $noticia['imagen'];
                        if (file_exists($rutaArchivo)) {
                            unlink($rutaArchivo);
                        }
                    }
                    $resultado = $this->modeloNoticia->mdlEliminarNoticia($id);
                    $mensaje = $resultado ? ['tipo' => 'exito', 'texto' => 'Noticia eliminada con éxito.'] : ['tipo' => 'error', 'texto' => 'Error al eliminar la noticia.'];
                }
                $accion = 'listar';
                break;
        }

        if (isset($_SESSION["logged"]) && $_SESSION["logged"] == true) {
            include_once __DIR__ . '/../Vista/noticiaAdministrador.php';
        } else {
            include_once __DIR__ . '/../Vista/noticiaUsuario.php';
        }
    }
}
