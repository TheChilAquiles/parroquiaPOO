<?php
// Asegúrate de que las rutas a tus modelos sean correctas
require_once(__DIR__ . '/../Modelo/ModeloGrupo.php');
require_once(__DIR__ . '/../Modelo/ModeloUsuario.php'); 

class GrupoController
{
    private $modeloGrupo;
    private $modeloUsuario;

    public function __construct()
    {
        $this->modeloGrupo = new ModeloGrupo();
        $this->modeloUsuario = new ModeloUsuario();
    }
    
    // Método principal que gestiona todas las acciones de los grupos
    public function ctrlGestionarGrupos()
    {
        // Obtener la acción solo de POST, GET solo para navegación básica
        $action = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
            // Solo permitir acciones de visualización por GET
            $allowedGetActions = ['ver', 'crear', 'editar', 'eliminar'];
            if (in_array($_GET['action'], $allowedGetActions)) {
                $action = $_GET['action'];
            }
        }

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
                // Si no hay acción o no es reconocida, mostramos la lista de grupos por defecto
                $this->listarGrupos();
                break;
        }
    }

    /**
     * Muestra la lista principal de grupos
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
     * Muestra el detalle de un grupo específico
     */
    private function verDetalle()
    {
        $grupo_id = null;
        
        // Obtener ID desde POST o GET (para navegación)
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
            $grupo = $this->modeloGrupo->mdlObtenerGrupoPorId($grupo_id);
            
            if (!$grupo) {
                $this->redirigirConMensaje('Grupo no encontrado.', 'error');
                return;
            }
            
            $miembros = $this->modeloGrupo->mdlListarMiembrosGrupo($grupo_id);
            $rolesGrupo = $this->modeloGrupo->mdlListarRolesGrupo();
            $usuariosDisponibles = $this->modeloGrupo->mdlListarUsuariosDisponibles();
            
            include_once(__DIR__ . '/../Vista/grupoDetalle.php');
        } catch (Exception $e) {
            $this->manejarError("Error al cargar el detalle del grupo: " . $e->getMessage());
        }
    }

    /**
     * Maneja la creación de un nuevo grupo
     */
    private function crearGrupo()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar la creación
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
            // Mostrar formulario de creación
            include_once(__DIR__ . '/../Vista/grupoFormulario.php');
        }
    }

    /**
     * Maneja la edición de un grupo existente
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
            // Procesar la edición
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
            // Mostrar formulario de edición
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
     * Maneja la eliminación de un grupo
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
            // Verificar que el grupo existe antes de eliminarlo
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
                // Mostrar confirmación de eliminación
                include_once(__DIR__ . '/../Vista/grupoConfirmarEliminacion.php');
            }
        } catch (Exception $e) {
            $this->manejarError("Error al eliminar el grupo: " . $e->getMessage());
        }
    }

    /**
     * Maneja agregar un miembro al grupo
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

            // Validaciones
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
     * Maneja eliminar un miembro del grupo
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
     * Maneja actualizar el rol de un miembro (funcionalidad adicional)
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

            // Eliminar y volver a agregar con el nuevo rol
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

    /**
     * Maneja la restauración de un grupo eliminado
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
     * Muestra el historial de grupos eliminados
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

    /**
     * Método auxiliar para manejar errores
     */
    private function manejarError($mensaje)
    {
        error_log($mensaje);
        $this->redirigirConMensaje('Ha ocurrido un error interno. Por favor, inténtelo más tarde.', 'error');
    }

    /**
     * Método auxiliar para redireccionar con mensajes a la lista principal
     */
    private function redirigirConMensaje($mensaje, $tipo = 'info')
    {
        // Guardar mensaje en sesión si está disponible
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['tipo_mensaje'] = $tipo;

        // Crear redirección con formulario POST para mantener URLs limpias
        echo '<form id="redirect-form" method="POST" action="index.php" style="display: none;">
                <input type="hidden" name="menu-item" value="Grupos">
                </form>
                <script>
                document.getElementById("redirect-form").submit();
                </script>';
        exit();
    }

    /**
     * Método auxiliar para redireccionar con mensajes al detalle de un grupo
     */
    private function redirigirConMensajeConGrupo($mensaje, $grupo_id, $tipo = 'info')
    {
        // Guardar mensaje en sesión si está disponible
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['tipo_mensaje'] = $tipo;

        // Crear redirección con formulario POST para mantener URLs limpias
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

    /**
     * Método para obtener datos necesarios para las vistas (AJAX)
     */
    public function ctrlObtenerDatosGrupo()
    {
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
}
?>