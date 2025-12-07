<?php

// ============================================================================
// ModeloDashboard.php - Modelo para estadísticas del dashboard
// ============================================================================

class ModeloDashboard
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

        // === CERTIFICADOS DEL USUARIO ===
        $userId = $_SESSION['user-id'] ?? 0;
        
        // Solo obtener certificados si hay un usuario autenticado
        if ($userId > 0) {
            try {
                $stmt = $this->conexion->prepare("SELECT COUNT(*) FROM certificados WHERE feligres_id IN (SELECT id FROM feligreses WHERE usuario_id = ?) AND estado = ?");
                
                $stmt->execute([$userId, 'aprobado']);
                $aprobados = (int)$stmt->fetchColumn();
                
                $stmt->execute([$userId, 'pendiente']);
                $pendientes = (int)$stmt->fetchColumn();
                
                $stmt->execute([$userId, 'rechazado']);
                $rechazados = (int)$stmt->fetchColumn();
                
                $estadisticas['certificados'] = [
                    'aprobados' => $aprobados,
                    'pendientes' => $pendientes,
                    'rechazados' => $rechazados
                ];
            } catch (Exception $e) {
                Logger::error("Error al obtener certificados del usuario:", ['error' => $e->getMessage()]);
                $estadisticas['certificados'] = [
                    'aprobados' => 0,
                    'pendientes' => 0,
                    'rechazados' => 0
                ];
            }
        } else {
            // Usuario no autenticado, valores por defecto
            $estadisticas['certificados'] = [
                'aprobados' => 0,
                'pendientes' => 0,
                'rechazados' => 0
            ];
        }

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