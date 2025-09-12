<?php

class ModeloParticipante {

    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    // Crear participante
    public function crearParticipante($data) {


        try {
            $stmt = $this->conexion->prepare("
                INSERT INTO participantes (feligres_id, sacramento_id, rol_participante_id)
                VALUES (:feligresId, :sacramentoId, :participanteId)
            ");

            $stmt->bindParam(':feligresId', $data['feligres-id']);
            $stmt->bindParam(':sacramentoId', $data['sacramento-id']);
            $stmt->bindParam(':participanteId', $data['participante-id']);

            $stmt->execute();

            return [
                'success' => true,
                'id' => $this->conexion->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    // Obtener todos los participantes
    public function obtenerParticipantes() {
        try {
            $stmt = $this->conexion->query("SELECT * FROM participantes");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtener participante por ID
    public function obtenerPorId($id) {
        try {
            $stmt = $this->conexion->prepare("SELECT * FROM participantes WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Eliminar participante
    public function eliminar($id) {
        try {
            $stmt = $this->conexion->prepare("DELETE FROM participantes WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Actualizar participante
    public function actualizar($id, $data) {
        try {
            $stmt = $this->conexion->prepare("
                UPDATE participantes
                SET nombre = :nombre, apellido = :apellido, email = :email
                WHERE id = :id
            ");

            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':apellido', $data['apellido']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>
