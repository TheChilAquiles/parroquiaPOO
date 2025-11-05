<?php

use Dompdf\Dompdf;
use Dompdf\Options;

// ============================================================================
// CertificadosController.php
// ============================================================================

class CertificadosController
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
        include_once __DIR__ . '/../Vista/certificados.php';
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
                error_log("Certificado no encontrado: $certificadoId");
                return false;
            }

            // Validar que esté en estado pendiente_pago o ya pagado
            if (!in_array($certificado['estado'], ['pendiente_pago', 'generado'])) {
                error_log("Estado inválido para generación: " . $certificado['estado']);
                return false;
            }

            // Obtener datos del sacramento
            $sacramento = $this->modeloSacramento->mdlObtenerPorId($certificado['sacramento_id']);

            if (!$sacramento) {
                error_log("Sacramento no encontrado: " . $certificado['sacramento_id']);
                return false;
            }

            // Obtener datos del feligrés
            $feligres = $this->modeloFeligres->mdlObtenerPorId($certificado['feligres_certificado_id']);

            if (!$feligres) {
                error_log("Feligrés no encontrado: " . $certificado['feligres_certificado_id']);
                return false;
            }

            // Preparar datos para el PDF
            $nombreCompleto = trim(
                $feligres['primer_nombre'] . ' ' .
                ($feligres['segundo_nombre'] ?? '') . ' ' .
                $feligres['primer_apellido'] . ' ' .
                ($feligres['segundo_apellido'] ?? '')
            );

            $tipoSacramento = $certificado['tipo_certificado'] ?? 'Sacramento';
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
                    </p>

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
                    <p>Válido hasta: ' . date('d/m/Y', strtotime('+30 days')) . '</p>
                    <p class="codigo-certificado">Código: CERT-' . str_pad($certificadoId, 8, '0', STR_PAD_LEFT) . '</p>
                </div>
            </body>
            </html>';

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Crear directorio si no existe
            $outDir = __DIR__ . '/../certificados_generados';
            if (!is_dir($outDir)) {
                mkdir($outDir, 0755, true);
            }

            // Nombre del archivo
            $safeName = preg_replace('/[^A-Za-z0-9_-]/', '', str_replace(' ', '_', $nombreCompleto));
            $filename = 'cert_' . $certificadoId . '_' . $safeName . '.pdf';
            $outPath = $outDir . '/' . $filename;

            // Guardar PDF
            file_put_contents($outPath, $dompdf->output());

            // Actualizar registro en BD
            $rutaRelativa = 'certificados_generados/' . $filename;
            $actualizado = $this->modeloSolicitud->mdlActualizarTrasGeneracion($certificadoId, $rutaRelativa);

            if (!$actualizado) {
                error_log("Error al actualizar BD para certificado: $certificadoId");
                return false;
            }

            return true;

        } catch (Exception $e) {
            error_log("Error al generar certificado automático: " . $e->getMessage());
            return false;
        }
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
            error_log("Error al generar certificado desde sacramento: " . $e->getMessage());
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
            error_log("Error en generarSimplificado: " . $e->getMessage());
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
            // Verificar autenticación
            if (!isset($_SESSION['logged']) || !in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'No autorizado'
                ]);
                exit;
            }

            // Obtener todos los certificados
            $certificados = $this->modeloSolicitud->mdlObtenerTodosLosCertificados();

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
            error_log("Error en listarTodos: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al listar certificados'
            ]);
        }

        exit;
    }
}