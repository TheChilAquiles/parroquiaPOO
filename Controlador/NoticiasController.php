<?php

// ============================================================================
// NoticiasController.php - CON DEBUG PARA AJAX
// ============================================================================

class NoticiasController extends BaseController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloNoticia();
    }

    /**
     * Muestra la lista de noticias (vista unificada)
     */
    public function index()
    {
        // Obtener mensajes de sesión
        $mensaje = null;
        if (isset($_SESSION['mensaje'])) {
            $mensaje = $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }

        // Obtener filtro de búsqueda
        $filtro = $_POST['buscar'] ?? '';
        
        // Obtener noticias desde el modelo
        $noticias = $this->modelo->mdlObtenerNoticias($filtro);

        // Cargar vista unificada
        include_once __DIR__ . '/../Vista/noticias.php';
    }

    /**
     * Crea una nueva noticia
     */
    public function crear()
    {

        $this->requiereAdmin();

        // 🔥 DEBUG: Loguear información de la petición
        Logger::error("========== CREAR NOTICIA DEBUG ==========");
        Logger::error("Es AJAX:", ['info' => ($this->esAjax() ? 'SÍ' : 'NO')]);
        Logger::error("X-Requested-With:", ['info' => ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? 'NO DEFINIDO')]);
        Logger::error("Método:", ['info' => $_SERVER['REQUEST_METHOD']]);
        Logger::error("POST:", ['info' => print_r($_POST, true)]);
        Logger::error("=========================================");

    

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responderError('Método no permitido');
            return;
        }

        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        // Validar campos requeridos
        if (empty($titulo) || empty($descripcion)) {
            $this->responderError('Título y descripción son obligatorios.');
            return;
        }

        // Sanitizar datos
        $titulo = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');

        // Procesar imagen
        $imagen = 'assets/img/noticias/default.jpg';
        try {
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->procesarImagen($_FILES['imagen']);
            }
        } catch (Exception $e) {
            $this->responderError($e->getMessage());
            return;
        }

        // Crear noticia
        $datos = [
            'id_usuario' => $_SESSION['user-id'] ?? null,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'imagen' => $imagen
        ];

        $respuesta = $this->modelo->mdlCrearNoticia($datos);

        // Responder
        if ($respuesta['exito']) {
            $this->responderExito($respuesta['mensaje']);
        } else {
            $this->responderError($respuesta['mensaje']);
        }
    }

    /**
     * Actualiza una noticia existente
     */
    public function actualizar()
    {
        // 🔥 DEBUG
        Logger::error("========== ACTUALIZAR NOTICIA DEBUG ==========");
        Logger::error("Es AJAX:", ['info' => ($this->esAjax() ? 'SÍ' : 'NO')]);
        Logger::error("=============================================");

        if (!$this->tienePermisos()) {
            $this->responderError('No tienes permisos para realizar esta acción.');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responderError('Método no permitido');
            return;
        }

        $id = $_POST['id'] ?? null;
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        // Validar ID
        if (empty($id) || !is_numeric($id)) {
            $this->responderError('ID de noticia inválido.');
            return;
        }

        // Validar campos requeridos
        if (empty($titulo) || empty($descripcion)) {
            $this->responderError('Título y descripción son obligatorios.');
            return;
        }

        // Sanitizar datos
        $titulo = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');

        // Procesar imagen
        $imagen = $_POST['imagen_actual'] ?? 'assets/img/noticias/default.jpg';
        try {
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->procesarImagen($_FILES['imagen']);
            }
        } catch (Exception $e) {
            $this->responderError($e->getMessage());
            return;
        }

        // Actualizar noticia
        $datos = [
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'imagen' => $imagen
        ];

        $respuesta = $this->modelo->mdlActualizarNoticia($id, $datos);

        if ($respuesta['exito']) {
            $this->responderExito($respuesta['mensaje']);
        } else {
            $this->responderError($respuesta['mensaje']);
        }
    }

    /**
     * Elimina una noticia (borrado lógico)
     */
    public function eliminar()
    {
        // 🔥 DEBUG
        Logger::error("========== ELIMINAR NOTICIA DEBUG ==========");
        Logger::error("Es AJAX:", ['info' => ($this->esAjax() ? 'SÍ' : 'NO')]);
        Logger::error("===========================================");

        if (!$this->tienePermisos()) {
            $this->responderError('No tienes permisos para realizar esta acción.');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responderError('Método no permitido');
            return;
        }

        $id = $_POST['id'] ?? null;

        // Validar ID
        if (empty($id) || !is_numeric($id)) {
            $this->responderError('ID de noticia inválido.');
            return;
        }

        // Eliminar noticia
        $respuesta = $this->modelo->mdlBorrarNoticia($id);

        if ($respuesta['exito']) {
            $this->responderExito($respuesta['mensaje']);
        } else {
            $this->responderError($respuesta['mensaje']);
        }
    }

    // ========================================================================
    // MÉTODOS PRIVADOS AUXILIARES
    // ========================================================================

    /**
     * Verifica si el usuario tiene permisos de administración
     */
    private function tienePermisos()
    {
        return isset($_SESSION['user-rol']) && 
               ($_SESSION['user-rol'] === 'Administrador' || $_SESSION['user-rol'] === 'Secretario');
    }

    /**
     * Detecta si la petición es AJAX
     */
    private function esAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Responde con éxito (JSON si es AJAX, redirect si no)
     */
    private function responderExito($mensaje)
    {
        if ($this->esAjax()) {
            // 🔥 IMPORTANTE: Limpiar cualquier output buffer antes
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
            exit(); // ← CRÍTICO: Terminar ejecución aquí
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => $mensaje
            ];
            redirect('noticias');
            
            exit();
        }
    }

    /**
     * Responde con error (JSON si es AJAX, redirect si no)
     */
    private function responderError($mensaje)
    {
        if ($this->esAjax()) {
            // 🔥 IMPORTANTE: Limpiar output buffer
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode([
                'exito' => false,
                'status' => 'error',
                'mensaje' => $mensaje,
                'message' => $mensaje
            ], JSON_UNESCAPED_UNICODE);
            exit(); // ← CRÍTICO
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => $mensaje
            ];
            redirect('noticias');
            exit();
        }
    }

    /**
     * Procesa y valida la imagen subida
     */
    private function procesarImagen($file)
    {
        // Validar tipo de archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        // Usar getimagesize en lugar de mime_content_type (más confiable)
        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            throw new Exception('El archivo no es una imagen válida.');
        }

        $tipoArchivo = $imageInfo['mime'];
        if (!in_array($tipoArchivo, $tiposPermitidos)) {
            throw new Exception('Tipo de archivo no permitido. Solo JPG, PNG, GIF o WEBP.');
        }

        // Validar tamaño (máximo 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('Archivo demasiado grande. Máximo 5MB.');
        }

        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid('noticia_', true) . '.' . strtolower($extension);

        // Directorio de destino
        $directorioDestino = __DIR__ . '/../assets/img/noticias/';
        
        // Crear directorio si no existe
        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0755, true)) {
                throw new Exception('No se pudo crear el directorio de imágenes.');
            }
        }

        $rutaCompleta = $directorioDestino . $nombreArchivo;

        // Mover archivo subido
        if (!move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
            throw new Exception('Error al guardar la imagen.');
        }

        // Retornar ruta relativa
        return 'assets/img/noticias/' . $nombreArchivo;
    }
}