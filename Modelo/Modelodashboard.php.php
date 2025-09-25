<?php
// models/DashboardModel.php

// El modelo es el encargado de toda la lógica de negocio y el acceso a los datos.
// Si necesitas conectar a una base de datos, hacer cálculos, o consultar una API,
// todo eso se hace aquí. La vista y el controlador no saben (ni les importa)
// de dónde vienen los datos.

class DashboardModel {

    // En una aplicación real, aquí tendrías la conexión a la base de datos.
    // private $db;
    // public function __construct() {
    //     $this->db = new PDO("mysql:host=localhost;dbname=parroquia", "user", "pass");
    // }

    /**
     * Obtiene todas las estadísticas necesarias para el dashboard.
     * Este método agrupa todas las consultas para ser más eficiente.
     * @return array Un array asociativo con todos los datos.
     */
    public function getDashboardData() {
        // Para este ejemplo, simularemos la obtención de datos con valores aleatorios.
        // En una aplicación real, cada una de estas líneas sería una consulta a la base de datos.
        // Por ejemplo: $this->db->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();

        $data = [
            'usuarios_total'      => rand(100, 200),
            'usuarios_roles'      => rand(5, 15),
            'usuarios_feligreses' => rand(800, 1200),
            'libros_id'           => rand(50, 80),
            'libros_tipo'         => rand(3, 5),
            'libros_num'          => rand(1000, 2000),
            'documentos_id'       => rand(200, 300),
            'documentos_tipo'     => rand(8, 12),
            'reportes_id'         => rand(40, 60),
            'reportes_categorias' => rand(4, 7),
            'pagos_id'            => rand(500, 700),
            'pagos_completos'     => rand(450, 490),
            'pagos_cancelados'    => rand(10, 50),
            'contactos'           => rand(30, 90)
        ];

        return $data;
    }
}