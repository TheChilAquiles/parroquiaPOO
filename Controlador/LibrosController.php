<?php

// ============================================================================
// LibrosController.php
// ============================================================================

class LibrosController extends BaseController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloLibro();
    }

    public function index()
    {
        // Verificar autenticación y perfil completo
        $this->requiereAutenticacion();

        include_once __DIR__ . '/../Vista/libros-tipo.php';
    }

    public function seleccionarTipo()
    {
        // Se permite GET y POST para flexibilidad en las vistas
        $tipo = $_REQUEST['tipo'] ?? null;

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
        $tipo = $_REQUEST['tipo'] ?? null;

        if (empty($tipo) || !is_numeric($tipo)) {
            $_SESSION['error'] = 'Tipo de libro inválido.';
            // Redirigimos al listado general en caso de error
            header("Location: " . url('libros'));
            exit;
        }

        $tipo = (int)$tipo;
        
        // Consultar cuántos hay y sumar 1
        $cantidad = $this->modelo->mdlConsultarCantidadLibros($tipo);
        $resultado = $this->modelo->mdlAñadirLibro($tipo, $cantidad + 1);

        // Verificamos si realmente se insertó en la BD
        if ($resultado) {
            $_SESSION['success'] = 'Libro creado exitosamente.';
        } else {
            $_SESSION['error'] = 'Error al crear el libro. Verifica que los tipos existan en la base de datos.';
        }

        // REDIRECCIÓN: Forzamos al navegador a recargar la página correcta
        header("Location: " . url('libros/seleccionar-tipo') . "?tipo=" . $tipo);
        exit;
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