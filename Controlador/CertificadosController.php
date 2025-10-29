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

            // Generar PDF
            require_once __DIR__ . '/../Vista/fpdf.php';
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, utf8_decode('Certificado de ' . $data['sacramento']), 0, 1, 'C');
            $pdf->Ln(6);
            $pdf->SetFont('Arial', '', 12);

            $content = "Se certifica que " . $_POST['nombre_feligres'] . " ha recibido el sacramento de " . 
                       $data['sacramento'] . " en fecha " . $data['fecha_realizacion'] . " en " . $data['lugar'] . ".";
            
            $pdf->MultiCell(0, 8, utf8_decode($content));
            $pdf->Ln(10);
            $pdf->Cell(0, 8, utf8_decode('Firmado por la parroquia'), 0, 1, 'R');

            // Guardar PDF
            $outDir = __DIR__ . '/certificados_files';
            if (!is_dir($outDir)) {
                mkdir($outDir, 0755, true);
            }
            $outPath = $outDir . '/' . $filename;
            $pdf->Output('F', $outPath);

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