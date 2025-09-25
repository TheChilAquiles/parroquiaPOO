<?php
require_once __DIR__ . "/../Modelo/Modelodashboard.php";

class DashboardController {
    private $modelo;

    public function __construct() {
        $this->modelo = new DashboardModel();
    }

    public function mostrarDashboard() {
        $estadisticas = $this->modelo->obtenerEstadisticas();
        require __DIR__ . "/../Vista/dashboard.php";
    }
}
