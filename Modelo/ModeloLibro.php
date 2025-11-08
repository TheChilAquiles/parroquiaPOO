<?php

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
            Logger::error("Error al consultar cantidad de libros:", ['error' => $e->getMessage()]);
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
            Logger::error("Error al añadir libro:", ['error' => $e->getMessage()]);
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
            Logger::error("Error al obtener libros:", ['error' => $e->getMessage()]);
            return [];
        }
    }
}