<?php

/**
 * Clase que gestiona la interacción con la base de datos para la entidad Feligrés.
 *
 * Esta clase contiene los métodos que actúan como la capa de modelo en el patrón MVC,
 * realizando las operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * sobre la tabla de feligreses.
 *
 * @version 1.0
 * @author Rusbel Godoy
 */
class ModeloFeligres
{
    // Nota: Por estándar, los atributos de clase deberían ir aquí, pero no
    // se han definido en el código original.
    // Ejemplo: private $conexion;
    
    /**
     * Consulta un feligrés en la base de datos por su tipo y número de documento.
     *
     * @param int $tipoDoc El ID del tipo de documento.
     * @param string $documento El número del documento.
     * @return array|false Retorna un array asociativo con los datos del feligrés si es encontrado,
     * o false si no se encuentra. También puede retornar un array con un mensaje
     * de error en caso de excepción.
     */
    public function mdlConsultarFeligres($tipoDoc, $documento)
    {
        // Se establece la conexión a la base de datos.
        $conexion = Conexion::conectar();

        try {
            // Se prepara la consulta SQL usando parámetros para prevenir inyecciones.
            $sql = "SELECT * FROM feligreses WHERE tipo_documento_id = ? AND numero_documento = ?";
            $stmt = $conexion->prepare($sql);
            
            // Se ejecuta la consulta con los valores proporcionados.
            $stmt->execute([$tipoDoc, $documento]);
            
            // Se obtiene la primera fila de resultados como un array asociativo.
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retorna el resultado de la consulta.
            return $resultado;
        } catch (PDOException $e) {
            // Manejo de excepciones en caso de un error en la base de datos.
            // Nota: Este bloque de código está duplicado en los 3 métodos. Podría
            // ser refactorizado en un método privado para mejorar la reutilización.
            if ($e->getCode() == 23000) {
                return ['exito' => false, 'mensaje' => "Error: Ya existe una partida de Bautizo con el mismo N° de Libro, Folio y Acta."];
            }
            error_log("Error al consultar feligrés: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al consultar el feligrés."];
        } finally {
            // Se cierran el statement y la conexión para liberar recursos.
            $stmt = null;
            $conexion = null;
        }
    }

    /**
     * Inserta un nuevo feligrés en la base de datos.
     *
     * @param array $datosFeligres Un array asociativo con los datos del feligrés a crear.
     * @return int|array Retorna el ID del último registro insertado o un array con un mensaje de error.
     */
    public function mdlCrearFeligres($datosFeligres)
    {
        $conexion = Conexion::conectar();

        try {
            $sql = "INSERT INTO feligreses (usuario_id, tipo_documento_id, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, numero_documento, telefono, direccion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);

            // Asignación de variables desde el array de datos, con valores por defecto si no existen.
            $idUser          = $datosFeligres['idUser'] ?? null;
            $tipoDoc         = $datosFeligres['tipo-doc'];
            $primerNombre    = $datosFeligres['primer-nombre'];
            $segundoNombre   = $datosFeligres['segundo-nombre'] ?? '';
            $primerApellido  = $datosFeligres['primer-apellido'];
            $segundoApellido = $datosFeligres['segundo-apellido'] ?? '';
            $documento       = $datosFeligres['documento'];
            $telefono        = $datosFeligres['telefono'];
            $direccion       = $datosFeligres['direccion'];

            // Se ejecuta el statement con los valores correspondientes.
            $stmt->execute([
                $idUser,
                $tipoDoc,
                $primerNombre,
                $segundoNombre,
                $primerApellido,
                $segundoApellido,
                $documento,
                $telefono,
                $direccion
            ]);
            
            // Retorna el ID del último registro insertado.
            // Nota: La llamada a `fetchColumn` aquí es incorrecta para una operación INSERT.
            // Para obtener el último ID insertado, se debería usar `$conexion->lastInsertId()`.
            // La llamada actual probablemente retornará `false` o 0.
            $resultado = $stmt->fetchColumn();

            return $resultado;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['exito' => false, 'mensaje' => "Error: Ya existe una partida de Bautizo con el mismo N° de Libro, Folio y Acta."];
            }
            error_log("Error al crear feligrés: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al guardar la partida de Bautizo."];
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }

    /**
     * Actualiza los datos de un feligrés existente.
     *
     * @param array $datosFeligres Un array asociativo con los datos del feligrés a actualizar.
     * @return int|array Retorna el número de filas afectadas o un array con un mensaje de error.
     */
    public function mdlUpdateFeligres($datosFeligres)
    {
        $conexion = Conexion::conectar();

        try {
            $sql = "UPDATE `feligreses` SET `tipo_documento_id` = ? , `numero_documento` = ? , `primer_nombre` = ? , `segundo_nombre` = ? , `primer_apellido` = ? , `segundo_apellido` = ? , `telefono` = ? , `direccion` = ? WHERE `documento` = ?";
            $stmt = $conexion->prepare($sql);

            $idUser          = $datosFeligres['idUser'] ?? null;
            $tipoDoc         = $datosFeligres['tipo-doc'];
            $primerNombre    = $datosFeligres['primer-nombre'];
            $segundoNombre   = $datosFeligres['segundo-nombre'] ?? '';
            $primerApellido  = $datosFeligres['primer-apellido'];
            $segundoApellido = $datosFeligres['segundo-apellido'] ?? '';
            $documento       = $datosFeligres['documento'];
            $telefono        = $datosFeligres['telefono'];
            $direccion       = $datosFeligres['direccion'];

            // Se ejecuta el statement con los valores de los parámetros.
            // La variable $documento aparece dos veces: una en SET y otra en WHERE.
            $stmt->execute([
                $tipoDoc,
                $documento,
                $primerNombre,
                $segundoNombre,
                $primerApellido,
                $segundoApellido,
                $telefono,
                $direccion,
                $documento
            ]);
            
            // Nota: La llamada a `fetchColumn` aquí es incorrecta para una operación UPDATE.
            // Se debería usar `$stmt->rowCount()` para obtener el número de filas afectadas.
            $resultado = $stmt->fetchColumn();

            return $resultado;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['exito' => false, 'mensaje' => "Error: Ya existe una partida de Bautizo con el mismo N° de Libro, Folio y Acta."];
            }
            error_log("Error al actualizar feligrés: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => "Error interno al guardar la partida de Bautizo."];
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }
}