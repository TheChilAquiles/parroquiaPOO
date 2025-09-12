<?php

/**
 * Clase ModeloLibro
 *
 * Esta clase se encarga de las operaciones de acceso a datos relacionadas con la entidad 'Libro'.
 * Utiliza PDO para interactuar de forma segura con la base de datos, previniendo la inyección SQL.
 *
 * @package Modelo
 * @category Data Access
 */
class ModeloLibro
{
    /**
     * Consulta la cantidad de libros por tipo.
     *
     * Este método ejecuta una consulta SQL para contar el número de registros en la tabla `libros`
     * basándose en un `libro_tipo_id` específico.
     *
     * @param int $tipo El ID del tipo de libro que se desea consultar.
     * @return int|array La cantidad de libros si la consulta es exitosa, o un array con
     * un mensaje de error en caso de fallo.
     */



    private $conexion;
    
    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }


    public function mdlConsultarCantidadLibros($tipo)
    {
        // Obtiene la conexión a la base de datos a través de la clase 'Conexion'.

        try {
            // Prepara la consulta SQL para contar registros. El uso de '?' previene la inyección SQL.
            $sql = "SELECT COUNT(*) FROM libros WHERE libro_tipo_id = ?";
            $stmt = $this->conexion->prepare($sql);

            // Ejecuta la consulta con el valor del tipo de libro.
            $stmt->execute([$tipo]);

            // Obtiene el resultado de la consulta (la columna única del conteo).
            $resultado = $stmt->fetchColumn();

            // Devuelve el resultado del conteo.
            return $resultado;
        } catch (PDOException $e) {
            // Captura errores específicos de PDO.
            if ($e->getCode() == 23000) {
                // Error 23000 indica una violación de unicidad (como en un índice UNIQUE).
                return ['exito' => false, 'mensaje' => "Error: Ya existe una partida de Bautizo con el mismo N° de Libro, Folio y Acta."];
            }
            // Muestra un mensaje de error genérico para otros tipos de errores de PDO.
            echo "Error al adicionar bautizo: " . $e->getMessage();
            return ['exito' => false, 'mensaje' => "Error interno al guardar la partida de Bautizo."];
        } finally {
            // Cierra la conexión y libera los recursos del statement.
            $stmt = null;
            
        }
    }

    /**
     * Añade un nuevo libro a la base de datos.
     *
     * Este método inserta un nuevo registro en la tabla `libros` con el tipo y el
     * siguiente número secuencial.
     *
     * @param int $tipo     El ID del tipo de libro.
     * @param int $cantidad La cantidad actual de libros, para calcular el siguiente número.
     * @return mixed El resultado de la operación (normalmente `null` o un array de error).
     */
    public function mdlAñadirLibro($tipo, $cantidad)
    {
        try {
            // Prepara la consulta SQL para la inserción.
            // El número del libro es la cantidad actual + 1.
            $sql = "INSERT INTO `libros`(`libro_tipo_id`, `numero`) VALUES (?,?)";
            $stmt = $this->conexion->prepare($sql);

            // Ejecuta la inserción con los valores proporcionados.
            $stmt->execute([$tipo, $cantidad + 1]);

            // En este caso, `fetchColumn()` no devolverá nada útil para una inserción,
            // ya que no hay un conjunto de resultados. Sin embargo, si la inserción
            // fue exitosa, el método no lanzará una excepción.
            $resultado = $stmt->fetchColumn();

            // Podrías devolver el ID de la última inserción con `$this->conexion->lastInsertId();`
            // si fuera necesario.
            return $resultado;
        } catch (PDOException $e) {
            // Manejo de errores similar al método anterior.
            if ($e->getCode() == 23000) {
                return ['exito' => false, 'mensaje' => "Error: Ya existe una partida de Bautizo con el mismo N° de Libro, Folio y Acta."];
            }
            echo "Error al adicionar bautizo: " . $e->getMessage();
            return ['exito' => false, 'mensaje' => "Error interno al guardar la partida de Bautizo."];
        } finally {
            // Cierra la conexión y libera los recursos.
            $stmt = null;
            
        }
    }
}