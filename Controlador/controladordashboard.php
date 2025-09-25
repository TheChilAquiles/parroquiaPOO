<?php
// controllers/DashboardController.php

// El controlador es el "director de orquesta". No contiene lógica de negocio
// ni de presentación. Su única función es:
// 1. Recibir la petición del usuario (a través del index.php).
// 2. Pedirle al modelo los datos necesarios.
// 3. Pasar esos datos a la vista para que los muestre.

// Incluimos el modelo que vamos a necesitar.
require_once 'Modelo/Modelodashboard.php';

class DashboardController {

    /**
     * Muestra la página principal del dashboard.
     */
    public function index() {
        // 1. Creamos una instancia del modelo.
        $dashboardModel = new DashboardModel();

        // 2. Le pedimos al modelo todos los datos que necesita el dashboard.
        // El modelo nos devuelve un array asociativo con toda la información.
        $data = $dashboardModel->getDashboardData();

        // La función extract() es muy útil aquí. Convierte las claves de un
        // array asociativo en variables. Por ejemplo, de $data['usuarios_total']
        // creará una variable llamada $usuarios_total.
        extract($data);

        // 3. Incluimos el archivo de la vista. Como hemos usado extract(),
        // la vista tendrá acceso a todas las variables que necesita ($usuarios_total,
        // $libros_id, etc.) para mostrar la información.
        require_once 'Vista/dashboard.php';
    }
}