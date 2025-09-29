<?php
// CAMBIO: Es crucial iniciar la sesión al principio del script.
// Si ya lo haces en otro archivo principal que incluye este, puedes omitirlo aquí.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Modelo/ModeloNoticia.php';

class ControladorNoticia
{
    private $modeloNoticia;

    public function __construct()
    {
        $this->modeloNoticia = new ModeloNoticia();
    }

    public function ctrGestionarNoticias()
    {
        // CAMBIO: Se elimina md5() para mayor claridad.
        $action = $_POST['action'] ?? '';
        $isLoggedIn = isset($_SESSION["logged"]);

        if ($isLoggedIn) {
            switch ($action) {
                case 'guardar': // CAMBIO: Se usa texto simple.
                    $this->ctrGuardarNoticia();
                    break;
                case 'eliminar': // CAMBIO: Se usa texto simple.
                    $this->ctrEliminarNoticia();
                    break;
                default:
                    if ($_SESSION['user-rol'] == 'Feligres') {
                        $this->ctrMostrarNoticiasPublicas();
                    } else {
                        $this->ctrMostrarAdminNoticias();
                    }
                    break;
            }
        } else {
            $this->ctrMostrarNoticiasPublicas();
        }
    }

    private function ctrGuardarNoticia()
    {
        // Tu lógica de guardado está muy bien, no necesita cambios mayores.
        $id = $_POST['id'] ?? null;
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
        $id_usuario = $_SESSION['user-id'] ?? null;
        $imagen = $_POST['imagen_actual'] ?? null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $tipo_archivo = mime_content_type($_FILES['imagen']['tmp_name']);
            if (!in_array($tipo_archivo, ['image/jpeg', 'image/png', 'image/gif'])) {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => "El archivo subido no es una imagen válida."];
                // CAMBIO: Redirección estándar. Asumo que la página se llama 'noticias.php' o similar.
                // Si la página tiene otro nombre, ajústalo aquí.
                header('Location: noticias.php'); 
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
                header('Location: noticias.php');
                exit();
            }
        } elseif (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Manejar otros errores de subida
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => $this->getUploadErrorMessage($_FILES['imagen']['error'])];
            header('Location: noticias.php');
            exit();
        }

        $datos = [
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'imagen' => $imagen
        ];

        if (empty($id)) {
            if (empty($id_usuario)) {
                $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => "Error: No se encontró la sesión de usuario."];
                header('Location: noticias.php');
                exit();
            }
            $datos['id_usuario'] = $id_usuario;
            $respuesta = $this->modeloNoticia->mdlCrearNoticia($datos);
        } else {
            $respuesta = $this->modeloNoticia->mdlActualizarNoticia($id, $datos);
        }

        $_SESSION['mensaje'] = ['tipo' => $respuesta['exito'] ? 'success' : 'error', 'texto' => $respuesta['mensaje']];
        $_SESSION['menu-item'] = 'Noticias';
        
        // CAMBIO: Redirección estándar y robusta.
        header('Location: noticias.php');
        exit();
    }
    
    // La función getUploadErrorMessage está perfecta, no necesita cambios.
    private function getUploadErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE: return "El archivo es demasiado grande (servidor).";
            case UPLOAD_ERR_FORM_SIZE: return "El archivo es demasiado grande (formulario).";
            case UPLOAD_ERR_PARTIAL: return "El archivo se subió parcialmente.";
            case UPLOAD_ERR_NO_FILE: return "No se subió ningún archivo.";
            case UPLOAD_ERR_NO_TMP_DIR: return "Falta una carpeta temporal.";
            case UPLOAD_ERR_CANT_WRITE: return "Fallo al escribir el archivo en el disco.";
            case UPLOAD_ERR_EXTENSION: return "Una extensión de PHP detuvo la subida.";
            default: return "Error de subida desconocido.";
        }
    }

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
        // CAMBIO: Se reemplaza refresh:0 por una redirección estándar.
        header('Location: noticias.php');
        exit();
    }

    private function ctrMostrarAdminNoticias()
    {
        $filtro_busqueda = $_POST['buscar'] ?? '';
        $noticias = $this->modeloNoticia->mdlObtenerNoticias($filtro_busqueda);
        $mensaje = $_SESSION['mensaje'] ?? null;
        unset($_SESSION['mensaje']);
        require_once __DIR__ . '/../Vista/noticiaAdministrador.php';
    }

    private function ctrMostrarNoticiasPublicas()
    {
        $noticias = $this->modeloNoticia->mdlObtenerNoticias();
        $mensaje = $_SESSION['mensaje'] ?? null;
        unset($_SESSION['mensaje']);
        require_once __DIR__ . '/../Vista/noticiaUsuario.php';
    }
}