<?php

/**
 * @file ControladorNoticia.php
 * @version 2.0
 * @author Samuel Bedoya
 * @brief Controlador principal para la gestión de noticias
 * 
 * Implementa el patrón MVC actuando como intermediario entre la vista y el modelo.
 * Maneja la lógica de negocio, validaciones y flujo de la aplicación.
 * 
 * @architecture
 * - Patrón MVC (Model-View-Controller)
 * - Validación de permisos basada en roles
 * - Sanitización de entradas del usuario
 * - Manejo de subida de archivos con validaciones
 * 
 * @security
 * - Validación de sesión antes de operaciones sensibles
 * - Sanitización con htmlspecialchars() contra XSS
 * - Validación de tipos MIME para archivos
 * - Manejo seguro de rutas de archivo
 * 
 * @package Controlador
 * @dependency ModeloNoticia.php - Capa de acceso a datos
 */

// ============================================================================
// GESTIÓN DE SESIÓN
// Inicia sesión si no está activa. Evita warnings si ya existe una sesión.
// ============================================================================
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Modelo/ModeloNoticia.php';

class ControladorNoticia
{
    /**
     * Instancia del modelo de noticias.
     * Se inicializa en el constructor.
     * 
     * @var ModeloNoticia
     */
    private $modeloNoticia;

    /**
     * Constructor de la clase.
     * Inicializa la dependencia del modelo.
     */
    public function __construct()
    {
        $this->modeloNoticia = new ModeloNoticia();
    }

    // ========================================================================
    // PUNTO DE ENTRADA PRINCIPAL
    // Router que distribuye la lógica según la acción solicitada
    // ========================================================================

    /**
     * Método principal que gestiona todas las operaciones de noticias.
     * 
     * Actúa como router central analizando la acción solicitada y el rol del usuario
     * para determinar qué operación ejecutar y qué vista mostrar.
     * 
     * @workflow
     * 1. Determina la acción del usuario (guardar, eliminar, ver)
     * 2. Valida si hay sesión activa
     * 3. Ejecuta la operación correspondiente según rol y acción
     * 4. Renderiza la vista apropiada
     * 
     * @note Usa texto plano en lugar de hashes MD5 para mayor claridad
     */
    public function ctrGestionarNoticias()
    {
        // Obtiene la acción solicitada (si existe)
        $action = $_POST['action'] ?? '';
        $isLoggedIn = isset($_SESSION["logged"]);

        // Usuarios autenticados: acceso a operaciones CRUD
        if ($isLoggedIn) {
            switch ($action) {
                case 'guardar':
                    $this->ctrGuardarNoticia();
                    break;
                    
                case 'eliminar':
                    $this->ctrEliminarNoticia();
                    break;
                    
                default:
                    // Sin acción específica: mostrar vista según rol
                    if ($_SESSION['user-rol'] == 'Feligres') {
                        $this->ctrMostrarNoticiasPublicas();
                    } else {
                        $this->ctrMostrarAdminNoticias();
                    }
                    break;
            }
        } else {
            // Usuarios no autenticados: solo vista pública
            $this->ctrMostrarNoticiasPublicas();
        }
    }

    // ========================================================================
    // OPERACIONES CRUD
    // ========================================================================

    /**
     * Procesa el guardado (crear o actualizar) de una noticia.
     * 
     * Maneja validación completa de datos, subida de archivos y
     * llamadas al modelo para persistir la información.
     * 
     * @workflow
     * 1. Extrae y sanitiza datos del formulario
     * 2. Valida y procesa subida de imagen (si aplica)
     * 3. Determina si es creación o actualización (por ID)
     * 4. Llama al modelo correspondiente
     * 5. Establece mensaje de feedback en sesión
     * 6. Redirige al listado
     * 
     * @security
     * - Sanitización con htmlspecialchars()
     * - Validación de tipo MIME de imagen
     * - Nombres de archivo únicos con uniqid()
     * - Validación de directorio de destino
     * 
     * @redirect noticias.php con mensaje en sesión
     */
    private function ctrGuardarNoticia()
    {
        // ====================================================================
        // EXTRACCIÓN Y SANITIZACIÓN DE DATOS
        // Obtiene datos del formulario y los limpia contra XSS
        // ====================================================================
        $id = $_POST['id'] ?? null;
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES, 'UTF-8');
        $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
        $id_usuario = $_SESSION['user-id'] ?? null;
        $imagen = $_POST['imagen_actual'] ?? null; // Imagen existente por defecto

        // ====================================================================
        // PROCESAMIENTO DE SUBIDA DE IMAGEN
        // Valida tipo, tamaño y mueve archivo al directorio de destino
        // ====================================================================
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            
            // Validación de tipo MIME (seguridad)
            $tipo_archivo = mime_content_type($_FILES['imagen']['tmp_name']);
            if (!in_array($tipo_archivo, ['image/jpeg', 'image/png', 'image/gif'])) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'error', 
                    'texto' => "El archivo subido no es una imagen válida."
                ];
                header('Location: noticias.php'); 
                exit();
            }

            // Genera nombre único para evitar colisiones
            $nombre_archivo = uniqid() . '-' . basename($_FILES['imagen']['name']);
            $directorio_destino = __DIR__ . '/../assets/img/noticias/' . $nombre_archivo;

            // Crea directorio si no existe
            if (!is_dir(dirname($directorio_destino))) {
                mkdir(dirname($directorio_destino), 0777, true);
            }

            // Mueve archivo desde temp a destino final
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $directorio_destino)) {
                $imagen = 'assets/img/noticias/' . $nombre_archivo;
            } else {
                $_SESSION['mensaje'] = [
                    'tipo' => 'error', 
                    'texto' => "Error al mover el archivo de imagen."
                ];
                header('Location: noticias.php');
                exit();
            }
            
        } elseif (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Manejo de otros errores de subida
            $_SESSION['mensaje'] = [
                'tipo' => 'error', 
                'texto' => $this->getUploadErrorMessage($_FILES['imagen']['error'])
            ];
            header('Location: noticias.php');
            exit();
        }

        // ====================================================================
        // PREPARACIÓN DE DATOS PARA EL MODELO
        // ====================================================================
        $datos = [
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'imagen' => $imagen
        ];

        // ====================================================================
        // DETERMINACIÓN DE OPERACIÓN: CREAR VS ACTUALIZAR
        // Si no hay ID, es una creación. Si hay ID, es actualización.
        // ====================================================================
        if (empty($id)) {
            // Validación adicional para creación: requiere usuario autenticado
            if (empty($id_usuario)) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'error', 
                    'texto' => "Error: No se encontró la sesión de usuario."
                ];
                header('Location: noticias.php');
                exit();
            }
            $datos['id_usuario'] = $id_usuario;
            $respuesta = $this->modeloNoticia->mdlCrearNoticia($datos);
        } else {
            $respuesta = $this->modeloNoticia->mdlActualizarNoticia($id, $datos);
        }

        // ====================================================================
        // FEEDBACK Y REDIRECCIÓN
        // Establece mensaje en sesión y redirige al listado
        // ====================================================================
        $_SESSION['mensaje'] = [
            'tipo' => $respuesta['exito'] ? 'success' : 'error', 
            'texto' => $respuesta['mensaje']
        ];
        $_SESSION['menu-item'] = 'Noticias';
        
        header('Location: noticias.php');
        exit();
    }
    
    /**
     * Traduce códigos de error de subida de PHP a mensajes legibles.
     * 
     * PHP retorna constantes numéricas que no son user-friendly.
     * Esta función las convierte en mensajes comprensibles.
     * 
     * @param int $errorCode Código de error de $_FILES['field']['error']
     * @return string Mensaje descriptivo del error
     * 
     * @see https://www.php.net/manual/en/features.file-upload.errors.php
     */
    private function getUploadErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE: 
                return "El archivo es demasiado grande (servidor).";
            case UPLOAD_ERR_FORM_SIZE: 
                return "El archivo es demasiado grande (formulario).";
            case UPLOAD_ERR_PARTIAL: 
                return "El archivo se subió parcialmente.";
            case UPLOAD_ERR_NO_FILE: 
                return "No se subió ningún archivo.";
            case UPLOAD_ERR_NO_TMP_DIR: 
                return "Falta una carpeta temporal.";
            case UPLOAD_ERR_CANT_WRITE: 
                return "Fallo al escribir el archivo en el disco.";
            case UPLOAD_ERR_EXTENSION: 
                return "Una extensión de PHP detuvo la subida.";
            default: 
                return "Error de subida desconocido.";
        }
    }

    /**
     * Procesa la eliminación (lógica) de una noticia.
     * 
     * Valida el ID y llama al modelo para realizar el borrado lógico.
     * Establece feedback y redirige.
     * 
     * @security Solo usuarios autenticados pueden eliminar (verificado en router)
     * @redirect noticias.php con mensaje en sesión
     */
    private function ctrEliminarNoticia()
    {
        $id = $_POST['id'] ?? null;
        
        if ($id) {
            $respuesta = $this->modeloNoticia->mdlBorrarNoticia($id);
            $_SESSION['mensaje'] = [
                'tipo' => $respuesta['exito'] ? 'success' : 'error', 
                'texto' => $respuesta['mensaje']
            ];
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'error', 
                'texto' => "ID de noticia no proporcionado."
            ];
        }

        $_SESSION['menu-item'] = 'Noticias';
        header('Location: noticias.php');
        exit();
    }

    // ========================================================================
    // RENDERIZADO DE VISTAS
    // ========================================================================

    /**
     * Muestra la vista de administración de noticias.
     * 
     * Carga todas las noticias (con filtro opcional) y renderiza
     * la vista con controles CRUD completos.
     * 
     * @access Solo para usuarios administradores (verificado en router)
     */
    private function ctrMostrarAdminNoticias()
    {
        // Obtiene filtro de búsqueda si existe
        $filtro_busqueda = $_POST['buscar'] ?? '';
        
        // Carga noticias desde el modelo
        $noticias = $this->modeloNoticia->mdlObtenerNoticias($filtro_busqueda);
        
        // Obtiene mensaje de feedback de la sesión y lo limpia
        $mensaje = $_SESSION['mensaje'] ?? null;
        unset($_SESSION['mensaje']);
        
        // Renderiza vista de administrador
        require_once __DIR__ . '/../Vista/noticiaAdministrador.php';
    }

    /**
     * Muestra la vista pública de noticias.
     * 
     * Carga todas las noticias sin filtros de búsqueda y renderiza
     * la vista de solo lectura para usuarios finales.
     * 
     * @access Accesible para cualquier usuario (autenticado o no)
     */
    private function ctrMostrarNoticiasPublicas()
    {
        // Carga todas las noticias disponibles
        $noticias = $this->modeloNoticia->mdlObtenerNoticias();
        
        // Obtiene mensaje de feedback de la sesión y lo limpia
        $mensaje = $_SESSION['mensaje'] ?? null;
        unset($_SESSION['mensaje']);
        
        // Renderiza vista pública
        require_once __DIR__ . '/../Vista/noticiaUsuario.php';
    }
}