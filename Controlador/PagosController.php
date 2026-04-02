<?php

// ============================================================================
// PagosController.php - Refactorizado para usar ModeloPago
// ============================================================================

class PagosController extends BaseController
{
    private $modelo;
    private $modeloSolicitud;
    private $controladorCertificados;
    private $modeloConfiguracion; 

    public function __construct()
    {
        $this->modelo = new ModeloPago();
        $this->modeloSolicitud = new ModeloSolicitudCertificado();
        $this->controladorCertificados = new CertificadosController();
        $this->modeloConfiguracion = new ModeloConfiguracion(); 
    }

    /**
     * Lista todos los pagos
     */
    public function index()
    {
        // Verificar autenticación
        $this->requiereAutenticacion();

        Logger::info("Acceso a lista de pagos", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'rol' => $_SESSION['user-rol'] ?? 'none',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        // Procesar eliminación si viene por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            $this->eliminar();
        }

        try {
            // Obtener todos los pagos desde el modelo
            $pagos = $this->modelo->mdlObtenerTodos();

            // Calcular estadísticas
            $estadisticas = $this->modelo->mdlObtenerEstadisticas();

            Logger::info("Lista de pagos cargada exitosamente", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'total_pagos' => count($pagos),
                'total_recaudado' => $estadisticas['total_recaudado'] ?? 0
            ]);

            // Incluir vista (variables $pagos y $estadisticas están disponibles)
            include __DIR__ . '/../Vista/pagos.php';

        } catch (Exception $e) {
            Logger::error("Error al cargar lista de pagos", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $_SESSION['error'] = 'Error al cargar los pagos.';
            redirect('dashboard');
            
            exit();
        }
    }

    /**
     * Muestra formulario para crear pago
     */
    public function crear()
    {
        // Verificar permisos
        $this->requiereAdmin();

        Logger::info("Acceso a formulario de crear pago", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'rol' => $_SESSION['user-rol'] ?? 'none',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar creación
            $this->procesarCreacion();
        } else {
            try {
                // Mostrar formulario
                $certificados = $this->modelo->mdlObtenerCertificados();
                include __DIR__ . '/../Vista/agregar_pago.php';
            } catch (Exception $e) {
                Logger::error("Error al cargar formulario de pago", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'error' => $e->getMessage()
                ]);
                $_SESSION['error'] = 'Error al cargar el formulario.';
                redirect('pagos');
                
                exit();
            }
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

        Logger::info("Intento de crear pago", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'certificado_id' => $certificado_id,
            'valor' => $valor,
            'estado' => $estado,
            'metodo' => $metodo,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        // Validar campos requeridos
        if (empty($certificado_id) || empty($valor) || empty($estado) || empty($metodo)) {
            Logger::warning("Creación de pago fallida: campos faltantes", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id' => $certificado_id,
                'campos_vacios' => array_filter([
                    'certificado_id' => empty($certificado_id),
                    'valor' => empty($valor),
                    'estado' => empty($estado),
                    'metodo' => empty($metodo)
                ])
            ]);
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
            return;
        }

        // Validar tipo de dato
        if (!is_numeric($valor) || $valor <= 0) {
            Logger::warning("Creación de pago fallida: valor inválido", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id' => $certificado_id,
                'valor_recibido' => $valor
            ]);
            $_SESSION['error'] = 'El valor debe ser un número positivo.';
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
            return;
        }

        try {
            // Crear pago usando el modelo
            $resultado = $this->modelo->mdlCrear([
                'certificado_id' => (int)$certificado_id,
                'valor' => (float)$valor,
                'estado' => $estado,
                'metodo_de_pago' => $metodo
            ]);

            if ($resultado['exito']) {
                Logger::info("Pago creado exitosamente", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'certificado_id' => $certificado_id,
                    'valor' => (float)$valor,
                    'estado' => $estado,
                    'metodo' => $metodo
                ]);
                $_SESSION['success'] = $resultado['mensaje'];
                redirect('pagos');
                exit();
            } else {
                Logger::warning("Creación de pago fallida", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'certificado_id' => $certificado_id,
                    'mensaje' => $resultado['mensaje']
                ]);
                $_SESSION['error'] = $resultado['mensaje'];
                $certificados = $this->modelo->mdlObtenerCertificados();
                include __DIR__ . '/../Vista/agregar_pago.php';
            }

        } catch (Exception $e) {
            Logger::error("Error crítico al crear pago", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id' => $certificado_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $_SESSION['error'] = 'Error al crear el pago.';
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

        Logger::info("Acceso a actualización de pago", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'pago_id' => $id,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        if (empty($id) || !is_numeric($id)) {
            Logger::warning("Actualización de pago fallida: ID inválido", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'pago_id_recibido' => $id
            ]);
            $_SESSION['error'] = 'ID de pago inválido.';
            redirect('pagos');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar actualización
            $this->procesarActualizacion((int)$id);
        } else {
            try {
                // Mostrar formulario
                $pago = $this->modelo->mdlObtenerPorId((int)$id);

                if (!$pago) {
                    Logger::warning("Pago no encontrado para actualización", [
                        'user_id' => $_SESSION['user-id'] ?? 'guest',
                        'pago_id' => $id
                    ]);
                    $_SESSION['error'] = 'Pago no encontrado.';
                    redirect('pagos');
                    exit();
                }

                include __DIR__ . '/../Vista/actualizar_pago.php';

            } catch (Exception $e) {
                Logger::error("Error al cargar formulario de actualización de pago", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'pago_id' => $id,
                    'error' => $e->getMessage()
                ]);
                $_SESSION['error'] = 'Error al cargar el formulario.';
                redirect('pagos');
                exit();
            }
        }
    }

    /**
     * Procesa la actualización de un pago
     */
    private function procesarActualizacion($id)
    {
        $estado = $_POST['estado'] ?? null;
        $metodo = $_POST['metodo_de_pago'] ?? null;

        Logger::info("Intento de actualizar pago", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'pago_id' => $id,
            'estado' => $estado,
            'metodo' => $metodo,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        // Validar campos
        if (empty($estado) || empty($metodo)) {
            Logger::warning("Actualización de pago fallida: campos faltantes", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'pago_id' => $id,
                'estado_vacio' => empty($estado),
                'metodo_vacio' => empty($metodo)
            ]);
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            $pago = $this->modelo->mdlObtenerPorId($id);
            include __DIR__ . '/../Vista/actualizar_pago.php';
            return;
        }

        try {
            // Actualizar usando el modelo
            $resultado = $this->modelo->mdlActualizar($id, [
                'estado' => $estado,
                'metodo_de_pago' => $metodo
            ]);

            if ($resultado['exito']) {
                Logger::info("Pago actualizado exitosamente", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'pago_id' => $id,
                    'estado' => $estado,
                    'metodo' => $metodo
                ]);
                $_SESSION['success'] = $resultado['mensaje'];
                redirect('pagos');
                exit();
            } else {
                Logger::warning("Actualización de pago fallida", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'pago_id' => $id,
                    'mensaje' => $resultado['mensaje']
                ]);
                $_SESSION['error'] = $resultado['mensaje'];
                $pago = $this->modelo->mdlObtenerPorId($id);
                include __DIR__ . '/../Vista/actualizar_pago.php';
            }

        } catch (Exception $e) {
            Logger::error("Error crítico al actualizar pago", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'pago_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $_SESSION['error'] = 'Error al actualizar el pago.';
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

        Logger::info("Intento de eliminar pago", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'pago_id' => $id,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        if (empty($id) || !is_numeric($id)) {
            Logger::warning("Eliminación de pago fallida: ID inválido", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'pago_id_recibido' => $id
            ]);
            $_SESSION['error'] = 'ID inválido.';
            return;
        }

        try {
            // Eliminar usando el modelo
            $resultado = $this->modelo->mdlEliminar((int)$id);

            if ($resultado['exito']) {
                Logger::info("Pago eliminado exitosamente", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'pago_id' => $id
                ]);
                $_SESSION['success'] = $resultado['mensaje'];
            } else {
                Logger::warning("Eliminación de pago fallida", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'pago_id' => $id,
                    'mensaje' => $resultado['mensaje']
                ]);
                $_SESSION['error'] = $resultado['mensaje'];
            }

        } catch (Exception $e) {
            Logger::error("Error crítico al eliminar pago", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'pago_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $_SESSION['error'] = 'Error al eliminar el pago.';
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

        Logger::info("Acceso a página de pago de certificado", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'certificado_id' => $certificadoId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
        
        $precioCertificado = $this->modeloConfiguracion->obtenerPrecioCertificado($certificado['tipo_certificado'] ?? 'general');

        if (empty($certificadoId) || !is_numeric($certificadoId)) {
            Logger::warning("Pago de certificado fallido: ID inválido", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id_recibido' => $certificadoId
            ]);
            $_SESSION['error'] = 'ID de certificado inválido.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }

        try {
            // Obtener certificado
            $certificado = $this->modeloSolicitud->mdlObtenerPorId($certificadoId);

            if (!$certificado) {
                Logger::warning("Certificado no encontrado para pago", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'certificado_id' => $certificadoId
                ]);
                $_SESSION['error'] = 'Certificado no encontrado.';
                header('Location: ?route=certificados/mis-solicitudes');
                exit;
            }

            // Validar que esté pendiente de pago
            if ($certificado['estado'] !== 'pendiente_pago') {
                Logger::warning("Intento de pagar certificado ya procesado", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'certificado_id' => $certificadoId,
                    'estado_actual' => $certificado['estado']
                ]);
                $_SESSION['error'] = 'Este certificado ya fue pagado o procesado.';
                header('Location: ?route=certificados/mis-solicitudes');
                exit;
            }

            // Validar que el usuario sea el solicitante
            $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);
            if ($certificado['solicitante_id'] != $feligresId) {
                Logger::warning("Intento de pago no autorizado de certificado", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'certificado_id' => $certificadoId,
                    'solicitante_id' => $certificado['solicitante_id'],
                    'feligres_id' => $feligresId,
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error'] = 'No tiene permiso para pagar este certificado.';
                header('Location: ?route=certificados/mis-solicitudes');
                exit;
            }

            Logger::info("Página de pago de certificado cargada exitosamente", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id' => $certificadoId,
                'tipo_certificado' => $certificado['tipo_certificado'] ?? 'no especificado'
            ]);

            // Mostrar vista de pago
            include_once __DIR__ . '/../Vista/pagar-certificado.php';

        } catch (Exception $e) {
            Logger::error("Error al cargar página de pago de certificado", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id' => $certificadoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $_SESSION['error'] = 'Error al cargar la página de pago.';
            header('Location: ?route=certificados/mis-solicitudes');
            exit;
        }
    }

    

    

    /**
     * Registra un pago en efectivo (solo Secretario/Admin) - VERSIÓN AJAX JSON
     */
    public function registrarPagoEfectivo()
    {
        // 1. Limpiamos buffer y forzamos salida JSON para que AJAX no falle
        if (ob_get_level()) ob_clean();
        header('Content-Type: application/json');

        // Verificar que sea Secretario o Admin
        if (!isset($_SESSION['user-rol']) || !in_array($_SESSION['user-rol'], ['Secretario', 'Administrador'])) {
            Logger::warning("Intento de registrar pago en efectivo sin permisos", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'rol' => $_SESSION['user-rol'] ?? 'none',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            echo json_encode(['success' => false, 'message' => 'No tiene permisos para realizar esta acción.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $certificadoId = $_POST['certificado_id'] ?? null;
        $valor = $_POST['monto'] ?? $_POST['valor'] ?? null; // Aceptamos 'monto' que es lo que manda tu AJAX

        Logger::info("Intento de registrar pago en efectivo", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'rol' => $_SESSION['user-rol'] ?? 'none',
            'certificado_id' => $certificadoId,
            'valor' => $valor,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        if (empty($certificadoId) || !is_numeric($certificadoId)) {
            Logger::warning("Registro de pago en efectivo fallido: ID inválido", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id_recibido' => $certificadoId
            ]);
            echo json_encode(['success' => false, 'message' => 'ID de certificado inválido.']);
            exit;
        }

        if (empty($valor) || !is_numeric($valor) || $valor <= 0) {
            Logger::warning("Registro de pago en efectivo fallido: valor inválido", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id' => $certificadoId,
                'valor_recibido' => $valor
            ]);
            echo json_encode(['success' => false, 'message' => 'Valor de pago inválido.']);
            exit;
        }

        try {
            // Obtener certificado
            $certificado = $this->modeloSolicitud->mdlObtenerPorId($certificadoId);

            if (!$certificado || $certificado['estado'] !== 'pendiente_pago') {
                Logger::warning("Certificado no válido para pago en efectivo", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'certificado_id' => $certificadoId,
                    'estado' => $certificado['estado'] ?? 'no encontrado'
                ]);
                echo json_encode(['success' => false, 'message' => 'Certificado no válido para pago.']);
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

                Logger::info("Pago en efectivo registrado exitosamente", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'certificado_id' => $certificadoId,
                    'valor' => (float)$valor,
                    'pdf_generado' => $pdfGenerado
                ]);

                if ($pdfGenerado) {
                    echo json_encode(['success' => true, 'message' => 'Pago en efectivo registrado y Certificado generado exitosamente.']);
                } else {
                    echo json_encode(['success' => true, 'message' => 'Pago registrado. Hubo un error al generar el PDF (revisar logs).']);
                }
                exit;

            } else {
                Logger::warning("Registro de pago en efectivo fallido", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'certificado_id' => $certificadoId,
                    'mensaje' => $resultadoPago['mensaje']
                ]);
                echo json_encode(['success' => false, 'message' => 'Error al registrar el pago en BD.']);
                exit;
            }

        } catch (Exception $e) {
            Logger::error("Error crítico al registrar pago en efectivo", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id' => $certificadoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            echo json_encode(['success' => false, 'message' => 'Error crítico al registrar el pago.']);
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

        Logger::info("Acceso a vista de mis pagos", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        try {
            // Obtener feligrés ID del usuario autenticado
            $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

            if (!$feligresId) {
                Logger::warning("Perfil de feligrés no encontrado para usuario", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest'
                ]);
                $_SESSION['error'] = 'No se encontró un perfil de feligrés asociado a su cuenta.';
                header('Location: ?route=dashboard');
                exit;
            }

            // Obtener pagos pendientes usando el modelo de solicitudes
            $pagosPendientes = $this->modeloSolicitud->mdlObtenerPendientesPago($feligresId);

            Logger::info("Pagos pendientes cargados exitosamente", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'feligres_id' => $feligresId,
                'total_pendientes' => count($pagosPendientes)
            ]);

            // Incluir vista
            include_once __DIR__ . '/../Vista/mis-pagos.php';

        } catch (Exception $e) {
            Logger::error("Error al cargar mis pagos", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $_SESSION['error'] = 'Error al cargar sus pagos.';
            header('Location: ?route=dashboard');
            exit;
        }
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
}
