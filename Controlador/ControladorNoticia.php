<?php

/**
 * Clase ControladorNoticia
 *
 * Este controlador es la pieza central que orquesta la aplicación. Se encarga de:
 * 1. Incluir los archivos de las clases Modelo y de Vista necesarias.
 * 2. Procesar las solicitudes del usuario (guardar, eliminar, mostrar).
 * 3. Interactuar con el Modelo para obtener o modificar datos.
 * 4. Incluir la Vista (HTML) para mostrar los datos.
 *
 * @package Controlador
 */

// Se inicia la sesión para el manejo de usuarios.
session_start();

// El controlador se encarga de requerir los archivos necesarios.
// Se asume que el archivo del modelo está en el mismo directorio.
require_once 'ModeloNoticia.php';

class ControladorNoticia
{
    /**
     * @var ModeloNoticia Instancia del modelo de noticias.
     */
    private $modeloNoticia;

    /**
     * Constructor de la clase.
     *
     * Inicializa una nueva instancia del modelo de noticias.
     */
    public function __construct()
    {
        $this->modeloNoticia = new ModeloNoticia();
    }
    
    /**
     * Método principal que gestiona la lógica de la aplicación.
     *
     * Este método actúa como un enrutador simple. Basado en la acción y el estado
     * de la sesión, determina qué método privado se debe ejecutar.
     */
    public function ctrGestionarNoticias()
    {
        $action = isset($_POST[md5('action')]) ? $_POST[md5('action')] : '';
        $isLoggedIn = isset($_SESSION['user-id']);

        if ($isLoggedIn) {
            switch ($action) {
                case md5('guardar'):
                    $this->ctrGuardarNoticia();
                    break;
                case md5('eliminar'):
                    $this->ctrEliminarNoticia($_POST['id']);
                    break;
                default:
                    $this->ctrMostrarAdminNoticias();
                    break;
            }
        } else {
            $this->ctrMostrarNoticiasPublicas();
        }
    }

    /**
     * Guarda o actualiza una noticia.
     *
     * Este método maneja el procesamiento de formularios, incluyendo la subida
     * de archivos de imagen, y llama al modelo para guardar los datos.
     */
    private function ctrGuardarNoticia()
    {
        $id = $_POST['id'] ?? null;
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
        $id_usuario = $_SESSION['user-id'];
        
        // Manejo de la imagen. La lógica de guardado es la misma que la tuya.
        $imagen = $_POST['imagen_actual'] ?? '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_archivo = uniqid() . '-' . $_FILES['imagen']['name'];
            $directorio_destino = __DIR__ . '/../Recursos/Imagenes/' . $nombre_archivo;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio_destino)) {
                $imagen = 'Recursos/Imagenes/' . $nombre_archivo;
            } else {
                // Manejar error en la subida.
                echo "Error al subir la imagen.";
                exit;
            }
        }
        
        if (empty($id)) {
            // Es una nueva noticia.
            $this->modeloNoticia->mdlCrearNoticia([
                'id_usuario' => $id_usuario,
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'imagen' => $imagen
            ]);
        } else {
            // Es una actualización.
            $this->modeloNoticia->mdlActualizarNoticia($id, [
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'imagen' => $imagen
            ]);
        }

        $_SESSION['menu-item'] = 'Noticias';
        header('Location: index.php');
        exit();
    }

    /**
     * Elimina una noticia por su ID.
     *
     * @param int $id El ID de la noticia a eliminar.
     */
    private function ctrEliminarNoticia($id)
    {
        $this->modeloNoticia->mdlBorrarNoticia($id);
        $_SESSION['menu-item'] = 'Noticias';
        header('Location: index.php');
        exit();
    }

    /**
     * Muestra la vista de administrador de noticias.
     */
    private function ctrMostrarAdminNoticias()
    {
        $noticias = $this->modeloNoticia->mdlObtenerNoticias();
        require_once 'Vista/NoticiaAdministrador.php';
    }

    /**
     * Muestra la vista pública de noticias.
     */
    private function ctrMostrarNoticiasPublicas()
    {
        $noticias = $this->modeloNoticia->mdlObtenerNoticias();
        require_once 'VistaNoticiaUsuario.php';
    }
}
