<?php

/**
 * Clase ModeloNoticia
 *
 * Esta clase se encarga de las operaciones de acceso a datos (CRUD) relacionadas con la entidad 'noticias'.
 * Utiliza PDO para interactuar de forma segura con la base de datos, previniendo la inyección SQL.
 *
 * Se asume la existencia de una clase 'Conexion' con un método estático 'conectar()'.
 *
 * @package Modelo
 * @category Data Access
 */
class ModeloNoticia
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    /**
     * Crea un nuevo registro de noticia en la base de datos.
     *
     * Este método inserta una nueva noticia en la tabla `noticias` con los datos proporcionados.
     * Utiliza una transacción para garantizar la integridad de los datos.
     *
     * @param array $datos Un array asociativo que contiene los datos de la noticia:
     * 'id_usuario', 'titulo', 'descripcion', 'imagen'.
     * @return array Un array con un mensaje de éxito o un array con un mensaje de error en caso de fallo.
     */
    public function mdlCrearNoticia($datos)
    {
         
        try {
            // Obtiene la conexión a la base de datos.
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            // La fecha de publicación se llena automáticamente por el campo DEFAULT en la BD.
            $sql = "INSERT INTO `noticias` (`id_usuario`, `titulo`, `descripcion`, `imagen`) VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
                $datos['id_usuario'],
                $datos['titulo'],
                $datos['descripcion'],
                $datos['imagen']
            ]);

            // Comprueba si la inserción fue exitosa.
            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Noticia creada correctamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se pudo crear la noticia."];
            }
        } catch (PDOException $e) {
            // Captura errores de PDO y devuelve un mensaje de error.
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            error_log("Error al crear noticia: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al guardar la noticia."];
        } finally {
            // Cierra la conexión y libera los recursos.
            $stmt = null;
             
        }
    }

    /**
     * Obtiene todas las noticias NO ELIMINADAS de la base de datos.
     *
     * Este método selecciona los registros de la tabla `noticias` donde estado_registro es nulo.
     *
     * @return array Un array de arrays asociativos con los datos de las noticias.
     * Retorna un array vacío si no se encuentran noticias o en caso de error.
     */
    public function mdlObtenerNoticias()
    {
         
        $noticias = [];
        try {
            // Obtiene la conexión a la base de datos.
            $this->conexion = Conexion::conectar();

            // Prepara la consulta SQL para obtener solo las noticias activas (no eliminadas lógicamente).
            $sql = "SELECT * FROM `noticias` WHERE `estado_registro` IS NULL ORDER BY `fecha_publicacion` DESC";
            $stmt = $this->conexion->prepare($sql);

            // Ejecuta la consulta.
            $stmt->execute();

            // Obtiene todos los resultados como un array asociativo.
            $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Captura errores de PDO.
            error_log("Error al obtener noticias: " . $e->getMessage());
        } finally {
            // Cierra la conexión y libera los recursos.
            $stmt = null;
             
        }

        return $noticias;
    }

    /**
     * Actualiza una noticia existente en la base de datos.
     *
     * Este método modifica un registro en la tabla `noticias` basado en su ID.
     * Utiliza una transacción para garantizar la integridad de los datos.
     *
     * @param int $id El ID de la noticia a actualizar.
     * @param array $datos Array con los datos de la noticia a actualizar:
     * 'titulo', 'descripcion', 'imagen'.
     * @return array Un array con un mensaje de éxito o un array con un mensaje de error.
     */
    public function mdlActualizarNoticia($id, $datos)
    {
         
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
             
        }
    }

    /**
     * Marca una noticia como eliminada lógicamente en la base de datos.
     *
     * Este método actualiza el campo `estado_registro` con la fecha y hora de la eliminación.
     * Utiliza una transacción para garantizar la integridad de los datos.
     *
     * @param int $id El ID de la noticia a eliminar lógicamente.
     * @return array Un array con un mensaje de éxito o un array con un mensaje de error.
     */
    public function mdlBorrarNoticia($id)
    {
         
        try {
            // Obtiene la conexión a la base de datos.
            $this->conexion = Conexion::conectar();
            $this->conexion->beginTransaction();

            // Prepara la consulta SQL para el borrado lógico.
            $sql = "UPDATE `noticias` SET `estado_registro` = ? WHERE `id` = ?";
            $stmt = $this->conexion->prepare($sql);

            // Vincula la fecha actual y el ID a la consulta.
            $stmt->execute([date('Y-m-d H:i:s'), $id]);

            // Comprueba si se actualizó algún registro.
            if ($stmt->rowCount() > 0) {
                $this->conexion->commit();
                return ['exito' => true, 'mensaje' => "Noticia eliminada lógicamente."];
            } else {
                $this->conexion->rollBack();
                return ['exito' => false, 'mensaje' => "No se encontró la noticia para eliminar."];
            }
        } catch (PDOException $e) {
            // Captura errores de PDO.
            if ($this->conexion && $this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            error_log("Error al borrar noticia: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al borrar la noticia."];
        } finally {
            // Cierra la conexión y libera los recursos.
            $stmt = null;
             
        }
    }
}