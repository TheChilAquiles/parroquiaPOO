<?php

// ============================================================================
// ModeloSolicitudCertificado.php
// Gestiona solicitudes de certificados (propios y de familiares)
// ============================================================================

class ModeloSolicitudCertificado
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Valida si un feligrés puede solicitar certificado de otro (parentesco)
     * @param int $solicitanteId ID del feligrés que solicita
     * @param int $feligresId ID del feligrés del certificado
     * @return array ['valido' => bool, 'parentesco_id' => int|null, 'mensaje' => string]
     */
    public function mdlValidarParentesco($solicitanteId, $feligresId)
    {
        try {
            // Caso 1: Es para sí mismo
            if ($solicitanteId == $feligresId) {
                return [
                    'valido' => true,
                    'parentesco_id' => null,
                    'parentesco_nombre' => 'Propio',
                    'mensaje' => 'Certificado propio'
                ];
            }

            // Caso 2: Buscar relación en tabla parientes (bidireccional)
            $sql = "SELECT p.*, pa.parentesco
                    FROM parientes p
                    JOIN parentescos pa ON p.parentesco_id = pa.id
                    WHERE ((p.feligres_sujeto_id = ? AND p.feligres_pariente_id = ?)
                       OR (p.feligres_sujeto_id = ? AND p.feligres_pariente_id = ?))
                    AND p.estado_registro IS NULL
                    LIMIT 1";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$solicitanteId, $feligresId, $feligresId, $solicitanteId]);
            $relacion = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($relacion) {
                return [
                    'valido' => true,
                    'parentesco_id' => $relacion['parentesco_id'],
                    'parentesco_nombre' => $relacion['parentesco'],
                    'mensaje' => "Solicitante es {$relacion['parentesco']}"
                ];
            }

            // Caso 3: No hay relación registrada
            return [
                'valido' => false,
                'parentesco_id' => null,
                'parentesco_nombre' => null,
                'mensaje' => 'No existe parentesco registrado. Debe registrarlo primero en secretaría.'
            ];
        } catch (PDOException $e) {
            error_log("Error al validar parentesco: " . $e->getMessage());
            return [
                'valido' => false,
                'parentesco_id' => null,
                'mensaje' => 'Error al validar parentesco'
            ];
        }
    }

    /**
     * Crea una nueva solicitud de certificado
     * @param array $datos [solicitante_id, feligres_certificado_id, parentesco_id, sacramento_id, motivo]
     * @return array ['status' => 'success'|'error', 'id' => int|null, 'message' => string]
     */
    public function mdlCrearSolicitud($datos)
    {
        try {
            // Validar parentesco si no es certificado propio
            if ($datos['solicitante_id'] != $datos['feligres_certificado_id']) {
                $validacion = $this->mdlValidarParentesco(
                    $datos['solicitante_id'],
                    $datos['feligres_certificado_id']
                );

                if (!$validacion['valido']) {
                    return [
                        'status' => 'error',
                        'id' => null,
                        'message' => $validacion['mensaje']
                    ];
                }

                $datos['parentesco_id'] = $validacion['parentesco_id'];
            } else {
                $datos['parentesco_id'] = null;
            }

            // Obtener tipo de sacramento
            $sqlTipo = "SELECT st.tipo
                       FROM sacramentos s
                       JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                       WHERE s.id = ?";
            $stmtTipo = $this->conexion->prepare($sqlTipo);
            $stmtTipo->execute([$datos['sacramento_id']]);
            $sacramento = $stmtTipo->fetch(PDO::FETCH_ASSOC);

            if (!$sacramento) {
                return [
                    'status' => 'error',
                    'id' => null,
                    'message' => 'Sacramento no encontrado'
                ];
            }

            // Crear certificado
            $sql = "INSERT INTO certificados (
                        usuario_generador_id, solicitante_id, feligres_certificado_id,
                        parentesco_id, fecha_solicitud, tipo_certificado,
                        motivo_solicitud, sacramento_id, estado
                    ) VALUES (NULL, ?, ?, ?, NOW(), ?, ?, ?, 'pendiente_pago')";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $datos['solicitante_id'],
                $datos['feligres_certificado_id'],
                $datos['parentesco_id'],
                $sacramento['tipo'],
                $datos['motivo'] ?? 'Solicitud de certificado',
                $datos['sacramento_id']
            ]);

            $certificadoId = $this->conexion->lastInsertId();

            return [
                'status' => 'success',
                'id' => $certificadoId,
                'message' => 'Solicitud creada exitosamente. Proceda al pago.'
            ];
        } catch (PDOException $e) {
            error_log("Error al crear solicitud: " . $e->getMessage());
            return [
                'status' => 'error',
                'id' => null,
                'message' => 'Error al crear solicitud'
            ];
        }
    }

    /**
     * Obtiene todas las solicitudes de un feligrés
     * @param int $feligresId ID del feligrés
     * @return array Lista de solicitudes
     */
    public function mdlObtenerMisSolicitudes($feligresId)
    {
        try {
            $sql = "SELECT
                        c.id,
                        c.tipo_certificado,
                        c.fecha_solicitud,
                        c.fecha_expiracion,
                        c.estado,
                        c.ruta_archivo,
                        CONCAT(f.primer_nombre, ' ', f.primer_apellido) AS feligres_nombre,
                        pa.parentesco AS relacion,
                        s.fecha_generacion AS fecha_sacramento
                    FROM certificados c
                    JOIN feligreses f ON c.feligres_certificado_id = f.id
                    LEFT JOIN parentescos pa ON c.parentesco_id = pa.id
                    JOIN sacramentos s ON c.sacramento_id = s.id
                    WHERE c.solicitante_id = ?
                    AND c.estado_registro IS NULL
                    ORDER BY c.fecha_solicitud DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$feligresId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener solicitudes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un certificado por ID
     * @param int $id ID del certificado
     * @return array|false Datos del certificado o false
     */
    public function mdlObtenerPorId($id)
    {
        try {
            $sql = "SELECT * FROM certificados WHERE id = ? AND estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener certificado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marca un certificado como pagado
     * @param int $id ID del certificado
     * @return bool
     */
    public function mdlMarcarPagado($id)
    {
        try {
            $sql = "UPDATE certificados
                    SET fecha_pago = NOW(),
                        estado = 'generado'
                    WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error al marcar como pagado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Marca un certificado como descargado
     * @param int $id ID del certificado
     * @return bool
     */
    public function mdlMarcarDescargado($id)
    {
        try {
            $sql = "UPDATE certificados SET estado = 'descargado' WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error al marcar como descargado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza certificado tras generar PDF
     * @param int $id ID del certificado
     * @param string $rutaArchivo Ruta del PDF generado
     * @return bool
     */
    public function mdlActualizarTrasGeneracion($id, $rutaArchivo)
    {
        try {
            $sql = "UPDATE certificados
                    SET fecha_generacion = NOW(),
                        fecha_expiracion = DATE_ADD(NOW(), INTERVAL 30 DAY),
                        ruta_archivo = ?,
                        estado = 'generado'
                    WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$rutaArchivo, $id]);
        } catch (PDOException $e) {
            error_log("Error al actualizar tras generación: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica y marca certificados expirados (para cron job)
     * @return int Cantidad de certificados expirados
     */
    public function mdlVerificarExpiracion()
    {
        try {
            $sql = "UPDATE certificados
                    SET estado = 'expirado'
                    WHERE fecha_expiracion < CURDATE()
                    AND estado IN ('generado', 'descargado')
                    AND estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error al verificar expiración: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Crea certificado directamente (flujo simplificado para admin/secretario)
     * Busca automáticamente el sacramento del feligrés y genera certificado sin validación de parentesco
     * @param array $datos [usuario_generador_id, tipo_documento_id, numero_documento, tipo_sacramento_id]
     * @return array ['status' => 'success'|'error', 'id' => int|null, 'message' => string]
     */
    public function mdlCrearCertificadoDirecto($datos)
    {
        try {
            // 1. Buscar feligrés por documento
            $sqlFeligres = "SELECT id FROM feligreses
                           WHERE tipo_documento_id = ?
                           AND numero_documento = ?
                           AND estado_registro IS NULL
                           LIMIT 1";
            $stmtFeligres = $this->conexion->prepare($sqlFeligres);
            $stmtFeligres->execute([
                $datos['tipo_documento_id'],
                $datos['numero_documento']
            ]);
            $feligres = $stmtFeligres->fetch(PDO::FETCH_ASSOC);

            if (!$feligres) {
                return [
                    'status' => 'error',
                    'id' => null,
                    'message' => 'Feligrés no encontrado con ese documento'
                ];
            }

            $feligresId = $feligres['id'];

            // 2. Buscar sacramento del feligrés por tipo
            $sqlSacramento = "SELECT DISTINCT s.id, st.tipo
                             FROM sacramentos s
                             JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                             JOIN participantes p ON p.sacramento_id = s.id
                             WHERE p.feligres_id = ?
                             AND s.tipo_sacramento_id = ?
                             AND s.estado_registro IS NULL
                             ORDER BY s.fecha_generacion DESC
                             LIMIT 1";
            $stmtSacramento = $this->conexion->prepare($sqlSacramento);
            $stmtSacramento->execute([
                $feligresId,
                $datos['tipo_sacramento_id']
            ]);
            $sacramento = $stmtSacramento->fetch(PDO::FETCH_ASSOC);

            if (!$sacramento) {
                return [
                    'status' => 'error',
                    'id' => null,
                    'message' => 'No se encontró sacramento de ese tipo para el feligrés'
                ];
            }

            // 3. Crear certificado directamente
            $sql = "INSERT INTO certificados (
                        usuario_generador_id, solicitante_id, feligres_certificado_id,
                        parentesco_id, fecha_solicitud, tipo_certificado,
                        motivo_solicitud, sacramento_id, estado
                    ) VALUES (?, ?, ?, NULL, NOW(), ?, 'Generación directa', ?, 'pendiente_pago')";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $datos['usuario_generador_id'],
                $feligresId, // El solicitante es el mismo feligrés
                $feligresId,
                $sacramento['tipo'],
                $sacramento['id']
            ]);

            $certificadoId = $this->conexion->lastInsertId();

            return [
                'status' => 'success',
                'id' => $certificadoId,
                'message' => 'Certificado creado exitosamente',
                'feligres_id' => $feligresId,
                'sacramento_id' => $sacramento['id']
            ];
        } catch (PDOException $e) {
            error_log("Error al crear certificado directo: " . $e->getMessage());
            return [
                'status' => 'error',
                'id' => null,
                'message' => 'Error al crear certificado: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene todos los certificados del sistema (para vista admin)
     * @return array Lista de certificados con información completa
     */
    public function mdlObtenerTodosLosCertificados()
    {
        try {
            $sql = "SELECT
                        c.id,
                        c.tipo_certificado,
                        c.fecha_solicitud,
                        c.fecha_generacion,
                        c.fecha_expiracion,
                        c.estado,
                        c.ruta_archivo,
                        CONCAT(f.primer_nombre, ' ', f.primer_apellido) AS feligres_nombre,
                        f.numero_documento,
                        CONCAT(sol.primer_nombre, ' ', sol.primer_apellido) AS solicitante_nombre,
                        CONCAT(gen.nombre, ' ', gen.apellido) AS generador_nombre,
                        pa.parentesco AS relacion,
                        s.fecha_generacion AS fecha_sacramento,
                        st.tipo AS tipo_sacramento
                    FROM certificados c
                    JOIN feligreses f ON c.feligres_certificado_id = f.id
                    JOIN feligreses sol ON c.solicitante_id = sol.id
                    LEFT JOIN usuarios gen ON c.usuario_generador_id = gen.id
                    LEFT JOIN parentescos pa ON c.parentesco_id = pa.id
                    JOIN sacramentos s ON c.sacramento_id = s.id
                    JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                    WHERE c.estado_registro IS NULL
                    ORDER BY c.fecha_solicitud DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todos los certificados: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene certificados pendientes de pago (para módulo de pagos)
     * @param int|null $feligresId Si se proporciona, filtra por feligrés solicitante
     * @return array Lista de certificados pendientes de pago
     */
    public function mdlObtenerPendientesPago($feligresId = null)
    {
        try {
            $sql = "SELECT
                        c.id,
                        c.tipo_certificado,
                        c.fecha_solicitud,
                        CONCAT(f.primer_nombre, ' ', f.primer_apellido) AS feligres_nombre,
                        f.numero_documento,
                        pa.parentesco AS relacion,
                        15000 AS monto -- Valor fijo por ahora, puede ser configurable
                    FROM certificados c
                    JOIN feligreses f ON c.feligres_certificado_id = f.id
                    LEFT JOIN parentescos pa ON c.parentesco_id = pa.id
                    WHERE c.estado = 'pendiente_pago'
                    AND c.estado_registro IS NULL";

            if ($feligresId !== null) {
                $sql .= " AND c.solicitante_id = ?";
                $stmt = $this->conexion->prepare($sql);
                $stmt->execute([$feligresId]);
            } else {
                $stmt = $this->conexion->prepare($sql);
                $stmt->execute();
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener pendientes de pago: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Verifica si ya existe una solicitud pendiente para un sacramento específico
     * @param int $sacramentoId ID del sacramento
     * @param int $feligresId ID del feligrés solicitante
     * @param int $tipoSacramentoId ID del tipo de sacramento
     * @return bool True si existe solicitud pendiente, False si no
     */
    public function mdlVerificarSolicitudExistente($sacramentoId, $feligresId, $tipoSacramentoId)
    {
        try {
            $sql = "SELECT COUNT(*) as total
                    FROM certificados c
                    WHERE c.sacramento_id = ?
                    AND c.solicitante_id = ?
                    AND c.tipo_certificado_id = ?
                    AND c.estado IN ('pendiente_pago', 'generado', 'descargado')
                    AND c.estado_registro IS NULL";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$sacramentoId, $feligresId, $tipoSacramentoId]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($resultado['total'] > 0);

        } catch (PDOException $e) {
            Logger::error("Error al verificar solicitud existente", [
                'sacramento_id' => $sacramentoId,
                'feligres_id' => $feligresId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
