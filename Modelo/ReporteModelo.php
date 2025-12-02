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

    // ========================================================================
    // REPORTES ANALÍTICOS - CERTIFICADOS
    // ========================================================================

    public function reporteCertificadosPorTipo()
    {
        try {
            $sql = "SELECT tipo_certificado, COUNT(*) as total 
                    FROM certificados 
                    GROUP BY tipo_certificado 
                    ORDER BY total DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteCertificadosPorTipo", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteCertificadosPorEstado()
    {
        try {
            $sql = "SELECT estado, COUNT(*) as total 
                    FROM certificados 
                    GROUP BY estado 
                    ORDER BY total DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteCertificadosPorEstado", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteTiemposProcesamiento()
    {
        try {
            $sql = "SELECT tipo_certificado, 
                           AVG(DATEDIFF(fecha_emision, fecha_solicitud)) as promedio_dias
                    FROM certificados 
                    WHERE fecha_emision IS NOT NULL AND fecha_solicitud IS NOT NULL
                    GROUP BY tipo_certificado";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteTiemposProcesamiento", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteCertificadosMasSolicitados($limite = 10)
    {
        try {
            $sql = "SELECT tipo_certificado, COUNT(*) as total_solicitudes
                    FROM certificados 
                    GROUP BY tipo_certificado 
                    ORDER BY total_solicitudes DESC 
                    LIMIT :limite";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteCertificadosMasSolicitados", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - FELIGRESES
    // ========================================================================

    public function reporteFeligresesPorTipoDocumento()
    {
        try {
            $sql = "SELECT tipo_documento, COUNT(*) as total 
                    FROM feligreses 
                    GROUP BY tipo_documento 
                    ORDER BY total DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteFeligresesPorTipoDocumento", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteFeligresesMasActivos($limite = 10)
    {
        try {
            $sql = "SELECT f.nombre_completo, f.numero_documento, COUNT(c.id) as total_certificados
                    FROM feligreses f
                    LEFT JOIN certificados c ON f.id = c.id_feligres
                    GROUP BY f.id
                    ORDER BY total_certificados DESC
                    LIMIT :limite";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteFeligresesMasActivos", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteFeligresesNuevos($dias = 30)
    {
        try {
            $sql = "SELECT nombre_completo, numero_documento, fecha_registro
                    FROM feligreses 
                    WHERE fecha_registro >= DATE_SUB(NOW(), INTERVAL :dias DAY)
                    ORDER BY fecha_registro DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':dias', $dias, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteFeligresesNuevos", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - SACRAMENTOS
    // ========================================================================

    public function reporteSacramentosPorTipo()
    {
        try {
            $sql = "SELECT tipo_sacramento, COUNT(*) as total 
                    FROM sacramentos 
                    GROUP BY tipo_sacramento 
                    ORDER BY total DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteSacramentosPorTipo", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteSacramentosPorLibro()
    {
        try {
            $sql = "SELECT l.nombre as nombre_libro, COUNT(s.id) as total_sacramentos
                    FROM libros l
                    LEFT JOIN sacramentos s ON l.id = s.id_libro
                    GROUP BY l.id
                    ORDER BY total_sacramentos DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteSacramentosPorLibro", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteTendenciasSacramentos($meses = 12)
    {
        try {
            $sql = "SELECT DATE_FORMAT(fecha_sacramento, '%Y-%m') as mes, COUNT(*) as total
                    FROM sacramentos 
                    WHERE fecha_sacramento >= DATE_SUB(NOW(), INTERVAL :meses MONTH)
                    GROUP BY mes
                    ORDER BY mes DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':meses', $meses, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteTendenciasSacramentos", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - FINANCIERO
    // ========================================================================

    public function reporteIngresosPorConcepto()
    {
        try {
            $sql = "SELECT concepto, SUM(valor) as total_ingresos
                    FROM pagos 
                    WHERE estado IN ('completo', 'pagado', 'paid', 'complete')
                    GROUP BY concepto 
                    ORDER BY total_ingresos DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteIngresosPorConcepto", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteEstadoPagos()
    {
        try {
            $sql = "SELECT estado, COUNT(*) as cantidad, SUM(valor) as total
                    FROM pagos 
                    GROUP BY estado 
                    ORDER BY cantidad DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteEstadoPagos", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteMetodosPago()
    {
        try {
            $sql = "SELECT metodo_pago, COUNT(*) as cantidad, SUM(valor) as total
                    FROM pagos 
                    WHERE metodo_pago IS NOT NULL
                    GROUP BY metodo_pago 
                    ORDER BY cantidad DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteMetodosPago", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteValoresPromedio()
    {
        try {
            $sql = "SELECT concepto, AVG(valor) as promedio
                    FROM pagos 
                    WHERE valor > 0
                    GROUP BY concepto 
                    ORDER BY promedio DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteValoresPromedio", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - ACTIVIDAD DEL SISTEMA
    // ========================================================================

    public function reporteUsuariosMasActivos($limite = 10)
    {
        try {
            $sql = "SELECT u.nombre as nombre_usuario, u.rol, COUNT(c.id) as total_certificados
                    FROM usuarios u
                    LEFT JOIN certificados c ON u.id = c.id_usuario_genera
                    GROUP BY u.id
                    ORDER BY total_certificados DESC
                    LIMIT :limite";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteUsuariosMasActivos", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteActividadPorRol()
    {
        try {
            $sql = "SELECT u.rol, COUNT(DISTINCT u.id) as total_usuarios, COUNT(c.id) as total_certificados
                    FROM usuarios u
                    LEFT JOIN certificados c ON u.id = c.id_usuario_genera
                    GROUP BY u.rol
                    ORDER BY total_certificados DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteActividadPorRol", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteTasaConversion()
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_solicitudes,
                        SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END) as certificados_completados,
                        (SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END) / COUNT(*) * 100) as tasa_conversion
                    FROM certificados";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteTasaConversion", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - LIBROS PARROQUIALES
    // ========================================================================

    public function reporteLibrosPorTipo()
    {
        try {
            $sql = "SELECT tipo, COUNT(*) as total 
                    FROM libros 
                    GROUP BY tipo 
                    ORDER BY total DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteLibrosPorTipo", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteCapacidadLibros()
    {
        try {
            $sql = "SELECT l.nombre, l.capacidad, COUNT(s.id) as registros_actuales,
                           (COUNT(s.id) / l.capacidad * 100) as porcentaje_uso
                    FROM libros l
                    LEFT JOIN sacramentos s ON l.id = s.id_libro
                    GROUP BY l.id
                    ORDER BY porcentaje_uso DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteCapacidadLibros", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteLibrosActivos()
    {
        try {
            $sql = "SELECT l.nombre, l.tipo, COUNT(s.id) as total_registros
                    FROM libros l
                    LEFT JOIN sacramentos s ON l.id = s.id_libro
                    WHERE l.estado = 'activo'
                    GROUP BY l.id
                    ORDER BY total_registros DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteLibrosActivos", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - USUARIOS Y ROLES
    // ========================================================================

    public function reporteUsuariosPorRol()
    {
        try {
            $sql = "SELECT rol, COUNT(*) as total 
                    FROM usuarios 
                    GROUP BY rol 
                    ORDER BY total DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteUsuariosPorRol", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteUsuariosSinDatosCompletos()
    {
        try {
            $sql = "SELECT nombre, email, rol
                    FROM usuarios 
                    WHERE telefono IS NULL OR telefono = ''
                    ORDER BY nombre";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteUsuariosSinDatosCompletos", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteRegistrosUsuarios($fechaInicio, $fechaFin)
    {
        try {
            $sql = "SELECT nombre, email, rol, fecha_registro
                    FROM usuarios 
                    WHERE fecha_registro BETWEEN :inicio AND :fin
                    ORDER BY fecha_registro DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':inicio', $fechaInicio);
            $stmt->bindValue(':fin', $fechaFin);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteRegistrosUsuarios", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - NOTICIAS
    // ========================================================================

    public function reporteNoticiasPorPeriodo($fechaInicio = null, $fechaFin = null)
    {
        try {
            if ($fechaInicio && $fechaFin) {
                $sql = "SELECT titulo, autor, fecha_publicacion
                        FROM noticias 
                        WHERE fecha_publicacion BETWEEN :inicio AND :fin
                        ORDER BY fecha_publicacion DESC";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':inicio', $fechaInicio);
                $stmt->bindValue(':fin', $fechaFin);
                $stmt->execute();
            } else {
                $sql = "SELECT titulo, autor, fecha_publicacion
                        FROM noticias 
                        ORDER BY fecha_publicacion DESC";
                $stmt = $this->db->query($sql);
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteNoticiasPorPeriodo", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function reporteAutoresMasActivos($limite = 10)
    {
        try {
            $sql = "SELECT autor, COUNT(*) as total_noticias
                    FROM noticias 
                    GROUP BY autor 
                    ORDER BY total_noticias DESC
                    LIMIT :limite";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteAutoresMasActivos", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - CONTACTOS
    // ========================================================================

    public function reporteContactosPorPeriodo($fechaInicio = null, $fechaFin = null)
    {
        try {
            if ($fechaInicio && $fechaFin) {
                $sql = "SELECT nombre, email, asunto, fecha_envio
                        FROM contactos 
                        WHERE fecha_envio BETWEEN :inicio AND :fin
                        ORDER BY fecha_envio DESC";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':inicio', $fechaInicio);
                $stmt->bindValue(':fin', $fechaFin);
                $stmt->execute();
            } else {
                $sql = "SELECT nombre, email, asunto, fecha_envio
                        FROM contactos 
                        ORDER BY fecha_envio DESC";
                $stmt = $this->db->query($sql);
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteContactosPorPeriodo", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTES COMPARATIVOS Y TENDENCIAS
    // ========================================================================

    public function reporteComparativoAnual($anio1, $anio2)
    {
        try {
            $sql = "SELECT 
                        'Certificados' as metrica,
                        SUM(CASE WHEN YEAR(fecha_solicitud) = :anio1 THEN 1 ELSE 0 END) as anio1_valor,
                        SUM(CASE WHEN YEAR(fecha_solicitud) = :anio2 THEN 1 ELSE 0 END) as anio2_valor
                    FROM certificados
                    UNION ALL
                    SELECT 
                        'Ingresos' as metrica,
                        SUM(CASE WHEN YEAR(fecha_pago) = :anio1 THEN valor ELSE 0 END) as anio1_valor,
                        SUM(CASE WHEN YEAR(fecha_pago) = :anio2 THEN valor ELSE 0 END) as anio2_valor
                    FROM pagos";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':anio1', $anio1, PDO::PARAM_INT);
            $stmt->bindValue(':anio2', $anio2, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en reporteComparativoAnual", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // REPORTE EJECUTIVO GENERAL
    // ========================================================================

    public function reporteResumenGeneral()
    {
        try {
            $resumen = [];
            
            // Total certificados
            $sql = "SELECT COUNT(*) as total FROM certificados";
            $resumen['total_certificados'] = $this->db->query($sql)->fetchColumn();
            
            // Total feligreses
            $sql = "SELECT COUNT(*) as total FROM feligreses";
            $resumen['total_feligreses'] = $this->db->query($sql)->fetchColumn();
            
            // Total sacramentos
            $sql = "SELECT COUNT(*) as total FROM sacramentos";
            $resumen['total_sacramentos'] = $this->db->query($sql)->fetchColumn();
            
            // Ingresos totales
            $sql = "SELECT SUM(valor) as total FROM pagos WHERE estado IN ('completo', 'pagado')";
            $resumen['ingresos_totales'] = $this->db->query($sql)->fetchColumn() ?? 0;
            
            // Usuarios activos
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'activo'";
            $resumen['usuarios_activos'] = $this->db->query($sql)->fetchColumn();
            
            return [$resumen];
        } catch (PDOException $e) {
            Logger::error("Error en reporteResumenGeneral", ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========================================================================
    // MÉTODOS DE EXPORTACIÓN
    // ========================================================================

    public function exportarCSV($datos)
    {
        if (empty($datos)) return '';
        
        $output = fopen('php://temp', 'r+');
        fputcsv($output, array_keys($datos[0]));
        
        foreach ($datos as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }

    public function exportarJSON($datos)
    {
        return json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
