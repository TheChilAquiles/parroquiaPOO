<?php
// Es crucial iniciar la sesión para poder validar al usuario
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * -------------------------------------------------------------------------
 * ENDPOINT PARA PETICIONES AJAX DE NOTICIAS
 * -------------------------------------------------------------------------
 * Este script actúa como el único punto de entrada para las acciones
 * realizadas desde JavaScript (guardar, eliminar, etc.).
 * Siempre responde con un objeto JSON.
 */

// 1. ESTABLECER LA RESPUESTA COMO JSON
header('Content-Type: application/json');

// 2. INCLUIR EL MODELO
// CORRECCIÓN #1: La ruta sube UN solo nivel (..) para salir de 'Vista' y encontrar 'Modelo'.
require_once __DIR__ . '/../Modelo/ModeloNoticia.php';

// 3. VERIFICACIÓN DE SEGURIDAD (LOGIN)
if (!isset($_SESSION['user-id'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Acceso denegado. Debes iniciar sesión.']);
    exit;
}

// 4. PROCESAR LA ACCIÓN SOLICITADA
$modeloNoticia = new ModeloNoticia();
$respuesta = ['exito' => false, 'mensaje' => 'Acción no reconocida o datos incompletos.'];
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'guardar':
        $id = $_POST['id'] ?? null;
        $datos = [
            'titulo'      => trim($_POST['titulo'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'imagen'      => $_POST['imagen_actual'] ?? null,
            'id_usuario'  => $_SESSION['user-id']
        ];

        // Lógica para manejar la subida de una nueva imagen.
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombre_archivo = uniqid('noticia_', true) . '-' . basename($_FILES['imagen']['name']);
            
            // CORRECCIÓN #2: Se ajusta la ruta de destino para subir un nivel y se añade
            // el código que crea las carpetas si no existen.
            $directorio_final = __DIR__ . '/../assets/img/noticias/';
            
            if (!is_dir($directorio_final)) {
                mkdir($directorio_final, 0777, true);
            }

            $ruta_completa_destino = $directorio_final . $nombre_archivo;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_completa_destino)) {
                $datos['imagen'] = 'assets/img/noticias/' . $nombre_archivo;
            } else {
                $respuesta = ['exito' => false, 'mensaje' => 'Error al mover la nueva imagen al servidor.'];
                break;
            }
        }

        if (empty($id)) {
            $respuesta = $modeloNoticia->mdlCrearNoticia($datos);
        } else {
            $respuesta = $modeloNoticia->mdlActualizarNoticia($id, $datos);
        }
        break;

    case 'eliminar':
        $id = $_POST['id'] ?? null;
        if ($id) {
            $respuesta = $modeloNoticia->mdlBorrarNoticia($id);
        } else {
            $respuesta = ['exito' => false, 'mensaje' => 'No se proporcionó un ID para eliminar.'];
        }
        break;
}

// 5. ENVIAR LA RESPUESTA FINAL
echo json_encode($respuesta);
exit;

?>