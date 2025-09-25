<?php
require_once "Conexion.php";

class DashboardModel {
    private $conexion;

    public function __construct() {
        // ✅ Aquí usamos tu método real
        $this->conexion = Conexion::conectar();
    }

    private function obtenerConteo($query) {
        try {
            return (int) $this->conexion->query($query)->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    public function obtenerEstadisticas() {
        $estadisticas = [];

        // Usuarios
        $estadisticas['usuarios'] = [
            'total' => $this->obtenerConteo("SELECT COUNT(id) FROM usuarios"),
            'roles' => $this->obtenerConteo("SELECT COUNT(id) FROM usuario_roles"),
            'feligreses' => $this->obtenerConteo("SELECT COUNT(id) FROM feligreses")
        ];

        // Libros
        $estadisticas['libros'] = [
            'total' => $this->obtenerConteo("SELECT COUNT(id) FROM libros"),
            'tipos' => $this->obtenerConteo("SELECT COUNT(DISTINCT libro_tipo_id) FROM libros"),
            'registros' => $this->obtenerConteo("SELECT COUNT(numero) FROM libros")
        ];

        // Documentos
        $estadisticas['documentos'] = [
            'tipos' => $this->obtenerConteo("SELECT COUNT(id) FROM documento_tipos"),
            'total' => $this->obtenerConteo("SELECT COUNT(tipo) FROM documento_tipos")
        ];

        // Reportes
        $estadisticas['reportes'] = [
            'total' => $this->obtenerConteo("SELECT COUNT(id) FROM reportes"),
            'categorias' => $this->obtenerConteo("SELECT COUNT(DISTINCT categoria) FROM reportes")
        ];

        // Pagos
        $estadisticas['pagos'] = [
            'total' => $this->obtenerConteo("SELECT COUNT(id) FROM pagos"),
            'completos' => $this->obtenerConteo("SELECT COUNT(*) FROM pagos WHERE estado='completo'"),
            'cancelados' => $this->obtenerConteo("SELECT COUNT(*) FROM pagos WHERE estado='cancelado'")
        ];
        $estadisticas['pagos']['pendientes'] = $estadisticas['pagos']['total']
                                             - $estadisticas['pagos']['completos']
                                             - $estadisticas['pagos']['cancelados'];

        // Contactos
        $estadisticas['contactos'] = [
            'total' => $this->obtenerConteo("SELECT COUNT(id) FROM contactos")
        ];

        // Totales
        $estadisticas['totales'] = [
            'usuarios_sistema' => $estadisticas['usuarios']['total'] + $estadisticas['usuarios']['roles'],
            'recursos' => $estadisticas['libros']['total'] + $estadisticas['documentos']['total'],
            'actividad' => $estadisticas['reportes']['total'] + $estadisticas['pagos']['total']
        ];

        return $estadisticas;
    }
}
