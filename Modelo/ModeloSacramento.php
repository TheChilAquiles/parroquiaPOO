<?php

// ============================================================================
// ModeloSacramento.php - SIMPLIFICADO
// ============================================================================

class ModeloSacramento
{
    private $conexion;
    private $libroID;
    private $sacramentoTipo;
    private $numero;

    public function __construct($tipo = null, $numero = null)
    {
        $this->conexion = Conexion::conectar();
        if ($tipo && $numero) {
            $this->sacramentoTipo = $tipo;
            $this->numero = $numero;
            $this->setLibroID();
        }
    }


    



    /**
     * Obtiene los participantes de un sacramento
     */
    public function getParticipantes($sacramentoId)
{
    // ✅ AGREGAR ESTAS LÍNEAS
    if (!is_numeric($sacramentoId) || $sacramentoId <= 0) {
        error_log("ID de sacramento inválido: " . $sacramentoId);
        return [];
    }
    
    $sacramentoId = (int)$sacramentoId; // Cast explícito
    
    try {
        $sql = "SELECT 
                    pr.rol,
                    CONCAT(f.primer_nombre, ' ', COALESCE(f.segundo_nombre, ''), ' ', 
                           f.primer_apellido, ' ', COALESCE(f.segundo_apellido, '')) AS nombre
                FROM participantes p
                JOIN feligreses f ON f.id = p.feligres_id
                JOIN participantes_rol pr ON pr.id = p.rol_participante_id
                WHERE p.sacramento_id = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$sacramentoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener participantes: " . $e->getMessage());
        return [];
    }
}

    /**
     * Establece el ID del libro
     */
    private function setLibroID()
    {
        try {
            $sql = "SELECT id FROM libros 
                    WHERE libro_tipo_id = ? AND numero = ? 
                    LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$this->sacramentoTipo, $this->numero]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->libroID = $resultado['id'] ?? null;
        } catch (PDOException $e) {
            error_log("Error al obtener ID del libro: " . $e->getMessage());
            $this->libroID = null;
        }
    }

    /**
     * Crea un nuevo sacramento
     */
    public function CrearSacramento($data)
    {
        if (!$this->libroID) {
            return false;
        }

        try {
            $this->conexion->beginTransaction();

            // Crear sacramento
            $sql_sacramento = "INSERT INTO sacramentos 
                              (libro_id, tipo_sacramento_id, fecha_generacion)
                              VALUES (?, ?, NOW())";
            $stmt = $this->conexion->prepare($sql_sacramento);
            $stmt->execute([$this->libroID, $this->sacramentoTipo]);
            
            $sacramentoID = $this->conexion->lastInsertId();

            // Procesar integrantes (participantes del sacramento)
            if (isset($data['integrantes']) && is_array($data['integrantes'])) {
                foreach ($data['integrantes'] as $integrante) {
                    // 1. Obtener o crear feligrés
                    $feligresId = $this->obtenerOCrearFeligres($integrante);

                    if (!$feligresId) {
                        throw new Exception("No se pudo crear/obtener feligrés para participante");
                    }

                    // 2. Insertar participante
                    $sql_participante = "INSERT INTO participantes
                                       (feligres_id, sacramento_id, rol_participante_id)
                                       VALUES (?, ?, ?)";
                    $stmt_participante = $this->conexion->prepare($sql_participante);
                    $stmt_participante->execute([
                        $feligresId,
                        $sacramentoID,
                        $integrante['rolParticipante']
                    ]);
                }
            }

            $this->conexion->commit();
            return $sacramentoID;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            error_log("Error al crear sacramento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene un feligrés existente o crea uno nuevo
     * @param array $datos Datos del feligrés (tipoDoc, numeroDoc, nombres, apellidos)
     * @return int|false ID del feligrés o false en caso de error
     */
    private function obtenerOCrearFeligres($datos)
    {
        try {
            // Validar datos mínimos requeridos
            if (empty($datos['tipoDoc']) || empty($datos['numeroDoc'])) {
                error_log("Datos insuficientes para buscar/crear feligrés");
                return false;
            }

            // 1. Buscar feligrés existente por documento
            $sql_buscar = "SELECT id FROM feligreses
                          WHERE tipo_documento_id = ? AND numero_documento = ?
                          AND estado_registro IS NULL
                          LIMIT 1";
            $stmt = $this->conexion->prepare($sql_buscar);
            $stmt->execute([$datos['tipoDoc'], $datos['numeroDoc']]);
            $feligres = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($feligres) {
                // Feligrés ya existe
                return $feligres['id'];
            }

            // 2. Crear nuevo feligrés
            $sql_crear = "INSERT INTO feligreses
                         (tipo_documento_id, numero_documento, primer_nombre,
                          segundo_nombre, primer_apellido, segundo_apellido)
                         VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_crear = $this->conexion->prepare($sql_crear);
            $stmt_crear->execute([
                $datos['tipoDoc'],
                $datos['numeroDoc'],
                $datos['primerNombre'] ?? '',
                $datos['segundoNombre'] ?? '',
                $datos['primerApellido'] ?? '',
                $datos['segundoApellido'] ?? ''
            ]);

            return $this->conexion->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al obtener/crear feligrés: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene un sacramento por su ID
     * @param int $id ID del sacramento
     * @return array|false Datos del sacramento o false si no existe
     */
    public function mdlObtenerPorId($id)
    {
        try {
            $sql = "SELECT s.*, st.tipo, l.numero AS libro_numero, lt.tipo AS libro_tipo
                    FROM sacramentos s
                    JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                    JOIN libros l ON s.libro_id = l.id
                    JOIN libro_tipos lt ON l.libro_tipo_id = lt.id
                    WHERE s.id = ? AND s.estado_registro IS NULL
                    LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener sacramento por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los sacramentos de un feligrés
     * @param int $feligresId ID del feligrés
     * @return array Lista de sacramentos
     */
    public function mdlObtenerPorFeligres($feligresId)
    {
        try {
            $sql = "SELECT DISTINCT s.id, st.tipo, s.fecha_generacion, s.lugar
                    FROM sacramentos s
                    JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                    JOIN participantes p ON p.sacramento_id = s.id
                    WHERE p.feligres_id = ?
                    AND s.estado_registro IS NULL
                    ORDER BY s.fecha_generacion DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$feligresId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener sacramentos por feligrés: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene todos los sacramentos de un libro específico
     * @param int $tipo ID del tipo de libro (1=Bautizos, 2=Confirmaciones, etc.)
     * @param int $numero Número del libro
     * @return array Lista de sacramentos con participantes
     */
    public function mdlObtenerPorLibro($tipo, $numero)
    {
        try {
            // Primero obtenemos el ID del libro
            $sqlLibro = "SELECT id FROM libros
                        WHERE libro_tipo_id = ? AND numero = ?
                        AND estado_registro IS NULL
                        LIMIT 1";
            $stmtLibro = $this->conexion->prepare($sqlLibro);
            $stmtLibro->execute([$tipo, $numero]);
            $libro = $stmtLibro->fetch(PDO::FETCH_ASSOC);

            if (!$libro) {
                return [];
            }

            $libroId = $libro['id'];

            // Ahora obtenemos los sacramentos de ese libro con sus participantes
            $sql = "SELECT
                        s.id,
                        s.fecha_generacion,
                        s.lugar,
                        st.tipo AS tipo_sacramento,
                        GROUP_CONCAT(
                            CONCAT(
                                f.primer_nombre, ' ',
                                COALESCE(f.segundo_nombre, ''), ' ',
                                f.primer_apellido, ' ',
                                COALESCE(f.segundo_apellido, ''),
                                ' (', pr.rol, ')'
                            )
                            SEPARATOR ', '
                        ) AS participantes
                    FROM sacramentos s
                    JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                    LEFT JOIN participantes p ON p.sacramento_id = s.id
                    LEFT JOIN feligreses f ON f.id = p.feligres_id
                    LEFT JOIN participantes_rol pr ON pr.id = p.rol_participante_id
                    WHERE s.libro_id = ?
                    AND s.estado_registro IS NULL
                    GROUP BY s.id
                    ORDER BY s.fecha_generacion DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$libroId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener sacramentos por libro: " . $e->getMessage());
            return [];
        }
    }
}