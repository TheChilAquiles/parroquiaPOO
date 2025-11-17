<?php
// Modelo/ReporteModelo.php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/Logger.php';

// ============================================================================
// ReporteModelo.php - REFACTORIZADO CON PATRÓN REPOSITORY
// ============================================================================

class ReporteModelo
{
    private $db;

    // Constantes para estados de pago
    const ESTADOS_PAGO_COMPLETADO = ['completo', 'pagado', 'paid', 'complete'];
    const ESTADOS_PAGO_PENDIENTE = ['pendiente', 'pending'];

    public function __construct()
    {
        $this->db = Conexion::conectar();
    }

    /**
     * Obtiene todos los reportes con su pago asociado
     * @return array
     */
    public function obtenerReportes()
    {
        try {
            $sql = "SELECT
                        r.id AS id_reporte,
                        r.titulo,
                        r.descripcion,
                        r.categoria,
                        r.fecha AS fecha_reporte,
                        r.estado_registro,
                        p.id AS id_pago,
                        p.valor,
                        p.estado AS estado_pago,
                        p.fecha_pago,
                        p.id_certificado AS certificado_id
                    FROM reportes r
                    INNER JOIN pagos p ON r.id_pagos = p.id
                    ORDER BY r.id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener reportes:", ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Obtiene un reporte específico por ID
     * @param int $id
     * @return array|null
     */
    public function obtenerReportePorId($id)
    {
        try {
            $sql = "SELECT
                        r.id AS id_reporte,
                        r.titulo,
                        r.descripcion,
                        r.categoria,
                        r.fecha AS fecha_reporte,
                        r.estado_registro,
                        r.id_pagos,
                        p.id AS id_pago,
                        p.valor,
                        p.estado AS estado_pago,
                        p.fecha_pago,
                        p.id_certificado AS certificado_id
                    FROM reportes r
                    INNER JOIN pagos p ON r.id_pagos = p.id
                    WHERE r.id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ?: null;
        } catch (PDOException $e) {
            Logger::error("Error al obtener reporte por ID:", [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Obtiene estadísticas de los reportes
     * @return array
     */
    public function obtenerEstadisticas()
    {
        try {
            $reportes = $this->obtenerReportes();

            $totalReportes = count($reportes);
            $totalValor = 0.0;
            $pagosCompletados = 0;
            $pagosPendientes = 0;
            $reportesActivos = 0;

            foreach ($reportes as $r) {
                $valor = isset($r['valor']) ? floatval($r['valor']) : 0.0;
                $totalValor += $valor;

                $estadoPago = $this->normalizarEstado($r['estado_pago'] ?? '');
                if ($this->esPagoCompletado($estadoPago)) {
                    $pagosCompletados++;
                } else {
                    $pagosPendientes++;
                }

                $estadoRegistro = strtolower(trim($r['estado_registro'] ?? ''));
                if ($estadoRegistro === 'activo') {
                    $reportesActivos++;
                }
            }

            return [
                'totalReportes' => $totalReportes,
                'totalValor' => $totalValor,
                'pagosCompletados' => $pagosCompletados,
                'pagosPendientes' => $pagosPendientes,
                'reportesActivos' => $reportesActivos,
                'promedioValor' => $totalReportes > 0 ? $totalValor / $totalReportes : 0
            ];
        } catch (Exception $e) {
            Logger::error("Error al calcular estadísticas:", ['error' => $e->getMessage()]);
            return [
                'totalReportes' => 0,
                'totalValor' => 0.0,
                'pagosCompletados' => 0,
                'pagosPendientes' => 0,
                'reportesActivos' => 0,
                'promedioValor' => 0
            ];
        }
    }

    /**
     * Obtiene reportes filtrados por estado de pago
     * @param string $estado
     * @return array
     */
    public function obtenerReportesPorEstadoPago($estado)
    {
        $reportes = $this->obtenerReportes();
        $estadoNormalizado = $this->normalizarEstado($estado);

        return array_filter($reportes, function($r) use ($estadoNormalizado) {
            $estadoPago = $this->normalizarEstado($r['estado_pago'] ?? '');

            if ($estadoNormalizado === 'completado') {
                return $this->esPagoCompletado($estadoPago);
            } elseif ($estadoNormalizado === 'pendiente') {
                return !$this->esPagoCompletado($estadoPago);
            }

            return $estadoPago === $estadoNormalizado;
        });
    }

    /**
     * Obtiene reportes filtrados por categoría
     * @param string $categoria
     * @return array
     */
    public function obtenerReportesPorCategoria($categoria)
    {
        try {
            $sql = "SELECT
                        r.id AS id_reporte,
                        r.titulo,
                        r.descripcion,
                        r.categoria,
                        r.fecha AS fecha_reporte,
                        r.estado_registro,
                        p.id AS id_pago,
                        p.valor,
                        p.estado AS estado_pago,
                        p.fecha_pago,
                        p.id_certificado AS certificado_id
                    FROM reportes r
                    INNER JOIN pagos p ON r.id_pagos = p.id
                    WHERE r.categoria = :categoria
                    ORDER BY r.id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':categoria', $categoria, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener reportes por categoría:", [
                'categoria' => $categoria,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Crea un nuevo reporte
     * @param array $datos
     * @return int|false ID del reporte creado o false en caso de error
     */
    public function crearReporte($datos)
    {
        try {
            $sql = "INSERT INTO reportes (id_pagos, titulo, descripcion, categoria, estado_registro)
                    VALUES (:id_pagos, :titulo, :descripcion, :categoria, :estado_registro)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id_pagos', $datos['id_pagos'], PDO::PARAM_INT);
            $stmt->bindValue(':titulo', $datos['titulo'], PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
            $stmt->bindValue(':categoria', $datos['categoria'], PDO::PARAM_STR);
            $stmt->bindValue(':estado_registro', $datos['estado_registro'] ?? 'activo', PDO::PARAM_STR);

            $stmt->execute();
            $id = $this->db->lastInsertId();

            Logger::info("Reporte creado exitosamente:", ['id' => $id, 'titulo' => $datos['titulo']]);
            return $id;
        } catch (PDOException $e) {
            Logger::error("Error al crear reporte:", [
                'datos' => $datos,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Actualiza un reporte existente
     * @param int $id
     * @param array $datos
     * @return bool
     */
    public function actualizarReporte($id, $datos)
    {
        try {
            $sql = "UPDATE reportes
                    SET titulo = :titulo,
                        descripcion = :descripcion,
                        categoria = :categoria,
                        estado_registro = :estado_registro
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':titulo', $datos['titulo'], PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
            $stmt->bindValue(':categoria', $datos['categoria'], PDO::PARAM_STR);
            $stmt->bindValue(':estado_registro', $datos['estado_registro'] ?? 'activo', PDO::PARAM_STR);

            $resultado = $stmt->execute();

            if ($resultado) {
                Logger::info("Reporte actualizado exitosamente:", ['id' => $id, 'titulo' => $datos['titulo']]);
            }

            return $resultado;
        } catch (PDOException $e) {
            Logger::error("Error al actualizar reporte:", [
                'id' => $id,
                'datos' => $datos,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Elimina un reporte (soft delete - cambia estado a inactivo)
     * @param int $id
     * @return bool
     */
    public function eliminarReporte($id)
    {
        try {
            $sql = "UPDATE reportes SET estado_registro = 'inactivo' WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $resultado = $stmt->execute();

            if ($resultado) {
                Logger::info("Reporte eliminado (soft delete):", ['id' => $id]);
            }

            return $resultado;
        } catch (PDOException $e) {
            Logger::error("Error al eliminar reporte:", [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Obtiene categorías únicas de reportes
     * @return array
     */
    public function obtenerCategorias()
    {
        try {
            $sql = "SELECT DISTINCT categoria FROM reportes ORDER BY categoria ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $resultado ?: [];
        } catch (PDOException $e) {
            Logger::error("Error al obtener categorías:", ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Obtiene reportes en un rango de fechas
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return array
     */
    public function obtenerReportesPorRangoFechas($fechaInicio, $fechaFin)
    {
        try {
            $sql = "SELECT
                        r.id AS id_reporte,
                        r.titulo,
                        r.descripcion,
                        r.categoria,
                        r.fecha AS fecha_reporte,
                        r.estado_registro,
                        p.id AS id_pago,
                        p.valor,
                        p.estado AS estado_pago,
                        p.fecha_pago,
                        p.id_certificado AS certificado_id
                    FROM reportes r
                    INNER JOIN pagos p ON r.id_pagos = p.id
                    WHERE DATE(r.fecha) BETWEEN :fecha_inicio AND :fecha_fin
                    ORDER BY r.id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':fecha_inicio', $fechaInicio, PDO::PARAM_STR);
            $stmt->bindValue(':fecha_fin', $fechaFin, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener reportes por rango de fechas:", [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Normaliza el estado a minúsculas y sin espacios
     * @param string $estado
     * @return string
     */
    private function normalizarEstado($estado)
    {
        return strtolower(trim($estado));
    }

    /**
     * Verifica si un estado de pago está completado
     * @param string $estadoPago
     * @return bool
     */
    public function esPagoCompletado($estadoPago)
    {
        $estadoNormalizado = $this->normalizarEstado($estadoPago);
        return in_array($estadoNormalizado, self::ESTADOS_PAGO_COMPLETADO);
    }

    /**
     * Obtiene datos para exportación
     * @return array
     */
    public function obtenerDatosParaExportacion()
    {
        $reportes = $this->obtenerReportes();
        $estadisticas = $this->obtenerEstadisticas();

        return [
            'reportes' => $reportes,
            'estadisticas' => $estadisticas,
            'fecha_generacion' => date('Y-m-d H:i:s')
        ];
    }
}
