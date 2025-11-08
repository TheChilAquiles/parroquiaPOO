<?php

// ============================================================================
// ModeloDashboard.php - Modelo para estadísticas del dashboard
// ============================================================================

class DashboardModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Obtiene todas las estadísticas necesarias para el dashboard
     * Retorna array con estructura completa de datos
     */
    public function obtenerEstadisticas()
    {
        $estadisticas = [];

        // === USUARIOS ===
        $estadisticas['usuarios'] = [
            'total' => $this->obtenerConteo("SELECT COUNT(id) FROM usuarios"),
            'roles' => $this->obtenerConteo("SELECT COUNT(id) FROM usuario_roles"),
            'feligreses' => $this->obtenerConteo("SELECT COUNT(id) FROM feligreses")
        ];

        // === LIBROS ===
        $estadisticas['libros'] = [
            'total' => $this->obtenerConteo("SELECT COUNT(id) FROM libros"),
            'tipos' => $this->obtenerConteo("SELECT COUNT(DISTINCT libro_tipo_id) FROM libros"),
            'registros' => $this->obtenerConteo("SELECT COUNT(numero) FROM libros")
        ];

        // === DOCUMENTOS ===
        $estadisticas['documentos'] = [
            'tipos' => $this->obtenerConteo("SELECT COUNT(id) FROM documento_tipos"),
            'total' => $this->obtenerConteo("SELECT COUNT(tipo) FROM documento_tipos")
        ];

        // === REPORTES ===
        $estadisticas['reportes'] = [
            'total' => $this->obtenerConteo("SELECT COUNT(id) FROM reportes"),
            'categorias' => $this->obtenerConteo("SELECT COUNT(DISTINCT categoria) FROM reportes")
        ];

        // === PAGOS ===
        $pagosTotal = $this->obtenerConteo("SELECT COUNT(id) FROM pagos");
        $pagosCompletos = $this->obtenerConteo("SELECT COUNT(*) FROM pagos WHERE estado='completo'");
        $pagosCancelados = $this->obtenerConteo("SELECT COUNT(*) FROM pagos WHERE estado='cancelado'");

        $estadisticas['pagos'] = [
            'total' => $pagosTotal,
            'completos' => $pagosCompletos,
            'cancelados' => $pagosCancelados,
            'pendientes' => $pagosTotal - $pagosCompletos - $pagosCancelados
        ];

        // === CONTACTOS ===
        $estadisticas['contactos'] = [
            'total' => $this->obtenerConteo("SELECT COUNT(id) FROM contactos")
        ];

        return $estadisticas;
    }

    /**
     * Calcula totales generales basados en estadísticas
     */
    public function calcularTotales($estadisticas)
    {
        return [
            'usuarios_sistema' => $estadisticas['usuarios']['total'] + $estadisticas['usuarios']['roles'],
            'recursos' => $estadisticas['libros']['total'] + $estadisticas['documentos']['total'],
            'actividad' => $estadisticas['reportes']['total'] + $estadisticas['pagos']['total']
        ];
    }

    /**
     * Obtiene un conteo de una consulta SQL de forma segura
     * Ahora usa prepare() en lugar de query() para seguridad
     */
    private function obtenerConteo($query)
    {
        try {
            $stmt = $this->conexion->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            Logger::error("Error al obtener conteo:", ['error' => $e->getMessage()]);
            return 0;
        }
    }
}