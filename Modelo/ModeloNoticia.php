<?php
// Asegúrate de que la ruta a tu clase de conexión sea correcta
require_once 'Conexion.php'; 

class ModeloNoticia
{
    private $tabla = 'noticias'; 

    /**
     * @param string $termino El término de búsqueda.
     * @return array|false Retorna un array de noticias que coinciden o false si hay un error.
     */
    public function mdlBuscarNoticias($termino)
    {
        $conexion = Conexion::conectar();
        // ✅ Se utiliza la variable de tabla para consistencia
        $sql = "SELECT id, titulo, descripcion, imagen, fecha FROM noticias WHERE titulo LIKE ? OR descripcion LIKE ? ORDER BY fecha DESC";
        $termino = "%" . $termino . "%"; 

        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$termino, $termino]);
            $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $noticias;
        } catch (PDOException $e) {
            error_log("Error al buscar noticias: " . $e->getMessage());
            return false;
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }

    /**
     * @param int $id El ID de la noticia a leer.
     * @return array|false Retorna la noticia o false si no se encuentra o hay un error.
     */
    public function mdlLeerNoticiaPorId($id)
    {
        $conexion = Conexion::conectar();
        // ✅ Se utiliza la variable de tabla para consistencia
        $sql = "SELECT id, titulo, descripcion, imagen, fecha FROM noticias WHERE id = ?";

        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$id]);
            $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
            return $noticia;
        } catch (PDOException $e) {
            error_log("Error al leer noticia por ID: " . $e->getMessage());
            return false;
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }

    /**
     * @param string $titulo
     * @param string $descripcion
     * @param string $imagen
     * @param int $idUsuario El ID del usuario que crea la noticia
     * @return bool Retorna true si se insertó con éxito, de lo contrario false.
     */
    public function mdlCrearNoticia($titulo, $descripcion, $imagen, $idUsuario)
    {
        $conexion = Conexion::conectar();
        // ✅ Se agrega 'id_usuario' a la consulta SQL
        $sql = "INSERT INTO {$this->tabla} (id_usuario, titulo, descripcion, imagen, fecha) VALUES (?, ?, ?, ?, NOW())";

        try {
            $stmt = $conexion->prepare($sql);
            // ✅ Se agrega $idUsuario al array de ejecución
            $stmt->execute([$idUsuario, $titulo, $descripcion, $imagen]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al crear noticia: " . $e->getMessage());
            return false;
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }

    /**
     * @param int $id
     * @param string $titulo
     * @param string $descripcion
     * @param string $imagen
     * @return bool Retorna true si se actualizó con éxito, de lo contrario false.
     */
    public function mdlActualizarNoticia($id, $titulo, $descripcion, $imagen)
    {
        $conexion = Conexion::conectar();
        // ✅ Se utiliza la variable de tabla y se elimina la fecha de la consulta
        $sql = "UPDATE {$this->tabla} SET titulo = ?, descripcion = ?, imagen = ? WHERE id = ?";

        try {
            $stmt = $conexion->prepare($sql);
            // ✅ Se corrige el orden y se pasan los parámetros correctos
            $stmt->execute([$titulo, $descripcion, $imagen, $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al actualizar noticia: " . $e->getMessage());
            return false;
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }

    /**
     * @param int $id El ID de la noticia a eliminar.
     * @return bool Retorna true si se eliminó con éxito, de lo contrario false.
     */
    public function mdlEliminarNoticia($id)
    {
        $conexion = Conexion::conectar();
        // ✅ Se utiliza la variable de tabla para consistencia
        $sql = "DELETE FROM {$this->tabla} WHERE id = ?";

        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar noticia: " . $e->getMessage());
            return false;
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }
}