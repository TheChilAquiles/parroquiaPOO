<?php

class ModeloFeligres
{


    public function mdlConsultarFeligres($tipoDoc, $documento)
    {
        $conexion = new Conexion();

        if (!$conexion->abrir()) {
            return ['status' => 'error', 'message' => 'Error al conectar a la base de datos'];
        }

        $mysqli = $conexion->obtenerMySQLI();
        $stmt = $mysqli->prepare("SELECT * FROM feligreses WHERE numero_documento = ? AND numero_documento = ?");
        $stmt->bind_param("ss", $tipoDoc, $documento);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $feligres = $resultado->fetch_assoc();
            $stmt->close();
            return ['status' => 'success', 'data' => $feligres];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Feligres no encontrado'];
        }
    }




    public function mdlCrearFeligres($datosFeligres)
    {
        $conexion = new Conexion();

        if (!$conexion->abrir()) {
            return ['status' => 'error', 'message' => 'Error al conectar a la base de datos'];
        }

        $mysqli = $conexion->obtenerMySQLI();
        $stmt = $mysqli->prepare("INSERT INTO feligreses (usuario_id,tipo_documento_id, primer_nombre , segundo_nombre , primer_apellido , segundo_apellido  , numero_documento , telefono , direccion ) VALUES ( ?, ?, ?, ?,?, ?, ?, ?, ?)");

        $idUser           = $datosFeligres['idUser'] ?? null;
        $tipoDoc          = $datosFeligres['tipo-doc'];
        $primerNombre     = $datosFeligres['primer-nombre'];
        $segundoNombre    = $datosFeligres['segundo-nombre'] ?? '';
        $primerApellido   = $datosFeligres['primer-apellido'];
        $segundoApellido  = $datosFeligres['segundo-apellido'] ?? '';
        $documento        = $datosFeligres['documento'];
        $telefono         = $datosFeligres['telefono'];
        $direccion        = $datosFeligres['direccion'];

        $stmt->bind_param(
            "iisssssss",
            $idUser,
            $tipoDoc,
            $primerNombre,
            $segundoNombre,
            $primerApellido,
            $segundoApellido,
            $documento,
            $telefono,
            $direccion
        );
        // Ejecutar la consulta

        if ($stmt->execute()) {
            $stmt->close();

            if ($idUser) {
                $conexion->consulta("UPDATE usuarios SET datos_completos = 1 WHERE id = $idUser");
                if ($conexion->obtenerFilasAfectadas() <= 0) {
                    return ['status' => 'error', 'message' => 'Error al actualizar el usuario'];
                }
                $_SESSION['user-datos'] = true; // Actualizar la sesión para indicar que los datos del usuario están completos
            }


            return ['status' => 'success', 'message' => 'Feligres creado correctamente'];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Error al crear el feligres'];
        }
    }


    public function mdlUpdateFeligres($datosFeligres)
    {
        $conexion = new Conexion();

        if (!$conexion->abrir()) {
            return ['status' => 'error', 'message' => 'Error al conectar a la base de datos'];
        }

        $mysqli = $conexion->obtenerMySQLI();
        $stmt = $mysqli->prepare("UPDATE `feligreses` SET `tipo_documento_id` = ? , `numero_documento` = ? , `primer_nombre` = ?  , `segundo_nombre` = ?  , `primer_apellido` = ?  , `segundo_apellido` = ?   , `telefono` = ?  , `direccion` = ?   WHERE `documento` = ?");

        $idUser           = $datosFeligres['idUser'] ?? null;
        $tipoDoc          = $datosFeligres['tipo-doc'];
        $primerNombre     = $datosFeligres['primer-nombre'];
        $segundoNombre    = $datosFeligres['segundo-nombre'] ?? '';
        $primerApellido   = $datosFeligres['primer-apellido'];
        $segundoApellido  = $datosFeligres['segundo-apellido'] ?? '';
        $documento        = $datosFeligres['documento'];
        $telefono         = $datosFeligres['telefono'];
        $direccion        = $datosFeligres['direccion'];

        $stmt->bind_param(
            "isssssssi",
            $tipoDoc,
            $documento,
            $primerNombre,
            $segundoNombre,
            $primerApellido,
            $segundoApellido,
            $telefono,
            $direccion,
            $documento
        );

        echo $stmt->execute();


    }
}
