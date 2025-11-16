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
     * Obtiene los participantes de un sacramento con datos completos del documento
     */
    public function getParticipantes($sacramentoId)
{
    // ✅ AGREGAR ESTAS LÍNEAS
    if (!is_numeric($sacramentoId) || $sacramentoId <= 0) {
        Logger::error("ID de sacramento inválido:", ['info' => $sacramentoId]);
        return [];
    }

    $sacramentoId = (int)$sacramentoId; // Cast explícito

    try {
        $sql = "SELECT
                    pr.rol,
                    pr.id AS rol_id,
                    f.tipo_documento_id,
                    f.numero_documento,
                    f.primer_nombre,
                    f.segundo_nombre,
                    f.primer_apellido,
                    f.segundo_apellido,
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
        Logger::error("Error al obtener participantes:", ['error' => $e->getMessage()]);
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
            Logger::error("Error al obtener ID del libro:", ['error' => $e->getMessage()]);
            $this->libroID = null;
        }
    }

    /**
     * Obtiene la próxima acta disponible para un libro
     * @param int $libroId ID del libro
     * @return array ['acta' => int, 'folio' => int] o ['error' => string] si excede límite
     */
    public function mdlObtenerProximaActaDisponible($libroId)
    {
        try {
            // Obtener la última acta registrada en el libro
            $sql = "SELECT MAX(acta) as ultima_acta FROM sacramentos
                    WHERE libro_id = ? AND estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$libroId]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            $ultimaActa = $resultado['ultima_acta'] ?? 0;
            $proximaActa = $ultimaActa + 1;

            // Validar límite (1 libro = 500 folios = 1000 actas)
            if ($proximaActa > 1000) {
                return [
                    'error' => 'Este libro ha alcanzado su capacidad máxima de 1000 actas. Debe crear un nuevo libro.'
                ];
            }

            $proximoFolio = $this->calcularFolio($proximaActa);

            return [
                'acta' => $proximaActa,
                'folio' => $proximoFolio
            ];
        } catch (PDOException $e) {
            Logger::error("Error al obtener próxima acta:", ['error' => $e->getMessage()]);
            return ['error' => 'Error al calcular próxima acta'];
        }
    }

    /**
     * Calcula el número de folio a partir del número de acta
     * Regla: Cada folio contiene 2 actas
     * @param int $acta Número de acta
     * @return int Número de folio
     */
    private function calcularFolio($acta)
    {
        // Cada folio tiene 2 actas
        // Ejemplos: acta 1 y 2 -> folio 1, acta 3 y 4 -> folio 2, etc.
        return (int)ceil($acta / 2);
    }

    /**
     * Crea un nuevo sacramento con auto-cálculo de acta/folio
     */
    public function CrearSacramento($data)
    {
        if (!$this->libroID) {
            return false;
        }

        try {
            $this->conexion->beginTransaction();

            // Obtener próxima acta y folio disponibles
            $proximaInfo = $this->mdlObtenerProximaActaDisponible($this->libroID);

            // Validar si hay error (libro lleno)
            if (isset($proximaInfo['error'])) {
                Logger::error("Libro lleno:", ['info' => $proximaInfo['error']]);
                $this->conexion->rollBack();
                return false;
            }

            $acta = $proximaInfo['acta'];
            $folio = $proximaInfo['folio'];

            // Crear sacramento con acta y folio auto-calculados
            $sql_sacramento = "INSERT INTO sacramentos
                              (libro_id, tipo_sacramento_id, acta, folio, fecha_generacion)
                              VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->conexion->prepare($sql_sacramento);
            $stmt->execute([$this->libroID, $this->sacramentoTipo, $acta, $folio]);
            
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
            Logger::error("Error al crear sacramento:", ['error' => $e->getMessage()]);
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
                Logger::error("Datos insuficientes para buscar/crear feligrés");
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
            Logger::error("Error al obtener/crear feligrés:", ['error' => $e->getMessage()]);
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
                    JOIN libro_tipo lt ON l.libro_tipo_id = lt.id
                    WHERE s.id = ? AND s.estado_registro IS NULL
                    LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener sacramento por ID:", ['error' => $e->getMessage()]);
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
            Logger::error("Error al obtener sacramentos por feligrés:", ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Obtiene sacramentos de un feligrés filtrados por tipo de sacramento
     * @param int $feligresId ID del feligrés
     * @param int $tipoSacramentoId ID del tipo de sacramento (1=Bautizo, 2=Confirmación, etc.)
     * @return array|false Sacramento encontrado o false si no existe
     */
    public function mdlObtenerPorFeligresYTipo($feligresId, $tipoSacramentoId)
    {
        try {
            $sql = "SELECT DISTINCT s.id, st.tipo, s.fecha_generacion, s.acta, s.folio,
                           l.numero AS libro_numero, lt.tipo AS libro_tipo
                    FROM sacramentos s
                    JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                    JOIN libros l ON s.libro_id = l.id
                    JOIN libro_tipo lt ON l.libro_tipo_id = lt.id
                    JOIN participantes p ON p.sacramento_id = s.id
                    WHERE p.feligres_id = ?
                    AND s.tipo_sacramento_id = ?
                    AND s.estado_registro IS NULL
                    ORDER BY s.fecha_generacion DESC
                    LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$feligresId, $tipoSacramentoId]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return $resultado ? $resultado : false;
        } catch (PDOException $e) {
            Logger::error("Error al obtener sacramento por feligrés y tipo:", ['error' => $e->getMessage()]);
            return false;
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

            // Ahora obtenemos los sacramentos de ese libro
            $sql = "SELECT
                        s.id,
                        s.fecha_generacion,
                        s.tipo_sacramento_id,
                        st.tipo AS tipo_sacramento
                    FROM sacramentos s
                    JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                    WHERE s.libro_id = ?
                    AND s.estado_registro IS NULL
                    ORDER BY s.fecha_generacion DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$libroId]);
            $sacramentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Para cada sacramento, obtener el participante principal y todos los participantes
            foreach ($sacramentos as &$sacramento) {
                $sacramento['lugar'] = 'N/A';

                // Obtener participante(s) principal(es) según el tipo de sacramento
                $participantePrincipal = $this->obtenerParticipantePrincipal($sacramento['id'], $sacramento['tipo_sacramento_id']);
                $sacramento['participante_principal'] = $participantePrincipal;

                // Mantener todos los participantes para la lista completa
                $todosParticipantes = $this->getParticipantes($sacramento['id']);
                $listaParticipantes = array_map(function($p) {
                    return $p['nombre'] . ' (' . $p['rol'] . ')';
                }, $todosParticipantes);

                $sacramento['participantes'] = implode(', ', $listaParticipantes);
            }

            return $sacramentos;

        } catch (PDOException $e) {
            Logger::error("Error al obtener sacramentos por libro:", ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Obtiene el participante principal de un sacramento según su tipo
     * Para bautizos: Bautizado
     * Para confirmaciones: Confirmando
     * Para defunciones: Difunto
     * Para matrimonios: Esposo y Esposa
     */
    private function obtenerParticipantePrincipal($sacramentoId, $tipoSacramentoId)
    {
        try {
            // Definir el rol principal según el tipo de sacramento
            $rolesPrincipales = [
                1 => ['Bautizado'],           // Bautizos
                2 => ['Confirmando'],         // Confirmaciones
                3 => ['Difunto'],             // Defunciones
                4 => ['Esposo', 'Esposa']     // Matrimonios - ambos son principales
            ];

            $roles = $rolesPrincipales[$tipoSacramentoId] ?? [];

            if (empty($roles)) {
                return null;
            }

            $placeholders = implode(',', array_fill(0, count($roles), '?'));

            $sql = "SELECT
                        CONCAT(
                            f.primer_nombre, ' ',
                            COALESCE(f.segundo_nombre, ''), ' ',
                            f.primer_apellido, ' ',
                            COALESCE(f.segundo_apellido, '')
                        ) AS nombre,
                        pr.rol,
                        td.tipo AS tipo_documento,
                        f.numero_documento
                    FROM participantes p
                    JOIN feligreses f ON f.id = p.feligres_id
                    JOIN participantes_rol pr ON pr.id = p.rol_participante_id
                    LEFT JOIN tipo_documento td ON td.id = f.tipo_documento_id
                    WHERE p.sacramento_id = ?
                    AND pr.rol IN ($placeholders)
                    AND p.estado_registro IS NULL
                    ORDER BY pr.id";

            $params = array_merge([$sacramentoId], $roles);
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);

            $participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Para matrimonios, devolver ambos como un array
            if ($tipoSacramentoId == 4 && count($participantes) == 2) {
                return $participantes;
            }

            // Para otros sacramentos, devolver el primero
            return $participantes[0] ?? null;

        } catch (PDOException $e) {
            Logger::error("Error al obtener participante principal:", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Obtiene todos los sacramentos donde un feligrés es participante
     * @param int $feligresId ID del feligrés
     * @return array Lista de sacramentos con información completa
     */
    public function mdlObtenerSacramentosPorFeligres($feligresId)
    {
        try {
            $sql = "SELECT
                        s.id,
                        s.fecha_generacion,
                        s.tipo_sacramento_id,
                        st.tipo AS tipo_sacramento,
                        pr.rol AS mi_rol,
                        l.numero AS libro_numero,
                        lt.tipo AS libro_tipo,
                        GROUP_CONCAT(
                            CONCAT(
                                f2.primer_nombre, ' ',
                                COALESCE(f2.segundo_nombre, ''), ' ',
                                f2.primer_apellido, ' ',
                                COALESCE(f2.segundo_apellido, ''),
                                ' (', pr2.rol, ')'
                            )
                            SEPARATOR ', '
                        ) AS participantes
                    FROM participantes p
                    JOIN sacramentos s ON s.id = p.sacramento_id
                    JOIN sacramento_tipo st ON s.tipo_sacramento_id = st.id
                    JOIN participantes_rol pr ON pr.id = p.rol_participante_id
                    JOIN libros l ON l.id = s.libro_id
                    JOIN libro_tipo lt ON lt.id = l.libro_tipo_id
                    LEFT JOIN participantes p2 ON p2.sacramento_id = s.id
                    LEFT JOIN feligreses f2 ON f2.id = p2.feligres_id
                    LEFT JOIN participantes_rol pr2 ON pr2.id = p2.rol_participante_id
                    WHERE p.feligres_id = ?
                    AND s.estado_registro IS NULL
                    GROUP BY s.id, s.fecha_generacion, s.tipo_sacramento_id, st.tipo, pr.rol, l.numero, lt.tipo
                    ORDER BY s.fecha_generacion DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$feligresId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            Logger::error("Error al obtener sacramentos por feligrés", [
                'feligres_id' => $feligresId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Actualiza un sacramento existente y sus participantes
     * @param int $sacramentoId ID del sacramento a actualizar
     * @param array $datos Datos del sacramento y participantes
     * @return bool|int ID del sacramento actualizado o false en caso de error
     */
    public function ActualizarSacramento($sacramentoId, $datos)
    {
        try {
            $this->conexion->beginTransaction();

            // 1. Actualizar datos del sacramento
            $fechaEvento = $datos['fecha-evento'] ?? date('Y-m-d');

            $sqlUpdate = "UPDATE sacramentos
                         SET fecha_generacion = ?
                         WHERE id = ? AND estado_registro IS NULL";
            $stmtUpdate = $this->conexion->prepare($sqlUpdate);
            $stmtUpdate->execute([$fechaEvento, $sacramentoId]);

            // 2. Eliminar participantes actuales (soft delete)
            $sqlDeletePart = "UPDATE participantes
                             SET estado_registro = NOW()
                             WHERE sacramento_id = ? AND estado_registro IS NULL";
            $stmtDelete = $this->conexion->prepare($sqlDeletePart);
            $stmtDelete->execute([$sacramentoId]);

            // 3. Insertar nuevos participantes
            $integrantes = $datos['integrantes'] ?? [];

            foreach ($integrantes as $integrante) {
                // Buscar o crear feligrés
                $feligresId = $this->buscarOCrearFeligres($integrante);

                if (!$feligresId) {
                    throw new Exception('Error al procesar participante');
                }

                // Insertar participante
                $sqlPart = "INSERT INTO participantes (sacramento_id, feligres_id, rol_participante_id)
                           VALUES (?, ?, ?)";
                $stmtPart = $this->conexion->prepare($sqlPart);
                $stmtPart->execute([
                    $sacramentoId,
                    $feligresId,
                    $integrante['rolParticipante']
                ]);
            }

            $this->conexion->commit();
            Logger::info("Sacramento actualizado correctamente", [
                'sacramento_id' => $sacramentoId,
                'num_participantes' => count($integrantes)
            ]);

            return $sacramentoId;

        } catch (PDOException $e) {
            $this->conexion->rollBack();
            Logger::error("Error al actualizar sacramento", [
                'sacramento_id' => $sacramentoId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Busca o crea un feligrés basado en su documento
     * @param array $datos Datos del feligrés
     * @return int|null ID del feligrés o null si hay error
     */
    private function buscarOCrearFeligres($datos)
    {
        try {
            $tipoDoc = $datos['tipoDoc'];
            $numeroDoc = $datos['numeroDoc'];

            // Buscar feligrés existente
            $sqlBuscar = "SELECT id FROM feligreses
                         WHERE tipo_documento_id = ? AND numero_documento = ?
                         AND estado_registro IS NULL
                         LIMIT 1";
            $stmtBuscar = $this->conexion->prepare($sqlBuscar);
            $stmtBuscar->execute([$tipoDoc, $numeroDoc]);
            $feligres = $stmtBuscar->fetch(PDO::FETCH_ASSOC);

            if ($feligres) {
                return $feligres['id'];
            }

            // Crear nuevo feligrés
            $sqlCrear = "INSERT INTO feligreses
                        (tipo_documento_id, numero_documento, primer_nombre, segundo_nombre,
                         primer_apellido, segundo_apellido)
                        VALUES (?, ?, ?, ?, ?, ?)";
            $stmtCrear = $this->conexion->prepare($sqlCrear);
            $stmtCrear->execute([
                $tipoDoc,
                $numeroDoc,
                $datos['primerNombre'] ?? '',
                $datos['segundoNombre'] ?? '',
                $datos['primerApellido'] ?? '',
                $datos['segundoApellido'] ?? ''
            ]);

            return $this->conexion->lastInsertId();

        } catch (PDOException $e) {
            Logger::error("Error al buscar/crear feligrés", [
                'datos' => $datos,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}