<?php
class ModeloReporte {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db;
    }

    // Crear reporte
    public function crearReporte($titulo, $descripcion, $usuario_id) {
        $sql = "INSERT INTO reportes (titulo, descripcion, usuario_id, fecha_creacion) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssi", $titulo, $descripcion, $usuario_id);
        return $stmt->execute();
    }

    // Obtener todos los reportes
    public function obtenerReportes() {
        $sql = "SELECT r.id, r.titulo, r.descripcion, r.fecha_creacion, u.nombre AS usuario 
                FROM reportes r
                INNER JOIN usuarios u ON r.usuario_id = u.id
                ORDER BY r.fecha_creacion DESC";
        $result = $this->conexion->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener reporte por ID
    public function obtenerReportePorId($id) {
        $sql = "SELECT * FROM reportes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Actualizar reporte
    public function actualizarReporte($id, $titulo, $descripcion) {
        $sql = "UPDATE reportes SET titulo = ?, descripcion = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssi", $titulo, $descripcion, $id);
        return $stmt->execute();
    }

    // Eliminar reporte
    public function eliminarReporte($id) {
        $sql = "DELETE FROM reportes WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
