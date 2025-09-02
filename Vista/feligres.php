<?php
// Se incluye el controlador de feligreses
require_once('../Controlador/ControladorFeligres    .php');

$feligresController = new FeligresController();
$resultadoConsulta = null;
$resultadoOperacion = null;

// Lógica para manejar las diferentes acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si la acción es "consultar"
    if (isset($_POST['accion']) && $_POST['accion'] === 'consultar') {
        $tipoDoc = $_POST['tipo-doc-consulta'];
        $documento = $_POST['documento-consulta'];
        $resultadoConsulta = $feligresController->ctrlConsularFeligres($tipoDoc, $documento);
    }
    
    // Si la acción es "crear"
    if (isset($_POST['accion']) && $_POST['accion'] === 'crear') {
        $resultadoOperacion = $feligresController->ctrlCrearFeligres($_POST);
    }
    
    // Si la acción es "actualizar"
    if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
        $resultadoOperacion = $feligresController->ctrlActualizarFeligres($_POST);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Feligreses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Gestión de Feligreses</h1>

    <?php if ($resultadoOperacion): ?>
        <div class="alert alert-<?php echo $resultadoOperacion['status'] === 'success' ? 'success' : 'danger'; ?>" role="alert">
            <?php echo $resultadoOperacion['status'] === 'success' ? $resultadoOperacion['message'] : $resultadoOperacion['error']; ?>
        </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <h4>Consultar Feligrés</h4>
        </div>
        <div class="card-body">
            <form id="form-consulta" method="POST">
                <input type="hidden" name="accion" value="consultar">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="tipo-doc-consulta">Tipo de Documento</label>
                        <select class="form-control" id="tipo-doc-consulta" name="tipo-doc-consulta" required>
                            <option value="">Seleccione...</option>
                            <option value="1">Cédula de Ciudadanía</option>
                            <option value="2">Tarjeta de Identidad</option>
                            <option value="3">Cédula de Extranjería</option>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="documento-consulta">Número de Documento</label>
                        <input type="text" class="form-control" id="documento-consulta" name="documento-consulta" required>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Consultar</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4><span id="titulo-form">Registrar Nuevo</span> Feligrés</h4>
        </div>
        <div class="card-body">
            <form id="form-feligres" method="POST">
                <input type="hidden" name="accion" id="accion-form" value="crear">
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="primer-nombre">Primer Nombre</label>
                        <input type="text" class="form-control" id="primer-nombre" name="primer-nombre" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="segundo-nombre">Segundo Nombre</label>
                        <input type="text" class="form-control" id="segundo-nombre" name="segundo-nombre">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="primer-apellido">Primer Apellido</label>
                        <input type="text" class="form-control" id="primer-apellido" name="primer-apellido" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="segundo-apellido">Segundo Apellido</label>
                        <input type="text" class="form-control" id="segundo-apellido" name="segundo-apellido">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="tipo-doc">Tipo de Documento</label>
                        <select class="form-control" id="tipo-doc" name="tipo-doc" required>
                            <option value="">Seleccione...</option>
                            <option value="1">Cédula de Ciudadanía</option>
                            <option value="2">Tarjeta de Identidad</option>
                            <option value="3">Cédula de Extranjería</option>
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="documento">Número de Documento</label>
                        <input type="text" class="form-control" id="documento" name="documento" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                </div>
                <button class="btn btn-success" type="submit">Guardar Feligrés</button>
            </form>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Si la consulta PHP encontró un feligrés, llenar el formulario
    <?php if ($resultadoConsulta && $resultadoConsulta['status'] === 'success'): ?>
        var datos = <?php echo json_encode($resultadoConsulta['data']); ?>;
        
        // Llenar los campos del formulario de gestión
        $('#primer-nombre').val(datos.primer_nombre);
        $('#segundo-nombre').val(datos.segundo_nombre);
        $('#primer-apellido').val(datos.primer_apellido);
        $('#segundo-apellido').val(datos.segundo_apellido);
        $('#tipo-doc').val(datos.tipo_documento_id);
        $('#documento').val(datos.numero_documento);
        $('#telefono').val(datos.telefono);
        $('#direccion').val(datos.direccion);
        
        // Cambiar el título y la acción del formulario a "actualizar"
        $('#titulo-form').text('Actualizar');
        $('#accion-form').val('actualizar');
        
        // Deshabilitar el campo de documento para evitar cambios accidentales
        $('#documento').prop('disabled', true);
    <?php elseif ($resultadoConsulta && $resultadoConsulta['status'] === 'error'): ?>
        // Si no se encontró el feligrés, limpiar el formulario y preparar para "crear"
        $('#form-feligres')[0].reset();
        $('#titulo-form').text('Registrar Nuevo');
        $('#accion-form').val('crear');
        $('#documento').prop('disabled', false);
    <?php endif; ?>
    
});
</script>

</body>
</html>