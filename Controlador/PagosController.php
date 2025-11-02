<?php

// ============================================================================
// PagosController.php - Refactorizado para usar ModeloPago
// ============================================================================

class PagosController extends BaseController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ModeloPago();
    }

    /**
     * Lista todos los pagos
     */
    public function index()
    {
        // Verificar autenticación
        $this->requiereAutenticacion();

        // Procesar eliminación si viene por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            $this->eliminar();
        }

        // Obtener todos los pagos desde el modelo
        $pagos = $this->modelo->mdlObtenerTodos();

        // Calcular estadísticas
        $estadisticas = $this->modelo->mdlObtenerEstadisticas();

        // Incluir vista (variables $pagos y $estadisticas están disponibles)
        include __DIR__ . '/../Vista/pagos.php';
    }

    /**
     * Muestra formulario para crear pago
     */
    public function crear()
    {
        // Verificar permisos
        $this->requiereAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar creación
            $this->procesarCreacion();
        } else {
            // Mostrar formulario
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
        }
    }

    /**
     * Procesa la creación de un nuevo pago
     */
    private function procesarCreacion()
    {
        $certificado_id = $_POST['certificado_id'] ?? null;
        $valor = $_POST['valor'] ?? null;
        $estado = $_POST['estado'] ?? null;
        $metodo = $_POST['metodo_de_pago'] ?? null;

        // Validar campos requeridos
        if (empty($certificado_id) || empty($valor) || empty($estado) || empty($metodo)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
            return;
        }

        // Validar tipo de dato
        if (!is_numeric($valor) || $valor <= 0) {
            $_SESSION['error'] = 'El valor debe ser un número positivo.';
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
            return;
        }

        // Crear pago usando el modelo
        $resultado = $this->modelo->mdlCrear([
            'certificado_id' => (int)$certificado_id,
            'valor' => (float)$valor,
            'estado' => $estado,
            'metodo_de_pago' => $metodo
        ]);

        if ($resultado['exito']) {
            $_SESSION['success'] = $resultado['mensaje'];
            header('Location: ?route=pagos');
            exit();
        } else {
            $_SESSION['error'] = $resultado['mensaje'];
            $certificados = $this->modelo->mdlObtenerCertificados();
            include __DIR__ . '/../Vista/agregar_pago.php';
        }
    }

    /**
     * Muestra formulario para actualizar pago
     */
    public function actualizar()
    {
        // Verificar permisos
        $this->requiereAdmin();

        $id = $_GET['id'] ?? $_POST['id'] ?? null;

        if (empty($id) || !is_numeric($id)) {
            $_SESSION['error'] = 'ID de pago inválido.';
            header('Location: ?route=pagos');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar actualización
            $this->procesarActualizacion((int)$id);
        } else {
            // Mostrar formulario
            $pago = $this->modelo->mdlObtenerPorId((int)$id);

            if (!$pago) {
                $_SESSION['error'] = 'Pago no encontrado.';
                header('Location: ?route=pagos');
                exit();
            }

            include __DIR__ . '/../Vista/actualizar_pago.php';
        }
    }

    /**
     * Procesa la actualización de un pago
     */
    private function procesarActualizacion($id)
    {
        $estado = $_POST['estado'] ?? null;
        $metodo = $_POST['metodo_de_pago'] ?? null;

        // Validar campos
        if (empty($estado) || empty($metodo)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            $pago = $this->modelo->mdlObtenerPorId($id);
            include __DIR__ . '/../Vista/actualizar_pago.php';
            return;
        }

        // Actualizar usando el modelo
        $resultado = $this->modelo->mdlActualizar($id, [
            'estado' => $estado,
            'metodo_de_pago' => $metodo
        ]);

        if ($resultado['exito']) {
            $_SESSION['success'] = $resultado['mensaje'];
            header('Location: ?route=pagos');
            exit();
        } else {
            $_SESSION['error'] = $resultado['mensaje'];
            $pago = $this->modelo->mdlObtenerPorId($id);
            include __DIR__ . '/../Vista/actualizar_pago.php';
        }
    }

    /**
     * Elimina un pago
     */
    public function eliminar()
    {
        // Verificar permisos
        $this->requiereAdmin();

        $id = $_POST['id'] ?? null;

        if (empty($id) || !is_numeric($id)) {
            $_SESSION['error'] = 'ID inválido.';
            return;
        }

        // Eliminar usando el modelo
        $resultado = $this->modelo->mdlEliminar((int)$id);

        if ($resultado['exito']) {
            $_SESSION['success'] = $resultado['mensaje'];
        } else {
            $_SESSION['error'] = $resultado['mensaje'];
        }
    }
}
