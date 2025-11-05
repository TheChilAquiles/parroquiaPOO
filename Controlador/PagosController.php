<?php

// ============================================================================
// PagosController.php - Refactorizado para usar ModeloPago
// ============================================================================

class PagosController extends BaseController
{
    private $modelo;
    private $modeloSolicitud;
    private $controladorCertificados;

    public function __construct()
    {
        $this->modelo = new ModeloPago();
        $this->modeloSolicitud = new ModeloSolicitudCertificado();
        $this->controladorCertificados = new CertificadosController();
    }

    /**
     * Lista todos los pagos
     */
    public function index()
    {
        // Verificar autenticación
        $this->requiereAutenticacion();

        // Procesar eliminación si viene por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            $this->eliminar();
        }

        // Obtener todos los pagos desde el modelo
        $pagos = $this->modelo->mdlObtenerTodos();

        // Calcular estadísticas
        $estadisticas = $this->modelo->mdlObtenerEstadisticas();

        // Incluir vista (variables $pagos y $estadisticas están disponibles)
        include __DIR__ . '/../Vista/pagos.php';
    }

    /**
     * Muestra formulario para crear pago
     */
    public function crear()
    {
        // Verificar permisos
        $this->requiereAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar creación
            $this->procesarCreacion();
        } else {
            // Mostrar formulario
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
        }
    }

    /**
     * Procesa la creación de un nuevo pago
     */
    private function procesarCreacion()
    {
        $certificado_id = $_POST['certificado_id'] ?? null;
        $valor = $_POST['valor'] ?? null;
        $estado = $_POST['estado'] ?? null;
        $metodo = $_POST['metodo_de_pago'] ?? null;

        // Validar campos requeridos
        if (empty($certificado_id) || empty($valor) || empty($estado) || empty($metodo)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
            return;
        }

        // Validar tipo de dato
        if (!is_numeric($valor) || $valor <= 0) {
            $_SESSION['error'] = 'El valor debe ser un número positivo.';
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
            return;
        }

        // Crear pago usando el modelo
        $resultado = $this->modelo->mdlCrear([
            'certificado_id' => (int)$certificado_id,
            'valor' => (float)$valor,
            'estado' => $estado,
            'metodo_de_pago' => $metodo
        ]);

        if ($resultado['exito']) {
            $_SESSION['success'] = $resultado['mensaje'];
            header('Location: ?route=pagos');
            exit();
        } else {
            $_SESSION['error'] = $resultado['mensaje'];
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
        }
    }

    /**
     * Muestra formulario para actualizar pago
     */
    public function actualizar()
    {
        // Verificar permisos
        $this->requiereAdmin();

        $id = $_GET['id'] ?? $_POST['id'] ?? null;

        if (empty($id) || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de pago inválido.';
            header('Location: ?route=pagos');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar actualización
            $this->procesarActualizacion((int)$id);
        } else {
            // Mostrar formulario
            $pago = $this->modelo->mdlObtenerPorId((int)$id);

            if (!$pago) {
                $_SESSION['error'] = 'Pago no encontrado.';
                header('Location: ?route=pagos');
                exit();
            }

            include __DIR__ . '/../Vista/actualizar_pago.php';
        }
    }

    /**
     * Procesa la actualización de un pago
     */
    private function procesarActualizacion($id)
    {
        $estado = $_POST['estado'] ?? null;
        $metodo = $_POST['metodo_de_pago'] ?? null;

        // Validar campos
        if (empty($estado) || empty($metodo)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            $pago = $this->modelo->mdlObtenerPorId($id);
            include __DIR__ . '/../Vista/actualizar_pago.php';
            return;
        }

        // Actualizar usando el modelo
        $resultado = $this->modelo->mdlActualizar($id, [
            'estado' => $estado,
            'metodo_de_pago' => $metodo
        ]);

        if ($resultado['exito']) {
            $_SESSION['success'] = $resultado['mensaje'];
            header('Location: ?route=pagos');
            exit();
        } else {
            $_SESSION['error'] = $resultado['mensaje'];
            $pago = $this->modelo->mdlObtenerPorId($id);
            include __DIR__ . '/../Vista/actualizar_pago.php';
        }
    }

    /**
     * Elimina un pago
     */
    public function eliminar()
    {
        // Verificar permisos
        $this->requiereAdmin();

        $id = $_POST['id'] ?? null;

        if (empty($id) || !is_numeric($id)) {
            $_SESSION['error'] = 'ID inválido.';
            return;
        }

        // Eliminar usando el modelo
        $resultado = $this->modelo->mdlEliminar((int)$id);

        if ($resultado['exito']) {
            $_SESSION['success'] = $resultado['mensaje'];
        } else {
            $_SESSION['error'] = $resultado['mensaje'];
        }
    }

    /**
     * Muestra la página de pago para un certificado específico
     */
    public function pagarCertificado()
    {
        // Verificar autenticación
        $this->requiereAutenticacion();

        $certificadoId = $_GET['id'] ?? null;

        if (empty($certificadoId) || !is_numeric($certificadoId)) {
            $_SESSION['error'] = 'ID de certificado inválido.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Obtener certificado
        $certificado = $this->modeloSolicitud->mdlObtenerPorId($certificadoId);

        if (!$certificado) {
            $_SESSION['error'] = 'Certificado no encontrado.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Validar que esté pendiente de pago
        if ($certificado['estado'] !== 'pendiente_pago') {
            $_SESSION['error'] = 'Este certificado ya fue pagado o procesado.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Validar que el usuario sea el solicitante
        $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);
        if ($certificado['solicitante_id'] != $feligresId) {
            $_SESSION['error'] = 'No tiene permiso para pagar este certificado.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Mostrar vista de pago
        include_once __DIR__ . '/../Vista/pagar-certificado.php';
    }

    /**
     * Procesa el pago online de un certificado
     * En un entorno real, esto redirigiría a la pasarela de pago
     */
    public function procesarPagoOnline()
    {
        // Verificar autenticación
        $this->requiereAutenticacion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        $certificadoId = $_POST['certificado_id'] ?? null;
        $metodoPago = $_POST['metodo_pago'] ?? 'online';

        if (empty($certificadoId) || !is_numeric($certificadoId)) {
            $_SESSION['error'] = 'ID de certificado inválido.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Obtener certificado
        $certificado = $this->modeloSolicitud->mdlObtenerPorId($certificadoId);

        if (!$certificado || $certificado['estado'] !== 'pendiente_pago') {
            $_SESSION['error'] = 'Certificado no válido para pago.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Validar que el usuario sea el solicitante
        $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);
        if ($certificado['solicitante_id'] != $feligresId) {
            $_SESSION['error'] = 'No tiene permiso para pagar este certificado.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // TODO: Aquí iría la integración con pasarela de pago real
        // Por ahora, simularemos pago exitoso para desarrollo

        // Crear registro de pago
        $resultadoPago = $this->modelo->mdlCrear([
            'certificado_id' => $certificadoId,
            'valor' => 10.00, // Valor fijo por ahora
            'estado' => 'pagado',
            'metodo_de_pago' => $metodoPago
        ]);

        if ($resultadoPago['exito']) {
            // Marcar certificado como pagado
            $this->modeloSolicitud->mdlMarcarPagado($certificadoId);

            // Generar PDF automáticamente
            $pdfGenerado = $this->controladorCertificados->generarAutomatico($certificadoId);

            if ($pdfGenerado) {
                $_SESSION['success'] = 'Pago procesado exitosamente. Su certificado está listo para descargar.';
            } else {
                $_SESSION['success'] = 'Pago procesado. El certificado se está generando.';
            }

            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        } else {
            $_SESSION['error'] = 'Error al procesar el pago. Intente nuevamente.';
            header('Location: ?route=pagos/pagar-certificado&id=' . $certificadoId);
            exit;
        }
    }

    /**
     * Webhook para confirmar pagos online
     * Este endpoint sería llamado por la pasarela de pago
     */
    public function webhookConfirmacion()
    {
        // Deshabilitar output por defecto
        header('Content-Type: application/json');

        try {
            // Obtener datos del webhook
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            // Validar datos (ejemplo simple)
            if (empty($data['certificado_id']) || empty($data['estado'])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
                exit;
            }

            $certificadoId = $data['certificado_id'];
            $estado = $data['estado'];

            // Solo procesar si el pago fue exitoso
            if ($estado !== 'pagado') {
                http_response_code(200);
                echo json_encode(['status' => 'received', 'message' => 'Pago no exitoso']);
                exit;
            }

            // Marcar certificado como pagado
            $this->modeloSolicitud->mdlMarcarPagado($certificadoId);

            // Generar PDF automáticamente
            $pdfGenerado = $this->controladorCertificados->generarAutomatico($certificadoId);

            if ($pdfGenerado) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Certificado generado']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Error al generar certificado']);
            }
            exit;

        } catch (Exception $e) {
            error_log("Error en webhook: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error interno']);
            exit;
        }
    }

    /**
     * Registra un pago en efectivo (solo Secretario/Admin)
     */
    public function registrarPagoEfectivo()
    {
        // Verificar que sea Secretario o Admin
        if (!isset($_SESSION['user-rol']) || !in_array($_SESSION['user-rol'], ['Secretario', 'Administrador'])) {
            $_SESSION['error'] = 'No tiene permisos para realizar esta acción.';
            header('Location: ?route=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?route=pagos');
            exit;
        }

        $certificadoId = $_POST['certificado_id'] ?? null;
        $valor = $_POST['valor'] ?? null;

        if (empty($certificadoId) || !is_numeric($certificadoId)) {
            $_SESSION['error'] = 'ID de certificado inválido.';
            header('Location: ?route=pagos');
            exit;
        }

        if (empty($valor) || !is_numeric($valor) || $valor <= 0) {
            $_SESSION['error'] = 'Valor de pago inválido.';
            header('Location: ?route=pagos');
            exit;
        }

        // Obtener certificado
        $certificado = $this->modeloSolicitud->mdlObtenerPorId($certificadoId);

        if (!$certificado || $certificado['estado'] !== 'pendiente_pago') {
            $_SESSION['error'] = 'Certificado no válido para pago.';
            header('Location: ?route=pagos');
            exit;
        }

        // Registrar pago en efectivo
        $resultadoPago = $this->modelo->mdlCrear([
            'certificado_id' => $certificadoId,
            'valor' => (float)$valor,
            'estado' => 'pagado',
            'metodo_de_pago' => 'efectivo'
        ]);

        if ($resultadoPago['exito']) {
            // Marcar certificado como pagado
            $this->modeloSolicitud->mdlMarcarPagado($certificadoId);

            // Generar PDF automáticamente
            $pdfGenerado = $this->controladorCertificados->generarAutomatico($certificadoId);

            if ($pdfGenerado) {
                $_SESSION['success'] = 'Pago en efectivo registrado. Certificado generado exitosamente.';
            } else {
                $_SESSION['success'] = 'Pago registrado. Error al generar certificado (revisar logs).';
            }

            header('Location: ?route=pagos');
            exit;
        } else {
            $_SESSION['error'] = 'Error al registrar el pago.';
            header('Location: ?route=pagos');
            exit;
        }
    }

    /**
     * Muestra los pagos pendientes del feligrés autenticado
     */
    public function misPagos()
    {
        // Verificar autenticación
        $this->requiereAutenticacion();

        // Obtener feligrés ID del usuario autenticado
        $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

        if (!$feligresId) {
            $_SESSION['error'] = 'No se encontró un perfil de feligrés asociado a su cuenta.';
            header('Location: ?route=dashboard');
            exit;
        }

        // Obtener pagos pendientes usando el modelo de solicitudes
        $pagosPendientes = $this->modeloSolicitud->mdlObtenerPendientesPago($feligresId);

        // Incluir vista
        include_once __DIR__ . '/../Vista/mis-pagos.php';
    }

    /**
     * Obtiene el ID del feligrés asociado a un usuario
     * @param int $usuarioId ID del usuario
     * @return int|null ID del feligrés o null
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
            error_log("Error al obtener feligrés por usuario: " . $e->getMessage());
            return null;
        }
    }
}
