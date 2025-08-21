<?php

class ModeloLibro
{

    

    public function mdlConsultarCantidadLibros($tipo)
    {
        $conexion = Conexion::conectar();

        try {

            $sql = "SELECT COUNT(*) FROM libros WHERE libro_tipo_id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$tipo]);
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


    public function mdlAñadirLibro($tipo,$cantidad)
    {

        $conexion = Conexion::conectar();

        try {

            $sql = "INSERT INTO `libros`(`libro_tipo_id`, `numero`) VALUES (?,?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$tipo,$cantidad+1]);
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
