<?php

// ============================================================================
// GruposController.php - ACTUALIZADO PARA URLs AMIGABLES
// ============================================================================

class GruposController
{
    private $modelo;

    public function __construct()
    {
        require_once __DIR__ . '/../Modelo/ModeloGrupo.php';
        $this->modelo = new ModeloGrupo();
    }

    public function index()
    {
        try {
            $grupos = $this->modelo->mdlListarGrupos();
            include_once __DIR__ . '/../Vista/grupos.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar los grupos.';
            include_once __DIR__ . '/../Vista/grupos.php';
        }
    }

    public function ver()
    {
        // Obtener ID de URL amigable: /grupos/ver/5 o parámetro GET
        global $router;
        $grupo_id = $router->getParam('id') ?? $_GET['id'] ?? $_POST['grupo_id'] ?? null;

        if (empty($grupo_id) || !is_numeric($grupo_id)) {
            $_SESSION['error'] = 'ID de grupo inválido.';
            header('Location: ?route=grupos');
            exit();
        }

        try {
            $grupo_id = (int)$grupo_id;
            $grupo = $this->modelo->mdlObtenerGrupoPorId($grupo_id);

            if (!$grupo) {
                $_SESSION['error'] = 'Grupo no encontrado.';
                header('Location: ?route=grupos');
                exit();
            }

            $miembros = $this->modelo->mdlListarMiembrosGrupo($grupo_id);
            $rolesGrupo = $this->modelo->mdlListarRolesGrupo();
            $usuariosDisponibles = $this->modelo->mdlListarUsuariosDisponibles();

            include_once __DIR__ . '/../Vista/grupoDetalle.php';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar el grupo.';
            header('Location: ?route=grupos');
            exit();
        }
    }

    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_grupo'] ?? '');

            if (empty($nombre)) {
                $_SESSION['error'] = 'El nombre del grupo es obligatorio.';
                include_once __DIR__ . '/../Vista/grupoFormulario.php';
                return;
            }

            try {
                $resultado = $this->modelo->mdlCrearGrupo($nombre);

                if ($resultado) {
                    $_SESSION['success'] = 'Grupo creado exitosamente.';
                    header('Location: /grupos');
                    exit();
                } else {
                    $_SESSION['error'] = 'El grupo ya existe.';
                    include_once __DIR__ . '/../Vista/grupoFormulario.php';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al crear el grupo.';
                include_once __DIR__ . '/../Vista/grupoFormulario.php';
            }
        } else {
            include_once __DIR__ . '/../Vista/grupoFormulario.php';
        }
    }

    public function editar()
    {
        global $router;
        $grupo_id = $router->getParam('id') ?? $_GET['id'] ?? $_POST['grupo_id'] ?? null;

        if (empty($grupo_id) || !is_numeric($grupo_id)) {
            $_SESSION['error'] = 'ID de grupo inválido.';
            header('Location: /grupos');
            exit();
        }

        $grupo_id = (int)$grupo_id;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_grupo'] ?? '');

            if (empty($nombre)) {
                $_SESSION['error'] = 'El nombre del grupo es obligatorio.';
                include_once __DIR__ . '/../Vista/grupoFormulario.php';
                return;
            }

            try {
                $resultado = $this->modelo->mdlActualizarGrupo($grupo_id, $nombre);

                if ($resultado) {
                    $_SESSION['success'] = 'Grupo actualizado exitosamente.';
                    header('Location: /grupos');
                    exit();
                } else {
                    $_SESSION['error'] = 'El grupo ya existe o no se puede actualizar.';
                    include_once __DIR__ . '/../Vista/grupoFormulario.php';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al actualizar el grupo.';
                include_once __DIR__ . '/../Vista/grupoFormulario.php';
            }
        } else {
            try {
                $grupo = $this->modelo->mdlObtenerGrupoPorId($grupo_id);

                if (!$grupo) {
                    $_SESSION['error'] = 'Grupo no encontrado.';
                    header('Location: /grupos');
                    exit();
                }

                include_once __DIR__ . '/../Vista/grupoFormulario.php';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al cargar el grupo.';
                header('Location: /grupos');
                exit();
            }
        }
    }

    public function eliminar()
    {
        global $router;
        $grupo_id = $router->getParam('id') ?? $_GET['id'] ?? $_POST['grupo_id'] ?? null;

        if (empty($grupo_id) || !is_numeric($grupo_id)) {
            $_SESSION['error'] = 'ID de grupo inválido.';
            header('Location: /grupos');
            exit();
        }

        $grupo_id = (int)$grupo_id;

        try {
            $grupo = $this->modelo->mdlObtenerGrupoPorId($grupo_id);

            if (!$grupo) {
                $_SESSION['error'] = 'Grupo no encontrado.';
                header('Location: /grupos');
                exit();
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_eliminacion'])) {
                $resultado = $this->modelo->mdlEliminarGrupo($grupo_id);

                if ($resultado) {
                    $_SESSION['success'] = 'Grupo eliminado exitosamente.';
                    header('Location: /grupos');
                    exit();
                } else {
                    $_SESSION['error'] = 'Error al eliminar el grupo.';
                    include_once __DIR__ . '/../Vista/grupoConfirmarEliminacion.php';
                }
            } else {
                include_once __DIR__ . '/../Vista/grupoConfirmarEliminacion.php';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al procesar eliminación.';
            header('Location: /grupos');
            exit();
        }
    }

    public function agregarMiembro()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            $_SESSION['error'] = 'Método no permitido.';
            header('Location: /grupos');
            exit();
        }

        $grupo_id = $_POST['grupo_id'] ?? null;
        $usuario_id = $_POST['usuario_id'] ?? null;
        $rol_id = $_POST['rol_id'] ?? null;

        if (empty($grupo_id) || empty($usuario_id) || empty($rol_id) ||
            !is_numeric($grupo_id) || !is_numeric($usuario_id) || !is_numeric($rol_id)) {
            $_SESSION['error'] = 'Datos incompletos o inválidos.';
            header('Location: /grupos/ver?id=' . $grupo_id);
            exit();
        }

        try {
            $resultado = $this->modelo->mdlAgregarMiembro((int)$grupo_id, (int)$usuario_id, (int)$rol_id);

            if ($resultado) {
                $_SESSION['success'] = 'Miembro agregado exitosamente.';
            } else {
                $_SESSION['error'] = 'El miembro ya existe en el grupo.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al agregar miembro.';
        }

        header('Location: /grupos/ver?id=' . $grupo_id);
        exit();
    }

    public function eliminarMiembro()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido.';
            header('Location: /grupos');
            exit();
        }

        $grupo_id = $_POST['grupo_id'] ?? null;
        $usuario_id = $_POST['usuario_id'] ?? null;

        if (empty($grupo_id) || empty($usuario_id) || !is_numeric($grupo_id) || !is_numeric($usuario_id)) {
            $_SESSION['error'] = 'Parámetros inválidos.';
            header('Location: /grupos');
            exit();
        }

        try {
            $resultado = $this->modelo->mdlEliminarMiembro((int)$grupo_id, (int)$usuario_id);

            if ($resultado) {
                $_SESSION['success'] = 'Miembro eliminado exitosamente.';
            } else {
                $_SESSION['error'] = 'Error al eliminar miembro.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar miembro.';
        }

        header('Location: /grupos/ver?id=' . $grupo_id);
        exit();
    }

    public function actualizarRol()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido.';
            header('Location: /grupos');
            exit();
        }

        $grupo_id = $_POST['grupo_id'] ?? null;
        $usuario_id = $_POST['usuario_id'] ?? null;
        $nuevo_rol_id = $_POST['nuevo_rol_id'] ?? null;

        if (empty($grupo_id) || empty($usuario_id) || empty($nuevo_rol_id) ||
            !is_numeric($grupo_id) || !is_numeric($usuario_id) || !is_numeric($nuevo_rol_id)) {
            $_SESSION['error'] = 'Datos incompletos o inválidos.';
            header('Location: /grupos');
            exit();
        }

        try {
            $elimino = $this->modelo->mdlEliminarMiembro((int)$grupo_id, (int)$usuario_id);

            if ($elimino) {
                $resultado = $this->modelo->mdlAgregarMiembro((int)$grupo_id, (int)$usuario_id, (int)$nuevo_rol_id);

                if ($resultado) {
                    $_SESSION['success'] = 'Rol actualizado exitosamente.';
                } else {
                    $_SESSION['error'] = 'Error al actualizar rol.';
                }
            } else {
                $_SESSION['error'] = 'Error al actualizar rol.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar rol.';
        }

        header('Location: /grupos/ver?id=' . $grupo_id);
        exit();
    }
}