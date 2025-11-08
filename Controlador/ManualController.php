<?php
/**
 * ManualController.php
 * 
 * Controlador para gestionar el Manual de Usuario del Sistema Parroquial
 * 
 * @author Sistema Parroquial
 * @version 1.0
 * @date Octubre 2025
 * 
 * Funcionalidades:
 * - Renderiza la vista del manual interactivo
 * - Gestiona las secciones documentadas
 * - Control de acceso (opcional para diferentes roles)
 */

class ManualController {
    
    /**
     * Muestra la vista principal del manual de usuario
     * 
     * @return void
     */
    public function index() {
        // Incluir la vista del manual
        require_once 'Vista/manual.php';
    }

    /**
     * Obtiene las secciones del manual con su información
     * 
     * @return array Array con las secciones del manual
     */
    public function getSecciones() {
        return [
            [
                'id' => 'home',
                'nombre' => 'Página de Inicio',
                'icono' => 'home',
                'descripcion' => 'Vista principal del sitio web',
                'componentes' => [
                    'Hero Section',
                    'Sobre Nosotros',
                    'Horarios',
                    'Servicios',
                    'Contacto'
                ]
            ],
            [
                'id' => 'historia',
                'nombre' => 'Nuestra Historia',
                'icono' => 'history',
                'descripcion' => 'Línea de tiempo interactiva',
                'componentes' => [
                    'Timeline 1996-2023',
                    'Espacios Sagrados',
                    'Ministerios',
                    'Equipo Pastoral'
                ]
            ],
            [
                'id' => 'noticias',
                'nombre' => 'Sistema de Noticias',
                'icono' => 'article',
                'descripcion' => 'Gestión completa de publicaciones',
                'componentes' => [
                    'Crear Noticias',
                    'Editar/Eliminar',
                    'Búsqueda',
                    'Gestión de Imágenes'
                ]
            ],
            [
                'id' => 'contacto',
                'nombre' => 'Información de Contacto',
                'icono' => 'phone',
                'descripcion' => 'Facebook y datos de ubicación',
                'componentes' => [
                    'Enlace Facebook',
                    'Google Maps',
                    'Llamada Directa',
                    'Copiar Teléfono'
                ]
            ],
            [
                'id' => 'dashboard',
                'nombre' => 'Dashboard Administrativo',
                'icono' => 'dashboard',
                'descripcion' => 'Panel de control y estadísticas',
                'componentes' => [
                    'Estadísticas en tiempo real',
                    'Gráficos Chart.js',
                    'Métricas de usuarios',
                    'Reportes visuales'
                ]
            ],
            [
                'id' => 'grupos',
                'nombre' => 'Gestión de Grupos',
                'icono' => 'groups',
                'descripcion' => 'Administración de grupos parroquiales',
                'componentes' => [
                    'Crear Grupos',
                    'Ver Detalles',
                    'Editar/Eliminar',
                    'Gestionar Miembros'
                ]
            ],
            [
                'id' => 'reportes',
                'nombre' => 'Sistema de Reportes',
                'icono' => 'assessment',
                'descripcion' => 'Gestión avanzada con pagos integrados',
                'componentes' => [
                    'CRUD Completo',
                    'Filtros por estado',
                    'Búsqueda avanzada',
                    'Estadísticas automáticas',
                    'Ver certificados'
                ]
            ],
            [
                'id' => 'pagos',
                'nombre' => 'Gestión de Pagos',
                'icono' => 'payments',
                'descripcion' => 'Control financiero con diseño premium',
                'componentes' => [
                    'CRUD de pagos',
                    '5 Tipos de pago',
                    'Estadísticas financieras',
                    'Validación de fechas',
                    'Diseño gradiente colorido'
                ]
            ],
            [
                'id' => 'auth',
                'nombre' => 'Sistema de Autenticación',
                'icono' => 'lock',
                'descripcion' => 'Login y Registro seguros',
                'componentes' => [
                    'Login con validación',
                    'Registro de usuarios',
                    'Validación JavaScript',
                    'Manejo de errores PHP',
                    'Rutas modernas (?route=)'
                ]
            ]
        ];
    }

    /**
     * Obtiene información detallada de una sección específica
     * 
     * @param string $seccionId ID de la sección
     * @return array|null Información de la sección o null si no existe
     */
    public function getSeccionDetalle($seccionId) {
        $secciones = $this->getSecciones();
        
        foreach ($secciones as $seccion) {
            if ($seccion['id'] === $seccionId) {
                return $seccion;
            }
        }
        
        return null;
    }

    /**
     * Registra la visualización de una sección (analytics)
     * 
     * @param string $seccionId ID de la sección vista
     * @param int $userId ID del usuario (opcional)
     * @return bool True si se registró correctamente
     */
    public function registrarVisualizacion($seccionId, $userId = null) {
        try {
            // Aquí podrías registrar en base de datos para analytics
            // Por ahora solo retornamos true
            
            // Ejemplo de log simple
            Logger::error("Manual - Sección vista: $seccionId por usuario:", ['info' => ($userId ?? 'anónimo')]);
            
            return true;
        } catch (Exception $e) {
            Logger::error("Error al registrar visualización:", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Genera estadísticas de uso del manual
     * 
     * @return array Estadísticas de visualizaciones
     */
    public function getEstadisticasManual() {
        // Esta función podría conectarse a la BD para obtener datos reales
        return [
            'total_visitas' => 0,
            'seccion_mas_vista' => 'home',
            'tiempo_promedio' => '5 minutos',
            'usuarios_unicos' => 0
        ];
    }

    /**
     * Busca contenido en el manual
     * 
     * @param string $query Término de búsqueda
     * @return array Resultados de la búsqueda
     */
    public function buscar($query) {
        $secciones = $this->getSecciones();
        $resultados = [];
        
        $query = strtolower($query);
        
        foreach ($secciones as $seccion) {
            // Buscar en nombre y descripción
            if (
                stripos($seccion['nombre'], $query) !== false ||
                stripos($seccion['descripcion'], $query) !== false
            ) {
                $resultados[] = $seccion;
            }
        }
        
        return $resultados;
    }

    /**
     * Verifica si el usuario tiene acceso al manual
     * (Opcional - puede implementarse control de acceso por roles)
     * 
     * @param string $rol Rol del usuario
     * @return bool True si tiene acceso
     */
    public function verificarAcceso($rol = 'publico') {
        // El manual es público por defecto
        // Puedes agregar lógica para secciones privadas
        
        $seccionesPublicas = ['home', 'historia', 'noticias', 'contacto'];
        $seccionesAdmin = ['dashboard', 'grupos'];
        
        if ($rol === 'Administrador' || $rol === 'Secretario') {
            return true; // Acceso total
        }
        
        // Para usuarios públicos, solo secciones públicas
        return true; // Por ahora todo es público
    }

    /**
     * Obtiene el índice de navegación del manual
     * 
     * @return array Estructura de navegación
     */
    public function getIndiceNavegacion() {
        $secciones = $this->getSecciones();
        $indice = [];
        
        foreach ($secciones as $seccion) {
            $indice[] = [
                'id' => $seccion['id'],
                'nombre' => $seccion['nombre'],
                'icono' => $seccion['icono']
            ];
        }
        
        return $indice;
    }

    /**
     * Exporta el manual a formato JSON (para API)
     * 
     * @return string JSON con toda la información del manual
     */
    public function exportarJSON() {
        $data = [
            'titulo' => 'Manual de Usuario - Sistema Parroquial',
            'version' => '1.0',
            'fecha_actualizacion' => date('Y-m-d'),
            'secciones' => $this->getSecciones()
        ];
        
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Obtiene la versión actual del manual
     * 
     * @return array Información de versión
     */
    public function getVersion() {
        return [
            'numero' => '1.0',
            'fecha' => 'Octubre 2025',
            'autor' => 'Sistema Parroquial',
            'cambios' => [
                'Versión inicial del manual',
                'Documentación de 6 secciones principales',
                'Diseño interactivo y responsive',
                'Integración con sistema parroquial'
            ]
        ];
    }

    /**
     * Maneja las peticiones AJAX para el manual
     * 
     * @return void
     */
    public function handleAjax() {
        if (!isset($_POST['action'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Acción no especificada']);
            return;
        }

        $action = $_POST['action'];
        
        switch ($action) {
            case 'get_seccion':
                $seccionId = $_POST['seccion_id'] ?? null;
                if ($seccionId) {
                    $detalle = $this->getSeccionDetalle($seccionId);
                    echo json_encode($detalle);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'ID de sección no proporcionado']);
                }
                break;
                
            case 'registrar_vista':
                $seccionId = $_POST['seccion_id'] ?? null;
                $userId = $_SESSION['user-id'] ?? null;
                $resultado = $this->registrarVisualizacion($seccionId, $userId);
                echo json_encode(['success' => $resultado]);
                break;
                
            case 'buscar':
                $query = $_POST['query'] ?? '';
                $resultados = $this->buscar($query);
                echo json_encode($resultados);
                break;
                
            case 'exportar':
                header('Content-Type: application/json');
                header('Content-Disposition: attachment; filename="manual_parroquial.json"');
                echo $this->exportarJSON();
                break;
                
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Acción no encontrada']);
        }
    }
}

/**
 * ============================================================================
 * PUNTO DE ENTRADA PRINCIPAL
 * ============================================================================
 * 
 * Este bloque se ejecuta cuando se accede directamente al controlador
 * o cuando es llamado desde el router principal (index.php)
 */

// Si se accede directamente al controlador
if (basename($_SERVER['PHP_SELF']) === 'ManualController.php') {
    session_start();
    
    $controller = new ManualController();
    
    // Verificar si es una petición AJAX
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {
        $controller->handleAjax();
    } else {
        // Mostrar la vista normal del manual
        $controller->index();
    }
}

/**
 * ============================================================================
 * NOTAS DE USO
 * ============================================================================
 * 
 * 1. INTEGRACIÓN CON INDEX.PHP PRINCIPAL:
 * 
 * En tu index.php principal, agrega esta opción al switch del menú:
 * 
 * case 'Manual':
 *     require_once 'Controlador/ManualController.php';
 *     $controller = new ManualController();
 *     $controller->index();
 *     break;
 * 
 * 
 * 2. ESTRUCTURA DE ARCHIVOS NECESARIA:
 * 
 * - Controlador/ManualController.php (este archivo)
 * - Vista/manual.php (el HTML completo del manual)
 * 
 * 
 * 3. USO DE LAS FUNCIONES:
 * 
 * // Obtener todas las secciones
 * $secciones = $controller->getSecciones();
 * 
 * // Buscar en el manual
 * $resultados = $controller->buscar('noticias');
 * 
 * // Exportar a JSON
 * $json = $controller->exportarJSON();
 * 
 * 
 * 4. PETICIONES AJAX (Opcional):
 * 
 * // JavaScript para obtener detalles de una sección
 * fetch('Controlador/ManualController.php', {
 *     method: 'POST',
 *     headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
 *     body: 'ajax=true&action=get_seccion&seccion_id=noticias'
 * })
 * .then(res => res.json())
 * .then(data => console.log(data));
 * 
 * 
 * 5. AGREGAR AL MENÚ DE NAVEGACIÓN:
 * 
 * En tu navbar/menu principal:
 * 
 * <form method="POST" action="index.php">
 *     <input type="hidden" name="menu-item" value="Manual">
 *     <button type="submit">
 *         <span class="material-icons">menu_book</span>
 *         Manual de Usuario
 *     </button>
 * </form>
 * 
 * 
 * 6. ANALYTICS (Opcional):
 * 
 * Para registrar qué secciones son más vistas, puedes crear una tabla:
 * 
 * CREATE TABLE manual_analytics (
 *     id INT AUTO_INCREMENT PRIMARY KEY,
 *     seccion_id VARCHAR(50),
 *     usuario_id INT NULL,
 *     fecha_vista TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 * );
 * 
 * Y luego implementar la función registrarVisualizacion() para guardar en BD.
 */