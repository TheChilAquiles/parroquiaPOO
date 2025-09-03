<?php
require_once __DIR__ . '/../Modelo/ModeloCertificados.php';
require_once __DIR__ . '/../Modelo/Conexion.php';

class CertificadoController {
    private $modelo;

    public function __construct($pdo) {
        $this->modelo = new Certificado($pdo);
    }

    public function index() {
        return $this->modelo->obtenerTodos();
    }

    public function show($id) {
        return $this->modelo->obtenerPorId($id);
    }

    public function store($data) {
        return $this->modelo->crear($data);
    }

    public function update($id, $data) {
        return $this->modelo->actualizar($id, $data);
    }

    public function delete($id) {
        return $this->modelo->eliminar($id);
    }
}
