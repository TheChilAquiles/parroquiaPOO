<?php

class ModeloFeligres
{


    public function mdlConsultarFeligres($tipoDoc, $documento)
    {
        $conexion = Conexion::conectar();

        try {

            $sql = "SELECT * FROM feligreses WHERE numero_documento = ? AND numero_documento = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$tipoDoc, $documento]);
            $resultado = $stmt->fetchColumn();


            return $resultado;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Error de duplicidad (por UNIQUE index)
                return ['exito' => false, 'mensaje' => "Error: Ya existe una partida de Bautizo con el mismo N° de Libro, Folio y Acta."];
            }
            echo "Error al adicionar bautizo: " . $e->getMessage();
            return ['exito' => false, 'mensaje' => "Error interno al guardar la partida de Bautizo."];
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }




    public function mdlCrearFeligres($datosFeligres)
    {
        $conexion = Conexion::conectar();

        try {

            $sql = "INSERT INTO feligreses (usuario_id,tipo_documento_id, primer_nombre , segundo_nombre , primer_apellido , segundo_apellido  , numero_documento , telefono , direccion ) VALUES ( ?, ?, ?, ?,?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);


            $idUser           = $datosFeligres['idUser'] ?? null;
            $tipoDoc          = $datosFeligres['tipo-doc'];
            $primerNombre     = $datosFeligres['primer-nombre'];
            $segundoNombre    = $datosFeligres['segundo-nombre'] ?? '';
            $primerApellido   = $datosFeligres['primer-apellido'];
            $segundoApellido  = $datosFeligres['segundo-apellido'] ?? '';
            $documento        = $datosFeligres['documento'];
            $telefono         = $datosFeligres['telefono'];
            $direccion        = $datosFeligres['direccion'];




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
            $resultado = $stmt->fetchColumn();


            return $resultado;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Error de duplicidad (por UNIQUE index)
                return ['exito' => false, 'mensaje' => "Error: Ya existe una partida de Bautizo con el mismo N° de Libro, Folio y Acta."];
            }
            echo "Error al adicionar bautizo: " . $e->getMessage();
            return ['exito' => false, 'mensaje' => "Error interno al guardar la partida de Bautizo."];
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }


    public function mdlUpdateFeligres($datosFeligres)
    {
        $conexion = Conexion::conectar();


        try {

            $sql = "UPDATE `feligreses` SET `tipo_documento_id` = ? , `numero_documento` = ? , `primer_nombre` = ?  , `segundo_nombre` = ?  , `primer_apellido` = ?  , `segundo_apellido` = ?   , `telefono` = ?  , `direccion` = ?   WHERE `documento` = ?";
            $stmt = $conexion->prepare($sql);

            $idUser           = $datosFeligres['idUser'] ?? null;
            $tipoDoc          = $datosFeligres['tipo-doc'];
            $primerNombre     = $datosFeligres['primer-nombre'];
            $segundoNombre    = $datosFeligres['segundo-nombre'] ?? '';
            $primerApellido   = $datosFeligres['primer-apellido'];
            $segundoApellido  = $datosFeligres['segundo-apellido'] ?? '';
            $documento        = $datosFeligres['documento'];
            $telefono         = $datosFeligres['telefono'];
            $direccion        = $datosFeligres['direccion'];


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
            $resultado = $stmt->fetchColumn();


            return $resultado;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Error de duplicidad (por UNIQUE index)
                return ['exito' => false, 'mensaje' => "Error: Ya existe una partida de Bautizo con el mismo N° de Libro, Folio y Acta."];
            }
            echo "Error al adicionar bautizo: " . $e->getMessage();
            return ['exito' => false, 'mensaje' => "Error interno al guardar la partida de Bautizo."];
        } finally {
            $stmt = null;
            $conexion = null;
        }
    }
}
