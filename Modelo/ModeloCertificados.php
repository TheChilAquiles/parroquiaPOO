<?php
// ModeloCertificados.php
// Modelo de acceso a datos para certificados usando PDO y POO.
// Comentarios explicativos incluidos.

// Requiere un archivo de conexión que devuelva una instancia PDO.
// Asegúrese de que Conexion.php defina una clase Conexion con método conectar() estático.
require_once __DIR__ . "/Conexion.php";
class Certificados
{
    private $db; // PDO instance

    public function __construct(PDO $pdo = null)
    {
        // Permite inyección de dependencia para pruebas.
        if ($pdo !== null) {
            $this->db = $pdo;
        } else {
            // Intentar obtener la conexión desde Conexion::conectar()
            if (!class_exists('Conexion')) {
                throw new Exception('Falta la clase Conexion. Cree Conexion.php que devuelva PDO.');
            }
            $this->db = Conexion::conectar();
        }
    }

    // Obtiene todos los certificados (ejemplo simple).
    public function obtenerTodos()
    {
        $sql = "SELECT * FROM certificados ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un certificado por id
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM certificados WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Inserta un nuevo registro de certificado
    public function crear($data)
    {
        $sql = "INSERT INTO certificados (usuario_id, feligres_id, sacramento, fecha_realizacion, lugar, observaciones, creado_en)
                VALUES (:usuario_id, :feligres_id, :sacramento, :fecha_realizacion, :lugar, :observaciones, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':usuario_id', $data['usuario_id'], PDO::PARAM_INT);
        $stmt->bindValue(':feligres_id', $data['feligres_id'], PDO::PARAM_INT);
        $stmt->bindValue(':sacramento', $data['sacramento'], PDO::PARAM_STR);
        $stmt->bindValue(':fecha_realizacion', $data['fecha_realizacion'], PDO::PARAM_STR);
        $stmt->bindValue(':lugar', $data['lugar'], PDO::PARAM_STR);
        $stmt->bindValue(':observaciones', $data['observaciones'], PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
}
