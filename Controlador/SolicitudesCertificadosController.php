<?php

// ============================================================================
// SolicitudesCertificadosController.php
// Gestiona solicitudes de certificados (propios y familiares)
// ============================================================================

class SolicitudesCertificadosController
{
    private $modeloSolicitud;
    private $modeloParentesco;
    private $modeloSacramento;
    private $modeloFeligres;

    public function __construct()
    {
        $this->modeloSolicitud = new ModeloSolicitudCertificado();
        $this->modeloParentesco = new ModeloParentesco();
        $this->modeloSacramento = new ModeloSacramento();
        $this->modeloFeligres = new ModeloFeligres();
    }

    /**
     * Muestra el formulario de solicitud de certificado
     */
    public function mostrarFormulario()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            header('Location: ?route=login');
            exit;
        }

        // Obtener ID del feligrés asociado al usuario
        $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

        if (!$feligresId) {
            $_SESSION['error'] = 'Debe completar sus datos personales primero.';
            header('Location: ?route=perfil');
            exit;
        }

        // Obtener sacramentos del feligrés (para solicitud propia)
        $sacramentosPropio = $this->modeloSacramento->mdlObtenerPorFeligres($feligresId);

        // Obtener familiares registrados
        $familiares = $this->modeloParentesco->mdlObtenerPorFeligres($feligresId);

        // Obtener tipos de parentesco (para formulario)
        $tiposParentesco = $this->modeloParentesco->mdlObtenerTiposParentesco();

        include_once __DIR__ . '/../Vista/solicitar-certificado.php';
    }

    /**
     * Busca sacramentos de un familiar (AJAX)
     */
    public function buscarSacramentosFamiliar()
    {
        // Verificar AJAX
        if (!$this->esAjax()) {
            http_response_code(400);
            exit;
        }

        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            echo json_encode(['status' => 'error', 'message' => 'No autenticado']);
            exit;
        }

        $familiarId = $_POST['familiar_id'] ?? null;

        if (empty($familiarId) || !is_numeric($familiarId)) {
            echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
            exit;
        }

        // Obtener ID del solicitante
        $solicitanteId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

        // Validar parentesco
        $validacion = $this->modeloSolicitud->mdlValidarParentesco($solicitanteId, $familiarId);

        if (!$validacion['valido']) {
            echo json_encode([
                'status' => 'error',
                'message' => $validacion['mensaje']
            ]);
            exit;
        }

        // Obtener sacramentos del familiar
        $sacramentos = $this->modeloSacramento->mdlObtenerPorFeligres($familiarId);

        echo json_encode([
            'status' => 'success',
            'sacramentos' => $sacramentos,
            'parentesco' => $validacion['parentesco_nombre']
        ]);
        exit;
    }

    /**
     * Crea una nueva solicitud de certificado
     */
    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->mostrarFormulario();
            return;
        }

        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            header('Location: ?route=login');
            exit;
        }

        // Validar datos recibidos
        $feligresCertificadoId = $_POST['feligres_certificado_id'] ?? null;
        $sacramentoId = $_POST['sacramento_id'] ?? null;
        $motivo = $_POST['motivo'] ?? 'Solicitud de certificado';

        if (empty($feligresCertificadoId) || empty($sacramentoId)) {
            $_SESSION['error'] = 'Debe seleccionar feligrés y sacramento.';
            $this->mostrarFormulario();
            return;
        }

        // Validar que sean números
        if (!is_numeric($feligresCertificadoId) || !is_numeric($sacramentoId)) {
            $_SESSION['error'] = 'Datos inválidos.';
            $this->mostrarFormulario();
            return;
        }

        // Obtener ID del solicitante
        $solicitanteId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

        if (!$solicitanteId) {
            $_SESSION['error'] = 'Error al obtener datos del solicitante.';
            $this->mostrarFormulario();
            return;
        }

        // Preparar datos para crear solicitud
        $datos = [
            'solicitante_id' => $solicitanteId,
            'feligres_certificado_id' => $feligresCertificadoId,
            'sacramento_id' => $sacramentoId,
            'motivo' => $motivo
        ];

        // Crear solicitud (el modelo valida automáticamente el parentesco)
        $resultado = $this->modeloSolicitud->mdlCrearSolicitud($datos);

        if ($resultado['status'] === 'error') {
            $_SESSION['error'] = $resultado['message'];
            $this->mostrarFormulario();
            return;
        }

        // Redirigir a la página de pago
        $_SESSION['success'] = 'Solicitud creada. Por favor proceda al pago.';
        header('Location: ?route=certificados/pagar&id=' . $resultado['id']);
        exit;
    }

    /**
     * Muestra el historial de solicitudes del usuario
     */
    public function misSolicitudes()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            header('Location: ?route=login');
            exit;
        }

        // Obtener ID del feligrés
        $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

        if (!$feligresId) {
            $_SESSION['error'] = 'Debe completar sus datos personales primero.';
            header('Location: ?route=perfil');
            exit;
        }

        // Obtener solicitudes
        $solicitudes = $this->modeloSolicitud->mdlObtenerMisSolicitudes($feligresId);

        include_once __DIR__ . '/../Vista/mis-certificados.php';
    }

    /**
     * Descarga un certificado (con validaciones)
     */
    public function descargar()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged'])) {
            header('Location: ?route=login');
            exit;
        }

        // Obtener ID del certificado
        $certificadoId = $_GET['id'] ?? null;

        if (empty($certificadoId) || !is_numeric($certificadoId)) {
            $_SESSION['error'] = 'ID de certificado inválido.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Obtener datos del certificado
        $certificado = $this->modeloSolicitud->mdlObtenerPorId($certificadoId);

        if (!$certificado) {
            $_SESSION['error'] = 'Certificado no encontrado.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Validar que el usuario sea el solicitante
        $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

        if ($certificado['solicitante_id'] != $feligresId) {
            $_SESSION['error'] = 'No tiene permiso para descargar este certificado.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Validar estado (debe estar generado o descargado, no expirado)
        if (!in_array($certificado['estado'], ['generado', 'descargado'])) {
            $_SESSION['error'] = 'El certificado no está disponible para descarga.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Validar expiración (30 días desde generación)
        if ($certificado['fecha_expiracion'] < date('Y-m-d H:i:s')) {
            $_SESSION['error'] = 'El certificado ha expirado. Debe solicitar uno nuevo.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Validar que exista el archivo
        if (empty($certificado['ruta_archivo']) || !file_exists($certificado['ruta_archivo'])) {
            $_SESSION['error'] = 'Archivo de certificado no encontrado.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        // Marcar como descargado (si aún no lo está)
        if ($certificado['estado'] === 'generado') {
            $this->modeloSolicitud->mdlMarcarDescargado($certificadoId);
        }

        // Servir archivo
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="certificado_' . $certificadoId . '.pdf"');
        header('Content-Length: ' . filesize($certificado['ruta_archivo']));
        readfile($certificado['ruta_archivo']);
        exit;
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
            Logger::error("Error al obtener feligrés por usuario:", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Verifica si la petición es AJAX
     * @return bool
     */
    private function esAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
