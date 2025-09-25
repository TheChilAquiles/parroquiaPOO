<?php
// Vista/actualizar_pago.php
session_start();
require_once __DIR__ . "/../Modelo/Conexion.php";

$mensaje = "";
$conexion = Conexion::conectar();
$pago = null;

// Cargar datos si viene id por GET
if (isset($_GET['id'])) {
    try {
        $stmt = $conexion->prepare("SELECT * FROM pagos WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $pago = $stmt->fetch();

        if (!$pago) {
            $mensaje = "Pago no encontrado.";
        }
    } catch (PDOException $e) {
        $mensaje = "Error al obtener el pago: " . $e->getMessage();
    }
} else {
    $mensaje = "Falta el ID del pago.";
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "UPDATE pagos 
                SET certificado_id = ?, valor = ?, estado = ?, tipo_pago_id = ? 
                WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            $_POST['certificado_id'],
            $_POST['valor'],
            $_POST['estado'],
            $_POST['tipo_pago_id'],
            $_POST['id']
        ]);

        $_SESSION['mensaje'] = "Pago actualizado correctamente.";
        // ✅ Redirigir SIEMPRE a la ruta real de pagos.php
        header("Location: /ParroquiaPOO/parroquiaPOO/index.php?menu-item=Pagos");
        exit();

    } catch (PDOException $e) {
        $mensaje = "Error al actualizar: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Actualizar Pago</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f9f1eb] text-gray-800 min-h-screen flex items-center justify-center">
  <div class="w-full max-w-lg bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
    <h1 class="text-3xl font-bold text-blue-600 mb-6 text-center">Actualizar Pago</h1>

    <?php if ($mensaje): ?>
      <p class="text-red-500 mb-4 text-center font-semibold"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <?php if ($pago): ?>
      <form method="POST" class="space-y-5">
        <input type="hidden" name="id" value="<?= htmlspecialchars($pago['id']) ?>">

        <div>
          <label class="block text-gray-700 font-semibold mb-1">Certificado ID:</label>
          <input type="number" name="certificado_id" required value="<?= htmlspecialchars($pago['certificado_id']) ?>"
                 class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-1">Valor:</label>
          <input type="number" step="0.01" name="valor" required value="<?= htmlspecialchars($pago['valor']) ?>"
                 class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-1">Estado:</label>
          <select name="estado" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
            <option value="PENDIENTE" <?= ($pago['estado'] === 'PENDIENTE') ? 'selected' : '' ?>>PENDIENTE</option>
            <option value="PAGADO" <?= ($pago['estado'] === 'PAGADO') ? 'selected' : '' ?>>PAGADO</option>
            <option value="CANCELADO" <?= ($pago['estado'] === 'CANCELADO') ? 'selected' : '' ?>>CANCELADO</option>
          </select>
        </div>

        <div>
    <label class="block text-gray-700 font-semibold mb-1">Tipo de Pago:</label>
    <select name="tipo_pago_id" required class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
        <?php
        $tipos = $conexion->query("SELECT * FROM tipos_pago");
        foreach ($tipos as $tipo) {
            $selected = ($pago['tipo_pago_id'] == $tipo['id']) ? 'selected' : '';
            echo "<option value='{$tipo['id']}' $selected>{$tipo['descripcion']}</option>";
        }
        ?>
    </select>
</div>

        <div class="flex justify-between items-center pt-4">
          <!-- ✅ Cancelar regresa a la vista correcta -->
          <button type="button" 
        onclick="window.history.back()"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
    Cancelar
</button>
          <button type="submit" 
                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg shadow font-semibold">
                  Actualizar Pago
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>