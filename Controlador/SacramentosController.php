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

        Logger::info("Solicitud de datos de sacramento", [
            'sacramento_id' => $sacramentoId,
            'user_id' => $_SESSION['user-id'] ?? 'guest'

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