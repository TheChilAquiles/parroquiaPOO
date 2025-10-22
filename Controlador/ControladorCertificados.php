<?php
// ControladorCertificados.php
// Controlador para manejar la generación de certificados PDF.

// Incluir el modelo y la librería FPDF
require_once __DIR__ . '/../Modelo/ModeloCertificados.php';
require_once __DIR__ . '/../Vista/fpdf.php';

class ControladorCertificados
{
    private $modelo;

    public function __construct($modelo = null)
    {
        $this->modelo = $modelo ?: new ModeloCertificados();
    }

    // Muestra formulario (view)
    public function mostrarFormulario()
    {
        require __DIR__ . '/vistacertificado.php';
    }

    // Procesa formulario y genera PDF
    public function generarPDF($post)
    {
        // Validación básica (debe reforzarse en producción)
        $required = ['usuario_id','feligres_id','nombre_feligres','sacramento','fecha_realizacion','lugar'];
        foreach ($required as $r) {
            if (empty($post[$r])) {
                throw new Exception('Falta campo obligatorio: ' . $r);
            }
        }

        // Preparar datos para guardar en BD (opcional)
        $data = [
            'usuario_id' => (int)$post['usuario_id'],
            'feligres_id' => (int)$post['feligres_id'],
            'sacramento' => $post['sacramento'],
            'fecha_realizacion' => $post['fecha_realizacion'],
            'lugar' => $post['lugar'],
            'observaciones' => $post['observaciones'] ?? ''
        ];

        // Intentar guardar en BD (si el modelo está conectado)
        try {
            $id = $this->modelo->crear($data);
        } catch (Exception $e) {
            // Si falla la base de datos, continuamos generando el PDF sin guardar.
            $id = null;
        }

        // Crear nombre de archivo usando el nombre del feligrés
        // Limpiar caracteres no permitidos en nombre de archivo
        $safeName = preg_replace('/[^A-Za-z0-9 _.-]/', '', $post['nombre_feligres']);
        $safeName = trim(str_replace(' ', '_', $safeName));
        $filename = "certificado_{$safeName}.pdf";

        // Generar PDF con FPDF_simple (implementado en libs)
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,utf8_decode('Certificado de ' . $post['sacramento']),0,1,'C');
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',12);
        $content = "Se certifica que " . $post['nombre_feligres'] . " ha recibido el sacramento de " . $post['sacramento'] .
                    " en fecha " . $post['fecha_realizacion'] . " en " . $post['lugar'] . ".";
        $pdf->MultiCell(0,8,utf8_decode($content));
        $pdf->Ln(10);
        $pdf->Cell(0,8,utf8_decode('Firmado por la parroquia'),0,1,'R');

        // Guardar en servidor en carpeta certificados/ (crear si no existe)
        $outDir = __DIR__ . '/certificados_files';
        if (!is_dir($outDir)) mkdir($outDir, 0755, true);
        $outPath = $outDir . '/' . $filename;
        $pdf->Output('F', $outPath);

        // Forzar descarga al navegador (si se desea), aquí retornamos la ruta y nombre
        return ['path'=>$outPath, 'filename'=>$filename, 'id'=>$id];
    }
}
