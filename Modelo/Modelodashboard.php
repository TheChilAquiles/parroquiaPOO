<?php

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