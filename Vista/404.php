<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#F8EDE3]">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
            <p class="text-2xl text-gray-700 mb-6">Página no encontrada</p>
            <p class="text-gray-600 mb-8">
                La ruta "<strong><?php echo htmlspecialchars($_GET['route'] ?? 'desconocida', ENT_QUOTES, 'UTF-8'); ?></strong>" no existe
            </p>
            <a href="<?= url('inicio') ?>"
                class="px-6 py-3 bg-[#DFD3C3] text-gray-900 font-semibold rounded hover:bg-[#D0C3B3] transition-colors">
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
