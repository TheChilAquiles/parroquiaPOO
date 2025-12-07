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

        Logger::info("Acceso a vista de certificados", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'rol' => $rol,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        // Administrador y Secretario ven vista administrativa con DataTables
        if (in_array($rol, ['Administrador', 'Secretario'])) {
            include_once __DIR__ . '/../Vista/certificados.php';
        }
        // Feligrés ve vista amigable con cards
        else {
            // Obtener certificados del feligrés
            $feligresId = $this->obtenerFeligresIdUsuario($_SESSION['user-id']);

            if (!$feligresId) {
                Logger::warning("Feligrés sin perfil completo intentó acceder a certificados", [
                    'user_id' => $_SESSION['user-id'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                // Mostrar vista vacía con mensaje de perfil incompleto
                $misCertificados = [];
                $_SESSION['info'] = 'Tu perfil de feligrés aún no está completo. Contacta con la secretaría para completar tu registro y poder solicitar certificados.';
                include_once __DIR__ . '/../Vista/mis-certificados.php';
                return;
            }

            // Obtener todos los certificados (propios + familiares + generados por secretario)
            $misCertificados = $this->modeloSolicitud->mdlObtenerMisSolicitudes($feligresId);

            Logger::info("Certificados de feligrés cargados", [
                'user_id' => $_SESSION['user-id'],
                'feligres_id' => $feligresId,
                'cantidad' => count($misCertificados)
            ]);

            include_once __DIR__ . '/../Vista/mis-certificados.php';
        }
    }

    /**
     * Descarga un certificado (con validaciones)
     */
    public function descargar()
    {
        // Verificar autenticación
        $this->requiereAutenticacion();

        // Obtener ID del usuario y rol
        $usuarioId = $_SESSION['user-id'];
        $rolUsuario = $_SESSION['user-rol'] ?? 'Feligrés';
        
        // Obtener ID del certificado
        $certificadoId = $_GET['id'] ?? null;

        if (empty($certificadoId) || !is_numeric($certificadoId)) {
            $_SESSION['error'] = 'ID de certificado inválido.';
            $this->mostrar();
            return;
        }

        // Obtener datos del certificado
        $certificado = $this->modeloSolicitud->mdlObtenerPorId($certificadoId);

        if (!$certificado) {
            $_SESSION['error'] = 'Certificado no encontrado.';
            $this->mostrar();
            return;
        }

        // Validar permisos: el usuario debe ser el solicitante, el dueño del sacramento (feligrés), o ser administrador/secretario
        $esAdminOSecretario = in_array($rolUsuario, ['Administrador', 'Secretario']);
        
        $feligresIdUsuario = $this->obtenerFeligresIdUsuario($usuarioId);
        $esPropio = ($certificado['feligres_certificado_id'] == $feligresIdUsuario);
        $esSolicitante = ($certificado['solicitante_id'] == $feligresIdUsuario);

        if (!$esAdminOSecretario && !$esPropio && !$esSolicitante) {
            $_SESSION['error'] = 'No tiene permiso para descargar este certificado.';
            $this->mostrar();
            return;
        }

        // Validar que exista el archivo
        if (empty($certificado['ruta_archivo'])) {
             $_SESSION['error'] = 'El archivo no ha sido generado aún.';
             $this->mostrar();
             return;
        }

        // Construir rutas posibles (absoluta vs relativa)
        $rutaArchivo = $certificado['ruta_archivo'];
        
        // Si es ruta relativa, prepend base dir
        if (!file_exists($rutaArchivo)) {
             // Intentar con __DIR__ si la ruta es relativa desde root
             $rutaAlternativa = __DIR__ . '/../' . $rutaArchivo;
             if (file_exists($rutaAlternativa)) {
                 $rutaArchivo = $rutaAlternativa;
             } elseif (file_exists(__DIR__ . '/../certificados_generados/' . basename($rutaArchivo))) {
                 // Intentar buscar solo por nombre en carpeta de output
                 $rutaArchivo = __DIR__ . '/../certificados_generados/' . basename($rutaArchivo);
             } else {
                Logger::error("Archivo físico no encontrado para descarga", [
                    'ruta_bd' => $certificado['ruta_archivo'],
                    'ruta_alt' => $rutaAlternativa,
                    'id' => $certificadoId
                ]);
                $_SESSION['error'] = 'Archivo físico no encontrado en el servidor.';
                $this->mostrar();
                return;
             }
        }

        // Marcar como descargado (si aún no lo está y es la primera vez que se descarga)
        if ($certificado['estado'] === 'generado') {
            $this->modeloSolicitud->mdlMarcarDescargado($certificadoId);
        }

        // Limpiar cualquier salida previa del buffer
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Servir archivo
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="certificado_' . $certificadoId . '.pdf"');
        header('Content-Length: ' . filesize($rutaArchivo));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        // Log para depuración
        Logger::info("Sirviendo descarga de certificado", [
            'id' => $certificadoId,
            'ruta_final' => $rutaArchivo,
            'size' => filesize($rutaArchivo),
            'mime' => mime_content_type($rutaArchivo),
            'user' => $_SESSION['user-id']
        ]);

        readfile($rutaArchivo);
        exit;
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
        Logger::info("Intento de generación de certificado", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'method' => $_SERVER['REQUEST_METHOD'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'via' => isset($_GET['sacramento_id']) ? 'sacramento_id' : 'formulario'
        ]);

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
                Logger::warning("Generación de certificado - campo faltante", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'campo_faltante' => $field,
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
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

            Logger::info("Certificado generado exitosamente (método legacy)", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'certificado_id' => $id,
                'feligres_id' => $data['feligres_id'],
                'sacramento' => $data['sacramento'],
                'archivo' => $filename,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            // Descargar
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile($outPath);
            exit();

        } catch (Exception $e) {
            Logger::error("Error al generar certificado (método legacy)", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
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
    /**
     * Genera solicitud de certificado desde un sacramento_id
     * Llamado cuando se hace clic en "Generar Certificado" desde la vista de sacramentos
     * @param int $sacramentoId ID del sacramento
     */
    private function generarDesdeSacramento($sacramentoId)
    {
        Logger::info("Intento de solicitud de certificado desde sacramento", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'sacramento_id' => $sacramentoId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        try {
            // Obtener datos del sacramento
            $sacramento = $this->modeloSacramento->mdlObtenerPorId($sacramentoId);

            if (!$sacramento) {
                $_SESSION['error'] = "Sacramento no encontrado con ID: $sacramentoId";
                redirect('sacramentos');
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

            // Buscar participante principal por rol
            $rolesPrincipales = [
                1 => 'Bautizado',      // Bautismo
                2 => 'Confirmando',    // Confirmación
                3 => 'Difunto',        // Defunción
                4 => 'Esposo'          // Matrimonio (tomar esposo como principal o cualquiera)
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

            $feligresId = $participantePrincipal['feligres_id'];

            // Mapear tipo de sacramento a string para la DB
            $tiposSacramento = [
                1 => 'Bautismo',
                2 => 'Confirmación',
                3 => 'Defunción',
                4 => 'Matrimonio'
            ];
            $tipoSacramentoStr = $tiposSacramento[$tipoSacramentoId] ?? 'Sacramento';

            // Verificar si ya existe solicitud pendiente
            if ($this->modeloSolicitud->mdlVerificarSolicitudExistente($sacramentoId, $feligresId, $tipoSacramentoId)) {
                $_SESSION['warning'] = "Ya existe una solicitud activa o un certificado generado para este sacramento. Revise la lista de certificados.";
                header('Location: ?route=certificados');
                exit();
            }

            // Crear solicitud de certificado "Pendiente de Pago"
            // Usamos modeloSolicitud->mdlCrearSolicitud o insertamos directamente si necesitamos campos específicos de admin
            // Reutilizaremos mdlCrearCertificadoDirecto que ya tenemos para simplificar, pero adaptando los datos
            
            $datos = [
                'usuario_generador_id' => $_SESSION['user-id'],
                'tipo_documento_id' => $participantePrincipal['tipo_documento_id'], // No usado x mdlCrearCertificadoDirecto en select feligres, pero...
                // mdlCrearCertificadoDirecto busca por documento. Mejor insertamos usando el ID de feligres que ya tenemos.
                // Insertamos manualmente aquí para controlar el ID exacto del feligres y sacramento
            ];

            // Inserción manual para garantizar integridad con los IDs que ya recuperamos
            $sql = "INSERT INTO certificados (
                        usuario_generador_id, solicitante_id, feligres_certificado_id,
                        parentesco_id, fecha_solicitud, tipo_certificado,
                        motivo_solicitud, sacramento_id, estado
                    ) VALUES (?, ?, ?, NULL, NOW(), ?, 'Solicitud desde Sacramentos', ?, 'pendiente_pago')";
            
            // Nota: Asumimos que el solicitante es el Admin que está logueado haciendo la gestión? 
            // NO, la tabla pide un feligres_id como solicitante. Si es un Admin, usuario_generador_id queda set, y solicitante_id...
            // En lógica anterior de 'crear', se usaba el feligres del certificado como solicitante si era directo.
            
            $conexion = Conexion::conectar();
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                $_SESSION['user-id'],     // Usuario generador (Admin/Secretario)
                $feligresId,              // Solicitante (El feligrés dueño del sacramento, asumido por defecto)
                $feligresId,              // Feligrés certificado
                $tipoSacramentoStr,       // Tipo certificado
                $sacramentoId             // Sacramento ID
            ]);

            Logger::info("Solicitud de certificado creada desde sacramentos", [
                'sacramento_id' => $sacramentoId,
                'feligres_id' => $feligresId,
                'estado' => 'pendiente_pago'
            ]);

            $_SESSION['success'] = "Solicitud creada. El certificado está PENDIENTE DE PAGO. Por favor proceda a registrar el pago.";
            header('Location: ?route=certificados');
            exit();

        } catch (Exception $e) {
            Logger::error("Error al generar solicitud desde sacramento:", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $_SESSION['error'] = 'Error al procesar la solicitud: ' . $e->getMessage();
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

        Logger::info("Intento de generación simplificada de certificado", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'rol' => $_SESSION['user-rol'] ?? 'unknown',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Logger::warning("Generación simplificada - método no permitido", [
                'method' => $_SERVER['REQUEST_METHOD'],
                'user_id' => $_SESSION['user-id'] ?? 'guest'
            ]);
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
                    Logger::warning("Generación simplificada - campo faltante", [
                        'user_id' => $_SESSION['user-id'] ?? 'guest',
                        'campo_faltante' => $field,
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                    ]);
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
                Logger::warning("Generación simplificada - error al crear certificado", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'numero_documento_prefix' => substr($datos['numero_documento'], 0, 3) . '***',
                    'tipo_sacramento_id' => $datos['tipo_sacramento_id'],
                    'mensaje' => $resultado['message'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
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
                    Logger::info("Certificado simplificado creado y PDF generado exitosamente", [
                        'user_id' => $_SESSION['user-id'] ?? 'guest',
                        'certificado_id' => $certificadoId,
                        'numero_documento_prefix' => substr($datos['numero_documento'], 0, 3) . '***',
                        'tipo_sacramento_id' => $datos['tipo_sacramento_id'],
                        'metodo_pago' => 'efectivo',
                        'pdf_generado' => true,
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                    ]);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Certificado creado y PDF generado exitosamente',
                        'certificado_id' => $certificadoId,
                        'pdf_generado' => true
                    ]);
                } else {
                    Logger::warning("Certificado simplificado creado pero PDF no generado", [
                        'user_id' => $_SESSION['user-id'] ?? 'guest',
                        'certificado_id' => $certificadoId,
                        'numero_documento_prefix' => substr($datos['numero_documento'], 0, 3) . '***',
                        'tipo_sacramento_id' => $datos['tipo_sacramento_id'],
                        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                    ]);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Certificado creado pero hubo error al generar PDF',
                        'certificado_id' => $certificadoId,
                        'pdf_generado' => false
                    ]);
                }
            } else {
                // Sin pago en efectivo, solo crear solicitud
                Logger::info("Certificado simplificado creado - pendiente de pago", [
                    'user_id' => $_SESSION['user-id'] ?? 'guest',
                    'certificado_id' => $certificadoId,
                    'numero_documento_prefix' => substr($datos['numero_documento'], 0, 3) . '***',
                    'tipo_sacramento_id' => $datos['tipo_sacramento_id'],
                    'estado' => 'pendiente_pago',
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                echo json_encode([
                    'success' => true,
                    'message' => 'Certificado creado exitosamente. Pendiente de pago.',
                    'certificado_id' => $certificadoId,
                    'estado' => 'pendiente_pago'
                ]);
            }

        } catch (Exception $e) {
            Logger::error("Error en generarSimplificado:", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
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
        // Limpiar cualquier salida previa
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        Logger::info("Listado de certificados solicitado", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'rol' => $_SESSION['user-rol'] ?? 'none'
        ]);

        // Verificar permisos (Admin/Secretario)
        if (!isset($_SESSION['user-rol']) || !in_array($_SESSION['user-rol'], ['Administrador', 'Secretario'])) {
            Logger::warning("Acceso denegado a listado de certificados", [
                'user_id' => $_SESSION['user-id'] ?? 'guest',
                'rol' => $_SESSION['user-rol'] ?? 'none'
            ]);
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Acceso denegado']);
            exit;
        }

        try {
            // Instanciar modelo de configuración para obtener precios
            $modeloConfig = new ModeloConfiguracion();
            
            $certificados = $this->modeloSolicitud->mdlObtenerTodosLosCertificados();

            $data = [];
            foreach ($certificados as $cert) {
                // Obtener precio según tipo de sacramento
                $precio = $modeloConfig->obtenerPrecioCertificado($cert['tipo_sacramento']);
                
                $data[] = [
                    'id' => $cert['id'],
                    'tipo_sacramento' => $cert['tipo_sacramento'],
                    'nombre_feligres' => $cert['feligres_nombre'],
                    'tipo_documento' => $cert['tipo_documento'],
                    'numero_documento' => $cert['numero_documento'],
                    'solicitante_nombre' => $cert['solicitante_nombre'] ?? null,
                    'estado' => $cert['estado'],
                    'fecha_solicitud' => $cert['fecha_solicitud'],
                    'fecha_generacion' => $cert['fecha_generacion'] ? date('d/m/Y', strtotime($cert['fecha_generacion'])) : 'N/A',
                    'fecha_expiracion' => $cert['fecha_expiracion'] ? date('d/m/Y', strtotime($cert['fecha_expiracion'])) : 'N/A',
                    'ruta_archivo' => $cert['ruta_archivo'],
                    'precio' => $precio  // Precio dinámico desde configuración
                ];
            }

            header('Content-Type: application/json');
            echo json_encode(['data' => $data]);

        } catch (Exception $e) {
            Logger::error("Error al listar certificados", [
                'error' => $e->getMessage()
            ]);
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al obtener certificados']);
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
        Logger::info("Solicitud de lista de familiares", [
            'user_id' => $_SESSION['user-id'] ?? 'guest',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);

        // Verificar autenticación
        if (!isset($_SESSION['logged']) || !isset($_SESSION['user-id'])) {
            Logger::warning("Obtener familiares - sesión no válida", [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
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
                Logger::warning("Obtener familiares - perfil de feligrés no encontrado", [
                    'user_id' => $_SESSION['user-id'],
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
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

            Logger::info("Lista de familiares obtenida exitosamente", [
                'user_id' => $_SESSION['user-id'],
                'feligres_id' => $feligresId,
                'cantidad_familiares' => count($familiares),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            if (ob_get_level()) ob_clean();
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $familiares
            ]);

        } catch (Exception $e) {
            Logger::error("Error al obtener familiares", [
                'user_id' => $_SESSION['user-id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            if (ob_get_level()) ob_clean();
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        exit;
    }



    /**
     * Verifica la autenticidad de un certificado por código (ruta pública para QR)
     * Muestra información del certificado si es válido
     */
    public function verificar()
    {
        $codigo = $_GET['codigo'] ?? null;

        Logger::info("Intento de verificación de certificado", [
            'codigo' => $codigo ?? 'no_proporcionado',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);

        try {
            // Obtener código del certificado desde GET
            if (empty($codigo)) {
                Logger::warning("Verificación de certificado sin código", [
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $_SESSION['error'] = 'No se proporcionó un código de verificación válido.';
                include_once __DIR__ . '/../Vista/verificar-certificado.php';
                return;
            }

            // Buscar certificado por ID
            $certificado = $this->modelo->mdlObtenerPorId($codigo);

            if (!$certificado) {
                Logger::warning("Verificación fallida - certificado no encontrado", [
                    'codigo' => $codigo,
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $mensaje = 'Certificado no encontrado. Verifique el código e intente nuevamente.';
                $valido = false;
                include_once __DIR__ . '/../Vista/verificar-certificado.php';
                return;
            }

            // Verificar que el certificado esté generado
            if (empty($certificado['ruta_archivo'])) {
                Logger::warning("Verificación fallida - certificado no generado", [
                    'codigo' => $codigo,
                    'certificado_id' => $certificado['id'],
                    'estado' => $certificado['estado'] ?? 'unknown',
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);
                $mensaje = 'Este certificado aún no ha sido generado por la parroquia.';
                $valido = false;
                include_once __DIR__ . '/../Vista/verificar-certificado.php';
                return;
            }

            // Certificado válido
            Logger::info("Certificado verificado exitosamente", [
                'codigo' => $codigo,
                'certificado_id' => $certificado['id'],
                'tipo' => $certificado['tipo_certificado'] ?? 'N/A',
                'fecha_generacion' => $certificado['fecha_generacion'] ?? 'N/A',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            $mensaje = 'Certificado válido y verificado.';
            $valido = true;

            // Preparar datos para la vista
            $datos = [
                'id' => $certificado['id'],
                'tipo' => $certificado['tipo_certificado'] ?? 'N/A',
                'feligres' => $certificado['feligres_nombre'] ?? 'N/A',
                'fecha_emision' => $certificado['fecha_generacion'] ? date('d/m/Y', strtotime($certificado['fecha_generacion'])) : 'N/A',
                'fecha_sacramento' => $certificado['fecha_sacramento'] ? date('d/m/Y', strtotime($certificado['fecha_sacramento'])) : 'N/A',
                'estado' => ucfirst(str_replace('_', ' ', $certificado['estado']))
            ];

            include_once __DIR__ . '/../Vista/verificar-certificado.php';

        } catch (Exception $e) {
            Logger::error("Error al verificar certificado:", [
                'error' => $e->getMessage(),
                'codigo' => $codigo ?? 'N/A',
                'trace' => $e->getTraceAsString(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
            $mensaje = 'Error al verificar el certificado. Por favor, intente más tarde.';
            $valido = false;
            include_once __DIR__ . '/../Vista/verificar-certificado.php';
        }
    }
}