ELIMINAR_PAGO
<?php
require_once __DIR__ . "/../Modelo/Conexion.php";

$mensaje = "";

if (isset($_GET['id'])) {
    try {
        $conexion = Conexion::conectar();

        $sql = "DELETE FROM pagos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$_GET['id']]);

        // Redirige al listado después de eliminar
        header("Location: Pagos.php");
        exit();

    } catch (PDOException $e) {
        $mensaje = "Error al eliminar: " . $e->getMessage();
    }
} else {
    $mensaje = "ID no proporcionado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f9f1eb] text-gray-800 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-gray-200 text-center">
        <h1 class="text-2xl font-bold text-red-600 mb-4">Eliminar Pago</h1>

        <?php if ($mensaje): ?>
            <p class="text-red-500 mb-6 font-semibold"><?= htmlspecialchars($mensaje) ?></p>
        <?php else: ?>
            <p class="text-green-600 mb-6 font-semibold">Pago eliminado correctamente.</p>
        <?php endif; ?>

        <a href="Pagos.php" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow font-semibold">
            Volver a Pagos
        </a>
    </div>
</body>
</html>


