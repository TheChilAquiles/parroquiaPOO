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
     * Genera HTML para el PDF
     * @param array $reportes
     * @param array $estadisticas
     * @return string
     */
    private function generarHTMLParaPDF($reportes, $estadisticas)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; font-size: 10px; }
                h1 { text-align: center; color: #333; }
                .stats { margin: 20px 0; padding: 10px; background: #f0f0f0; }
                .stats span { margin-right: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { background: #4a5568; color: white; padding: 8px; text-align: left; }
                td { padding: 6px; border-bottom: 1px solid #ddd; }
                tr:nth-child(even) { background: #f9f9f9; }
                .badge { padding: 3px 8px; border-radius: 3px; font-size: 9px; }
                .badge-success { background: #10b981; color: white; }
                .badge-warning { background: #f59e0b; color: white; }
            </style>
        </head>
        <body>
            <h1>Reporte de Pagos - ' . date('d/m/Y H:i') . '</h1>

            <div class="stats">
                <span><strong>Total Reportes:</strong> ' . $estadisticas['totalReportes'] . '</span>
                <span><strong>Pagos Completados:</strong> ' . $estadisticas['pagosCompletados'] . '</span>
                <span><strong>Pagos Pendientes:</strong> ' . $estadisticas['pagosPendientes'] . '</span>
                <span><strong>Valor Total:</strong> $' . number_format($estadisticas['totalValor'], 0, ',', '.') . '</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Fecha</th>
                        <th>Estado Pago</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($reportes as $reporte) {
            $estadoPago = $this->modelo->esPagoCompletado($reporte['estado_pago']) ? 'Pagado' : 'Pendiente';
            $badgeClass = $this->modelo->esPagoCompletado($reporte['estado_pago']) ? 'badge-success' : 'badge-warning';
            $fecha = date('d/m/Y', strtotime($reporte['fecha_reporte']));

            $html .= '
                    <tr>
                        <td>' . htmlspecialchars($reporte['id_reporte']) . '</td>
                        <td>' . htmlspecialchars($reporte['titulo']) . '</td>
                        <td>' . htmlspecialchars($reporte['categoria']) . '</td>
                        <td>' . $fecha . '</td>
                        <td><span class="badge ' . $badgeClass . '">' . $estadoPago . '</span></td>
                        <td>$' . number_format(floatval($reporte['valor']), 0, ',', '.') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        return $html;
    }
}
