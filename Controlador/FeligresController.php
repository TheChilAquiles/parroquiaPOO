<?php

// ============================================================================
// FeligresController.php
// ============================================================================

class FeligresController extends BaseController
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
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        // Solo administradores y secretarios pueden ver la lista completa
        if (!in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            redirect('inicio');
            
            exit();
        }

        include_once __DIR__ . '/../Vista/feligreses.php';
    }

    /**
     * Lista feligreses para DataTables (server-side)
     */
    public function listar()
    {
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

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

            // Formatear datos para DataTables (incluir todos los campos para edición)
            $data = [];
            foreach ($feligreses as $feligres) {
                $data[] = [
                    'id' => $feligres['id'],
                    'nombre_completo' => $feligres['nombre_completo'],
                    'tipo_documento' => $feligres['tipo_documento_nombre'] ?? 'N/A',
                    'tipo_documento_id' => $feligres['tipo_documento_id'],
                    'numero_documento' => $feligres['numero_documento'],
                    'primer_nombre' => $feligres['primer_nombre'],
                    'segundo_nombre' => $feligres['segundo_nombre'],
                    'primer_apellido' => $feligres['primer_apellido'],
                    'segundo_apellido' => $feligres['segundo_apellido'],
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
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        // Verificar autorización
        if (!in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            redirect('feligreses');
            
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validación básica
            $required = ['tipo-doc', 'documento', 'primer-nombre', 'primer-apellido', 'direccion'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    $error = "El campo $field es requerido";

                    // Detectar si es AJAX
                    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

                    if ($isAjax) {
                        if (ob_get_level()) {
                            ob_clean();
                        }
                        header('Content-Type: application/json');
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'message' => $error]);
                        exit();
                    } else {
                        $_SESSION['error'] = $error;
                        redirect('feligreses');
                        
                        exit();
                    }
                }
            }

            $resultado = $this->modelo->mdlCrearFeligres($_POST);

            // Detectar si es AJAX
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if ($isAjax) {
                // Respuesta JSON para AJAX
                if (ob_get_level()) {
                    ob_clean();
                }
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit();
            } else {
                // Redireccionamiento tradicional
                if ($resultado['status'] === 'success') {
                    $_SESSION['success'] = $resultado['message'];
                } else {
                    $_SESSION['error'] = $resultado['message'];
                }
                redirect('feligreses');
                exit();
            }
        }

        // Si no es POST, retornar error
        $_SESSION['error'] = 'Método no permitido';
        redirect('feligreses');
        exit();
    }

    /**
     * Muestra el formulario para editar un feligrés
     */
    public function editar()
    {
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        // Verificar autorización
        if (!in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            $_SESSION['error'] = 'No tienes permisos para realizar esta acción';
            redirect('feligreses');
            exit();
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de feligrés inválido';
            redirect('feligreses');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['id'] = $id;
            $resultado = $this->modelo->mdlUpdateFeligres($_POST);

            // Detectar si es AJAX
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if ($isAjax) {
                // Respuesta JSON para AJAX
                if (ob_get_level()) {
                    ob_clean();
                }
                header('Content-Type: application/json');
                echo json_encode($resultado);
                exit();
            } else {
                // Redireccionamiento tradicional
                if ($resultado['status'] === 'success') {
                    $_SESSION['success'] = $resultado['message'];
                } else {
                    $_SESSION['error'] = $resultado['message'];
                }
                redirect('feligreses');
                exit();
            }
        }

        // Si no es POST, retornar error
        $_SESSION['error'] = 'Método no permitido';
        redirect('feligreses');
        exit();
    }

    /**
     * Elimina (soft delete) un feligrés
     */
    public function eliminar()
    {
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        // Solo administradores pueden eliminar
        if ($_SESSION['user-rol'] !== 'Administrador') {
            $_SESSION['error'] = 'No tienes permisos para eliminar feligreses';
            redirect('feligreses');
            exit();
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error'] = 'ID de feligrés inválido';
            redirect('feligreses');
            exit();
        }

        if ($this->modelo->mdlEliminar($id)) {
            $_SESSION['success'] = 'Feligrés eliminado correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar feligrés';
        }

        redirect('feligreses');
        exit();
    }
}
