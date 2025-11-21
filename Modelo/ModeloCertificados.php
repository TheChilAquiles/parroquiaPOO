<?php

// ============================================================================
// ModeloCertificados.php - REFACTORIZADO
// ============================================================================

class ModeloCertificados
{
    private $db;

    public function __construct(?PDO $pdo = null)
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
            Logger::error("Error al crear certificado:", ['error' => $e->getMessage()]);
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
            Logger::error("Error al obtener certificados:", ['error' => $e->getMessage()]);
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
            Logger::error("Error al obtener certificado:", ['error' => $e->getMessage()]);
            return null;
        }
    }
}