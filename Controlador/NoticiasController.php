<?php

// ============================================================================
// NoticiasController.php - REFACTORIZADO
// ============================================================================

class NoticiasController
{
    private $modelo;

    public function __construct()
    {
        require_once __DIR__ . '/../Modelo/ModeloNoticia.php';
        $this->modelo = new ModeloNoticia();
    }

    /**
     * Muestra la lista de noticias según el rol del usuario
     */
    public function index()
    {
        $rol = $_SESSION['user-rol'] ?? 'Feligres';
        
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

        // Decidir qué vista cargar según el rol
        if ($rol === 'Administrador' || $rol === 'Secretario') {
            include_once __DIR__ . '/../Vista/noticiaAdministrador.php';
        } else {
            include_once __DIR__ . '/../Vista/noticiaUsuario.php';
        }
    }

    /**
     * Crea una nueva noticia (solo administradores)
     */
    public function crear()
    {
        // Verificar permisos
        if (!isset($_SESSION['user-rol']) || 
            ($_SESSION['user-rol'] !== 'Administrador' && $_SESSION['user-rol'] !== 'Secretario')) {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción.';
            header('Location: ?route=noticias');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?route=noticias');
            return;
        }

        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        // Validar campos requeridos
        if (empty($titulo) || empty($descripcion)) {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Título y descripción son obligatorios.'
            ];
            header('Location: ?route=noticias');
            exit();
        }

        // Sanitizar datos
        $titulo = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');

        // Procesar imagen
        $imagen = null;
        try {
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->procesarImagen($_FILES['imagen']);
            } else {
                // Imagen por defecto si no se subió ninguna
                $imagen = 'assets/img/noticias/default.jpg';
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => $e->getMessage()
            ];
            header('Location: ?route=noticias');
            exit();
        }

        // Crear noticia
        $datos = [
            'id_usuario' => $_SESSION['user-id'] ?? null,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'imagen' => $imagen
        ];

        $respuesta = $this->modelo->mdlCrearNoticia($datos);

        $_SESSION['mensaje'] = [
            'tipo' => $respuesta['exito'] ? 'success' : 'error',
            'texto' => $respuesta['mensaje']
        ];

        header('Location: ?route=noticias');
        exit();
    }

    /**
     * Actualiza una noticia existente
     */
    public function actualizar()
    {
        // Verificar permisos
        if (!isset($_SESSION['user-rol']) || 
            ($_SESSION['user-rol'] !== 'Administrador' && $_SESSION['user-rol'] !== 'Secretario')) {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción.';
            header('Location: ?route=noticias');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?route=noticias');
            return;
        }

        $id = $_POST['id'] ?? null;
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        // Validar ID
        if (empty($id) || !is_numeric($id)) {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'ID de noticia inválido.'
            ];
            header('Location: ?route=noticias');
            exit();
        }

        // Validar campos requeridos
        if (empty($titulo) || empty($descripcion)) {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Título y descripción son obligatorios.'
            ];
            header('Location: ?route=noticias');
            exit();
        }

        // Sanitizar datos
        $titulo = htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');

        // Procesar imagen (mantener la actual si no se sube nueva)
        $imagen = $_POST['imagen_actual'] ?? null;
        try {
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagen = $this->procesarImagen($_FILES['imagen']);
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => $e->getMessage()
            ];
            header('Location: ?route=noticias');
            exit();
        }

        // Actualizar noticia
        $datos = [
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'imagen' => $imagen
        ];

        $respuesta = $this->modelo->mdlActualizarNoticia($id, $datos);

        $_SESSION['mensaje'] = [
            'tipo' => $respuesta['exito'] ? 'success' : 'error',
            'texto' => $respuesta['mensaje']
        ];

        header('Location: ?route=noticias');
        exit();
    }

    /**
     * Elimina una noticia (borrado lógico)
     */
    public function eliminar()
    {
        // Verificar permisos
        if (!isset($_SESSION['user-rol']) || 
            ($_SESSION['user-rol'] !== 'Administrador' && $_SESSION['user-rol'] !== 'Secretario')) {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción.';
            header('Location: ?route=noticias');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?route=noticias');
            return;
        }

        $id = $_POST['id'] ?? null;

        // Validar ID
        if (empty($id) || !is_numeric($id)) {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'ID de noticia inválido.'
            ];
            header('Location: ?route=noticias');
            exit();
        }

        // Eliminar noticia (soft delete)
        $respuesta = $this->modelo->mdlBorrarNoticia($id);

        $_SESSION['mensaje'] = [
            'tipo' => $respuesta['exito'] ? 'success' : 'error',
            'texto' => $respuesta['mensaje']
        ];

        header('Location: ?route=noticias');
        exit();
    }

    /**
     * Procesa y valida la imagen subida
     * 
     * @param array $file Array del archivo subido ($_FILES['imagen'])
     * @return string Ruta de la imagen guardada
     * @throws Exception Si hay error en la validación o guardado
     */
    private function procesarImagen($file)
    {
        // Validar tipo de archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $tipoArchivo = mime_content_type($file['tmp_name']);

        if (!in_array($tipoArchivo, $tiposPermitidos)) {
            throw new Exception('Tipo de archivo no permitido. Solo JPG, PNG, GIF o WEBP.');
        }

        // Validar tamaño (máximo 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('Archivo demasiado grande. Máximo 5MB.');
        }

        // Generar nombre único para evitar sobrescrituras
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid('noticia_', true) . '.' . $extension;

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

        // Retornar ruta relativa para guardar en BD
        return 'assets/img/noticias/' . $nombreArchivo;
    }

    /**
     * Método auxiliar para obtener una noticia por ID (para futuras funcionalidades)
     */
    public function obtener($id)
    {
        if (!is_numeric($id)) {
            return null;
        }

        // Este método requeriría agregar mdlObtenerNoticiaPorId() en el modelo
        // Por ahora retornamos null
        return null;
    }
}