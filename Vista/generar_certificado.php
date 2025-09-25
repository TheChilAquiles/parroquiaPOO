<?php
// generar_certificado.php
// Punto de entrada para procesar el formulario y generar el PDF.

require_once __DIR__ . '/../Controlador/ControladorCertificados.php';

// Incluir Conexion.php si existe (no obligatorio para generar PDF)
if (file_exists(__DIR__ . '/Conexion.php')) {
    require_once __DIR__ . '/Conexion.php';
}

try {
    $controller = new ControladorCertificados();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = $controller->generarPDF($_POST);

        // Descargar automáticamente
        if (file_exists($result['path'])) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
            readfile($result['path']);
            exit;
        } else {
            echo 'Certificado generado en: ' . htmlspecialchars($result['path']);
        }
    } else {
        // Mostrar formulario si no es POST
        $controller->mostrarFormulario();
    }
} catch (Exception $e) {
    echo '<h3>Error:</h3><pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
}
