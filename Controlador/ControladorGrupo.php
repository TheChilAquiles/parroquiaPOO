<?php

/**
 * @file GrupoController.php
 * @version 2.0
 * @author Samuel Bedoya
 * @brief Controlador para la gestión completa de grupos parroquiales
 * 
 * Implementa el patrón MVC actuando como intermediario entre las vistas
 * y los modelos de grupos y usuarios. Maneja toda la lógica de negocio
 * relacionada con grupos parroquiales, membresías y roles.
 * 
 * @architecture
 * - Patrón MVC (Model-View-Controller)
 * - Router centralizado con switch-case
 * - Validación de métodos HTTP (GET/POST)
 * - Redirección con mensajes en sesión
 * - Manejo robusto de excepciones
 * 
 * @security
 * - Validación de métodos HTTP permitidos
 * - Sanitización de IDs y parámetros
 * - Verificación de existencia de recursos
 * - Control de acceso implícito (requiere sesión)
 * 
 * @package Controlador
 * @dependency ModeloGrupo.php - Capa de acceso a datos de grupos
 * @dependency ModeloUsuario.php - Capa de acceso a datos de usuarios
 */

// ============================================================================
// DEPENDENCIAS DEL CONTROLADOR
// ============================================================================
require_once(__DIR__ . '/../Modelo/ModeloGrupo.php');
require_once(__DIR__ . '/../Modelo/ModeloUsuario.php');

class GrupoController
{
    /**
     * Instancia del modelo de grupos.
     * 
     * @var ModeloGrupo
     */
    private $modeloGrupo;

    /**
     * Instancia del modelo de usuarios.
     * 
     * @var ModeloUsuario
     */
    private $modeloUsuario;

    /**
     * Constructor de la clase.
     * Inicializa las dependencias de los modelos.
     */
    public function __construct()
    {
        $this->modeloGrupo = new ModeloGrupo();
        $this->modeloUsuario = new ModeloUsuario();
    }
    
    // ========================================================================
    // ROUTER PRINCIPAL
    // Punto de entrada que distribuye las acciones según parámetros
    // ========================================================================

    /**
     * Método principal que gestiona todas las acciones relacionadas con grupos.
     * 
     * Actúa como router central analizando la acción solicitada y delegando
     * a los métodos específicos correspondientes.
     * 
     * @workflow
     * 1. Determina el método HTTP (GET/POST)
     * 2. Extrae y valida la acción solicitada
     * 3. Ejecuta el método correspondiente
     * 4. Por defecto, muestra el listado de grupos
     * 
     * @security
     * - Solo permite ciertas acciones por GET (visualización)
     * - Operaciones modificadoras requieren POST
     */
    public function ctrlGestionarGrupos()
    {
        // ====================================================================
        // DETERMINACIÓN DE ACCIÓN SEGÚN MÉTODO HTTP
        // GET: Solo para navegación y visualización
        // POST: Para operaciones que modifican datos
        // ====================================================================
        $action = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
            // Whitelist de acciones permitidas por GET (solo visualización)
            $allowedGetActions = ['ver', 'crear', 'editar', 'eliminar'];
            if (in_array($_GET['action'], $allowedGetActions)) {
                $action = $_GET['action'];
            }
        }

        // ====================================================================
        // SWITCH PRINCIPAL - DELEGACIÓN A MÉTODOS ESPECÍFICOS
        // ====================================================================
        switch ($action) {
            case 'ver':
                $this->verDetalle();
                break;

            case 'crear':
                $this->crearGrupo();
                break;

            case 'editar':
                $this->editarGrupo();
                break;

            case 'eliminar':
                $this->eliminarGrupo();
                break;
            
            case 'agregar_miembro':
                $this->agregarMiembro();
                break;
                
            case 'eliminar_miembro':
                $this->eliminarMiembro();
                break;

            case 'actualizar_rol':
                $this->actualizarRolMiembro();
                break;

            case 'restaurar':
                $this->restaurarGrupo();
                break;

            case 'historial':
                $this->verHistorialEliminados();
                break;

            default:
                // Sin acción o acción no reconocida: mostrar listado
                $this->listarGrupos();
                break;
        }
    }

    // ========================================================================
    // OPERACIONES CRUD DE GRUPOS
    // ========================================================================

    /**
     * Muestra la lista principal de grupos activos.
     * 
     * Carga todos los grupos no eliminados y renderiza la vista principal.
     */
    private function listarGrupos()
    {
        try {
            $grupos = $this->modeloGrupo->mdlListarGrupos();
            include_once(__DIR__ . '/../Vista/grupos.php');
        } catch (Exception $e) {
            $this->manejarError("Error al cargar los grupos: " . $e->getMessage());
        }
    }

    /**
     * Muestra el detalle completo de un grupo específico.
     * 
     * Incluye información del grupo, lista de miembros, roles disponibles
     * y usuarios que pueden ser agregados.
     * 
     * @workflow
     * 1. Obtiene y valida el ID del grupo (POST o GET)
     * 2. Verifica que el grupo existe
     * 3. Carga datos relacionados (miembros, roles, usuarios)
     * 4. Renderiza vista de detalle
     */
    private function verDetalle()
    {
        $grupo_id = null;
        
        // Obtener ID desde POST o GET (para navegación directa)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grupo_id'])) {
            $grupo_id = (int)$_POST['grupo_id'];
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $grupo_id = (int)$_GET['id'];
        }
        
        // Validación de ID
        if (!$grupo_id || $grupo_id <= 0) {
            $this->redirigirConMensaje('ID de grupo inválido.', 'error');
            return;
        }

        try {
            $grupo = $this->modeloGrupo->mdlObtenerGrupoPorId($grupo_id);
            
            if (!$grupo) {
                $this->redirigirConMensaje('Grupo no encontrado.', 'error');
                return;
            }
            
            // Cargar datos relacionados necesarios para la vista
            $miembros = $this->modeloGrupo->mdlListarMiembrosGrupo($grupo_id);
            $rolesGrupo = $this->modeloGrupo->mdlListarRolesGrupo();
            $usuariosDisponibles = $this->modeloGrupo->mdlListarUsuariosDisponibles();
            
            include_once(__DIR__ . '/../Vista/grupoDetalle.php');
        } catch (Exception $e) {
            $this->manejarError("Error al cargar el detalle del grupo: " . $e->getMessage());
        }
    }

    /**
     * Maneja la creación de un nuevo grupo parroquial.
     * 
     * Si es GET: Muestra el formulario de creación
     * Si es POST: Procesa la creación del grupo
     * 
     * @validations
     * - Nombre no vacío
     * - Nombre único (validado en modelo)
     */
    private function crearGrupo()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // ================================================================
            // PROCESAMIENTO DE CREACIÓN
            // ================================================================
            if (!isset($_POST['nombre_grupo']) || empty(trim($_POST['nombre_grupo']))) {
                $this->redirigirConMensaje('El nombre del grupo es obligatorio.', 'error');
                return;
            }

            try {
                $nombre_grupo = trim($_POST['nombre_grupo']);
                $resultado = $this->modeloGrupo->mdlCrearGrupo($nombre_grupo);
                
                if ($resultado) {
                    $this->redirigirConMensaje('Grupo creado con éxito.', 'success');
                } else {
                    $this->redirigirConMensaje('Error al crear el grupo. Es posible que ya exista un grupo con ese nombre.', 'error');
                }
            } catch (Exception $e) {
                $this->manejarError("Error al crear el grupo: " . $e->getMessage());
            }
        } else {
            // ================================================================
            // RENDERIZADO DE FORMULARIO
            // ================================================================
            include_once(__DIR__ . '/../Vista/grupoFormulario.php');
        }
    }

    /**
     * Maneja la edición de un grupo existente.
     * 
     * Si es GET: Muestra el formulario de edición con datos precargados
     * Si es POST con nombre: Procesa la actualización
     * 
     * @validations
     * - ID válido y grupo existe
     * - Nombre no vacío
     * - Nombre único (excepto el mismo grupo)
     */
    private function editarGrupo()
    {
        $grupo_id = null;
        
        // Obtener ID desde POST o GET
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grupo_id'])) {
            $grupo_id = (int)$_POST['grupo_id'];
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $grupo_id = (int)$_GET['id'];
        }
        
        if (!$grupo_id || $grupo_id <= 0) {
            $this->redirigirConMensaje('ID de grupo inválido.', 'error');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_grupo'])) {
            // ================================================================
            // PROCESAMIENTO DE EDICIÓN
            // ================================================================
            if (empty(trim($_POST['nombre_grupo']))) {
                $this->redirigirConMensaje('El nombre del grupo es obligatorio.', 'error');
                return;
            }

            try {
                $nombre_grupo = trim($_POST['nombre_grupo']);
                $resultado = $this->modeloGrupo->mdlActualizarGrupo($grupo_id, $nombre_grupo);
                
                if ($resultado) {
                    $this->redirigirConMensaje('Grupo actualizado con éxito.', 'success');
                } else {
                    $this->redirigirConMensaje('Error al actualizar el grupo. Es posible que ya exista un grupo con ese nombre.', 'error');
                }
            } catch (Exception $e) {
                $this->manejarError("Error al actualizar el grupo: " . $e->getMessage());
            }
        } else {
            // ================================================================
            // RENDERIZADO DE FORMULARIO DE EDICIÓN
            // ================================================================
            try {
                $grupo = $this->modeloGrupo->mdlObtenerGrupoPorId($grupo_id);
                if (!$grupo) {
                    $this->redirigirConMensaje('Grupo no encontrado.', 'error');
                    return;
                }
                include_once(__DIR__ . '/../Vista/grupoFormulario.php');
            } catch (Exception $e) {
                $this->manejarError("Error al cargar el grupo: " . $e->getMessage());
            }
        }
    }

    /**
     * Maneja la eliminación lógica de un grupo.
     * 
     * Si viene por GET: Muestra página de confirmación
     * Si viene por POST con confirmación: Ejecuta la eliminación
     * 
     * @note Elimina el grupo Y todas sus relaciones de miembros (soft delete)
     * @security Requiere confirmación explícita para prevenir eliminaciones accidentales
     */
    private function eliminarGrupo()
    {
        $grupo_id = null;
        
        // Obtener ID desde POST o GET
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grupo_id'])) {
            $grupo_id = (int)$_POST['grupo_id'];
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $grupo_id = (int)$_GET['id'];
        }
        
        if (!$grupo_id || $grupo_id <= 0) {
            $this->redirigirConMensaje('ID de grupo inválido.', 'error');
            return;
        }

        try {
            // Verificar que el grupo existe antes de eliminar
            $grupo = $this->modeloGrupo->mdlObtenerGrupoPorId($grupo_id);
            if (!$grupo) {
                $this->redirigirConMensaje('Grupo no encontrado.', 'error');
                return;
            }

            // Confirmar eliminación si viene por POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar_eliminacion'])) {
                $resultado = $this->modeloGrupo->mdlEliminarGrupo($grupo_id);
                
                if ($resultado) {
                    $this->redirigirConMensaje('Grupo eliminado con éxito.', 'success');
                } else {
                    $this->redirigirConMensaje('Error al eliminar el grupo.', 'error');
                }
            } else {
                // Mostrar página de confirmación
                include_once(__DIR__ . '/../Vista/grupoConfirmarEliminacion.php');
            }
        } catch (Exception $e) {
            $this->manejarError("Error al eliminar el grupo: " . $e->getMessage());
        }
    }

    // ========================================================================
    // GESTIÓN DE MEMBRESÍAS
    // ========================================================================

    /**
     * Agrega un miembro a un grupo con un rol específico.
     * 
     * @requires POST con grupo_id, usuario_id, rol_id
     * 
     * @validations
     * - Método POST
     * - Todos los parámetros presentes
     * - IDs válidos y positivos
     * - Validaciones adicionales en modelo (existencia, duplicados)
     */
    private function agregarMiembro()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirigirConMensaje('Método no permitido.', 'error');
            return;
        }

        if (!isset($_POST['grupo_id'], $_POST['usuario_id'], $_POST['rol_id'])) {
            $this->redirigirConMensaje('Datos incompletos para agregar miembro.', 'error');
            return;
        }

        try {
            $grupo_id = (int)$_POST['grupo_id'];
            $usuario_id = (int)$_POST['usuario_id'];
            $rol_id = (int)$_POST['rol_id'];

            // Validaciones de rango
            if ($grupo_id <= 0 || $usuario_id <= 0 || $rol_id <= 0) {
                $this->redirigirConMensajeConGrupo('IDs inválidos.', 'error', $grupo_id);
                return;
            }

            $resultado = $this->modeloGrupo->mdlAgregarMiembro($grupo_id, $usuario_id, $rol_id);
            
            if ($resultado) {
                $this->redirigirConMensajeConGrupo('Miembro agregado con éxito.', 'success', $grupo_id);
            } else {
                $this->redirigirConMensajeConGrupo('Error al agregar miembro. Es posible que ya esté en el grupo.', 'error', $grupo_id);
            }
        } catch (Exception $e) {
            $this->manejarError("Error al agregar miembro: " . $e->getMessage());
        }
    }
                
    /**
     * Elimina lógicamente un miembro de un grupo.
     * 
     * @requires POST con grupo_id, usuario_id
     * @pattern Soft Delete - No elimina físicamente, marca fecha de eliminación
     */
    private function eliminarMiembro()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirigirConMensaje('Método no permitido.', 'error');
            return;
        }

        if (!isset($_POST['grupo_id'], $_POST['usuario_id']) || 
            !is_numeric($_POST['grupo_id']) || !is_numeric($_POST['usuario_id'])) {
            $this->redirigirConMensaje('Parámetros inválidos.', 'error');
            return;
        }

        try {
            $grupo_id = (int)$_POST['grupo_id'];
            $usuario_id = (int)$_POST['usuario_id'];

            $resultado = $this->modeloGrupo->mdlEliminarMiembro($grupo_id, $usuario_id);
            
            if ($resultado) {
                $this->redirigirConMensajeConGrupo('Miembro eliminado con éxito.', 'success', $grupo_id);
            } else {
                $this->redirigirConMensajeConGrupo('Error al eliminar miembro.', 'error', $grupo_id);
            }
        } catch (Exception $e) {
            $this->manejarError("Error al eliminar miembro: " . $e->getMessage());
        }
    }

    /**
     * Actualiza el rol de un miembro en el grupo.
     * 
     * Implementación: Elimina la relación actual y crea una nueva con el nuevo rol.
     * Esto mantiene el historial de auditoría con las fechas de eliminación.
     * 
     * @requires POST con grupo_id, usuario_id, nuevo_rol_id
     * 
     * @note Operación atómica: si falla la re-inserción, el miembro queda eliminado
     * @todo Considerar implementar transacción o método dedicado en el modelo
     */
    private function actualizarRolMiembro()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirigirConMensaje('Método no permitido.', 'error');
            return;
        }

        if (!isset($_POST['grupo_id'], $_POST['usuario_id'], $_POST['nuevo_rol_id'])) {
            $this->redirigirConMensaje('Datos incompletos.', 'error');
            return;
        }

        try {
            $grupo_id = (int)$_POST['grupo_id'];
            $usuario_id = (int)$_POST['usuario_id'];
            $nuevo_rol_id = (int)$_POST['nuevo_rol_id'];

            // Estrategia: Eliminar y volver a agregar con el nuevo rol
            $elimino = $this->modeloGrupo->mdlEliminarMiembro($grupo_id, $usuario_id);
            if ($elimino) {
                $resultado = $this->modeloGrupo->mdlAgregarMiembro($grupo_id, $usuario_id, $nuevo_rol_id);
                
                if ($resultado) {
                    $this->redirigirConMensajeConGrupo('Rol actualizado con éxito.', 'success', $grupo_id);
                } else {
                    $this->redirigirConMensajeConGrupo('Error al actualizar el rol.', 'error', $grupo_id);
                }
            } else {
                $this->redirigirConMensajeConGrupo('Error al actualizar el rol.', 'error', $grupo_id);
            }
        } catch (Exception $e) {
            $this->manejarError("Error al actualizar rol: " . $e->getMessage());
        }
    }

    // ========================================================================
    // AUDITORÍA Y RECUPERACIÓN
    // ========================================================================

    /**
     * Restaura un grupo eliminado lógicamente.
     * 
     * @requires POST con grupo_id
     * 
     * @validations
     * - Grupo existe y está eliminado
     * - No hay otro grupo activo con el mismo nombre
     */
    private function restaurarGrupo()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirigirConMensaje('Método no permitido.', 'error');
            return;
        }

        if (!isset($_POST['grupo_id']) || !is_numeric($_POST['grupo_id'])) {
            $this->redirigirConMensaje('ID de grupo inválido.', 'error');
            return;
        }

        try {
            $grupo_id = (int)$_POST['grupo_id'];
            $resultado = $this->modeloGrupo->mdlRestaurarGrupo($grupo_id);
            
            if ($resultado) {
                $this->redirigirConMensaje('Grupo restaurado con éxito.', 'success');
            } else {
                $this->redirigirConMensaje('Error al restaurar el grupo.', 'error');
            }
        } catch (Exception $e) {
            $this->manejarError("Error al restaurar grupo: " . $e->getMessage());
        }
    }

    /**
     * Muestra el historial de grupos eliminados.
     * 
     * Útil para auditoría y posible restauración de grupos.
     */
    private function verHistorialEliminados()
    {
        try {
            $gruposEliminados = $this->modeloGrupo->mdlListarGruposEliminados();
            include_once(__DIR__ . '/../Vista/gruposHistorial.php');
        } catch (Exception $e) {
            $this->manejarError("Error al cargar el historial: " . $e->getMessage());
        }
    }

    // ========================================================================
    // API JSON (PARA PETICIONES AJAX)
    // ========================================================================

    /**
     * Endpoint JSON para obtener datos de un grupo vía AJAX.
     * 
     * Retorna JSON con información del grupo, miembros y roles disponibles.
     * Útil para actualización dinámica de UI sin recargar página.
     * 
     * @requires POST con grupo_id
     * 
     *         {
     *           "grupo": {...},
     *           "miembros": [...],
     *           "roles": [...]
     *         }
     */
    public function ctrlObtenerDatosGrupo()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['grupo_id']) || !is_numeric($_POST['grupo_id'])) {
            echo json_encode(['error' => 'ID inválido']);
            return;
        }

        try {
            $grupo_id = (int)$_POST['grupo_id'];
            $grupo = $this->modeloGrupo->mdlObtenerGrupoPorId($grupo_id);
            $miembros = $this->modeloGrupo->mdlListarMiembrosGrupo($grupo_id);
            $roles = $this->modeloGrupo->mdlListarRolesGrupo();

            echo json_encode([
                'grupo' => $grupo,
                'miembros' => $miembros,
                'roles' => $roles
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al obtener datos del grupo']);
        }
    }

    // ========================================================================
    // MÉTODOS AUXILIARES
    // Funciones de utilidad para manejo de errores y redirecciones
    // ========================================================================

    /**
     * Método auxiliar para manejar errores de forma centralizada.
     * 
     * - Registra el error en logs del servidor
     * - Muestra mensaje genérico al usuario (no expone detalles técnicos)
     * - Redirige al listado principal
     * 
     * @param string $mensaje Mensaje técnico para logging
     */
    private function manejarError($mensaje)
    {
        error_log($mensaje);
        $this->redirigirConMensaje('Ha ocurrido un error interno. Por favor, inténtelo más tarde.', 'error');
    }

    /**
     * Método auxiliar para redireccionar con mensajes a la lista principal.
     * 
     * Usa formulario POST auto-submit para mantener URLs limpias y
     * evitar problemas con navegación del navegador.
     * 
     * @param string $mensaje Mensaje a mostrar al usuario
     * @param string $tipo Tipo de mensaje: 'success', 'error', 'info'
     * 
     * @pattern POST-Redirect-GET para evitar reenvíos accidentales
     */
    private function redirigirConMensaje($mensaje, $tipo = 'info')
    {
        // Guardar mensaje en sesión
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['tipo_mensaje'] = $tipo;

        // Auto-submit con formulario POST oculto
        echo '<form id="redirect-form" method="POST" action="index.php" style="display: none;">
                <input type="hidden" name="menu-item" value="Grupos">
                </form>
                <script>
                document.getElementById("redirect-form").submit();
                </script>';
        exit();
    }

    /**
     * Método auxiliar para redireccionar al detalle de un grupo con mensaje.
     * 
     * Similar a redirigirConMensaje pero mantiene el contexto del grupo específico.
     * 
     * @param string $mensaje Mensaje a mostrar
     * @param int $grupo_id ID del grupo al que redirigir
     * @param string $tipo Tipo de mensaje
     */
    private function redirigirConMensajeConGrupo($mensaje, $tipo, $grupo_id)
    {
        // Guardar mensaje en sesión
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['tipo_mensaje'] = $tipo;

        // Auto-submit con formulario POST oculto
        echo '<form id="redirect-form" method="POST" action="index.php" style="display: none;">
                <input type="hidden" name="menu-item" value="Grupos">
                <input type="hidden" name="action" value="ver">
                <input type="hidden" name="grupo_id" value="' . $grupo_id . '">
                </form>
                <script>
                document.getElementById("redirect-form").submit();
                </script>';
        exit();
    }
}
?>