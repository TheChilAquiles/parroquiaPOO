<?php
// Script de prueba para verificar la respuesta del endpoint
session_start();

// Simular sesión de administrador
$_SESSION['logged'] = true;
$_SESSION['user-rol'] = 'Administrador';
$_SESSION['user-id'] = 1;

// Incluir archivos necesarios
require_once 'config.php';
require_once 'Utilidades/Conexion.php';
require_once 'Utilidades/Logger.php';
require_once 'Modelo/ModeloSolicitudCertificado.php';
require_once 'Modelo/ModeloConfiguracion.php';

// Limpiar buffer
while (ob_get_level()) {
    ob_end_clean();
}

try {
    $modeloConfig = new ModeloConfiguracion();
    $modeloSolicitud = new ModeloSolicitudCertificado();
    
    $certificados = $modeloSolicitud->mdlObtenerTodosLosCertificados();
    
    $data = [];
    foreach ($certificados as $cert) {
        $precio = $modeloConfig->obtenerPrecioCertificado($cert['tipo_sacramento']);
        
        $data[] = [
            'id' => $cert['id'],
            'tipo_sacramento' => $cert['tipo_sacramento'],
            'nombre_feligres' => $cert['nombre_feligres'],
            'tipo_documento' => $cert['tipo_documento'],
            'numero_documento' => $cert['numero_documento'],
            'solicitante_nombre' => $cert['solicitante_nombre'] ?? null,
            'estado' => $cert['estado'],
            'fecha_solicitud' => $cert['fecha_solicitud'],
            'precio' => $precio
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode(['data' => $data], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT);
}
