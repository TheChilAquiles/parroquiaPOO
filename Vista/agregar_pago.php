<?php
require_once __DIR__ . "/../Modelo/Conexion.php";

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conexion = Conexion::conectar();

        $sql = "INSERT INTO pagos (certificado_id, valor, estado, fecha_pago, tipo_pago_id) 
                VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            $_POST['certificado_id'],
            $_POST['valor'],
            $_POST['estado'],
            $_POST['tipo_pago_id']
        ]);

        // ✅ Redirige al listado principal después de insertar
        header("Location: /ParroquiaPOO/");
        exit();

    } catch (PDOException $e) {
        $mensaje = "Error al guardar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f9f1eb] text-gray-800 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg bg-white p-8 rounded-2xl shadow-lg border border-gray-200">
        <h1 class="text-3xl font-bold text-blue-600 mb-6 text-center">Agregar Pago</h1>

        <?php if ($mensaje): ?>
            <p class="text-red-500 mb-4 text-center font-semibold"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <!-- Certificado -->
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Certificado ID:</label>
                <input type="number" name="certificado_id" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
            </div>

            <!-- Valor -->
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Valor:</label>
                <input type="number" name="valor" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Estado:</label>
                <select name="estado" required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="PENDIENTE">PENDIENTE</option>
                    <option value="PAGADO">PAGADO</option>
                    <option value="CANCELADO">CANCELADO</option>
                </select>
            </div>

            <!-- Tipo de pago -->
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Tipo Pago ID:</label>
                <input type="number" name="tipo_pago_id" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
            </div>

            <!-- Botones -->
            <div class="flex justify-between items-center pt-4">
                <a href="/ParroquiaPOO/" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
                    Cancelar
                </a>

                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow font-semibold">
                    Guardar Pago
                </button>
            </div>
        </form>
    </div>
</body>
</html>
