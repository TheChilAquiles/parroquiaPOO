<?php

/**
 * ControladorFeligres.php - REFACTORIZADO
 * 
 * Controlador para gestión de feligreses
 * Actúa como intermediario entre vistas y modelos
 */

class FeligresController
{
    private $feligresModel;

    public function __construct()
    {
        require_once __DIR__ . '/../Modelo/ModeloFeligres.php';
        $this->feligresModel = new ModeloFeligres();
    }

    /**
     * Consulta un feligrés por tipo y número de documento
     * Retorna array con estructura estándar
     */
    public function ctrlConsularFeligres($tipoDoc, $documento)
    {
        // Validación de datos obligatorios
        if (empty($tipoDoc) || empty($documento)) {
            return [
                'status' => 'error',
                'error' => 'Tipo y número de documento son obligatorios'
            ];
        }

        try {
            $resultado = $this->feligresModel->mdlConsultarFeligres($tipoDoc, $documento);

            if ($resultado) {
                return [
                    'status' => 'success',
                    'message' => 'Feligres encontrado',
                    'data' => $resultado,
                    'numero_documento' => $resultado['numero_documento']
                ];
            } else {
                return [
                    'status' => 'error',
                    'error' => 'Feligres no encontrado'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => 'Error al consultar feligrés'
            ];
        }
    }

    /**
     * Crea un nuevo feligrés
     */
    public function ctrlCrearFeligres($datosFeligres)
    {
        // Validación de datos obligatorios
        if (empty($datosFeligres['tipo-doc']) || empty($datosFeligres['documento'])) {
            return ['status' => 'error', 'error' => 'Datos obligatorios faltantes'];
        }

        try {
            $resultado = $this->feligresModel->mdlCrearFeligres($datosFeligres);
            return $resultado;
        } catch (Exception $e) {
            return ['status' => 'error', 'error' => 'Error al crear feligrés'];
        }
    }

    /**
     * Actualiza los datos de un feligrés
     */
    public function ctrlActualizarFeligres($datosFeligres)
    {
        // Validación de datos obligatorios
        if (empty($datosFeligres['tipo-doc']) || empty($datosFeligres['documento'])) {
            return ['status' => 'error', 'error' => 'Datos obligatorios faltantes'];
        }

        try {
            $resultado = $this->feligresModel->mdlUpdateFeligres($datosFeligres);
            return $resultado;
        } catch (Exception $e) {
            return ['status' => 'error', 'error' => 'Error al actualizar feligrés'];
        }
    }

    /**
     * Obtiene un feligrés por ID
     */
    public function ctrlObtenerPorId($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return null;
        }

        try {
            return $this->feligresModel->mdlObtenerPorId($id);
        } catch (Exception $e) {
            return null;
        }
    }
}