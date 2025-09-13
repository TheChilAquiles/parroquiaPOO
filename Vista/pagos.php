<?php
require_once __DIR__ . "/../Modelo/Conexion.php";

$mensaje = "";

try {
    $conexion = Conexion::conectar();

    // ====== BORRADO via POST ======
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id'] ?? 0);

        if ($id > 0) {
            $check = $conexion->prepare("SELECT id FROM pagos WHERE id = ?");
            $check->execute([$id]);

            if ($check->fetch()) {
                $del = $conexion->prepare("DELETE FROM pagos WHERE id = ?");
                $del->execute([$id]);
                $mensaje = "Pago con ID {$id} eliminado correctamente.";
            } else {
                $mensaje = "No se encontró pago con ID {$id}.";
            }
        } else {
            $mensaje = "ID inválido.";
        }

        // Redirigir para evitar reenvío
        $redirect = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: $redirect");
        exit();
    }

    // ====== LISTADO ======
    $sql = "SELECT * FROM pagos";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error en la conexión o consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-blue-600 mb-6">Listado de Pagos</h1>

        <!-- Mensaje -->
        <?php if (!empty($mensaje)): ?>
            <div class="mb-4 p-3 rounded <?= (strpos(strtolower($mensaje),'error')!==false || strpos(strtolower($mensaje),'no se')!==false) ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <!-- Botón Agregar Pago -->
        <div class="mb-4">
            <a href="/ParroquiaPOO/Vista/agregar_pago.php" 
               class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Agregar Pago
            </a>
        </div>

        <table class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Certificado ID</th>
                    <th class="px-4 py-2 text-left">Valor</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Fecha pago</th>
                    <th class="px-4 py-2 text-left">Tipo Pago ID</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pagos)): ?>
                    <?php foreach ($pagos as $pago): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-2"><?= htmlspecialchars($pago['id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($pago['certificado_id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($pago['valor']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($pago['estado']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($pago['fecha_pago']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($pago['tipo_pago_id']) ?></td>
                            <td class="px-4 py-2 space-x-2 flex">
                                <a href="actualizar_pago.php?id=<?= htmlspecialchars($pago['id']) ?>" 
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-1 px-3 rounded">
                                   Actualizar
                                </a>

                                <!-- Eliminar con POST -->
                                <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este pago?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($pago['id']) ?>">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center px-4 py-2 text-red-500">No hay registros de pagos</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>


