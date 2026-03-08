<?php
// Modelo/ModeloReporte.php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/Logger.php';

// ============================================================================
// ModeloReporte.php - REPARADO Y ADAPTADO A LA BD REAL
// ============================================================================

class ModeloReporte
{
    private $db;

    // Constantes para estados de pago
    const ESTADOS_PAGO_COMPLETADO = ['completo', 'pagado', 'paid', 'complete'];
    const ESTADOS_PAGO_PENDIENTE = ['pendiente', 'pending', 'pendiente_pago'];

    public function __construct()
    {
        $this->db = Conexion::conectar();
    }

    /**
     * Obtiene todos los reportes con su pago asociado
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
                        p.certificado_id
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
                        p.certificado_id
                    FROM reportes r
                    INNER JOIN pagos p ON r.id_pagos = p.id
                    WHERE r.id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ?: null;
        } catch (PDOException $e) {
            Logger::error("Error al obtener reporte por ID:", ['id' => $id, 'error' => $e->getMessage()]);
            return null;
        }
    }

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

                $estadoRegistro = strtolower(trim($r['estado_registro'] ?? 'activo'));
                if (empty($r['estado_registro']) || $estadoRegistro === 'activo') {
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
                'totalReportes' => 0, 'totalValor' => 0.0, 'pagosCompletados' => 0,
                'pagosPendientes' => 0, 'reportesActivos' => 0, 'promedioValor' => 0
            ];
        }
    }

    public function obtenerReportesPorEstadoPago($estado)
    {
        $reportes = $this->obtenerReportes();
        $estadoNormalizado = $this->normalizarEstado($estado);

        return array_filter($reportes, function($r) use ($estadoNormalizado) {
            $estadoPago = $this->normalizarEstado($r['estado_pago'] ?? '');
            if ($estadoNormalizado === 'completado') return $this->esPagoCompletado($estadoPago);
            elseif ($estadoNormalizado === 'pendiente') return !$this->esPagoCompletado($estadoPago);
            return $estadoPago === $estadoNormalizado;
        });
    }

    public function obtenerReportesPorCategoria($categoria)
    {
        try {
            $sql = "SELECT
                        r.id AS id_reporte, r.titulo, r.descripcion, r.categoria, r.fecha AS fecha_reporte,
                        r.estado_registro, p.id AS id_pago, p.valor, p.estado AS estado_pago,
                        p.fecha_pago, p.certificado_id
                    FROM reportes r
                    INNER JOIN pagos p ON r.id_pagos = p.id
                    WHERE r.categoria = :categoria ORDER BY r.id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':categoria', $categoria, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

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
            $stmt->bindValue(':estado_registro', $datos['estado_registro'] ?? null, PDO::PARAM_STR);

            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            Logger::error("Error al crear reporte:", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function actualizarReporte($id, $datos)
    {
        try {
            $sql = "UPDATE reportes SET titulo = :titulo, descripcion = :descripcion, categoria = :categoria, estado_registro = :estado_registro WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':titulo', $datos['titulo'], PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
            $stmt->bindValue(':categoria', $datos['categoria'], PDO::PARAM_STR);
            $stmt->bindValue(':estado_registro', $datos['estado_registro'] ?? null, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminarReporte($id)
    {
        try {
            $sql = "UPDATE reportes SET estado_registro = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerCategorias()
    {
        try {
            $sql = "SELECT DISTINCT categoria FROM reportes WHERE categoria IS NOT NULL ORDER BY categoria ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    public function obtenerReportesPorRangoFechas($fechaInicio, $fechaFin)
    {
        try {
            $sql = "SELECT r.*, p.valor, p.estado AS estado_pago, p.fecha_pago, p.certificado_id
                    FROM reportes r INNER JOIN pagos p ON r.id_pagos = p.id
                    WHERE DATE(r.fecha) BETWEEN :fecha_inicio AND :fecha_fin ORDER BY r.id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':fecha_inicio', $fechaInicio, PDO::PARAM_STR);
            $stmt->bindValue(':fecha_fin', $fechaFin, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    private function normalizarEstado($estado)
    {
        return strtolower(trim($estado));
    }

    public function esPagoCompletado($estadoPago)
    {
        return in_array($this->normalizarEstado($estadoPago), self::ESTADOS_PAGO_COMPLETADO);
    }

    public function obtenerDatosParaExportacion()
    {
        return [
            'reportes' => $this->obtenerReportes(),
            'estadisticas' => $this->obtenerEstadisticas(),
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
                    GROUP BY tipo_certificado ORDER BY total DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteCertificadosPorEstado()
    {
        try {
            $sql = "SELECT estado, COUNT(*) as total FROM certificados GROUP BY estado ORDER BY total DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteTiemposProcesamiento()
    {
        try {
            $sql = "SELECT tipo_certificado, AVG(DATEDIFF(fecha_generacion, fecha_solicitud)) as promedio_dias
                    FROM certificados WHERE fecha_generacion IS NOT NULL AND fecha_solicitud IS NOT NULL
                    GROUP BY tipo_certificado";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteCertificadosMasSolicitados($limite = 10)
    {
        try {
            $sql = "SELECT tipo_certificado, COUNT(*) as total_solicitudes
                    FROM certificados GROUP BY tipo_certificado ORDER BY total_solicitudes DESC LIMIT :limite";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - FELIGRESES
    // ========================================================================

    public function reporteFeligresesPorTipoDocumento()
    {
        try {
            $sql = "SELECT dt.tipo as tipo_documento, COUNT(f.id) as total 
                    FROM feligreses f
                    LEFT JOIN documento_tipos dt ON f.tipo_documento_id = dt.id
                    GROUP BY dt.id ORDER BY total DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteFeligresesMasActivos($limite = 10)
    {
        try {
            $sql = "SELECT CONCAT(f.primer_nombre, ' ', COALESCE(f.primer_apellido, '')) as nombre_completo, f.numero_documento, COUNT(c.id) as total_certificados
                    FROM feligreses f
                    LEFT JOIN certificados c ON f.id = c.solicitante_id
                    GROUP BY f.id ORDER BY total_certificados DESC LIMIT :limite";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteFeligresesNuevos($dias = 30)
    {
        try {
            // Como feligreses no tiene fecha de registro como tal en tu BD, usamos los últimos agregados por ID
            $sql = "SELECT CONCAT(primer_nombre, ' ', COALESCE(primer_apellido, '')) as nombre_completo, numero_documento 
                    FROM feligreses ORDER BY id DESC LIMIT :dias";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':dias', $dias, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - SACRAMENTOS
    // ========================================================================

    public function reporteSacramentosPorTipo()
    {
        try {
            $sql = "SELECT st.tipo as tipo_sacramento, COUNT(s.id) as total 
                    FROM sacramentos s
                    LEFT JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                    GROUP BY st.id ORDER BY total DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteSacramentosPorLibro()
    {
        try {
            $sql = "SELECT CONCAT(lt.tipo, ' #', l.numero) as nombre_libro, COUNT(s.id) as total_sacramentos
                    FROM libros l
                    LEFT JOIN libro_tipo lt ON l.libro_tipo_id = lt.id
                    LEFT JOIN sacramentos s ON l.id = s.libro_id
                    GROUP BY l.id ORDER BY total_sacramentos DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteTendenciasSacramentos($meses = 12)
    {
        try {
            $sql = "SELECT DATE_FORMAT(fecha_generacion, '%Y-%m') as mes, COUNT(*) as total
                    FROM sacramentos WHERE fecha_generacion >= DATE_SUB(NOW(), INTERVAL :meses MONTH)
                    GROUP BY mes ORDER BY mes DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':meses', $meses, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - FINANCIERO
    // ========================================================================

    public function reporteIngresosPorConcepto()
    {
        try {
            $sql = "SELECT tipo_concepto as concepto, SUM(valor) as total_ingresos
                    FROM pagos WHERE estado IN ('completo', 'pagado', 'paid', 'complete')
                    GROUP BY tipo_concepto ORDER BY total_ingresos DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteEstadoPagos()
    {
        try {
            $sql = "SELECT estado, COUNT(*) as cantidad, SUM(valor) as total
                    FROM pagos GROUP BY estado ORDER BY cantidad DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteMetodosPago()
    {
        try {
            $sql = "SELECT tp.descripcion as metodo_pago, COUNT(p.id) as cantidad, SUM(p.valor) as total
                    FROM pagos p
                    LEFT JOIN tipos_pago tp ON p.tipo_pago_id = tp.id
                    WHERE p.tipo_pago_id IS NOT NULL GROUP BY p.tipo_pago_id ORDER BY cantidad DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteValoresPromedio()
    {
        try {
            $sql = "SELECT tipo_concepto as concepto, AVG(valor) as promedio
                    FROM pagos WHERE valor > 0 GROUP BY tipo_concepto ORDER BY promedio DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - ACTIVIDAD DEL SISTEMA
    // ========================================================================

    public function reporteUsuariosMasActivos($limite = 10)
    {
        try {
            $sql = "SELECT CONCAT(f.primer_nombre, ' ', COALESCE(f.primer_apellido, '')) as nombre_usuario, ur.rol, COUNT(c.id) as total_certificados
                    FROM usuarios u
                    LEFT JOIN feligreses f ON u.id = f.usuario_id
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id
                    LEFT JOIN certificados c ON u.id = c.usuario_generador_id
                    GROUP BY u.id ORDER BY total_certificados DESC LIMIT :limite";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteActividadPorRol()
    {
        try {
            $sql = "SELECT ur.rol, COUNT(DISTINCT u.id) as total_usuarios, COUNT(c.id) as total_certificados
                    FROM usuarios u
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id
                    LEFT JOIN certificados c ON u.id = c.usuario_generador_id
                    GROUP BY ur.id ORDER BY total_certificados DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteTasaConversion()
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_solicitudes,
                        SUM(CASE WHEN estado IN ('generado', 'descargado') THEN 1 ELSE 0 END) as certificados_completados,
                        (SUM(CASE WHEN estado IN ('generado', 'descargado') THEN 1 ELSE 0 END) / COUNT(*) * 100) as tasa_conversion
                    FROM certificados";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - LIBROS PARROQUIALES
    // ========================================================================

    public function reporteLibrosPorTipo()
    {
        try {
            $sql = "SELECT lt.tipo, COUNT(l.id) as total 
                    FROM libros l
                    LEFT JOIN libro_tipo lt ON l.libro_tipo_id = lt.id
                    GROUP BY lt.id ORDER BY total DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteCapacidadLibros()
    {
        try {
            // Como tu BD no maneja "capacidad" estática, solo listaremos el uso actual
            $sql = "SELECT CONCAT(lt.tipo, ' #', l.numero) as nombre, COUNT(s.id) as registros_actuales
                    FROM libros l
                    LEFT JOIN libro_tipo lt ON l.libro_tipo_id = lt.id
                    LEFT JOIN sacramentos s ON l.id = s.libro_id
                    GROUP BY l.id ORDER BY registros_actuales DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteLibrosActivos()
    {
        try {
            $sql = "SELECT CONCAT(lt.tipo, ' #', l.numero) as nombre, lt.tipo, COUNT(s.id) as total_registros
                    FROM libros l
                    LEFT JOIN libro_tipo lt ON l.libro_tipo_id = lt.id
                    LEFT JOIN sacramentos s ON l.id = s.libro_id
                    WHERE l.estado_registro IS NULL
                    GROUP BY l.id ORDER BY total_registros DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - USUARIOS Y ROLES
    // ========================================================================

    public function reporteUsuariosPorRol()
    {
        try {
            $sql = "SELECT ur.rol, COUNT(u.id) as total 
                    FROM usuarios u
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id
                    GROUP BY ur.id ORDER BY total DESC";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteUsuariosSinDatosCompletos()
    {
        try {
            $sql = "SELECT u.email, ur.rol, CONCAT(f.primer_nombre, ' ', COALESCE(f.primer_apellido, '')) as nombre
                    FROM usuarios u 
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id
                    LEFT JOIN feligreses f ON u.id = f.usuario_id
                    WHERE u.datos_completos = 0";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteRegistrosUsuarios($fechaInicio, $fechaFin)
    {
        try {
            // Usuarios no tiene fecha_registro en tu BD, traemos la lista ordenada por ID
            $sql = "SELECT u.email, ur.rol, CONCAT(f.primer_nombre, ' ', COALESCE(f.primer_apellido, '')) as nombre
                    FROM usuarios u 
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id
                    LEFT JOIN feligreses f ON u.id = f.usuario_id
                    ORDER BY u.id DESC LIMIT 50";
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - NOTICIAS
    // ========================================================================

    public function reporteNoticiasPorPeriodo($fechaInicio = null, $fechaFin = null)
    {
        try {
            $sql = "SELECT n.titulo, CONCAT(f.primer_nombre, ' ', COALESCE(f.primer_apellido, '')) as autor, n.fecha_publicacion
                    FROM noticias n
                    LEFT JOIN usuarios u ON n.id_usuario = u.id
                    LEFT JOIN feligreses f ON u.id = f.usuario_id ";
            
            if ($fechaInicio && $fechaFin) {
                $sql .= "WHERE n.fecha_publicacion BETWEEN :inicio AND :fin ";
            }
            $sql .= "ORDER BY n.fecha_publicacion DESC";

            $stmt = $this->db->prepare($sql);
            if ($fechaInicio && $fechaFin) {
                $stmt->bindValue(':inicio', $fechaInicio);
                $stmt->bindValue(':fin', $fechaFin);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function reporteAutoresMasActivos($limite = 10)
    {
        try {
            $sql = "SELECT CONCAT(f.primer_nombre, ' ', COALESCE(f.primer_apellido, '')) as autor, COUNT(n.id) as total_noticias
                    FROM noticias n 
                    LEFT JOIN usuarios u ON n.id_usuario = u.id
                    LEFT JOIN feligreses f ON u.id = f.usuario_id
                    GROUP BY n.id_usuario ORDER BY total_noticias DESC LIMIT :limite";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // ========================================================================
    // REPORTES ANALÍTICOS - CONTACTOS
    // ========================================================================

    public function reporteContactosPorPeriodo($fechaInicio = null, $fechaFin = null)
    {
        // NOTA: Tu base de datos NO TIENE una tabla de 'contactos', por lo que esto se deja vacío por ahora
        return [];
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
            
            $resumen['total_certificados'] = $this->db->query("SELECT COUNT(*) FROM certificados")->fetchColumn();
            $resumen['total_feligreses'] = $this->db->query("SELECT COUNT(*) FROM feligreses")->fetchColumn();
            $resumen['total_sacramentos'] = $this->db->query("SELECT COUNT(*) FROM sacramentos")->fetchColumn();
            $resumen['ingresos_totales'] = $this->db->query("SELECT SUM(valor) FROM pagos WHERE estado IN ('completo', 'pagado')")->fetchColumn() ?? 0;
            // Usuarios confirmados activos
            $resumen['usuarios_activos'] = $this->db->query("SELECT COUNT(*) FROM usuarios WHERE email_confirmed = 1")->fetchColumn();
            
            return [$resumen];
        } catch (PDOException $e) {
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
        foreach ($datos as $row) { fputcsv($output, $row); }
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