<?php


$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new PagosController();
    $response = $controller->crearPago($_POST);

    $mensaje = $response["message"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Registrar Pago</h2>

        <?php if (!empty($mensaje)) : ?>
            <p class="text-center font-semibold text-green-600 mb-4"><?= $mensaje ?></p>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label for="certificado_id" class="block text-sm font-medium text-gray-700">ID Certificado:</label>
                <input type="number" name="certificado_id" required 
                    class="mt-1 w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
            </div>

            <div>
                <label for="valor" class="block text-sm font-medium text-gray-700">Valor:</label>
                <input type="number" step="0.01" name="valor" required 
                    class="mt-1 w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
            </div>

            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700">Estado:</label>
                <select name="estado" required 
                    class="mt-1 w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    <option value="pendiente">Pendiente</option>
                    <option value="pagado">Pagado</option>
                    <option value="fallido">Fallido</option>
                </select>
            </div>

            <div>
                <label for="metodo_de_pago" class="block text-sm font-medium text-gray-700">Método de Pago:</label>
                <select name="metodo_de_pago" required 
                    class="mt-1 w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    <option value="tarjeta">Tarjeta</option>
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>

            <button type="submit" 
                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition">
                Guardar Pago
            </button>
        </form>
    </div>
</body>
</html>
