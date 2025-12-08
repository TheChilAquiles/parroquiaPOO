<?php

// ============================================================================
// GruposController.php - REFACTORIZADO PARA MVC
// ============================================================================

class GruposController extends BaseController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloGrupo();
    }

    /**
     * Lista todos los grupos
     */
    public function index()
    {
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        try {
            $grupos = $this->modelo->mdlListarGrupos();
            include_once __DIR__ . '/../Vista/grupos.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar los grupos.';
            Logger::error("Error en GruposController::index -", ['error' => $e->getMessage()]);
            include_once __DIR__ . '/../Vista/grupos.php';
        }
    }

    private function obtenerGrupoId()
    {
        $id = $_GET['id'] ?? $_POST['grupo_id'] ?? null;

        if (empty($id) || !is_numeric($id)) {
            return null;
        }

        return (int) $id;
    }

    /**
     * Muestra detalles de un grupo específico
     */
    public function ver()
    {
        // Obtener ID desde GET
        $grupo_id = $this->obtenerGrupoId(); // ✅ Usar método auxiliar

        if ($grupo_id === null) {
            $_SESSION['mensaje'] = 'ID de grupo inválido.';
            $_SESSION['tipo_mensaje'] = 'error';
            redirect('grupos');

            exit();
        }

        try {
            $grupo_id = (int) $grupo_id;
            $grupo = $this->modelo->mdlObtenerGrupoPorId($grupo_id);

            if (!$grupo) {
                $_SESSION['mensaje'] = 'Grupo no encontrado.';
                $_SESSION['tipo_mensaje'] = 'error';
                redirect('grupos');
                exit();
            }

            $miembros = $this->modelo->mdlListarMiembrosGrupo($grupo_id);
            $rolesGrupo = $this->modelo->mdlListarRolesGrupo();
            $usuariosDisponibles = $this->modelo->mdlListarUsuariosDisponibles();

            include_once __DIR__ . '/../Vista/grupoDetalle.php';
        } catch (Exception $e) {
            $_SESSION['mensaje'] = 'Error al cargar el grupo.';
            $_SESSION['tipo_mensaje'] = 'error';
            Logger::error("Error en GruposController::ver -", ['error' => $e->getMessage()]);
            redirect('grupos');
            exit();
        }
    }

    /**
     * Crea un nuevo grupo
     */
    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_grupo'] ?? '');

            if (empty($nombre)) {
                $_SESSION['mensaje'] = 'El nombre del grupo es obligatorio.';
                $_SESSION['tipo_mensaje'] = 'error';
                redirect('grupos'); // <--- SIMPLIFICADO: Vuelve a la lista
                exit();
            }

            try {
                $resultado = $this->modelo->mdlCrearGrupo($nombre);

                if ($resultado) {
                    $_SESSION['mensaje'] = 'Grupo creado exitosamente.';
                    $_SESSION['tipo_mensaje'] = 'success';
                } else {
                    // AQUÍ ESTABA EL PROBLEMA
                    // Antes: Cargabas la vista manualmente y faltaban variables.
                    // AHORA: Simplemente le dices que falló y lo mandas a la lista.
                    $_SESSION['mensaje'] = 'El grupo ya existe.';
                    $_SESSION['tipo_mensaje'] = 'error';
                }
                
                // En ambos casos (éxito o fallo por duplicado), rediriges.
                redirect('grupos'); 
                exit();

            } catch (Exception $e) {
                $_SESSION['mensaje'] = 'Error interno al crear el grupo.';
                $_SESSION['tipo_mensaje'] = 'error';
                Logger::error("Error...", ['error' => $e->getMessage()]);
                redirect('grupos');
                exit();
            }
        } else {
            // Mostrar formulario vacío
            $grupo = null;
            include_once __DIR__ . '/../Vista/grupoFormulario.php';
        }
    }

    /**
     * Edita un grupo existente
     */
    public function editar()
    {
        $grupo_id = $_GET['id'] ?? $_POST['grupo_id'] ?? null;

        if (empty($grupo_id) || !is_numeric($grupo_id)) {
            $_SESSION['mensaje'] = 'ID de grupo inválido.';
            $_SESSION['tipo_mensaje'] = 'error';
            redirect('grupos');
            exit();
        }

        $grupo_id = (int) $grupo_id;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_grupo'])) {
            $nombre = trim($_POST['nombre_grupo'] ?? '');

            if (empty($nombre)) {
                $_SESSION['mensaje'] = 'El nombre del grupo es obligatorio.';
                $_SESSION['tipo_mensaje'] = 'error';
                try {
                    $grupo = $this->modelo->mdlObtenerGrupoPorId($grupo_id);
                    include_once __DIR__ . '/../Vista/grupoFormulario.php';
                } catch (Exception $e) {
                    redirect('grupos');
                    exit();
                }
                return;
            }

            try {
                $resultado = $this->modelo->mdlActualizarGrupo($grupo_id, $nombre);

                if ($resultado) {
                    $_SESSION['mensaje'] = 'Grupo actualizado exitosamente.';
                    $_SESSION['tipo_mensaje'] = 'success';
                    redirect('grupos');
                    exit();
                } else {
                    $_SESSION['mensaje'] = 'El grupo ya existe o no se puede actualizar.';
                    $_SESSION['tipo_mensaje'] = 'error';
                    $grupo = $this->modelo->mdlObtenerGrupoPorId($grupo_id);
                    include_once __DIR__ . '/../Vista/grupoFormulario.php';
                }
            } catch (Exception $e) {
                $_SESSION['mensaje'] = 'Error al actualizar el grupo.';
                $_SESSION['tipo_mensaje'] = 'error';
                Logger::error("Error en GruposController::editar -", ['error' => $e->getMessage()]);
                $grupo = $this->modelo->mdlObtenerGrupoPorId($grupo_id);
                include_once __DIR__ . '/../Vista/grupoFormulario.php';
            }
        } else {
            // Mostrar formulario con datos del grupo
            try {
                $grupo = $this->modelo->mdlObtenerGrupoPorId($grupo_id);

                if (!$grupo) {
                    $_SESSION['mensaje'] = 'Grupo no encontrado.';
                    $_SESSION['tipo_mensaje'] = 'error';
                    redirect('grupos');
                    exit();
                }

                include_once __DIR__ . '/../Vista/grupoFormulario.php';
            } catch (Exception $e) {
                $_SESSION['mensaje'] = 'Error al cargar el grupo.';
                $_SESSION['tipo_mensaje'] = 'error';
                Logger::error("Error en GruposController::editar GET -", ['error' => $e->getMessage()]);
                redirect('grupos');
                exit();
            }
        }
    }

    /**
     * Elimina un grupo (confirmación y ejecución)
     */
    public function eliminar()
    {
        $grupo_id = $_GET['id'] ?? $_POST['grupo_id'] ?? null;

        if (empty($grupo_id) || !is_numeric($grupo_id)) {
            $_SESSION['mensaje'] = 'ID de grupo inválido.';
            $_SESSION['tipo_mensaje'] = 'error';
            redirect('grupos');
            exit();
        }

        $grupo_id = (int) $grupo_id;

        try {
            $grupo = $this->modelo->mdlObtenerGrupoPorId($grupo_id);

            if (!$grupo) {
                $_SESSION['mensaje'] = 'Grupo no encontrado.';
                $_SESSION['tipo_mensaje'] = 'error';
                redirect('grupos');
                exit();
            }

            // Si viene confirmación, eliminar
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_eliminacion'])) {
                $resultado = $this->modelo->mdlEliminarGrupo($grupo_id);

                if ($resultado) {
                    $_SESSION['mensaje'] = 'Grupo eliminado exitosamente.';
                    $_SESSION['tipo_mensaje'] = 'success';
                    redirect('grupos');
                    exit();
                } else {
                    $_SESSION['mensaje'] = 'Error al eliminar el grupo.';
                    $_SESSION['tipo_mensaje'] = 'error';
                    $miembros = $this->modelo->mdlListarMiembrosGrupo($grupo_id);
                    include_once __DIR__ . '/../Vista/grupoConfirmarEliminacion.php';
                }
            } else {
                // Mostrar página de confirmación
                $miembros = $this->modelo->mdlListarMiembrosGrupo($grupo_id);
                include_once __DIR__ . '/../Vista/grupoConfirmarEliminacion.php';
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = 'Error al procesar eliminación.';
            $_SESSION['tipo_mensaje'] = 'error';
            Logger::error("Error en GruposController::eliminar -", ['error' => $e->getMessage()]);
            redirect('grupos');
            exit();
        }
    }

    /**
     * Agrega un miembro a un grupo
     */
    public function agregarMiembro()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $_SESSION['mensaje'] = 'Método no permitido.';
            $_SESSION['tipo_mensaje'] = 'error';
            redirect('grupos');
            exit();
        }

        $grupo_id = $_POST['grupo_id'] ?? null;
        $usuario_id = $_POST['usuario_id'] ?? null;
        $rol_id = $_POST['rol_id'] ?? null;

        if (
            empty($grupo_id) || empty($usuario_id) || empty($rol_id) ||
            !is_numeric($grupo_id) || !is_numeric($usuario_id) || !is_numeric($rol_id)
        ) {
            $_SESSION['mensaje'] = 'Datos incompletos o inválidos.';
            $_SESSION['tipo_mensaje'] = 'error';

            // CORRECCIÓN: Si falla, intentamos volver al detalle si tenemos ID, sino a la lista
            if (!empty($grupo_id)) {
                redirect('grupos/ver', ['id' => $grupo_id]); // <--- CORRECCIÓN AQUÍ
            } else {
                redirect('grupos');
            }
            exit();
        }

        try {
            $resultado = $this->modelo->mdlAgregarMiembro((int) $grupo_id, (int) $usuario_id, (int) $rol_id);

            if ($resultado) {
                $_SESSION['mensaje'] = 'Miembro agregado exitosamente.';
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                $_SESSION['mensaje'] = 'El miembro ya existe en el grupo.';
                $_SESSION['tipo_mensaje'] = 'error';
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = 'Error al agregar miembro.';
            $_SESSION['tipo_mensaje'] = 'error';
            Logger::error("Error en GruposController::agregarMiembro -", ['error' => $e->getMessage()]);
        }

        // CORRECCIÓN: Redirigir a 'grupos/ver' en lugar de 'grupos'
        redirect('grupos/ver', ['id' => $grupo_id]); // <--- CORRECCIÓN AQUÍ
        exit();
    }

    /**
     * Elimina un miembro de un grupo
     */
    public function eliminarMiembro()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['mensaje'] = 'Método no permitido.';
            $_SESSION['tipo_mensaje'] = 'error';
            redirect('grupos');
            exit();
        }

        $grupo_id = $_POST['grupo_id'] ?? null;
        $usuario_id = $_POST['usuario_id'] ?? null;

        if (empty($grupo_id) || empty($usuario_id) || !is_numeric($grupo_id) || !is_numeric($usuario_id)) {
            $_SESSION['mensaje'] = 'Parámetros inválidos.';
            $_SESSION['tipo_mensaje'] = 'error';
            redirect('grupos');
            exit();
        }

        try {
            $resultado = $this->modelo->mdlEliminarMiembro((int) $grupo_id, (int) $usuario_id);

            if ($resultado) {
                $_SESSION['mensaje'] = 'Miembro eliminado exitosamente.';
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                $_SESSION['mensaje'] = 'Error al eliminar miembro.';
                $_SESSION['tipo_mensaje'] = 'error';
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = 'Error al eliminar miembro.';
            $_SESSION['tipo_mensaje'] = 'error';
            Logger::error("Error en GruposController::eliminarMiembro -", ['error' => $e->getMessage()]);
        }

        // CORRECCIÓN: Redirigir a 'grupos/ver' y arreglar indentación
        redirect('grupos/ver', ['id' => $grupo_id]); // <--- CORRECCIÓN AQUÍ
        exit();
    }

    /**
     * Actualiza el rol de un miembro en el grupo
     */
    public function actualizarRol()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['mensaje'] = 'Método no permitido.';
            $_SESSION['tipo_mensaje'] = 'error';
            redirect('grupos');
            exit();
        }

        $grupo_id = $_POST['grupo_id'] ?? null;
        $usuario_id = $_POST['usuario_id'] ?? null;
        $nuevo_rol_id = $_POST['nuevo_rol_id'] ?? null;

        if (
            empty($grupo_id) || empty($usuario_id) || empty($nuevo_rol_id) ||
            !is_numeric($grupo_id) || !is_numeric($usuario_id) || !is_numeric($nuevo_rol_id)
        ) {
            $_SESSION['mensaje'] = 'Datos incompletos o inválidos.';
            $_SESSION['tipo_mensaje'] = 'error';

            // CORRECCIÓN: Volver al detalle si es posible
            if (!empty($grupo_id)) {
                redirect('grupos/ver', ['id' => $grupo_id]); // <--- CORRECCIÓN AQUÍ
            } else {
                redirect('grupos');
            }
            exit();
        }

        try {
            // Nota: Mantenemos tu lógica original aquí, aunque recuerda el consejo del Error #3 sobre la atomicidad
            $elimino = $this->modelo->mdlEliminarMiembro((int) $grupo_id, (int) $usuario_id);

            if ($elimino) {
                $resultado = $this->modelo->mdlAgregarMiembro((int) $grupo_id, (int) $usuario_id, (int) $nuevo_rol_id);

                if ($resultado) {
                    $_SESSION['mensaje'] = 'Rol actualizado exitosamente.';
                    $_SESSION['tipo_mensaje'] = 'success';
                } else {
                    $_SESSION['mensaje'] = 'Error al asignar el nuevo rol.';
                    $_SESSION['tipo_mensaje'] = 'error';
                }
            } else {
                $_SESSION['mensaje'] = 'Error al actualizar rol (no se pudo eliminar el anterior).';
                $_SESSION['tipo_mensaje'] = 'error';
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = 'Error al actualizar rol.';
            $_SESSION['tipo_mensaje'] = 'error';
            Logger::error("Error en GruposController::actualizarRol -", ['error' => $e->getMessage()]);
        }

        // CORRECCIÓN: Redirigir a 'grupos/ver'
        redirect('grupos/ver', ['id' => $grupo_id]); // <--- CORRECCIÓN AQUÍ
        exit();
    }

    /**
     * Crea un nuevo rol de grupo
     */
    public function crearRol()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['mensaje'] = 'Método no permitido.';
            $_SESSION['tipo_mensaje'] = 'error';
            redirect('grupos');
            exit();
        }

        $nombreRol = trim($_POST['nombre_rol'] ?? '');

        if (empty($nombreRol)) {
            $_SESSION['mensaje'] = 'El nombre del rol es obligatorio.';
            $_SESSION['tipo_mensaje'] = 'error';
            redirect('grupos');
            exit();
        }

        try {
            $resultado = $this->modelo->mdlCrearRolGrupo($nombreRol);

            if ($resultado) {
                $_SESSION['mensaje'] = "Rol '$nombreRol' creado exitosamente.";
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                $_SESSION['mensaje'] = 'El rol ya existe o no se pudo crear.';
                $_SESSION['tipo_mensaje'] = 'error';
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = 'Error al crear el rol.';
            $_SESSION['tipo_mensaje'] = 'error';
            Logger::error("Error en GruposController::crearRol -", ['error' => $e->getMessage()]);
        }

        redirect('grupos');
        exit();
    }
}
