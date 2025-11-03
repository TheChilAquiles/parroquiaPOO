<?php

// ============================================================================
// SacramentosController.php
// ============================================================================

class SacramentosController
{
    private $modeloSacramento;
    private $modeloFeligres;

    public function __construct()
    {
        // El autoload.php ya carga las clases automáticamente
        $this->modeloSacramento = new ModeloSacramento();
        $this->modeloFeligres = new ModeloFeligres();
    }

    public function index()
    {
        // Mostrar vista de sacramentos
        include_once __DIR__ . '/../Vista/sacramentos.php';
    }   

    private function buscarFeligres($tipoDoc, $numeroDoc)
    {
        // Usar la instancia ya creada en el constructor
        return $this->modeloFeligres->mdlConsultarFeligres($tipoDoc, $numeroDoc);
    }


    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->index();
            return;
        }

        $tipo = $_POST['Tipo'] ?? null;
        $numero = $_POST['Numero'] ?? null;

        if (empty($tipo) || empty($numero)) {
            $_SESSION['error'] = 'Tipo y número son requeridos.';
            $this->index();
            return;
        }

        // Procesar integrantes
        $integrantes = $_POST['integrantes'] ?? [];

        if (empty($integrantes)) {
            $_SESSION['error'] = 'Debe agregar al menos un integrante.';
            $this->index();
            return;
        }

        try {
            // Limpiar buffer para evitar HTML contaminado
            if (ob_get_level()) {
                ob_clean();
            }

            $sacramento = new ModeloSacramento($tipo, $numero);
            $resultado = $sacramento->CrearSacramento($_POST);

            header('Content-Type: application/json');

            if ($resultado !== false) {
                echo json_encode([
                    'success' => true,
                    'id' => $resultado,
                    'message' => 'Sacramento creado correctamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear sacramento'
                ]);
            }
            exit();

        } catch (Exception $e) {
            if (ob_get_level()) {
                ob_clean();
            }

            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    public function buscarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
            return;
        }

        $tipoDoc = $_POST['tipoDoc'] ?? null;
        $numeroDoc = $_POST['numeroDoc'] ?? null;

        if (empty($tipoDoc) || empty($numeroDoc)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
            return;
        }

        // Limpiar buffer para evitar contaminación HTML
        if (ob_get_level()) {
            ob_clean();
        }

        $feligres = $this->modeloFeligres->mdlConsultarFeligres($tipoDoc, $numeroDoc);

        header('Content-Type: application/json');

        if ($feligres) {
            // Usuario encontrado - devolver formato esperado por JavaScript
            echo json_encode([
                'status' => 'success',
                'data' => $feligres
            ]);
        } else {
            // Usuario no encontrado
            echo json_encode([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ]);
        }

        exit();
    }

    public function getParticipantes()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }

        $sacramentoId = $_POST['sacramento_id'] ?? null;

        if (empty($sacramentoId) || !is_numeric($sacramentoId)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de sacramento inválido']);
            return;
        }

        // Limpiar cualquier output previo del buffer (evitar contaminación HTML en JSON)
        if (ob_get_level()) {
            ob_clean();
        }

        $sacramento = new ModeloSacramento();
        $participantes = $sacramento->getParticipantes((int) $sacramentoId);

        header('Content-Type: application/json');
        echo json_encode($participantes);
        exit();
    }

    /**
     * Muestra la vista de sacramentos de un libro específico
     * Recibe tipo y número por GET desde Vista/libros.php
     */
    public function verLibro()
    {
        // Obtener parámetros de GET
        $tipo = $_GET['tipo'] ?? null;
        $numero = $_GET['numero'] ?? null;

        // Validar parámetros
        if (empty($tipo) || !is_numeric($tipo)) {
            $_SESSION['error'] = 'Tipo de libro inválido.';
            header('Location: ?route=libros');
            exit;
        }

        if (empty($numero) || !is_numeric($numero)) {
            $_SESSION['error'] = 'Número de libro inválido.';
            header('Location: ?route=libros');
            exit;
        }

        // Convertir a enteros
        $tipo = (int)$tipo;
        $numeroLibro = (int)$numero;

        // Obtener nombre legible del tipo
        $libroTipo = $this->obtenerNombreTipo($tipo);

        // Pasar variables a la vista
        include_once __DIR__ . '/../Vista/sacramentos.php';
    }

    /**
     * Endpoint AJAX para DataTables
     * Devuelve lista de sacramentos de un libro en formato JSON
     */
    public function listar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }

        // Obtener parámetros de POST (AJAX)
        $tipo = $_POST['tipo'] ?? null;
        $numero = $_POST['numero'] ?? null;

        if (empty($tipo) || !is_numeric($tipo) || empty($numero) || !is_numeric($numero)) {
            http_response_code(400);
            echo json_encode(['error' => 'Parámetros inválidos']);
            return;
        }

        // Limpiar cualquier output previo del buffer (evitar contaminación HTML en JSON)
        if (ob_get_level()) {
            ob_clean();
        }

        // Obtener sacramentos del modelo
        $sacramentos = $this->modeloSacramento->mdlObtenerPorLibro((int)$tipo, (int)$numero);

        // Devolver JSON para DataTables
        header('Content-Type: application/json');
        echo json_encode(['data' => $sacramentos]);
        exit();
    }

    /**
     * Obtiene el nombre legible del tipo de libro según su ID
     * @param int $id ID del tipo de libro
     * @return string Nombre del tipo de libro
     */
    private function obtenerNombreTipo($id)
    {
        $tipos = [
            1 => 'Bautizos',
            2 => 'Confirmaciones',
            3 => 'Defunciones',
            4 => 'Matrimonios'
        ];

        return $tipos[$id] ?? 'Desconocido';
    }
}