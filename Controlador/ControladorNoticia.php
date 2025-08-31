<?php

require_once(__DIR__ . '/../Modelo/ModeloNoticia.php');

class NoticiaController {

    private $noticiaModel;

    public function __construct() {
        $this->noticiaModel = new NoticiaModel();
    }

    public function ctrlGestionarNoticias() {
        $action = isset($_POST[md5('action')]) ? $_POST[md5('action')] : '';
        $isLoggedIn = isset($_SESSION['user-id']);

        if ($isLoggedIn) {
            switch ($action) {
                case md5('guardar'):
                    $this->ctrlGuardarNoticia();
                    break;
                case md5('eliminar'):
                    $this->ctrlEliminarNoticia($_POST['id']);
                    break;
                default:
                    $this->ctrlMostrarAdminNoticias();
                    break;
            }
        } else {
            $this->ctrlMostrarNoticiasPublicas();
        }
    }
    
    // Método privado para guardar/actualizar una noticia
    private function ctrlGuardarNoticia() {
        // Asumiendo que el formulario envía los datos por POST
        $id = $_POST['id'] ?? null;
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $id_usuario = $_SESSION['user-id']; // ID del usuario logueado

        // Manejo de la imagen
        $imagen = $_POST['imagen_actual'] ?? ''; // Por defecto, la imagen actual
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            // Lógica para guardar la nueva imagen en el servidor
            $nombre_archivo = uniqid() . '-' . $_FILES['imagen']['name'];
            $directorio_destino = __DIR__ . '/../Recursos/Imagenes/' . $nombre_archivo;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio_destino);
            $imagen = 'Recursos/Imagenes/' . $nombre_archivo;
        }

        if (empty($id)) {
            // Es una nueva noticia
            $this->noticiaModel->crearNoticia($titulo, $descripcion, $imagen, $id_usuario);
        } else {
            // Es una actualización
            $this->noticiaModel->actualizarNoticia($id, $titulo, $descripcion, $imagen);
        }
        
        // Redirige para evitar el reenvío del formulario
        $_SESSION['menu-item'] = 'Noticias';
        header('Location: index.php');
        exit();
    }

    private function ctrlEliminarNoticia($id) {
        $this->noticiaModel->eliminarNoticia($id);
        $_SESSION['menu-item'] = 'Noticias';
        header('Location: index.php');
        exit();
    }

    private function ctrlMostrarAdminNoticias() {
        $noticias = $this->noticiaModel->obtenerTodasLasNoticias();
        require_once(__DIR__ . '/../Vista/NoticiaAdministrador.php');
    }

    private function ctrlMostrarNoticiasPublicas() {
        $noticias = $this->noticiaModel->obtenerTodasLasNoticias();
        require_once(__DIR__ . '/../Vista/noticiaUsuario.php');
    }
}