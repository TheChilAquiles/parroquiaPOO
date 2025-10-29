<?php

// ============================================================================
// DashboardController.php
// ============================================================================

class DashboardController {
    private $modelo;

    public function __construct() {
        $this->modelo = new DashboardModel();
    }

    public function mostrar() {
        $estadisticas = $this->modelo->obtenerEstadisticas();
        include_once __DIR__ . '/../Vista/dashboard.php';
    }
}