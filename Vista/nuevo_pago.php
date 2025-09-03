<h2>Crear nuevo pago</h2>
<form action="../controlador/PagoControlador.php" method="POST">
    <label>Usuario ID:</label>
    <input type="number" name="usuario_id" required>

    <label>Monto:</label>
    <input type="number" step="0.01" name="monto" required>

    <label>Fecha:</label>
    <input type="date" name="fecha" required>

    <label>Descripción:</label>
    <input type="text" name="descripcion">

    <button type="submit">Guardar</button>
</form>
