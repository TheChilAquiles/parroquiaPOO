<?php

class FeligresController {


    public function __construct()
    {
        require_once('Modelo/ModeloFeligres.php');
        require_once('Modelo/Conexion.php');
    }


    public function consultarUsuario($email)
    {
        $conexion = new Conexion();

        if (!$conexion->abrir()) {
            return false;
        }

        // Usamos sentencia preparada para evitar inyección
        $mysqli = $conexion->obtenerMySQLI();
        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $stmt->close();
            return $usuario; // Devuelve un array asociativo con los datos del usuario
        } else {
            $stmt->close();
            return null; // Usuario no encontrado
        }
    }
    

    public function ctrlConsularFeligres($tipoDoc,$documento){

        if(!isset($tipoDoc) || !isset($documento)){
        return [ 'status' => 'error' , 'error' => 'Datos Obliarios' ]; // El feligres ya existe

        }

        $feligresModel = new ModeloFeligres();
        $resultado = $feligresModel->mdlConsultarFeligres($tipoDoc,$documento);
    
        if ($resultado && $resultado['status'] === 'error') {
            return [ 'status' => 'error' , 'error' => $resultado['message'] ]; // El feligres ya existe
        } else {
            return [ 'status' => 'success', 'message' => "Feligres consultado correctamente" ]; // Feligres consultado correctamente
        }

    }


    public function ctrlCrearFeligres($datosFeligres)
    {

        if (!isset($datosFeligres['tipo-doc']) || !isset($datosFeligres['documento'])) {
            return [ 'status' => 'error', 'error' => 'Datos Obligatorios' ]; // Datos obligatorios no proporcionados
        }

        // Crear una instancia del modelo de feligres
        $feligresModel = new ModeloFeligres();
        // Registrar el feligres
        $resultado = $feligresModel->mdlCrearFeligres($datosFeligres);

        if ($resultado && $resultado['status'] === 'error') {
            return [ 'status' => 'error', 'error' => $resultado['message'] ]; // El feligres ya existe
        } else {
            return [ 'status' => 'success', 'message' => "Feligres registrado correctamente" ]; // Feligres registrado correctamente
        }
    }


    public function mdlActualizarFeligres($datosFeligres)
    {

        if (!isset($datosFeligres['tipo-doc']) || !isset($datosFeligres['documento'])) {
            return [ 'status' => 'error', 'error' => 'Datos Obligatorios' ]; // Datos obligatorios no proporcionados
        }

        // Crear una instancia del modelo de feligres
        $feligresModel = new ModeloFeligres();
        // Registrar el feligres
        $resultado = $feligresModel->mdlUpdateFeligres($datosFeligres);

        if ($resultado && $resultado['status'] === 'error') {
            return [ 'status' => 'error', 'error' => $resultado['message'] ]; // El feligres ya existe
        } else {
            return [ 'status' => 'success', 'message' => "Feligres actualizado correctamente" ]; // Feligres actualizado correctamente
        }
       
    }   


}
