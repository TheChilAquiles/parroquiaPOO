<?php
class Certificado {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar todos
    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT * FROM certificados");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener uno por ID
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM certificado WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insertar
    public function crear($data) {
        $sql = "INSERT INTO certificado (usuario_generador_id, feligres_certificado_id, fecha_emision, fecha_expiracion, tipo_certificado, sacramento_id, ruta_archivo, estado) 
                VALUES (?,?,?,?,?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['usuario_generador_id'],
            $data['feligres_certificado_id'],
            $data['fecha_emision'],
            $data['fecha_expiracion'],
            $data['tipo_certificado'],
            $data['sacramento_id'],
            $data['ruta_archivo'],
            $data['estado']
        ]);
    }

    // Actualizar
    public function actualizar($id, $data) {
        $sql = "UPDATE certificado SET usuario_generador_id=?, feligres_certificado_id=?, fecha_emision=?, fecha_expiracion=?, tipo_certificado=?, sacramento_id=?, ruta_archivo=?, estado=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['usuario_generador_id'],
            $data['feligres_certificado_id'],
            $data['fecha_emision'],
            $data['fecha_expiracion'],
            $data['tipo_certificado'],
            $data['sacramento_id'],
            $data['ruta_archivo'],
            $data['estado'],
            $id
        ]);
    }

    // Eliminar
    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM certificado WHERE id=?");
        return $stmt->execute([$id]);
    }
}
