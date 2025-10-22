<?php
// ============================================================================
// ModeloUsuario.php - REFACTORIZADO
// ============================================================================

class ModeloUsuario
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Registra un nuevo usuario en el sistema
     */
    public function mdlRegistrarUsuario($usuario)
    {
        if (!isset($usuario['email']) || !isset($usuario['password'])) {
            return ['status' => 'error', 'message' => 'Email y contraseña son requeridos'];
        }

        // Verificar si el email ya existe
        if ($this->existEmail($usuario['email'])) {
            return ['status' => 'error', 'message' => 'El email ya está registrado'];
        }

        try {
            $sql = "INSERT INTO usuarios (usuario_rol_id, email, contraseña) 
                    VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $hashedPassword = md5($usuario['password']);
            
            $stmt->execute([1, $usuario['email'], $hashedPassword]);

            return ['status' => 'success', 'message' => 'Usuario registrado correctamente'];
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error al registrar usuario'];
        }
    }

    /**
     * Consulta un usuario por email
     */
    public function consultarUsuario($email)
    {
        try {
            $sql = "SELECT u.*, ur.rol 
                    FROM usuarios u
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id 
                    WHERE u.email = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$email]);
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $usuario ?: null;
        } catch (PDOException $e) {
            error_log("Error al consultar usuario: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Verifica si un email ya existe en la base de datos
     */
    private function existEmail($email)
    {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar email: " . $e->getMessage());
            return true; // Por seguridad, asumimos que existe
        }
    }

    /**
     * Obtiene un usuario por ID
     */
    public function obtenerUsuarioPorId($id)
    {
        try {
            $sql = "SELECT u.*, ur.rol 
                    FROM usuarios u
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id 
                    WHERE u.id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene todos los usuarios
     */
    public function obtenerUsuarios()
    {
        try {
            $sql = "SELECT u.*, ur.rol 
                    FROM usuarios u
                    LEFT JOIN usuario_roles ur ON u.usuario_rol_id = ur.id 
                    ORDER BY u.email ASC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }
}

// ============================================================================
// ModeloFeligres.php - REFACTORIZADO
// ============================================================================

class ModeloFeligres
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Consulta un feligrés por tipo y número de documento
     */
    public function mdlConsultarFeligres($tipoDoc, $documento)
    {
        try {
            $sql = "SELECT * FROM feligreses 
                    WHERE tipo_documento_id = ? AND numero_documento = ? 
                    LIMIT 1";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$tipoDoc, $documento]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ?: false;
        } catch (PDOException $e) {
            error_log("Error al consultar feligrés: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea un nuevo feligrés
     */
    public function mdlCrearFeligres($datos)
    {
        try {
            $sql = "INSERT INTO feligreses 
                    (usuario_id, tipo_documento_id, numero_documento, primer_nombre, 
                     segundo_nombre, primer_apellido, segundo_apellido, telefono, direccion) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $datos['idUser'] ?? null,
                $datos['tipo-doc'],
                $datos['documento'],
                $datos['primer-nombre'],
                $datos['segundo-nombre'] ?? '',
                $datos['primer-apellido'],
                $datos['segundo-apellido'] ?? '',
                $datos['telefono'] ?? '',
                $datos['direccion']
            ]);

            if ($stmt->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Feligrés registrado correctamente'];
            }
            return ['status' => 'error', 'message' => 'No se pudo registrar el feligrés'];
        } catch (PDOException $e) {
            error_log("Error al crear feligrés: " . $e->getMessage());
            if ($e->getCode() == 23000) {
                return ['status' => 'error', 'message' => 'El feligrés ya existe'];
            }
            return ['status' => 'error', 'message' => 'Error al registrar feligrés'];
        }
    }

    /**
     * Actualiza los datos de un feligrés
     */
    public function mdlUpdateFeligres($datos)
    {
        try {
            $sql = "UPDATE feligreses 
                    SET tipo_documento_id = ?, numero_documento = ?, 
                        primer_nombre = ?, segundo_nombre = ?, 
                        primer_apellido = ?, segundo_apellido = ?, 
                        telefono = ?, direccion = ? 
                    WHERE numero_documento = ?";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $datos['tipo-doc'],
                $datos['documento'],
                $datos['primer-nombre'],
                $datos['segundo-nombre'] ?? '',
                $datos['primer-apellido'],
                $datos['segundo-apellido'] ?? '',
                $datos['telefono'] ?? '',
                $datos['direccion'],
                $datos['documento']
            ]);

            if ($stmt->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Feligrés actualizado correctamente'];
            }
            return ['status' => 'error', 'message' => 'No se pudo actualizar el feligrés'];
        } catch (PDOException $e) {
            error_log("Error al actualizar feligrés: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error al actualizar feligrés'];
        }
    }

    /**
     * Obtiene un feligrés por ID
     */
    public function mdlObtenerPorId($id)
    {
        try {
            $sql = "SELECT * FROM feligreses WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener feligrés: " . $e->getMessage());
            return null;
        }
    }
}

// ============================================================================
// ModeloLibro.php - REFACTORIZADO
// ============================================================================

class ModeloLibro
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Consulta la cantidad de libros por tipo
     */
    public function mdlConsultarCantidadLibros($tipo)
    {
        try {
            $sql = "SELECT COUNT(*) FROM libros WHERE libro_tipo_id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$tipo]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al consultar cantidad de libros: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Añade un nuevo libro
     */
    public function mdlAñadirLibro($tipo, $cantidad)
    {
        try {
            $sql = "INSERT INTO libros (libro_tipo_id, numero) VALUES (?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$tipo, $cantidad + 1]);

            if ($stmt->rowCount() > 0) {
                return $this->conexion->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al añadir libro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los libros de un tipo
     */
    public function mdlObtenerLibrosPorTipo($tipo)
    {
        try {
            $sql = "SELECT * FROM libros WHERE libro_tipo_id = ? ORDER BY numero DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$tipo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener libros: " . $e->getMessage());
            return [];
        }
    }
}

// ============================================================================
// ModeloCertificados.php - REFACTORIZADO
// ============================================================================

class Certificados
{
    private $db;

    public function __construct(PDO $pdo = null)
    {
        $this->db = $pdo ?? Conexion::conectar();
    }

    /**
     * Crea un nuevo certificado
     */
    public function crear($data)
    {
        try {
            $sql = "INSERT INTO certificados 
                    (usuario_id, feligres_id, sacramento, fecha_realizacion, lugar, observaciones, creado_en)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                $data['usuario_id'],
                $data['feligres_id'],
                $data['sacramento'],
                $data['fecha_realizacion'],
                $data['lugar'],
                $data['observaciones']
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear certificado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los certificados
     */
    public function obtenerTodos()
    {
        try {
            $sql = "SELECT * FROM certificados ORDER BY id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener certificados: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un certificado por ID
     */
    public function obtenerPorId($id)
    {
        try {
            $sql = "SELECT * FROM certificados WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener certificado: " . $e->getMessage());
            return null;
        }
    }
}

// ============================================================================
// ModeloDashboard.php - REFACTORIZADO
// ============================================================================

class DashboardModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Obtiene estadísticas generales del sistema
     */
    public function obtenerEstadisticas()
    {
        $estadisticas = [
            'usuarios' => $this->obtenerConteo("SELECT COUNT(*) FROM usuarios"),
            'feligreses' => $this->obtenerConteo("SELECT COUNT(*) FROM feligreses"),
            'libros' => $this->obtenerConteo("SELECT COUNT(*) FROM libros"),
            'grupos' => $this->obtenerConteo("SELECT COUNT(*) FROM grupos WHERE estado_registro IS NULL"),
            'noticias' => $this->obtenerConteo("SELECT COUNT(*) FROM noticias WHERE estado_registro IS NULL"),
            'certificados' => $this->obtenerConteo("SELECT COUNT(*) FROM certificados"),
            'pagos' => $this->obtenerConteo("SELECT COUNT(*) FROM pagos"),
            'pagos_completados' => $this->obtenerConteo("SELECT COUNT(*) FROM pagos WHERE LOWER(estado) = 'pagado'"),
        ];

        return $estadisticas;
    }

    /**
     * Obtiene un conteo de una consulta
     */
    private function obtenerConteo($query)
    {
        try {
            return (int)$this->conexion->query($query)->fetchColumn();
        } catch (Exception $e) {
            error_log("Error al obtener conteo: " . $e->getMessage());
            return 0;
        }
    }
}

// ============================================================================
// ReporteModelo.php - REFACTORIZADO
// ============================================================================

class ReporteModelo
{
    private $db;

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
                        p.id AS id_pago,
                        p.valor,
                        p.estado AS estado_pago,
                        p.fecha_pago
                    FROM reportes r
                    INNER JOIN pagos p ON r.id_pagos = p.id
                    ORDER BY r.id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener reportes: " . $e->getMessage());
            return [];
        }
    }
}

// ============================================================================
// ModeloParticipante.php - REFACTORIZADO
// ============================================================================

class ModeloParticipante
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Crea un nuevo participante
     */
    public function crearParticipante($data)
    {
        try {
            $sql = "INSERT INTO participantes 
                    (feligres_id, sacramento_id, rol_participante_id)
                    VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            
            $stmt->execute([
                $data['feligres-id'] ?? $data['feligres_id'],
                $data['sacramento-id'] ?? $data['sacramento_id'],
                $data['participante-id'] ?? $data['participante_id']
            ]);

            return ['success' => true, 'id' => $this->conexion->lastInsertId()];
        } catch (PDOException $e) {
            error_log("Error al crear participante: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Obtiene todos los participantes
     */
    public function obtenerParticipantes()
    {
        try {
            $sql = "SELECT * FROM participantes ORDER BY id DESC";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener participantes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene un participante por ID
     */
    public function obtenerPorId($id)
    {
        try {
            $sql = "SELECT * FROM participantes WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener participante: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza un participante
     */
    public function actualizar($id, $data)
    {
        try {
            $sql = "UPDATE participantes 
                    SET feligres_id = ?, sacramento_id = ?, rol_participante_id = ? 
                    WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            
            return $stmt->execute([
                $data['feligres_id'],
                $data['sacramento_id'],
                $data['rol_participante_id'],
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar participante: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un participante
     */
    public function eliminar($id)
    {
        try {
            $sql = "DELETE FROM participantes WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar participante: " . $e->getMessage());
            return false;
        }
    }
}

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

            // Procesar integrantes
            if (isset($data['integrantes']) && is_array($data['integrantes'])) {
                foreach ($data['integrantes'] as $integrante) {
                    // Aquí iría lógica para crear/obtener feligrés y participante
                    // Por ahora solo retornamos el sacramentoID
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
}