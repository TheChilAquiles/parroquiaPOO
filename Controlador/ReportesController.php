<?php

// ============================================================================
// ReportesController.php - REFACTORIZADO
// ============================================================================

class ReportesController extends BaseController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ReporteModelo();
    }

    /**
     * Muestra la lista de reportes con estadísticas
     */
    public function index()
    {
        $this->requiereAutenticacion();

        try {
            // Delegar toda la lógica al modelo
            $reportes = $this->modelo->obtenerReportes();
            $estadisticas = $this->modelo->obtenerEstadisticas();

            // Extraer estadísticas para la vista
            $totalReportes = $estadisticas['totalReportes'];
            $totalValor = $estadisticas['totalValor'];
            $pagosCompletados = $estadisticas['pagosCompletados'];
            $pagosPendientes = $estadisticas['pagosPendientes'];
            $reportesActivos = $estadisticas['reportesActivos'];

            include __DIR__ . '/../Vista/reportes.php';
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::index:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al cargar reportes. Por favor, intente nuevamente.';
            $reportes = [];
            $totalReportes = 0;
            $totalValor = 0.0;
            $pagosCompletados = 0;
            $pagosPendientes = 0;
            $reportesActivos = 0;
            include __DIR__ . '/../Vista/reportes.php';
        }
    }

    /**
     * Muestra el formulario para crear un nuevo reporte
     */
    public function crear()
    {
        $this->requiereAutenticacion();

        // Obtener datos necesarios para el formulario
        $categorias = $this->modelo->obtenerCategorias();

        include __DIR__ . '/../Vista/reportes_crear.php';
    }

    /**
     * Procesa la creación de un nuevo reporte
     */
    public function guardar()
    {
        $this->requiereAutenticacion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido.';
            header('Location: index.php?route=reportes');
            exit;
        }

        try {
            // Validar datos
            $errores = $this->validarDatosReporte($_POST);

            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                header('Location: index.php?route=reportes/crear');
                exit;
            }

            // Preparar datos
            $datos = [
                'id_pagos' => intval($_POST['id_pagos']),
                'titulo' => trim($_POST['titulo']),
                'descripcion' => trim($_POST['descripcion']),
                'categoria' => trim($_POST['categoria']),
                'estado_registro' => $_POST['estado_registro'] ?? 'activo'
            ];

            // Guardar en el modelo
            $id = $this->modelo->crearReporte($datos);

            if ($id) {
                $_SESSION['success'] = 'Reporte creado exitosamente.';
                header('Location: index.php?route=reportes');
            } else {
                $_SESSION['error'] = 'Error al crear el reporte.';
                header('Location: index.php?route=reportes/crear');
            }
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::guardar:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al guardar el reporte.';
            header('Location: index.php?route=reportes/crear');
        }
        exit;
    }

    /**
     * Muestra el formulario para editar un reporte
     */
    public function editar()
    {
        $this->requiereAutenticacion();

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID de reporte inválido.';
            header('Location: index.php?route=reportes');
            exit;
        }

        try {
            $reporte = $this->modelo->obtenerReportePorId($id);

            if (!$reporte) {
                $_SESSION['error'] = 'Reporte no encontrado.';
                header('Location: index.php?route=reportes');
                exit;
            }

            $categorias = $this->modelo->obtenerCategorias();
            include __DIR__ . '/../Vista/reportes_editar.php';
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::editar:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al cargar el reporte.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Procesa la actualización de un reporte
     */
    public function actualizar()
    {
        $this->requiereAutenticacion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido.';
            header('Location: index.php?route=reportes');
            exit;
        }

        try {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

            if ($id <= 0) {
                $_SESSION['error'] = 'ID de reporte inválido.';
                header('Location: index.php?route=reportes');
                exit;
            }

            // Validar datos
            $errores = $this->validarDatosReporte($_POST, $id);

            if (!empty($errores)) {
                $_SESSION['error'] = implode('<br>', $errores);
                header("Location: index.php?route=reportes/editar&id=$id");
                exit;
            }

            // Preparar datos
            $datos = [
                'titulo' => trim($_POST['titulo']),
                'descripcion' => trim($_POST['descripcion']),
                'categoria' => trim($_POST['categoria']),
                'estado_registro' => $_POST['estado_registro'] ?? 'activo'
            ];

            // Actualizar en el modelo
            $resultado = $this->modelo->actualizarReporte($id, $datos);

            if ($resultado) {
                $_SESSION['success'] = 'Reporte actualizado exitosamente.';
                header('Location: index.php?route=reportes');
            } else {
                $_SESSION['error'] = 'Error al actualizar el reporte.';
                header("Location: index.php?route=reportes/editar&id=$id");
            }
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::actualizar:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al actualizar el reporte.';
            header('Location: index.php?route=reportes');
        }
        exit;
    }

    /**
     * Elimina un reporte (soft delete)
     */
    public function eliminar()
    {
        $this->requiereAutenticacion();

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID de reporte inválido.';
            header('Location: index.php?route=reportes');
            exit;
        }

        try {
            $resultado = $this->modelo->eliminarReporte($id);

            if ($resultado) {
                $_SESSION['success'] = 'Reporte eliminado exitosamente.';
            } else {
                $_SESSION['error'] = 'Error al eliminar el reporte.';
            }
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::eliminar:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al eliminar el reporte.';
        }

        header('Location: index.php?route=reportes');
        exit;
    }

    /**
     * Exporta reportes a PDF
     */
    public function exportarPDF()
    {
        $this->requiereAutenticacion();

        try {
            require_once __DIR__ . '/../vendor/autoload.php';

            // Obtener datos para exportación
            $datos = $this->modelo->obtenerDatosParaExportacion();
            $reportes = $datos['reportes'];
            $estadisticas = $datos['estadisticas'];

            // Generar HTML para el PDF
            $html = $this->generarHTMLParaPDF($reportes, $estadisticas);

            // Configurar DomPDF
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            // Enviar PDF al navegador
            $dompdf->stream('reportes_' . date('Y-m-d') . '.pdf', ['Attachment' => true]);
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::exportarPDF:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar el PDF.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Filtra reportes por estado de pago
     */
    public function filtrar()
    {
        $this->requiereAutenticacion();

        $estado = isset($_GET['estado']) ? trim($_GET['estado']) : 'all';

        try {
            if ($estado === 'all') {
                $reportes = $this->modelo->obtenerReportes();
            } else {
                $reportes = $this->modelo->obtenerReportesPorEstadoPago($estado);
            }

            $estadisticas = $this->modelo->obtenerEstadisticas();
            $totalReportes = $estadisticas['totalReportes'];
            $totalValor = $estadisticas['totalValor'];
            $pagosCompletados = $estadisticas['pagosCompletados'];

            include __DIR__ . '/../Vista/reportes.php';
        } catch (Exception $e) {
            Logger::error("Error en ReportesController::filtrar:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al filtrar reportes.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Valida los datos del reporte
     * @param array $datos
     * @param int|null $id ID del reporte (para edición)
     * @return array Array de errores
     */
    private function validarDatosReporte($datos, $id = null)
    {
        $errores = [];

        // Validar título
        if (empty($datos['titulo']) || strlen(trim($datos['titulo'])) < 3) {
            $errores[] = 'El título debe tener al menos 3 caracteres.';
        }

        // Validar descripción
        if (empty($datos['descripcion']) || strlen(trim($datos['descripcion'])) < 10) {
            $errores[] = 'La descripción debe tener al menos 10 caracteres.';
        }

        // Validar categoría
        if (empty($datos['categoria'])) {
            $errores[] = 'La categoría es obligatoria.';
        }

        // Validar id_pagos solo en creación
        if ($id === null) {
            if (empty($datos['id_pagos']) || intval($datos['id_pagos']) <= 0) {
                $errores[] = 'Debe seleccionar un pago válido.';
            }
        }

        return $errores;
    }

    /**
     * Genera HTML dinámico para el PDF basado en los datos
     * @param array $datos
     * @param string $tipo
     * @return string
     */
    private function generarHTMLParaPDF($datos, $tipo)
    {
        $titulo = ucfirst($tipo);
        $fecha = date('d/m/Y H:i');
        
        // Obtener encabezados dinámicamente del primer elemento
        $headers = [];
        if (!empty($datos)) {
            $headers = array_keys($datos[0]);
        }

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; font-size: 10px; color: #333; }
                h1 { text-align: center; color: #2c3e50; margin-bottom: 5px; }
                .subtitle { text-align: center; color: #7f8c8d; margin-bottom: 20px; font-size: 12px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background: #2c3e50; color: white; padding: 8px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 9px; }
                td { padding: 8px; border-bottom: 1px solid #ddd; }
                tr:nth-child(even) { background: #f8f9fa; }
                .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #999; padding: 10px 0; }
            </style>
        </head>
        <body>
            <h1>Reporte de ' . $titulo . '</h1>
            <div class="subtitle">Generado el ' . $fecha . '</div>

            <table>
                <thead>
                    <tr>';
        
        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars(str_replace('_', ' ', ucfirst($header))) . '</th>';
        }

        $html .= '
                    </tr>
                </thead>
                <tbody>';

        foreach ($datos as $fila) {
            $html .= '<tr>';
            foreach ($fila as $valor) {
                $html .= '<td>' . htmlspecialchars($valor ?? '') . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="footer">
                Sistema de Gestión Parroquial - Reporte Generado Automáticamente
            </div>
        </body>
        </html>';

        return $html;
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - PRIMERA GENERACIÓN
    // ========================================================================

    /**
     * Reporte de certificados
     */
    public function reporteCertificados()
    {
        $this->requiereAutenticacion();

        try {
            $porTipo = $this->modelo->reporteCertificadosPorTipo();
            $porEstado = $this->modelo->reporteCertificadosPorEstado();
            $tiempos = $this->modelo->reporteTiemposProcesamiento();
            $masSolicitados = $this->modelo->reporteCertificadosMasSolicitados(10);

            include __DIR__ . '/../Vista/reportes_certificados.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteCertificados:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte de certificados.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Reporte de feligreses
     */
    public function reporteFeligreses()
    {
        $this->requiereAutenticacion();

        try {
            $porTipoDoc = $this->modelo->reporteFeligresesPorTipoDocumento();
            $masActivos = $this->modelo->reporteFeligresesMasActivos(10);
            $nuevos = $this->modelo->reporteFeligresesNuevos(30);

            include __DIR__ . '/../Vista/reportes_feligreses.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteFeligreses:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte de feligreses.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Reporte de sacramentos
     */
    public function reporteSacramentos()
    {
        $this->requiereAutenticacion();

        try {
            $porTipo = $this->modelo->reporteSacramentosPorTipo();
            $porLibro = $this->modelo->reporteSacramentosPorLibro();
            $tendencias = $this->modelo->reporteTendenciasSacramentos(12);

            include __DIR__ . '/../Vista/reportes_sacramentos.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteSacramentos:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte de sacramentos.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Reporte financiero
     */
    public function reporteFinanciero()
    {
        $this->requiereAutenticacion();

        try {
            $ingresos = $this->modelo->reporteIngresosPorConcepto();
            $estadoPagos = $this->modelo->reporteEstadoPagos();
            $metodosPago = $this->modelo->reporteMetodosPago();
            $valoresPromedio = $this->modelo->reporteValoresPromedio();

            include __DIR__ . '/../Vista/reportes_financiero.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteFinanciero:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte financiero.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Reporte de actividad del sistema
     */
    public function reporteActividad()
    {
        $this->requiereAutenticacion();

        try {
            $usuariosActivos = $this->modelo->reporteUsuariosMasActivos(10);
            $actividadRol = $this->modelo->reporteActividadPorRol();
            $tasaConversion = $this->modelo->reporteTasaConversion();

            include __DIR__ . '/../Vista/reportes_actividad.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteActividad:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte de actividad.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    // ========================================================================
    // NUEVOS REPORTES ANALÍTICOS
    // ========================================================================

    // ========================================================================
    // NUEVOS REPORTES ANALÍTICOS
    // ========================================================================

    /**
     * Reporte de libros parroquiales
     */
    public function reporteLibros()
    {
        $this->requiereAutenticacion();

        try {
            $porTipo = $this->modelo->reporteLibrosPorTipo();
            $capacidad = $this->modelo->reporteCapacidadLibros();
            $activos = $this->modelo->reporteLibrosActivos();

            include __DIR__ . '/../Vista/reportes_libros.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteLibros:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte de libros.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Reporte de usuarios
     */
    public function reporteUsuarios()
    {
        $this->requiereAutenticacion();

        try {
            $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
            $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

            $porRol = $this->modelo->reporteUsuariosPorRol();
            $sinDatos = $this->modelo->reporteUsuariosSinDatosCompletos();
            $registros = $this->modelo->reporteRegistrosUsuarios($fechaInicio, $fechaFin);

            include __DIR__ . '/../Vista/reportes_usuarios.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteUsuarios:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte de usuarios.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Reporte de noticias
     */
    public function reporteNoticias()
    {
        $this->requiereAutenticacion();

        try {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin = $_GET['fecha_fin'] ?? null;

            $porPeriodo = $this->modelo->reporteNoticiasPorPeriodo($fechaInicio, $fechaFin);
            $autoresActivos = $this->modelo->reporteAutoresMasActivos(10);

            include __DIR__ . '/../Vista/reportes_noticias.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteNoticias:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte de noticias.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Reporte de contactos
     */
    public function reporteContactos()
    {
        $this->requiereAutenticacion();

        try {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin = $_GET['fecha_fin'] ?? null;

            $porPeriodo = $this->modelo->reporteContactosPorPeriodo($fechaInicio, $fechaFin);

            include __DIR__ . '/../Vista/reportes_contactos.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteContactos:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte de contactos.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Reporte comparativo
     */
    public function reporteComparativo()
    {
        $this->requiereAutenticacion();

        try {
            $anio1 = $_GET['anio1'] ?? date('Y') - 1;
            $anio2 = $_GET['anio2'] ?? date('Y');

            $comparativo = $this->modelo->reporteComparativoAnual($anio1, $anio2);

            include __DIR__ . '/../Vista/reportes_comparativo.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteComparativo:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte comparativo.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Reporte ejecutivo general
     */
    public function reporteEjecutivo()
    {
        $this->requiereAutenticacion();

        try {
            $resumen = $this->modelo->reporteResumenGeneral();

            include __DIR__ . '/../Vista/reportes_ejecutivo.php';
        } catch (Exception $e) {
            Logger::error("Error en reporteEjecutivo:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al generar reporte ejecutivo.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    // ========================================================================
    // MÉTODOS DE EXPORTACIÓN
    // ========================================================================

    /**
     * Exporta datos a CSV
     */
    public function exportarCSV()
    {
        $this->requiereAutenticacion();

        try {
            $tipo = $_GET['tipo'] ?? 'general';
            $datos = $this->obtenerDatosParaExportar($tipo);

            if (empty($datos)) {
                $_SESSION['error'] = 'No hay datos disponibles para exportar.';
                header('Location: index.php?route=reportes');
                exit;
            }

            $csv = $this->modelo->exportarCSV($datos);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="reporte_' . $tipo . '_' . date('Y-m-d') . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');

            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            echo $csv;
            exit;
        } catch (Exception $e) {
            Logger::error("Error en exportarCSV:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al exportar datos a CSV.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Exporta datos a JSON
     */
    public function exportarJSON()
    {
        $this->requiereAutenticacion();

        try {
            $tipo = $_GET['tipo'] ?? 'general';
            $datos = $this->obtenerDatosParaExportar($tipo);

            if (empty($datos)) {
                $_SESSION['error'] = 'No hay datos disponibles para exportar.';
                header('Location: index.php?route=reportes');
                exit;
            }

            $json = $this->modelo->exportarJSON($datos);

            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename="reporte_' . $tipo . '_' . date('Y-m-d') . '.json"');
            header('Pragma: no-cache');
            header('Expires: 0');

            echo $json;
            exit;
        } catch (Exception $e) {
            Logger::error("Error en exportarJSON:", ['error' => $e->getMessage()]);
            $_SESSION['error'] = 'Error al exportar datos a JSON.';
            header('Location: index.php?route=reportes');
            exit;
        }
    }

    /**
     * Obtiene datos según el tipo de reporte para exportar
     */
    private function obtenerDatosParaExportar($tipo)
    {
        switch ($tipo) {
            case 'certificados':
                return $this->modelo->reporteCertificadosPorTipo();
            case 'feligreses':
                return $this->modelo->reporteFeligresesPorTipoDocumento();
            case 'sacramentos':
                return $this->modelo->reporteSacramentosPorTipo();
            case 'financiero':
                return $this->modelo->reporteIngresosPorConcepto();
            case 'actividad':
                return $this->modelo->reporteUsuariosMasActivos(10);
            case 'libros':
                return $this->modelo->reporteLibrosPorTipo();
            case 'usuarios':
                return $this->modelo->reporteUsuariosPorRol();
            case 'noticias':
                return $this->modelo->reporteNoticiasPorPeriodo();
            case 'contactos':
                return $this->modelo->reporteContactosPorPeriodo();
            default:
                return $this->modelo->obtenerDatosParaExportacion();
        }
    }
}
