<?php

require_once 'modelo/ModeloParticipante.php';

class ControladorParticipante
{
    private $modelo;
    private $sacramentoID;

    public function __construct($sacramentoId)
    {
        $this->sacramentoID = $sacramentoId;
        $this->modelo = new ModeloParticipante();
    }

    public function ctrlCrearParticipante($datos)
    {
        return $this->modelo->crearParticipante($datos);
    }

    public function ctrlListar()
    {
        return $this->modelo->obtenerParticipantes();
    }

    public function ctrlObtenerPorId($id)
    {
        return $this->modelo->obtenerPorId($id);
    }

    public function ctrlActualizar($id, $datos)
    {
        return $this->modelo->actualizar($id, $datos);
    }

    public function ctrlEliminar($id)
    {
        return $this->modelo->eliminar($id);
    }
}
