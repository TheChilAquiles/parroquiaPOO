<?php

class FeligresController
{


    public function __construct()
    {
        require_once('../Modelo/ModeloFeligres.php');
        require_once('../Modelo/Conexion.php');
    }






    public function ctrlConsularFeligres($tipoDoc, $documento)
    {
        if (empty($tipoDoc) || empty($documento)) {
            return [
                'status' => 'error',
                'error'  => 'Datos obligatorios'
            ];
        }

        $feligresModel = new ModeloFeligres();
        $resultado = $feligresModel->mdlConsultarFeligres($tipoDoc, $documento);

        if ($resultado) {
            return [
                'status' => 'success',
                'message' => "Feligres consultado correctamente",
                'data' => $resultado,
                'numero_documento' => $resultado['numero_documento']
            ];
        } else {
            return [
                'status' => 'error',
                'error' => 'No se encontró el feligrés'
            ];
        }
    }



    public function ctrlCrearFeligres($datosFeligres)
    {

        if (!isset($datosFeligres['tipo-doc']) || !isset($datosFeligres['documento'])) {
            return ['status' => 'error', 'error' => 'Datos Obligatorios']; // Datos obligatorios no proporcionados
        }

        // Crear una instancia del modelo de feligres
        $feligresModel = new ModeloFeligres();
        // Registrar el feligres
        $resultado = $feligresModel->mdlCrearFeligres($datosFeligres);

        if ($resultado && $resultado['status'] === 'error') {
            return ['status' => 'error', 'error' => $resultado['message']]; // El feligres ya existe
        } else {
            return ['status' => 'success', 'message' => "Feligres registrado correctamente"]; // Feligres registrado correctamente
        }
    }


    public function ctrlActualizarFeligres($datosFeligres)
    {

        if (!isset($datosFeligres['tipo-doc']) || !isset($datosFeligres['documento'])) {
            return ['status' => 'error', 'error' => 'Datos Obligatorios']; // Datos obligatorios no proporcionados
        }

        // Crear una instancia del modelo de feligres
        $feligresModel = new ModeloFeligres();
        // Registrar el feligres
        $resultado = $feligresModel->mdlUpdateFeligres($datosFeligres);

        if ($resultado && $resultado['status'] === 'error') {
            return ['status' => 'error', 'error' => $resultado['message']]; // El feligres ya existe
        } else {
            return ['status' => 'success', 'message' => "Feligres actualizado correctamente"]; // Feligres actualizado correctamente
        }
    }
}
