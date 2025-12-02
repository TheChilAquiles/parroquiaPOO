<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

/**
 * CertificadoGenerador
 * Servicio para generar certificados usando plantillas HTML y códigos QR
 */
class CertificadoGenerador
{
    private $plantillasDir;
    private $outputDir;
    private $baseUrl;

    public function __construct()
    {
        $this->plantillasDir = __DIR__ . '/../assets/plantillas/';
        $this->outputDir = __DIR__ . '/../certificados_generados/';

        // URL base para verificación de certificados
        $this->baseUrl = $_ENV['APP_URL'] ?? 'http://localhost';

        // Crear directorio de salida si no existe
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
    }

    /**
     * Genera un certificado usando una plantilla
     *
     * @param string $tipoSacramento Tipo: 'bautismo', 'confirmacion', 'matrimonio', 'defuncion'
     * @param array $datos Datos para reemplazar en la plantilla
     * @param int $certificadoId ID del certificado para generar código QR
     * @return array ['success' => bool, 'file_path' => string, 'message' => string]
     */
    public function generar($tipoSacramento, $datos, $certificadoId)
    {
        try {
            // Normalizar tipo de sacramento
            $tipo = strtolower($tipoSacramento);

            // Verificar que existe la plantilla
            $plantillaPath = $this->plantillasDir . $tipo . '.html';
            if (!file_exists($plantillaPath)) {
                Logger::error("Plantilla no encontrada", ['tipo' => $tipo, 'path' => $plantillaPath]);
                return [
                    'success' => false,
                    'file_path' => null,
                    'message' => "Plantilla no encontrada para: $tipo"
                ];
            }

            // Leer plantilla
            $html = file_get_contents($plantillaPath);
            if ($html === false) {
                return [
                    'success' => false,
                    'file_path' => null,
                    'message' => 'Error al leer la plantilla'
                ];
            }

            // Generar código QR
            $qrData = $this->generarQRCode($certificadoId);
            if (!$qrData['success']) {
                Logger::warning("Error al generar QR code", $qrData);
                // Continuar sin QR si falla
                $datos['QR_CODE'] = '';
            } else {
                $datos['QR_CODE'] = $qrData['data_uri'];
            }

            // Generar código de certificado
            $codigo = 'CERT-' . str_pad((string)$certificadoId, 8, '0', STR_PAD_LEFT);
            $datos['CODIGO_CERTIFICADO'] = $codigo;

            // Fecha de expedición
            $datos['FECHA_EXPEDICION'] = date('d/m/Y');

            // Reemplazar variables en la plantilla
            $html = $this->reemplazarVariables($html, $datos);

            // Generar PDF
            $pdfResult = $this->generarPDF($html, $certificadoId, $tipo);

            return $pdfResult;

        } catch (Exception $e) {
            Logger::error("Error al generar certificado", [
                'error' => $e->getMessage(),
                'tipo' => $tipoSacramento,
                'certificado_id' => $certificadoId
            ]);
            return [
                'success' => false,
                'file_path' => null,
                'message' => 'Error al generar certificado: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Genera un código QR para verificación del certificado
     *
     * @param int $certificadoId
     * @return array ['success' => bool, 'data_uri' => string]
     */
    private function generarQRCode($certificadoId)
    {
        try {
            // URL de verificación
            $verificationUrl = $this->baseUrl . '/index.php?route=certificados/verificar&codigo=' . $certificadoId;

            // Configuración del QR
            $options = new QROptions([
                'version'      => 5,
                'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'     => QRCode::ECC_L,
                'scale'        => 5,
                'imageBase64'  => true,
            ]);

            // Generar QR
            $qrcode = new QRCode($options);
            $qrImage = $qrcode->render($verificationUrl);

            return [
                'success' => true,
                'data_uri' => $qrImage
            ];

        } catch (Exception $e) {
            Logger::error("Error al generar QR code", [
                'error' => $e->getMessage(),
                'certificado_id' => $certificadoId
            ]);
            return [
                'success' => false,
                'data_uri' => null
            ];
        }
    }

    /**
     * Reemplaza las variables {{VARIABLE}} en la plantilla con los datos proporcionados
     *
     * @param string $html HTML de la plantilla
     * @param array $datos Datos para reemplazar
     * @return string HTML procesado
     */
    private function reemplazarVariables($html, $datos)
    {
        // Asegurar que todas las variables tengan un valor
        $datosDefecto = $this->obtenerDatosDefecto();
        $datos = array_merge($datosDefecto, $datos);

        // Reemplazar cada variable
        foreach ($datos as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $html = str_replace($placeholder, htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'), $html);
        }

        // Si hay QR code (data URI), no escaparlo
        if (isset($datos['QR_CODE']) && strpos($datos['QR_CODE'], 'data:image') === 0) {
            $html = str_replace(htmlspecialchars($datos['QR_CODE'], ENT_QUOTES, 'UTF-8'), $datos['QR_CODE'], $html);
        }

        return $html;
    }

    /**
     * Obtiene datos por defecto para las variables de la plantilla
     *
     * @return array
     */
    private function obtenerDatosDefecto()
    {
        return [
            'NOMBRE_PARROQUIA' => 'Parroquia',
            'DIRECCION_PARROQUIA' => '',
            'CIUDAD' => 'Ciudad',
            'PAIS' => 'País',
            'NUMERO_LIBRO' => '',
            'NUMERO_PAGINA' => '',
            'NUMERO_REGISTRO' => '',
            'NOMBRE_PARROCO' => 'Párroco',
            'NOMBRE_SECRETARIO' => 'Secretario(a)',
            'QR_CODE' => '',
            'CODIGO_CERTIFICADO' => '',
            'FECHA_EXPEDICION' => date('d/m/Y')
        ];
    }

    /**
     * Genera el PDF a partir del HTML procesado
     *
     * @param string $html
     * @param int $certificadoId
     * @param string $tipo
     * @return array
     */
    private function generarPDF($html, $certificadoId, $tipo)
    {
        try {
            // Configurar DomPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'Georgia');
            $options->set('enable_font_subsetting', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Nombre del archivo
            $filename = 'cert_' . $certificadoId . '_' . $tipo . '_' . date('Ymd_His') . '.pdf';
            $filepath = $this->outputDir . $filename;

            // Guardar PDF
            file_put_contents($filepath, $dompdf->output());

            Logger::info("Certificado generado exitosamente", [
                'certificado_id' => $certificadoId,
                'tipo' => $tipo,
                'file' => $filename
            ]);

            return [
                'success' => true,
                'file_path' => $filepath,
                'relative_path' => 'certificados_generados/' . $filename,
                'filename' => $filename,
                'message' => 'Certificado generado exitosamente'
            ];

        } catch (Exception $e) {
            Logger::error("Error al generar PDF", [
                'error' => $e->getMessage(),
                'certificado_id' => $certificadoId
            ]);
            return [
                'success' => false,
                'file_path' => null,
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene la ruta completa del directorio de salida
     *
     * @return string
     */
    public function getOutputDir()
    {
        return $this->outputDir;
    }

    /**
     * Obtiene la ruta completa del directorio de plantillas
     *
     * @return string
     */
    public function getPlantillasDir()
    {
        return $this->plantillasDir;
    }
}
