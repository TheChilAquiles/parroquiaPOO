<?php

require_once('Conexion.php');

class NoticiaModel {

    /**
     * Crea una nueva noticia en la base de datos.
     * @param string $titulo Título de la noticia.
     * @param string $descripcion Contenido de la noticia.
     * @param string $imagen Ruta o contenido de la imagen.
     * @param int $id_usuario ID del usuario que crea la noticia.
     * @return bool Retorna true si la inserción fue exitosa, de lo contrario false.
     */
    public function crearNoticia($titulo, $descripcion, $imagen, $id_usuario) {
        $sql = "INSERT INTO noticias (id_usuario, titulo, descripcion, imagen) VALUES (?, ?, ?, ?)";
        try {
            // Se usa el método estático directamente para obtener la conexión
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(2, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(3, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(4, $imagen, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al crear noticia: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todas las noticias de la base de datos.
     * @return array|false Retorna un array con todas las noticias o false en caso de error.
     */
    public function obtenerTodasLasNoticias() {
        $sql = "SELECT * FROM noticias ORDER BY estado_registro DESC";
        try {
            // Se usa el método estático directamente para obtener la conexión
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener noticias: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene una noticia específica por su ID.
     * @param int $id_noticia ID de la noticia a obtener.
     * @return array|false Retorna un array con los datos de la noticia o false.
     */
    public function obtenerNoticiaPorId($id_noticia) {
        $sql = "SELECT * FROM noticias WHERE id = ?";
        try {
            // Se usa el método estático directamente para obtener la conexión
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindParam(1, $id_noticia, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener noticia por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza una noticia existente.
     * @param int $id_noticia ID de la noticia a actualizar.
     * @param string $titulo Nuevo título.
     * @param string $descripcion Nuevo contenido.
     * @param string $imagen Nueva ruta de imagen.
     * @return bool Retorna true si la actualización fue exitosa, de lo contrario false.
     */
    public function actualizarNoticia($id_noticia, $titulo, $descripcion, $imagen) {
        $sql = "UPDATE noticias SET titulo = ?, descripcion = ?, imagen = ? WHERE id = ?";
        try {
            // Se usa el método estático directamente para obtener la conexión
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindParam(1, $titulo, PDO::PARAM_STR);
            $stmt->bindParam(2, $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(3, $imagen, PDO::PARAM_STR);
            $stmt->bindParam(4, $id_noticia, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar noticia: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina una noticia por su ID.
     * @param int $id_noticia ID de la noticia a eliminar.
     * @return bool Retorna true si la eliminación fue exitosa, de lo contrario false.
     */
    public function eliminarNoticia($id_noticia) {
        $sql = "DELETE FROM noticias WHERE id = ?";
        try {
            // Se usa el método estático directamente para obtener la conexión
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->bindParam(1, $id_noticia, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar noticia: " . $e->getMessage());
            return false;
        }
    }
}