<?php

// ============================================================================
// FeligresController.php
// ============================================================================

class FeligresController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloFeligres();
    }

    /**
     * Muestra la vista principal de feligreses
     */
    public function index()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            header('Location: ?route=login');
            exit();
        }

        // Solo administradores y secretarios pueden ver la lista completa
        if (!in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            header('Location: ?route=inicio');
            exit();
        }

        include_once __DIR__ . '/../Vista/feligreses.php';
    }

    /**
     * Lista feligreses para DataTables (server-side)
     */
    public function listar()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            exit();
        }

        // Verificar autorización
        if (!in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            http_response_code(403);
            echo json_encode(['error' => 'No autorizado']);
            exit();
        }

        // Limpiar buffer
        if (ob_get_level()) {
            ob_clean();
        }

        try {
            $feligreses = $this->modelo->mdlListarTodos();
            $total = $this->modelo->mdlContarTodos();

            // Formatear datos para DataTables
            $data = [];
            foreach ($feligreses as $feligres) {
                $data[] = [
                    'id' => $feligres['id'],
                    'nombre_completo' => $feligres['nombre_completo'],
                    'tipo_documento' => $feligres['tipo_documento_nombre'] ?? 'N/A',
                    'numero_documento' => $feligres['numero_documento'],
                    'telefono' => $feligres['telefono'] ?? 'N/A',
                    'direccion' => $feligres['direccion'] ?? 'N/A'
                ];
            }

            header('Content-Type: application/json');
            echo json_encode([
                'draw' => intval($_POST['draw'] ?? 1),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data
            ]);
            exit();

        } catch (Exception $e) {
            if (ob_get_level()) {
                ob_clean();
            }
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Error al cargar feligreses']);
            exit();
        }
    }

    /**
     * Muestra el formulario para crear un nuevo feligrés
     */
    public function crear()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            header('Location: ?route=login');
            exit();
        }

        // Verificar autorización
        if (!in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: ?route=feligreses');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validación básica
            $required = ['tipo-doc', 'documento', 'primer-nombre', 'primer-apellido', 'direccion'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    $_SESSION['error'] = "El campo $field es requerido";
                    header('Location: ?route=feligreses');
                    exit();
                }
            }

            $resultado = $this->modelo->mdlCrearFeligres($_POST);

            if ($resultado['status'] === 'success') {
                $_SESSION['success'] = $resultado['message'];
            } else {
                $_SESSION['error'] = $resultado['message'];
            }

            header('Location: ?route=feligreses');
            exit();
        }

        include_once __DIR__ . '/../Vista/feligreses_crear.php';
    }

    /**
     * Muestra el formulario para editar un feligrés
     */
    public function editar()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            header('Location: ?route=login');
            exit();
        }

        // Verificar autorización
        if (!in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            header('Location: ?route=feligreses');
            exit();
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de feligrés inválido';
            header('Location: ?route=feligreses');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['id'] = $id;
            $resultado = $this->modelo->mdlUpdateFeligres($_POST);

            if ($resultado['status'] === 'success') {
                $_SESSION['success'] = $resultado['message'];
            } else {
                $_SESSION['error'] = $resultado['message'];
            }

            header('Location: ?route=feligreses');
            exit();
        }

        $feligres = $this->modelo->mdlObtenerPorId($id);
        if (!$feligres) {
            $_SESSION['error'] = 'Feligrés no encontrado';
            header('Location: ?route=feligreses');
            exit();
        }

        include_once __DIR__ . '/../Vista/feligreses_editar.php';
    }

    /**
     * Elimina (soft delete) un feligrés
     */
    public function eliminar()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            header('Location: ?route=login');
            exit();
        }

        // Solo administradores pueden eliminar
        if ($_SESSION['user-rol'] !== 'Administrador') {
            $_SESSION['error'] = 'No tienes permisos para eliminar feligreses';
            header('Location: ?route=feligreses');
            exit();
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de feligrés inválido';
            header('Location: ?route=feligreses');
            exit();
        }

        if ($this->modelo->mdlEliminar($id)) {
            $_SESSION['success'] = 'Feligrés eliminado correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar feligrés';
        }

        header('Location: ?route=feligreses');
        exit();
    }
}
