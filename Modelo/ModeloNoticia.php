<?php

/**
 * Clase ModeloNoticia
 *
 * Se encarga de las operaciones CRUD para la entidad 'noticias'.
 * Utiliza PDO para interactuar de forma segura con la base de datos.
 *
 * @package Modelo
 */

require_once 'Conexion.php'; // <-- AÑADE ESTA LÍNEA
class ModeloNoticia
{
    private $conexion;

    /**
     * Crea un nuevo registro de noticia en la base de datos.
     *
     * @param array $datos Datos de la noticia: 'id_usuario', 'titulo', 'descripcion', 'imagen'.
     * @return array Resultado de la operación.
     */
    public function mdlCrearNoticia($datos)
    {
        $stmt = null; // Definir fuera del try para que sea accesible en finally
        try {
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            $sql = "INSERT INTO `noticias` (`id_usuario`, `titulo`, `descripcion`, `imagen`) VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $datos['id_usuario'],
                $datos['titulo'],
                $datos['descripcion'],
                $datos['imagen']
            ]);

            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Noticia creada correctamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se pudo crear la noticia."];
            }
        } catch (PDOException $e) {
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            error_log("Error al crear noticia: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al guardar la noticia."];
        } finally {
            // Liberar recursos
            $stmt = null;
            $this->conexion = null;
        }
    }

    /**
     * Obtiene todas las noticias no eliminadas.
     *
     * @param string $filtro Término de búsqueda opcional.
     * @return array Lista de noticias.
     */
    public function mdlObtenerNoticias($filtro = '')
    {
        $stmt = null;
        $noticias = [];
        try {
            $this->conexion = Conexion::conectar();
            
            $sql = "SELECT * FROM `noticias` WHERE `estado_registro` IS NULL";
            
            if (!empty($filtro)) {
                $sql .= " AND (`titulo` LIKE :filtro OR `descripcion` LIKE :filtro)";
            }
            
            $sql .= " ORDER BY `fecha_publicacion` DESC";
            
            $stmt = $this->conexion->prepare($sql);
            
            if (!empty($filtro)) {
                $stmt->bindValue(':filtro', '%' . $filtro . '%', PDO::PARAM_STR);
            }
            
            $stmt->execute();
            $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener noticias: " . $e->getMessage());
        } finally {
            $stmt = null;
            $this->conexion = null;
        }

        return $noticias;
    }

    /**
     * Actualiza una noticia existente.
     *
     * @param int $id El ID de la noticia.
     * @param array $datos Datos a actualizar: 'titulo', 'descripcion', 'imagen'.
     * @return array Resultado de la operación.
     */
    public function mdlActualizarNoticia($id, $datos)
    {
        $stmt = null;
        try {
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            $sql = "UPDATE `noticias` SET `titulo` = ?, `descripcion` = ?, `imagen` = ? WHERE `id` = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([
                $datos['titulo'],
                $datos['descripcion'],
                $datos['imagen'],
                $id
            ]);

            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Noticia actualizada correctamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se encontró la noticia para actualizar o no se realizaron cambios."];
            }
        } catch (PDOException $e) {
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            error_log("Error al actualizar noticia: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al actualizar la noticia."];
        } finally {
            $stmt = null;
            $this->conexion = null;
        }
    }

        /**
         * Realiza un borrado lógico de una noticia.
         *
         * @param int $id El ID de la noticia.
         * @return array Resultado de la operación.
         */
        public function mdlBorrarNoticia($id)
        {
            $stmt = null;
            try {
                $this->conexion = Conexion::conectar();
                $this->conexion->beginTransaction();

                $sql = "UPDATE `noticias` SET `estado_registro` = ? WHERE `id` = ?";
                $stmt = $this->conexion->prepare($sql);

                $stmt->execute([date('Y-m-d H:i:s'), $id]);

                if ($stmt->rowCount() > 0) {
                    $this->conexion->commit();
                    return ['exito' => true, 'mensaje' => "Noticia eliminada correctamente."];
                } else {
                    $this->conexion->rollBack();
                    return ['exito' => false, 'mensaje' => "No se encontró la noticia para eliminar."];
                }
            } catch (PDOException $e) {
                if ($this->conexion && $this->conexion->inTransaction()) {
                    $this->conexion->rollBack();
                }
                error_log("Error al borrar noticia: " . $e->getMessage());
                return ['exito' => false, 'mensaje' => "Error interno al borrar la noticia."];
            } finally {
                $stmt = null;
                $this->conexion = null;
            }
        }
}