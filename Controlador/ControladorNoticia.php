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

// El controlador se encarga de requerir los archivos necesarios.
// Se asume que el archivo del modelo está en el mismo directorio.
require_once __DIR__ . '/../Modelo/ModeloNoticia.php';
class ControladorNoticia
{
    /**
     * @var ModeloNoticia Instancia del modelo de noticias.
     */
    private $modeloNoticia;


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
        $id_usuario = $_SESSION['user-id'] ?? null;

        // Manejo de la subida de la imagen.
        $imagen = $_POST['imagen_actual'] ?? null; // ✅ CORRECCIÓN: Si no hay imagen, el valor es null.

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_archivo = uniqid() . '-' . $_FILES['imagen']['name'];
            $directorio_destino = __DIR__ . '/../assets/img/noticias/' . $nombre_archivo;

            if (!is_dir(dirname($directorio_destino))) {
                mkdir(dirname($directorio_destino), 0777, true);
            }

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio_destino)) {
                $imagen = 'assets/img/noticias/' . $nombre_archivo;
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => "Error al subir la imagen. Código de error: " . $_FILES['imagen']['error']];
                header('Location: index.php');
                exit();
            }
        }

        // Prepara los datos para el modelo.
        $datos = [
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'imagen' => $imagen
        ];

        if (empty($id)) {
            // Es una nueva noticia.

            // ✅ VALIDACIÓN: Asegúrate de que el ID de usuario existe.
            if (empty($id_usuario)) {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => "Error: No se encontró la sesión de usuario."];
                header('Location: index.php');
                exit();
            }

            $datos['id_usuario'] = $id_usuario;
            $respuesta = $this->modeloNoticia->mdlCrearNoticia($datos);
        } else {
            // Es una actualización.
            $respuesta = $this->modeloNoticia->mdlActualizarNoticia($id, $datos);
        }

        $_SESSION['mensaje'] = ['tipo' => $respuesta['exito'] ? 'success' : 'error', 'texto' => $respuesta['mensaje']];
        $_SESSION['menu-item'] = 'Noticias';
        header('Location: index.php');
        exit();
    }
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
        $isLoggedIn = isset($_SESSION["logged"]);

        // La acción se maneja solo si el usuario ha iniciado sesión, excepto para la visualización pública.
        if ($isLoggedIn) {
            switch ($action) {
                case md5('guardar'):
                    $this->ctrGuardarNoticia();
                    break;
                case md5('eliminar'):
                    $this->ctrEliminarNoticia();
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
     * Elimina una noticia por su ID.
     *
     * Este método llama al modelo para borrar un registro de la base de datos.
     */
    private function ctrEliminarNoticia()
    {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $respuesta = $this->modeloNoticia->mdlBorrarNoticia($id);
            $_SESSION['mensaje'] = ['tipo' => $respuesta['exito'] ? 'success' : 'error', 'texto' => $respuesta['mensaje']];
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => "ID de noticia no proporcionado."];
        }

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
        $mensaje = $_SESSION['mensaje'] ?? null;
        unset($_SESSION['mensaje']); // Limpia el mensaje después de usarlo.
        require_once __DIR__ . '/../Vista/noticiaAdministrador.php';
    }

    /**
     * Muestra la vista pública de noticias.
     */
    private function ctrMostrarNoticiasPublicas()
    {
        $noticias = $this->modeloNoticia->mdlObtenerNoticias();
        $mensaje = $_SESSION['mensaje'] ?? null;
        unset($_SESSION['mensaje']); // Limpia el mensaje después de usarlo.
        require_once __DIR__ . '/../Vista/noticiaUsuario.php';
    }
}
