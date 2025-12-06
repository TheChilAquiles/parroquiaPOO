<?php

// ============================================================================
// SacramentosController.php
// ============================================================================

class SacramentosController extends BaseController
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
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        $rol = $_SESSION['user-rol'];

        // Administrador y Secretario van a vista de libros
        if (in_array($rol, ['Administrador', 'Secretario'])) {
            header('Location: ?route=libros');
            exit;
        }

        // Feligrés ve sus propios sacramentos
        else {
            $this->misSacramentos();
        }
    }   

    private function buscarFeligres($tipoDoc, $numeroDoc)
    {
        // Usar la instancia ya creada en el constructor
        return $this->modeloFeligres->mdlConsultarFeligres($tipoDoc, $numeroDoc);
    }


    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Logger::warning("Intento de acceso directo a crear sacramento", [
                'method' => $_SERVER['REQUEST_METHOD'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $this->index();
            return;
        }

        $tipo = $_POST['Tipo'] ?? null;
        $numero = $_POST['Numero'] ?? null;
        $sacramentoId = $_POST['id'] ?? null;
        $accion = $_POST['Doaction'] ?? 'addRecord';

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

            Logger::info("Procesando sacramento", [
                'accion' => $accion,
                'tipo_libro' => $tipo,
                'numero_libro' => $numero,
                'sacramento_id' => $sacramentoId,
                'user_id' => $_SESSION['user-id'] ?? 'guest'
            ]);

            if ($accion === 'editRecord' && !empty($sacramentoId)) {
                // Actualizar sacramento existente
                $resultado = $sacramento->ActualizarSacramento($sacramentoId, $_POST);
                $mensaje = 'Sacramento actualizado correctamente';
            } else {
                // Crear nuevo sacramento
                $resultado = $sacramento->CrearSacramento($_POST);
                $mensaje = 'Sacramento creado correctamente';
            }

            header('Content-Type: application/json');

            if ($resultado !== false) {
                echo json_encode([
                    'success' => true,
                    'id' => $resultado,
                    'message' => $mensaje
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al procesar sacramento'
                ]);
            }
            exit();

        } catch (Exception $e) {
            if (ob_get_level()) {
                ob_clean();
            }

            Logger::error("Error al procesar sacramento", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'datos_post' => $_POST
            ]);

            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    /**
     * Obtiene los datos completos de un sacramento para edición
     */
    public function obtener()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $sacramentoId = $_POST['sacramento_id'] ?? null;

        if (empty($sacramentoId) || !is_numeric($sacramentoId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de sacramento inválido']);
            return;
        }

        if (ob_get_level()) ob_clean();

        try {
            // Obtener datos del sacramento
            $conexion = Conexion::conectar();
            $sql = "SELECT id, fecha_generacion, tipo_sacramento_id, libro_id
                    FROM sacramentos
                    WHERE id = ? AND estado_registro IS NULL";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$sacramentoId]);
            $sacramento = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$sacramento) {
                throw new Exception('Sacramento no encontrado');
            }

            // Obtener participantes con datos completos
            $participantes = $this->modeloSacramento->getParticipantes((int)$sacramentoId);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => $sacramento['id'],
                    'fecha_generacion' => $sacramento['fecha_generacion'],
                    'tipo_sacramento_id' => $sacramento['tipo_sacramento_id'],
                    'libro_id' => $sacramento['libro_id'],
                    'participantes' => $participantes
                ]
            ]);

        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            Logger::error("Error al obtener sacramento", [
                'sacramento_id' => $sacramentoId,
                'error' => $e->getMessage()
            ]);
        }

        exit();
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
     * Vista amigable de sacramentos para feligrés (card-based)
     * Muestra solo los sacramentos donde el feligrés es participante
     */
    public function misSacramentos()
    {
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        // Obtener ID del feligrés asociado al usuario
        $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

        if (!$feligresId) {
            // Mostrar vista vacía con mensaje de perfil incompleto
            $misSacramentos = [];
            $_SESSION['info'] = 'Tu perfil de feligrés aún no está completo. Contacta con la secretaría para completar tu registro.';
            include_once __DIR__ . '/../Vista/mis-sacramentos.php';
            return;
        }

        // Obtener sacramentos del feligrés
        $misSacramentos = $this->modeloSacramento->mdlObtenerSacramentosPorFeligres($feligresId);

        // Incluir vista card-based para feligrés
        include_once __DIR__ . '/../Vista/mis-sacramentos.php';
    }

    /**
     * Muestra la vista de sacramentos de un libro específico (Admin/Secretario)
     * Recibe tipo y número por GET desde Vista/libros.php
     */
    public function verLibro()
    {
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        // Solo admin y secretario pueden acceder a vista de libros
        if (!in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección.';
            header('Location: ?route=dashboard');
            exit;
        }

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

        // Pasar variables a la vista administrativa (DataTables)
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
        $draw = $_POST['draw'] ?? 1;
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? 10;
        $searchValue = $_POST['search']['value'] ?? null;

        if (empty($tipo) || !is_numeric($tipo) || empty($numero) || !is_numeric($numero)) {
            http_response_code(400);
            echo json_encode(['error' => 'Parámetros inválidos']);
            return;
        }

        // Limpiar cualquier output previo del buffer (evitar contaminación HTML en JSON)
        if (ob_get_level()) {
            ob_clean();
        }

        // 1. Obtener TOTAL de registros (sin filtro)
        $totalRecords = $this->modeloSacramento->contarPorLibro((int)$tipo, (int)$numero);

        // 2. Obtener registros FILTRADOS y PAGINADOS
        $sacramentos = $this->modeloSacramento->mdlObtenerPorLibro(
            (int)$tipo, 
            (int)$numero, 
            $searchValue, 
            (int)$start, 
            (int)$length
        );

        // 3. Contar registros filtrados (si hay búsqueda, es diferente al total)
        if (!empty($searchValue)) {
            $filteredRecords = $this->modeloSacramento->contarPorLibroFiltrado((int)$tipo, (int)$numero, $searchValue);
        } else {
            $filteredRecords = $totalRecords;
        }

        // Devolver JSON para DataTables
        header('Content-Type: application/json');
        echo json_encode([
            'draw' => (int)$draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $sacramentos
        ]);
        exit();
    }

    /**
     * Obtiene el ID del feligrés asociado a un usuario
     * @param int $usuarioId ID del usuario
     * @return int|null ID del feligrés o null si no existe
     */
    private function obtenerFeligresIdUsuario($usuarioId)
    {
        try {
            $conexion = Conexion::conectar();
            $sql = "SELECT id FROM feligreses WHERE usuario_id = ? AND estado_registro IS NULL LIMIT 1";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$usuarioId]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['id'] : null;
        } catch (PDOException $e) {
            Logger::error("Error al obtener feligrés por usuario: " . $e->getMessage());
            return null;
        }
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