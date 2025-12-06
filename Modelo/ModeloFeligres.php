<?php
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
            Logger::error("Error al consultar feligrés:", ['error' => $e->getMessage()]);
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
                Logger::info("Feligrés registrado exitosamente", [
                    'tipo_doc' => $datos['tipo-doc'],
                    'numero_doc_prefix' => substr($datos['documento'], 0, 3) . '***',
                    'nombre_completo' => $datos['primer-nombre'] . ' ' . $datos['primer-apellido']
                ]);
                return ['status' => 'success', 'message' => 'Feligrés registrado correctamente'];
            }
            return ['status' => 'error', 'message' => 'No se pudo registrar el feligrés'];
        } catch (PDOException $e) {
            Logger::error("Error al crear feligrés:", ['error' => $e->getMessage()]);
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
            // Verificar que el ID esté presente
            if (empty($datos['id'])) {
                return ['status' => 'error', 'message' => 'ID de feligrés requerido'];
            }

            $sql = "UPDATE feligreses
                    SET tipo_documento_id = ?, numero_documento = ?,
                        primer_nombre = ?, segundo_nombre = ?,
                        primer_apellido = ?, segundo_apellido = ?,
                        telefono = ?, direccion = ?
                    WHERE id = ?";
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
                $datos['id']  // Usar ID en vez de documento
            ]);

            if ($stmt->rowCount() > 0) {
                Logger::info("Feligrés actualizado exitosamente", [
                    'id' => $datos['id'],
                    'tipo_doc' => $datos['tipo-doc'],
                    'numero_doc_prefix' => substr($datos['documento'], 0, 3) . '***'
                ]);
                return ['status' => 'success', 'message' => 'Feligrés actualizado correctamente'];
            }
            return ['status' => 'error', 'message' => 'No se pudo actualizar el feligrés'];
        } catch (PDOException $e) {
            Logger::error("Error al actualizar feligrés:", ['error' => $e->getMessage()]);
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
            Logger::error("Error al obtener feligrés:", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Lista todos los feligreses activos (sin soft delete)
     * @return array Lista de feligreses
     */
    public function mdlListarTodos($start = 0, $length = 10, $search = null)
    {
        try {
            $sql = "SELECT f.*,
                    CONCAT(f.primer_nombre, ' ', IFNULL(f.segundo_nombre, ''), ' ',
                           f.primer_apellido, ' ', IFNULL(f.segundo_apellido, '')) AS nombre_completo
                    FROM feligreses f
                    WHERE f.estado_registro IS NULL";

            $params = [];

            if (!empty($search)) {
                $sql .= " AND (
                    f.primer_nombre LIKE ? OR 
                    f.segundo_nombre LIKE ? OR 
                    f.primer_apellido LIKE ? OR 
                    f.segundo_apellido LIKE ? OR 
                    f.numero_documento LIKE ? OR 
                    f.direccion LIKE ?
                )";
                $searchTerm = "%$search%";
                $params = array_fill(0, 6, $searchTerm);
            }

            $sql .= " ORDER BY f.id DESC";

            if ($length != -1) {
                $sql .= " LIMIT $start, $length";
            }

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);

            // Mapear tipos de documento (sin tabla tipos_documento)
            $tiposDoc = [
                1 => 'Cédula Ciudadanía',
                2 => 'Tarjeta Identidad',
                3 => 'Cédula Extranjera',
                4 => 'Registro Civil',
                5 => 'Permiso Especial',
                6 => 'NIT'
            ];

            $feligreses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Agregar nombre del tipo de documento
            foreach ($feligreses as &$feligres) {
                $feligres['tipo_documento_nombre'] = $tiposDoc[$feligres['tipo_documento_id']] ?? 'N/A';
            }

            return $feligreses;
        } catch (PDOException $e) {
            Logger::error("Error al listar feligreses:", ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function mdlContarFiltrados($search)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM feligreses f WHERE f.estado_registro IS NULL";
            $params = [];

            if (!empty($search)) {
                $sql .= " AND (
                    f.primer_nombre LIKE ? OR 
                    f.segundo_nombre LIKE ? OR 
                    f.primer_apellido LIKE ? OR 
                    f.segundo_apellido LIKE ? OR 
                    f.numero_documento LIKE ? OR 
                    f.direccion LIKE ?
                )";
                $searchTerm = "%$search%";
                $params = array_fill(0, 6, $searchTerm);
            }

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Cuenta el total de feligreses activos
     * @return int Total de feligreses
     */
    public function mdlContarTodos()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM feligreses WHERE estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total'];
        } catch (PDOException $e) {
            Logger::error("Error al contar feligreses:", ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Elimina (soft delete) un feligrés
     * @param int $id ID del feligrés
     * @return bool True si se eliminó, false en caso contrario
     */
    public function mdlEliminar($id)
    {
        try {
            $sql = "UPDATE feligreses SET estado_registro = NOW() WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            
            $eliminado = $stmt->rowCount() > 0;
            
            if ($eliminado) {
                Logger::info("Feligrés eliminado (soft delete)", [
                    'id' => $id,
                    'user_id' => $_SESSION['user-id'] ?? 'unknown'
                ]);
            }
            
            return $eliminado;
        } catch (PDOException $e) {
            Logger::error("Error al eliminar feligrés:", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Obtiene un feligrés por su ID de Usuario asociado
     */
    public function mdlObtenerPorUsuarioId($usuarioId)
    {
        try {
            $sql = "SELECT * FROM feligreses WHERE usuario_id = ? AND estado_registro IS NULL";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$usuarioId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error al obtener feligrés por usuario:", ['error' => $e->getMessage()]);
            return false;
        }
    }
}

