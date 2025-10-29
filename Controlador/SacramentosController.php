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
        require_once __DIR__ . '/../Modelo/ModeloSacramento.php';
        require_once __DIR__ . '/../Controlador/ControladorFeligres.php';
        $this->modeloSacramento = new ModeloSacramento();
        require_once __DIR__ . '/../Modelo/ModeloFeligres.php';
        $this->modeloFeligres = new ModeloFeligres(); // En línea 15
    }

    public function index()
    {
        $rolesDisponibles = $this->obtenerRolesPorTipo($tipo);
        $rolesObligatorios = $this->obtenerRolesObligatorios($tipo);
        include_once __DIR__ . '/../Vista/sacramentos.php';
    }   

    private function buscarFeligres($tipoDoc, $numeroDoc)
    {
        require_once __DIR__ . '/../Modelo/ModeloFeligres.php';
        $modelo = new ModeloFeligres();
        return $modelo->mdlConsultarFeligres($tipoDoc, $numeroDoc);
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
            $sacramento = new ModeloSacramento($tipo, $numero);
            $resultado = $sacramento->CrearSacramento($_POST);

            $_SESSION['success'] = 'Sacramento creado correctamente.';
            header('Location: ?route=sacramentos');
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear el sacramento: ' . $e->getMessage();
            $this->index();
        }
    }

    public function buscarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }

        $tipoDoc = $_POST['tipoDoc'] ?? null;
        $numeroDoc = $_POST['numeroDoc'] ?? null;

        if (empty($tipoDoc) || empty($numeroDoc)) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }

        $feligres = $this->modeloFeligres->ctrlConsularFeligres($tipoDoc, $numeroDoc);
        header('Content-Type: application/json');
        echo json_encode($feligres);
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

        $sacramento = new ModeloSacramento();
        $participantes = $sacramento->getParticipantes((int) $sacramentoId);

        header('Content-Type: application/json');
        echo json_encode($participantes);
        exit();
    }


}