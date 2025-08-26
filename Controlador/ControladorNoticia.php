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
        $this->conexion = Conexion::conectar();
        $this->modeloNoticia = new ModeloNoticia();
    }

    public function ctrlGestionarNoticias()
    {
        $accion = $_GET['accion'] ?? 'listar';
        $noticia = null;
        $noticias = [];
        $mensaje = null;

        switch ($accion) {
            case 'ver_form':
                // Si se pide editar, se obtiene la noticia
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $noticia = $this->modeloNoticia->mdlLeerNoticiaPorId($id);
                    if (!$noticia) {
                        $mensaje = ['tipo' => 'error', 'texto' => 'Noticia no encontrada.'];
                        $noticia = null; // Reiniciar noticia si no se encuentra
                    }
                }
                break;

            case 'guardar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = $_POST['id'] ?? null;
                    $titulo = $_POST['titulo'] ?? '';
                    $descripcion = $_POST['descripcion'] ?? '';
                    
                    // Lógica para subir la imagen (ejemplo)
                    $imagen = 'ruta/de/tu/imagen.jpg'; 

                    if ($id) {
                        $resultado = $this->modeloNoticia->mdlActualizarNoticia($id, $titulo, $descripcion, $imagen);
                        if ($resultado) {
                            $mensaje = ['tipo' => 'exito', 'texto' => 'Noticia actualizada con éxito.'];
                        } else {
                            $mensaje = ['tipo' => 'error', 'texto' => 'Error al actualizar la noticia.'];
                        }
                    } else {
                        $resultado = $this->modeloNoticia->mdlCrearNoticia($titulo, $descripcion, $imagen);
                        if ($resultado) {
                            $mensaje = ['tipo' => 'exito', 'texto' => 'Noticia creada con éxito.'];
                        } else {
                            $mensaje = ['tipo' => 'error', 'texto' => 'Error al crear la noticia.'];
                        }
                    }
                }
                $accion = 'listar'; // Volver a la vista de lista
                break;

            case 'eliminar':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $resultado = $this->modeloNoticia->mdlEliminarNoticia($id);
                    if ($resultado) {
                        $mensaje = ['tipo' => 'exito', 'texto' => 'Noticia eliminada con éxito.'];
                    } else {
                        $mensaje = ['tipo' => 'error', 'texto' => 'Error al eliminar la noticia.'];
                    }
                }
                $accion = 'listar'; // Volver a la vista de lista
                break;

            default:
            case 'listar':
                $noticias = $this->modeloNoticia->mdlLeerNoticias();
                break;
        }

        // Se incluye la única vista, y las variables se pasan
        include_once __DIR__ . '/../Vista/noticiaAdministrador.php';
    }
}
?>