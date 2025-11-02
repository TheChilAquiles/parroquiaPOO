<?php


// ============================================================================
// CertificadosController.php
// ============================================================================

class CertificadosController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloCertificados();
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
}