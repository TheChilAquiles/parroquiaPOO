<?php

// ============================================================================
// LibrosController.php
// ============================================================================

class LibrosController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloLibro();
    }

    public function index()
    {
        include_once __DIR__ . '/../Vista/libros-tipo.php';
    }

    public function seleccionarTipo()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->index();
            return;
        }

        $tipo = $_POST['tipo'] ?? null;

        if (empty($tipo) || !is_numeric($tipo)) {
            $_SESSION['error'] = 'Tipo de libro inválido.';
            $this->index();
            return;
        }

        $tipo = (int)$tipo;
        $tipoId = $tipo; // ID numérico para los formularios
        $cantidad = $this->modelo->mdlConsultarCantidadLibros($tipo);
        $libroTipo = $this->obtenerNombreTipo($tipo);

        include_once __DIR__ . '/../Vista/libros.php';
    }

    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->index();
            return;
        }

        $tipo = $_POST['tipo'] ?? null;

        if (empty($tipo) || !is_numeric($tipo)) {
            $_SESSION['error'] = 'Tipo de libro inválido.';
            $this->index();
            return;
        }

        $tipo = (int)$tipo;
        $cantidad = $this->modelo->mdlConsultarCantidadLibros($tipo);
        $resultado = $this->modelo->mdlAñadirLibro($tipo, $cantidad + 1);

        $_SESSION['success'] = 'Libro creado exitosamente.';
        $tipoId = $tipo; // ID numérico para los formularios
        $cantidad = $this->modelo->mdlConsultarCantidadLibros($tipo);
        $libroTipo = $this->obtenerNombreTipo($tipo);
        include_once __DIR__ . '/../Vista/libros.php';
    }

    /**
     * Obtiene el nombre legible del tipo de libro según su ID
     * @param int $id ID del tipo de libro
     * @return string Nombre del tipo de libro
     */
    private function obtenerNombreTipo($id)
    {
        $tipos = [
            1 => 'Bautizos',
            2 => 'Confirmaciones',
            3 => 'Defunciones',
            4 => 'Matrimonios'
        ];

        return $tipos[$id] ?? 'Desconocido';
    }
}