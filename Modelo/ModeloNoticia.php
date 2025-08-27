<?php
// Asegúrate de que la ruta a tu clase de conexión sea correcta
require_once 'Conexion.php'; 

class ModeloNoticia
{
    private $tabla = 'noticias'; // Reemplaza con el nombre de tu tabla de noticias

    /**
     * @return array|false Retorna un array de noticias o false si hay un error.
     */
    public function mdlLeerNoticias()
    {
        $conexion = Conexion::conectar();
        $sql = "SELECT id, titulo, descripcion, imagen, fecha FROM {$this->tabla} ORDER BY fecha DESC";

        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $noticias;
        } catch (PDOException $e) {
            // Manejo de errores
            error_log("Error al leer noticias: " . $e->getMessage());
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
        $sql = "SELECT id, titulo, descripcion, imagen, fecha FROM {$this->tabla} WHERE id = ?";

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
     * @return bool Retorna true si se insertó con éxito, de lo contrario false.
     */
    public function mdlCrearNoticia($titulo, $descripcion, $imagen)
    {
        $conexion = Conexion::conectar();
        $sql = "INSERT INTO noticias (titulo, descripcion, imagen, fecha) VALUES (?, ?, ?, NOW())";

        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$titulo, $descripcion, $imagen]);
            return true;
        } catch (PDOException $e) {
            // Un ejemplo de manejo de errores si necesitas
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
        $sql = "UPDATE {$this->tabla} SET titulo = ?, descripcion = ?, imagen = ? WHERE id = ?";

        try {
            $stmt = $conexion->prepare($sql);
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