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
            $this->ctrMostrarNoticiasHome();
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
        $id_usuario = $_SESSION['user-id'] ?? null;

        // Obtiene la ruta de la imagen actual del campo oculto del formulario.
        $imagen = $_POST['imagen_actual'] ?? null;

        // Lógica para la subida de imagen.
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Validar que no haya un error de subida real.
            if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => $this->getUploadErrorMessage($_FILES['imagen']['error'])];
                header('Location: #');
                exit();
            }

            // Validar que el archivo sea una imagen.
            $tipo_archivo = mime_content_type($_FILES['imagen']['tmp_name']);
            if (!in_array($tipo_archivo, ['image/jpeg', 'image/png', 'image/gif'])) {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => "El archivo subido no es una imagen válida."];
                header('Location: #');
                exit();
            }

            $nombre_archivo = uniqid() . '-' . basename($_FILES['imagen']['name']);
            $directorio_destino = __DIR__ . '/../assets/img/noticias/' . $nombre_archivo;
            
            if (!is_dir(dirname($directorio_destino))) {
                mkdir(dirname($directorio_destino), 0777, true);
            }

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio_destino)) {
                $imagen = 'assets/img/noticias/' . $nombre_archivo;
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => "Error al mover el archivo de imagen."];
                header('Location: #');
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
            if (empty($id_usuario)) {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => "Error: No se encontró la sesión de usuario."];
                header('Location: #');
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
        header('Location: #');
        exit();
    }

    /**
     * Devuelve el mensaje de error de subida de archivo.
     * @param int $errorCode El código de error de PHP.
     * @return string El mensaje de error correspondiente.
     */
    private function getUploadErrorMessage($errorCode) {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "El archivo es demasiado grande (excede upload_max_filesize).";
            case UPLOAD_ERR_FORM_SIZE:
                return "El archivo es demasiado grande (excede MAX_FILE_SIZE del formulario).";
            case UPLOAD_ERR_PARTIAL:
                return "El archivo se subió parcialmente.";
            case UPLOAD_ERR_NO_FILE:
                return "No se subió ningún archivo.";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Falta una carpeta temporal.";
            case UPLOAD_ERR_CANT_WRITE:
                return "Fallo al escribir el archivo en el disco.";
            case UPLOAD_ERR_EXTENSION:
                return "Una extensión de PHP detuvo la subida del archivo.";
            default:
                return "Error de subida desconocido.";
        }
    }

    /**
     * Elimina una noticia por su ID (borrado lógico).
     *
     * Este método llama al modelo para marcar un registro como eliminado.
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
        header('refresh:0');
        exit();
    }

    /**
     * Muestra la vista de administrador de noticias.
     */
    private function ctrMostrarAdminNoticias()
    {
        // CAMBIO AQUÍ: Capturamos el término de búsqueda de la solicitud POST.
        $filtro_busqueda = $_POST['buscar'] ?? '';
        
        // Pasamos el filtro al modelo.
        $noticias = $this->modeloNoticia->mdlObtenerNoticias($filtro_busqueda);
        
        $mensaje = $_SESSION['mensaje'] ?? null;
        unset($_SESSION['mensaje']);
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

    private function ctrMostrarNoticiasHome()
    {
        $noticias = $this->modeloNoticia->mdlObtenerNoticias();
        $mensaje = $_SESSION['mensaje'] ?? null;
        unset($_SESSION['mensaje']);
        require_once __DIR__ . '/../Vista/home.php';
    }
}