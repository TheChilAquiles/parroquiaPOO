<?php
// Asegúrate de que las rutas a tus modelos y vistas sean correctas
require_once '../modelo/Conexion.php';
require_once '../modelo/ModeloNoticia.php';

// Conexión y modelo
$conexion = Conexion::conectar();
$modeloNoticia = new ModeloNoticia($conexion);

// Lógica para manejar las acciones (GET y POST)
$accion = $_GET['accion'] ?? 'listar';

switch ($accion) {
    case 'listar':
        $noticias = $modeloNoticia->mdlLeerNoticias();
        // Incluir la vista que muestra el listado
        include_once '../vistas/ver_';
        break;

    case 'ver_editar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $noticia = $modeloNoticia->mdlLeerNoticiaPorId($id);
            // Incluir la vista del formulario de edición
            include_once '../vista/admin/noticias/editar_noticia.php';
        } else {
            // Redirigir o mostrar un error si no hay ID
            header('Location: NoticiaController.php?accion=listar');
        }
        break;

    case 'guardar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $titulo = $_POST['titulo'] ?? '';
            $descripcion = $_POST['descripcion'] ?? ''; 
            // Lógica para manejar la imagen (subida y ruta)
            $imagen = 'ruta/de/tu/imagen.jpg'; 

            if ($id) {
                // Si hay ID, es una actualización
                $modeloNoticia->mdlActualizarNoticia($id, $titulo, $descripcion, $imagen);
            } else {
                // Si no hay ID, es una nueva noticia
                $modeloNoticia->mdlCrearNoticia($titulo, $descripcion, $imagen);
            }
            // Redirigir al listado después de guardar
            header('Location: NoticiaController.php?accion=listar');
        }
        break;

    case 'eliminar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $modeloNoticia->mdlEliminarNoticia($id);
        }
        // Redirigir al listado
        header('Location: NoticiaController.php?accion=listar');
        break;

    default:
        // Por defecto, redirigir al listado
        header('Location: NoticiaController.php?accion=listar');
        break;
}

$conexion = null; // Cerrar la conexión