<?php


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

            use Dompdf\Dompdf;
            use Dompdf\Options;

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

            use Dompdf\Dompdf;
            use Dompdf\Options;

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
}