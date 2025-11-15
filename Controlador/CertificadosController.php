<?php

use Dompdf\Dompdf;
use Dompdf\Options;

// ============================================================================
// CertificadosController.php
// ============================================================================

class CertificadosController extends BaseController
{
    private $modelo;
    private $modeloSolicitud;
    private $modeloSacramento;
    private $modeloFeligres;

    public function __construct()
    {
        $this->modelo = new ModeloCertificados();
        $this->modeloSolicitud = new ModeloSolicitudCertificado();
        $this->modeloSacramento = new ModeloSacramento();
        $this->modeloFeligres = new ModeloFeligres();
    }

    public function mostrar()
    {
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        $rol = $_SESSION['user-rol'];

        // Administrador y Secretario ven vista administrativa con DataTables
        if (in_array($rol, ['Administrador', 'Secretario'])) {
            include_once __DIR__ . '/../Vista/certificados.php';
        }
        // Feligrés ve vista amigable con cards
        else {
            // Obtener certificados del feligrés
            $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

            if (!$feligresId) {
                // Mostrar vista vacía con mensaje de perfil incompleto
                $misCertificados = [];
                $_SESSION['info'] = 'Tu perfil de feligrés aún no está completo. Contacta con la secretaría para completar tu registro y poder solicitar certificados.';
                include_once __DIR__ . '/../Vista/mis-certificados.php';
                return;
            }

            // Obtener todos los certificados (propios + familiares + generados por secretario)
            $misCertificados = $this->modeloSolicitud->mdlObtenerMisSolicitudes($feligresId);

            include_once __DIR__ . '/../Vista/mis-certificados.php';
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

    public function generar()
    {
        // NUEVO: Manejar generación desde sacramento_id (vía GET)
        if (isset($_GET['sacramento_id'])) {
            $this->generarDesdeSacramento((int)$_GET['sacramento_id']);
            return;
        }

        // LEGACY: Manejar POST con formulario manual
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->mostrar();
            return;
        }

        // Validar campos requeridos
        $required = ['usuario_id', 'feligres_id', 'nombre_feligres', 'sacramento', 'fecha_realizacion', 'lugar'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = "Falta el campo requerido: $field";
                $this->mostrar();
                return;
            }
        }

        try {
            $data = [
                'usuario_id' => (int)$_POST['usuario_id'],
                'feligres_id' => (int)$_POST['feligres_id'],
                'sacramento' => htmlspecialchars($_POST['sacramento'], ENT_QUOTES, 'UTF-8'),
                'fecha_realizacion' => $_POST['fecha_realizacion'],
                'lugar' => htmlspecialchars($_POST['lugar'], ENT_QUOTES, 'UTF-8'),
                'observaciones' => $_POST['observaciones'] ?? ''
            ];

            // Guardar en BD
            $id = $this->modelo->crear($data);

            // Limpiar nombre para archivo
            $safeName = preg_replace('/[^A-Za-z0-9 _.-]/', '', $_POST['nombre_feligres']);
            $safeName = trim(str_replace(' ', '_', $safeName));
            $filename = "certificado_{$safeName}.pdf";

            // Generar PDF con DomPDF
            require_once __DIR__ . '/../vendor/autoload.php';

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);

            // Construir HTML del certificado
            $html = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: Arial, sans-serif; padding: 40px; text-align: center; }
                    h1 { color: #333; font-size: 24px; margin-bottom: 30px; }
                    .content { font-size: 16px; line-height: 1.8; text-align: justify; margin: 30px 0; }
                    .firma { text-align: right; margin-top: 60px; font-style: italic; }
                </style>
            </head>
            <body>
                <h1>Certificado de ' . htmlspecialchars($data['sacramento']) . '</h1>
                <div class="content">
                    Se certifica que ' . htmlspecialchars($_POST['nombre_feligres']) . ' ha recibido el sacramento de ' .
                    htmlspecialchars($data['sacramento']) . ' en fecha ' . htmlspecialchars($data['fecha_realizacion']) .
                    ' en ' . htmlspecialchars($data['lugar']) . '.
                </div>
                <div class="firma">Firmado por la parroquia</div>
            </body>
            </html>';

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Guardar PDF
            $outDir = __DIR__ . '/certificados_files';
            if (!is_dir($outDir)) {
                mkdir($outDir, 0755, true);
            }
            $outPath = $outDir . '/' . $filename;
            file_put_contents($outPath, $dompdf->output());

            // Descargar
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile($outPath);
            exit();

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al generar certificado: ' . $e->getMessage();
            $this->mostrar();
        }
    }

    /**
     * Genera automáticamente el PDF de un certificado tras confirmar pago
     * Llamado por PagosController cuando se confirma el pago
     * @param int $certificadoId ID del certificado
     * @return bool True si se generó exitosamente, false en caso contrario
     */
    public function generarAutomatico($certificadoId)
    {
        try {
            // Obtener datos del certificado
            $certificado = $this->modeloSolicitud->mdlObtenerPorId($certificadoId);

            if (!$certificado) {
                Logger::error("Certificado no encontrado: $certificadoId");
                return false;
            }

            // Validar que esté en estado pendiente_pago o ya pagado
            if (!in_array($certificado['estado'], ['pendiente_pago', 'generado'])) {
                Logger::error("Estado inválido para generación:", ['info' => $certificado['estado']]);
                return false;
            }

            // Obtener datos del sacramento
            $sacramento = $this->modeloSacramento->mdlObtenerPorId($certificado['sacramento_id']);

            if (!$sacramento) {
                Logger::error("Sacramento no encontrado:", ['info' => $certificado['sacramento_id']]);
                return false;
            }

            // Obtener datos del feligrés
            $feligres = $this->modeloFeligres->mdlObtenerPorId($certificado['feligres_certificado_id']);

            if (!$feligres) {
                Logger::error("Feligrés no encontrado:", ['info' => $certificado['feligres_certificado_id']]);
                return false;
            }

            // Obtener participantes del sacramento para datos adicionales
            $participantes = $this->modeloSacramento->getParticipantes($certificado['sacramento_id']);

            // Preparar datos para el generador de certificados
            $modeloConfiguracion = new ModeloConfiguracion();

            $nombreCompleto = trim(
                $feligres['primer_nombre'] . ' ' .
                ($feligres['segundo_nombre'] ?? '') . ' ' .
                $feligres['primer_apellido'] . ' ' .
                ($feligres['segundo_apellido'] ?? '')
            );

            // Determinar tipo de sacramento (normalizar)
            $tipoSacramento = strtolower($certificado['tipo_certificado'] ?? 'bautismo');
            if (!in_array($tipoSacramento, ['bautismo', 'confirmacion', 'matrimonio', 'defuncion'])) {
                $tipoSacramento = 'bautismo'; // Default
            }

            // Preparar datos para la plantilla
            $datos = [
                // Datos de la parroquia (desde configuración)
                'NOMBRE_PARROQUIA' => $modeloConfiguracion->obtenerPorClave('parroquia_nombre', 'Parroquia'),
                'DIRECCION_PARROQUIA' => $modeloConfiguracion->obtenerPorClave('parroquia_direccion', ''),
                'CIUDAD' => $modeloConfiguracion->obtenerPorClave('parroquia_ciudad', ''),
                'PAIS' => $modeloConfiguracion->obtenerPorClave('parroquia_pais', 'Colombia'),

                // Firmantes (desde configuración)
                'NOMBRE_PARROCO' => $modeloConfiguracion->obtenerPorClave('parroco_nombre', 'Párroco'),
                'NOMBRE_SECRETARIO' => $modeloConfiguracion->obtenerPorClave('secretario_nombre', 'Secretario(a)'),

                // Datos del libro
                'NUMERO_LIBRO' => $sacramento['libro_id'] ?? '',
                'NUMERO_PAGINA' => $sacramento['num_pagina'] ?? '',
                'NUMERO_REGISTRO' => $sacramento['num_registro'] ?? '',

                // Datos del certificado
                'NOMBRE_COMPLETO' => $nombreCompleto,
                'FECHA_NACIMIENTO' => isset($feligres['fecha_nacimiento']) ? date('d/m/Y', strtotime($feligres['fecha_nacimiento'])) : '',
                'LUGAR_NACIMIENTO' => $feligres['lugar_nacimiento'] ?? '',

                // Datos del sacramento
                'FECHA_' . strtoupper($tipoSacramento) => date('d/m/Y', strtotime($sacramento['fecha_generacion'])),
                'LUGAR_' . strtoupper($tipoSacramento) => $sacramento['lugar'] ?? '',
                'NOMBRE_MINISTRO' => $sacramento['ministro'] ?? '',

                // Datos de padrinos/padres (extraer de participantes)
                'NOMBRE_PADRE' => $this->obtenerNombreParticipante($participantes, 'Padre') ?: '',
                'NOMBRE_MADRE' => $this->obtenerNombreParticipante($participantes, 'Madre') ?: '',
                'NOMBRE_PADRINOS' => $this->obtenerNombreParticipante($participantes, 'Padrino') ?: '',
                'NOMBRE_PADRINO' => $this->obtenerNombreParticipante($participantes, 'Padrino') ?: '',
            ];

            // Usar el nuevo servicio de generación
            $generador = new CertificadoGenerador();
            $resultado = $generador->generar($tipoSacramento, $datos, $certificadoId);

            if (!$resultado['success']) {
                Logger::error("Error al generar certificado con plantilla", [
                    'certificado_id' => $certificadoId,
                    'mensaje' => $resultado['message']
                ]);
                return false;
            }

            // Actualizar registro en BD
            $actualizado = $this->modeloSolicitud->mdlActualizarTrasGeneracion(
                $certificadoId,
                $resultado['relative_path']
            );

            if (!$actualizado) {
                Logger::error("Error al actualizar BD para certificado: $certificadoId");
                return false;
            }

            Logger::info("Certificado generado exitosamente con plantilla", [
                'certificado_id' => $certificadoId,
                'tipo' => $tipoSacramento,
                'archivo' => $resultado['filename']
            ]);

            return true;

        } catch (Exception $e) {
            Logger::error("Error al generar certificado automático:", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Obtiene el nombre de un participante por su rol
     */
    private function obtenerNombreParticipante($participantes, $rol)
    {
        foreach ($participantes as $participante) {
            if ($participante['rol'] === $rol) {
                return trim(
                    $participante['primer_nombre'] . ' ' .
                    ($participante['segundo_nombre'] ?? '') . ' ' .
                    $participante['primer_apellido'] . ' ' .
                    ($participante['segundo_apellido'] ?? '')
                );
            }
        }
        return '';
    }

    /**
     * Genera certificado directamente desde un sacramento_id
     * Llamado cuando se hace clic en "Generar Certificado" desde la vista de sacramentos
     * @param int $sacramentoId ID del sacramento
     */
    private function generarDesdeSacramento($sacramentoId)
    {
        try {
            // Obtener datos del sacramento
            $sacramento = $this->modeloSacramento->mdlObtenerPorId($sacramentoId);

            if (!$sacramento) {
                $_SESSION['error'] = "Sacramento no encontrado con ID: $sacramentoId";
                header('Location: ?route=sacramentos');
                exit();
            }

            // Obtener participantes del sacramento
            $participantes = $this->modeloSacramento->getParticipantes($sacramentoId);

            if (empty($participantes)) {
                $_SESSION['error'] = "No se encontraron participantes para este sacramento";
                header('Location: ?route=sacramentos');
                exit();
            }

            // Determinar el participante principal según el tipo de sacramento
            $tipoSacramentoId = (int)$sacramento['tipo_sacramento_id'];
            $participantePrincipal = null;
            $tipoSacramento = '';

            // Mapear tipo de sacramento
            $tiposSacramento = [
                1 => 'Bautismo',
                2 => 'Confirmación',
                3 => 'Defunción',
                4 => 'Matrimonio'
            ];
            $tipoSacramento = $tiposSacramento[$tipoSacramentoId] ?? 'Sacramento';

            // Buscar participante principal por rol
            $rolesPrincipales = [
                1 => 'Bautizado',      // Bautismo
                2 => 'Confirmando',     // Confirmación
                3 => 'Difunto',         // Defunción
                4 => 'Esposo'           // Matrimonio (tomar esposo como principal)
            ];

            $rolBuscado = $rolesPrincipales[$tipoSacramentoId] ?? null;

            foreach ($participantes as $participante) {
                if ($participante['rol'] === $rolBuscado) {
                    $participantePrincipal = $participante;
                    break;
                }
            }

            // Si no se encuentra rol principal, tomar el primero
            if (!$participantePrincipal) {
                $participantePrincipal = $participantes[0];
            }

            // Construir nombre completo
            $nombreCompleto = trim(
                $participantePrincipal['primer_nombre'] . ' ' .
                ($participantePrincipal['segundo_nombre'] ?? '') . ' ' .
                $participantePrincipal['primer_apellido'] . ' ' .
                ($participantePrincipal['segundo_apellido'] ?? '')
            );

            $fechaSacramento = date('d/m/Y', strtotime($sacramento['fecha_generacion']));
            $lugarSacramento = $sacramento['lugar'] ?? 'Parroquia';

            // Generar PDF con DomPDF
            require_once __DIR__ . '/../vendor/autoload.php';

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);

            // Construir HTML del certificado
            $html = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <style>
                    body {
                        font-family: "Times New Roman", serif;
                        padding: 60px;
                        text-align: center;
                        background: #fff;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 40px;
                    }
                    h1 {
                        color: #1a1a1a;
                        font-size: 32px;
                        margin-bottom: 10px;
                        text-transform: uppercase;
                        letter-spacing: 2px;
                    }
                    h2 {
                        color: #444;
                        font-size: 24px;
                        margin-bottom: 30px;
                        font-weight: normal;
                    }
                    .content {
                        font-size: 18px;
                        line-height: 2.0;
                        text-align: justify;
                        margin: 50px 0;
                        padding: 0 40px;
                    }
                    .nombre-feligres {
                        font-weight: bold;
                        text-decoration: underline;
                    }
                    .participantes-list {
                        margin: 30px 0;
                        text-align: left;
                        padding-left: 80px;
                    }
                    .participante-item {
                        margin: 10px 0;
                        font-size: 16px;
                    }
                    .firma {
                        text-align: center;
                        margin-top: 80px;
                        font-style: italic;
                    }
                    .firma-line {
                        width: 300px;
                        border-top: 1px solid #000;
                        margin: 60px auto 10px auto;
                    }
                    .footer {
                        text-align: center;
                        margin-top: 40px;
                        font-size: 12px;
                        color: #666;
                    }
                    .codigo-certificado {
                        font-family: monospace;
                        font-size: 10px;
                        color: #999;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Certificado de ' . htmlspecialchars($tipoSacramento) . '</h1>
                    <h2>Parroquia</h2>
                </div>

                <div class="content">
                    <p>
                        Por medio de la presente se certifica que
                        <span class="nombre-feligres">' . htmlspecialchars($nombreCompleto) . '</span>
                        ha recibido el Sacramento de <strong>' . htmlspecialchars($tipoSacramento) . '</strong>
                        en fecha <strong>' . htmlspecialchars($fechaSacramento) . '</strong>
                        en <strong>' . htmlspecialchars($lugarSacramento) . '</strong>.
                    </p>';

            // Agregar lista de participantes si hay más de uno
            if (count($participantes) > 1) {
                $html .= '<div class="participantes-list"><p><strong>Participantes:</strong></p>';
                foreach ($participantes as $participante) {
                    $nombreParticipante = trim(
                        $participante['primer_nombre'] . ' ' .
                        ($participante['segundo_nombre'] ?? '') . ' ' .
                        $participante['primer_apellido'] . ' ' .
                        ($participante['segundo_apellido'] ?? '')
                    );
                    $html .= '<div class="participante-item">• <strong>' . htmlspecialchars($participante['rol']) . ':</strong> ' . htmlspecialchars($nombreParticipante) . '</div>';
                }
                $html .= '</div>';
            }

            $html .= '
                    <p>
                        Se expide el presente certificado a solicitud del interesado
                        para los fines que estime conveniente.
                    </p>
                </div>

                <div class="firma">
                    <div class="firma-line"></div>
                    <p>Firma del Párroco</p>
                    <p>Parroquia</p>
                </div>

                <div class="footer">
                    <p>Fecha de emisión: ' . date('d/m/Y H:i') . '</p>
                    <p class="codigo-certificado">Sacramento ID: ' . str_pad($sacramentoId, 8, '0', STR_PAD_LEFT) . '</p>
                </div>
            </body>
            </html>';

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Nombre del archivo
            $safeName = preg_replace('/[^A-Za-z0-9_-]/', '', str_replace(' ', '_', $nombreCompleto));
            $filename = 'certificado_' . $tipoSacramento . '_' . $safeName . '.pdf';

            // Forzar descarga
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            echo $dompdf->output();
            exit();

        } catch (Exception $e) {
            Logger::error("Error al generar certificado desde sacramento:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar certificado: ' . $e->getMessage();
            header('Location: ?route=sacramentos');
            exit();
        }
    }

    /**
     * Genera certificado con flujo simplificado (3 campos)
     * Para uso de Administrador/Secretario - busca automáticamente el feligrés y sacramento
     * Responde con JSON para AJAX
     */
    public function generarSimplificado()
    {
        // Limpiar buffer de salida
        if (ob_get_level()) {
            ob_clean();
        }

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            exit;
        }

        try {
            // Validar campos requeridos
            $required = ['tipo_documento_id', 'numero_documento', 'tipo_sacramento_id'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode([
                        'success' => false,
                        'message' => "Campo requerido faltante: $field"
                    ]);
                    exit;
                }
            }

            // Preparar datos
            $datos = [
                'usuario_generador_id' => $_SESSION['user-id'] ?? null,
                'tipo_documento_id' => (int)$_POST['tipo_documento_id'],
                'numero_documento' => trim($_POST['numero_documento']),
                'tipo_sacramento_id' => (int)$_POST['tipo_sacramento_id']
            ];

            // Crear certificado usando el modelo
            $resultado = $this->modeloSolicitud->mdlCrearCertificadoDirecto($datos);

            if ($resultado['status'] === 'error') {
                echo json_encode([
                    'success' => false,
                    'message' => $resultado['message']
                ]);
                exit;
            }

            // Certificado creado exitosamente
            $certificadoId = $resultado['id'];

            // Si se proporciona método de pago en efectivo, generar PDF automáticamente
            if (isset($_POST['metodo_pago']) && $_POST['metodo_pago'] === 'efectivo') {
                // Marcar como pagado
                $this->modeloSolicitud->mdlMarcarPagado($certificadoId);

                // Generar PDF automáticamente
                $generado = $this->generarAutomatico($certificadoId);

                if ($generado) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Certificado creado y PDF generado exitosamente',
                        'certificado_id' => $certificadoId,
                        'pdf_generado' => true
                    ]);
                } else {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Certificado creado pero hubo error al generar PDF',
                        'certificado_id' => $certificadoId,
                        'pdf_generado' => false
                    ]);
                }
            } else {
                // Sin pago en efectivo, solo crear solicitud
                echo json_encode([
                    'success' => true,
                    'message' => 'Certificado creado exitosamente. Pendiente de pago.',
                    'certificado_id' => $certificadoId,
                    'estado' => 'pendiente_pago'
                ]);
            }

        } catch (Exception $e) {
            Logger::error("Error en generarSimplificado:", ['error' => $e->getMessage()]);
            echo json_encode([
                'success' => false,
                'message' => 'Error al generar certificado: ' . $e->getMessage()
            ]);
        }

        exit;
    }

    /**
     * Lista todos los certificados (para vista admin con DataTables)
     * Responde con JSON para AJAX
     */
    public function listarTodos()
    {
        // Limpiar buffer de salida
        if (ob_get_level()) {
            ob_clean();
        }

        header('Content-Type: application/json');

        try {
            Logger::info("Intento de acceso a listarTodos", [
                'logged' => isset($_SESSION['logged']) ? $_SESSION['logged'] : 'not set',
                'user_rol' => isset($_SESSION['user-rol']) ? $_SESSION['user-rol'] : 'not set',
                'session_id' => session_id()
            ]);

            // Verificar autenticación
            if (!isset($_SESSION['logged']) || !in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
                Logger::error("Acceso denegado a listarTodos - no autorizado");
                echo json_encode([
                    'success' => false,
                    'message' => 'No autorizado'
                ]);
                exit;
            }

            // Obtener todos los certificados
            $certificados = $this->modeloSolicitud->mdlObtenerTodosLosCertificados();

            Logger::info("Certificados listados para admin", [
                'cantidad' => count($certificados)
            ]);

            // Formatear datos para DataTables
            $data = [];
            foreach ($certificados as $cert) {
                $data[] = [
                    'id' => $cert['id'],
                    'tipo_certificado' => $cert['tipo_certificado'],
                    'feligres_nombre' => $cert['feligres_nombre'],
                    'numero_documento' => $cert['numero_documento'],
                    'solicitante_nombre' => $cert['solicitante_nombre'],
                    'generador_nombre' => $cert['generador_nombre'] ?? 'Sistema',
                    'relacion' => $cert['relacion'] ?? 'Propio',
                    'fecha_solicitud' => date('d/m/Y', strtotime($cert['fecha_solicitud'])),
                    'fecha_generacion' => $cert['fecha_generacion'] ? date('d/m/Y', strtotime($cert['fecha_generacion'])) : 'N/A',
                    'fecha_expiracion' => $cert['fecha_expiracion'] ? date('d/m/Y', strtotime($cert['fecha_expiracion'])) : 'N/A',
                    'estado' => ucfirst(str_replace('_', ' ', $cert['estado'])),
                    'ruta_archivo' => $cert['ruta_archivo'],
                    'tipo_sacramento' => $cert['tipo_sacramento'],
                    'fecha_sacramento' => date('d/m/Y', strtotime($cert['fecha_sacramento']))
                ];
            }

            echo json_encode([
                'success' => true,
                'data' => $data
            ]);

        } catch (Exception $e) {
            Logger::error("Error en listarTodos:", ['error' => $e->getMessage()]);
            echo json_encode([
                'success' => false,
                'message' => 'Error al listar certificados'
            ]);
        }

        exit;
    }

    /**
     * Solicita un certificado desde la vista de sacramentos (feligrés)
     * Permite solicitar para sí mismo o para un familiar
     */
    public function solicitarDesdeSacramento()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged']) || !isset($_SESSION['user-id'])) {
            if (ob_get_level()) ob_clean();
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            exit;
        }

        // Verificar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (ob_get_level()) ob_clean();
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            exit;
        }

        try {
            $sacramentoId = $_POST['sacramento_id'] ?? null;
            $tipoSacramentoId = $_POST['tipo_sacramento_id'] ?? null;
            $paraQuien = $_POST['para_quien'] ?? 'yo';
            $familiarId = $_POST['familiar_id'] ?? null;

            // Validar datos requeridos
            if (empty($sacramentoId) || empty($tipoSacramentoId)) {
                throw new Exception('Datos incompletos');
            }

            // Obtener feligrés ID del usuario logueado
            $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

            if (!$feligresId) {
                throw new Exception('No se encontró perfil de feligrés');
            }

            // Verificar si ya existe una solicitud pendiente para este sacramento y feligrés
            $solicitudExistente = $this->modeloSolicitud->mdlVerificarSolicitudExistente(
                $sacramentoId,
                $feligresId,
                $tipoSacramentoId
            );

            if ($solicitudExistente) {
                throw new Exception('Ya existe una solicitud pendiente para este sacramento');
            }

            // Determinar para quién es el certificado
            $feligresCertificadoId = $feligresId; // Por defecto para el solicitante

            if ($paraQuien === 'familiar' && !empty($familiarId)) {
                // Verificar que el familiar_id es válido y pertenece al feligrés
                $conexion = Conexion::conectar();
                $sqlVerificar = "SELECT COUNT(*) as valido
                                FROM parientes p
                                WHERE ((p.feligres_sujeto_id = ? AND p.feligres_pariente_id = ?)
                                   OR (p.feligres_sujeto_id = ? AND p.feligres_pariente_id = ?))
                                AND p.estado_registro IS NULL";
                $stmtVerificar = $conexion->prepare($sqlVerificar);
                $stmtVerificar->execute([$feligresId, $familiarId, $familiarId, $feligresId]);
                $resultado = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

                if ($resultado['valido'] > 0) {
                    $feligresCertificadoId = $familiarId;
                } else {
                    throw new Exception('El familiar seleccionado no es válido');
                }
            }

            // Crear solicitud de certificado
            $datos = [
                'sacramento_id' => $sacramentoId,
                'tipo_sacramento_id' => $tipoSacramentoId,
                'feligres_certificado_id' => $feligresCertificadoId,
                'solicitante_id' => $feligresId,
                'para_quien' => $paraQuien,
                'estado' => 'pendiente_pago',
                'fecha_solicitud' => date('Y-m-d H:i:s')
            ];

            $resultado = $this->modeloSolicitud->mdlCrearSolicitud($datos);

            if ($resultado['status'] === 'success') {
                Logger::info("Certificado solicitado exitosamente", [
                    'certificado_id' => $resultado['id'],
                    'solicitante_id' => $feligresId,
                    'feligres_certificado_id' => $feligresCertificadoId,
                    'para_quien' => $paraQuien,
                    'sacramento_id' => $sacramentoId
                ]);

                if (ob_get_level()) ob_clean();
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => $resultado['message']
                ]);
            } else {
                throw new Exception($resultado['message'] ?? 'No se pudo crear la solicitud');
            }

        } catch (Exception $e) {
            if (ob_get_level()) ob_clean();
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            Logger::error("Error al solicitar certificado desde sacramento", [
                'usuario_id' => $_SESSION['user-id'] ?? null,
                'sacramento_id' => $sacramentoId ?? null,
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }

    /**
     * Obtiene la lista de familiares de un feligrés
     * Endpoint AJAX para cargar en el modal de solicitud
     */
    public function obtenerFamiliares()
    {
        // Verificar autenticación
        if (!isset($_SESSION['logged']) || !isset($_SESSION['user-id'])) {
            if (ob_get_level()) ob_clean();
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            exit;
        }

        try {
            // Obtener feligrés ID del usuario logueado
            $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

            if (!$feligresId) {
                throw new Exception('No se encontró perfil de feligrés');
            }

            // Obtener familiares (relaciones bidireccionales)
            $conexion = Conexion::conectar();
            $sql = "SELECT DISTINCT
                        CASE
                            WHEN p.feligres_sujeto_id = ? THEN p.feligres_pariente_id
                            ELSE p.feligres_sujeto_id
                        END as familiar_id,
                        CASE
                            WHEN p.feligres_sujeto_id = ? THEN CONCAT(f2.primer_nombre, ' ', f2.primer_apellido)
                            ELSE CONCAT(f1.primer_nombre, ' ', f1.primer_apellido)
                        END as nombre_completo,
                        pa.parentesco
                    FROM parientes p
                    JOIN feligreses f1 ON p.feligres_sujeto_id = f1.id
                    JOIN feligreses f2 ON p.feligres_pariente_id = f2.id
                    JOIN parentescos pa ON p.parentesco_id = pa.id
                    WHERE (p.feligres_sujeto_id = ? OR p.feligres_pariente_id = ?)
                    AND p.estado_registro IS NULL
                    AND f1.estado_registro IS NULL
                    AND f2.estado_registro IS NULL";

            $stmt = $conexion->prepare($sql);
            $stmt->execute([$feligresId, $feligresId, $feligresId, $feligresId]);
            $familiares = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (ob_get_level()) ob_clean();
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $familiares
            ]);

        } catch (Exception $e) {
            if (ob_get_level()) ob_clean();
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            Logger::error("Error al obtener familiares", [
                'usuario_id' => $_SESSION['user-id'] ?? null,
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }
}