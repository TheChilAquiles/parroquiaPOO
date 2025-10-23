<?php

// ============================================================================
// LibrosController.php
// ============================================================================

class LibrosController
{
    private $modelo;

    public function __construct()
    {
        require_once __DIR__ . '/../Modelo/ModeloLibro.php';
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
        $cantidad = $this->modelo->mdlConsultarCantidadLibros($tipo);

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
        $cantidad = $this->modelo->mdlConsultarCantidadLibros($tipo);
        include_once __DIR__ . '/../Vista/libros.php';
    }
}