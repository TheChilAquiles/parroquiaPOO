<?php

/**
 * Clase que actúa como controlador para la gestión de feligreses.
 *
 * Se encarga de recibir las peticiones de la vista, invocar la lógica
 * de negocio en el modelo y devolver la respuesta adecuada.
 *
 * @version 1.0
 * @author Rusbel Godoy
 */
class FeligresController
{
    /**
     * Constructor de la clase.
     * Incluye los archivos necesarios para el funcionamiento del controlador.
     */
    public function __construct()
    {
        require_once('../Modelo/ModeloFeligres.php');
    }

    /**
     * Controlador para consultar un feligrés por tipo y número de documento.
     *
     * @param string $tipoDoc   El tipo de documento del feligrés.
     * @param string $documento El número de documento del feligrés.
     * @return array            Un array con el estado de la operación, un mensaje y los datos si la consulta es exitosa.
     */
    public function ctrlConsularFeligres($tipoDoc, $documento)
    {
        // Validación de datos obligatorios en la capa del controlador.
        if (empty($tipoDoc) || empty($documento)) {
            return [
                'status' => 'error',
                'error'  => 'Datos obligatorios'
            ];
        }

        // Creación de una instancia del modelo para interactuar con la base de datos.
        $feligresModel = new ModeloFeligres();
        // Se invoca el método del modelo para consultar al feligrés.
        $resultado = $feligresModel->mdlConsultarFeligres($tipoDoc, $documento);

        // Se evalúa el resultado de la consulta.
        if ($resultado) {
            // Si se encuentra el feligrés, se devuelve un mensaje de éxito y los datos.
            return [
                'status' => 'success',
                'message' => "Feligres consultado correctamente",
                'data' => $resultado,
                'numero_documento' => $resultado['numero_documento']
            ];
        } else {
            // Si no se encuentra, se devuelve un mensaje de error.
            return [
                'status' => 'error',
                'error' => 'No se encontró el feligrés'
            ];
        }
    }

    /**
     * Controlador para crear un nuevo feligrés.
     *
     * @param array $datosFeligres Un array con los datos del feligrés a registrar.
     * @return array               Un array con el estado y un mensaje de la operación.
     */
    public function ctrlCrearFeligres($datosFeligres)
    {
        // Validación de datos obligatorios.
        if (!isset($datosFeligres['tipo-doc']) || !isset($datosFeligres['documento'])) {
            return ['status' => 'error', 'error' => 'Datos Obligatorios'];
        }

        // Creación de una instancia del modelo de feligres.
        $feligresModel = new ModeloFeligres();

        // Se invoca el método del modelo para registrar el feligrés.
        $resultado = $feligresModel->mdlCrearFeligres($datosFeligres);




        // Se evalúa el resultado del modelo.
        // Se asume que el modelo devuelve un array con 'status' y 'message' en caso de error.
        if (is_array($resultado) && isset($resultado['status']) && $resultado['status'] === 'error') {
            return ['status' => 'error', 'error' => $resultado['message']]; // El feligrés ya existe u otro error del modelo.
        } else {
            return ['status' => 'success', 'message' => "Feligres registrado correctamente"];
        }
    }

    /**
     * Controlador para actualizar los datos de un feligrés existente.
     *
     * @param array $datosFeligres Un array con los datos del feligrés a actualizar.
     * @return array               Un array con el estado y un mensaje de la operación.
     */
    public function ctrlActualizarFeligres($datosFeligres)
    {
        // Validación de datos obligatorios.
        if (!isset($datosFeligres['tipo-doc']) || !isset($datosFeligres['documento'])) {
            return ['status' => 'error', 'error' => 'Datos Obligatorios'];
        }

        // Creación de una instancia del modelo.
        $feligresModel = new ModeloFeligres();

        // Se invoca el método del modelo para actualizar el feligrés.
        $resultado = $feligresModel->mdlUpdateFeligres($datosFeligres);

        // Se evalúa el resultado del modelo.
        if (is_array($resultado) && isset($resultado['status']) && $resultado['status'] === 'error') {
            return ['status' => 'error', 'error' => $resultado['message']];
        } else {
            return ['status' => 'success', 'message' => "Feligres actualizado correctamente"];
        }
    }
}
