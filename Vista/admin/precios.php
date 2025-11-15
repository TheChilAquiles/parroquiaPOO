<?php
if (!isset($_SESSION['logged']) || $_SESSION['user-rol'] !== 'Administrador') {
    header('Location: ?route=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Precios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-dollar-sign text-green-600 text-2xl mr-3"></i>
                    <h1 class="text-xl font-bold text-gray-800">Gestión de Precios de Certificados</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="?route=dashboard" class="text-gray-600 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="?route=admin/configuraciones" class="text-gray-600 hover:text-blue-600">
                        <i class="fas fa-cog mr-2"></i>Configuraciones
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulario de Precios -->
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form method="POST" action="?route=admin/precios">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    $tiposCertificados = [
                        'bautismo' => ['nombre' => 'Bautismo', 'icono' => 'fa-cross', 'color' => 'blue'],
                        'confirmacion' => ['nombre' => 'Confirmación', 'icono' => 'fa-dove', 'color' => 'orange'],
                        'matrimonio' => ['nombre' => 'Matrimonio', 'icono' => 'fa-rings-wedding', 'color' => 'pink'],
                        'defuncion' => ['nombre' => 'Defunción', 'icono' => 'fa-cross', 'color' => 'gray'],
                        'general' => ['nombre' => 'General / Otros', 'icono' => 'fa-certificate', 'color' => 'indigo']
                    ];

                    foreach ($precios as $config):
                        $tipo = str_replace('cert_precio_', '', $config['clave']);
                        if (!isset($tiposCertificados[$tipo])) continue;
                        $info = $tiposCertificados[$tipo];
                    ?>
                        <div class="border-2 border-<?= $info['color'] ?>-200 rounded-lg p-6 hover:shadow-lg transition">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-<?= $info['color'] ?>-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas <?= $info['icono'] ?> text-<?= $info['color'] ?>-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800"><?= $info['nombre'] ?></h3>
                                    <p class="text-sm text-gray-500">Certificado de <?= $info['nombre'] ?></p>
                                </div>
                            </div>

                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500 font-bold">$</span>
                                <input type="number"
                                       name="precio[<?= $tipo ?>]"
                                       id="precio_<?= $tipo ?>"
                                       value="<?= htmlspecialchars($config['valor']) ?>"
                                       min="0"
                                       step="100"
                                       required
                                       class="pl-8 w-full text-2xl font-bold text-gray-800 border-2 border-gray-300 rounded-lg py-3 focus:border-<?= $info['color'] ?>-500 focus:ring-<?= $info['color'] ?>-500">
                                <span class="absolute right-3 top-3 text-gray-500">COP</span>
                            </div>

                            <p class="mt-2 text-xs text-gray-500">
                                Última actualización: <?= date('d/m/Y', strtotime($config['fecha_actualizacion'])) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- IVA Configuration -->
                <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-percent mr-2"></i>Configuración de IVA
                    </h3>
                    <?php
                    $configIVA = array_filter($precios, fn($c) => in_array($c['clave'], ['cert_aplicar_iva', 'cert_iva_porcentaje']));
                    foreach ($configIVA as $config):
                    ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?= htmlspecialchars($config['descripcion']) ?>
                            </label>
                            <?php if ($config['tipo'] === 'booleano'): ?>
                                <select name="precio[<?= str_replace('cert_precio_', '', $config['clave']) ?>]"
                                        class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="1" <?= $config['valor'] == '1' ? 'selected' : '' ?>>Sí, aplicar IVA</option>
                                    <option value="0" <?= $config['valor'] == '0' ? 'selected' : '' ?>>No aplicar IVA</option>
                                </select>
                            <?php else: ?>
                                <input type="number" name="precio[<?= str_replace('cert_precio_', '', $config['clave']) ?>]"
                                       value="<?= htmlspecialchars($config['valor']) ?>" min="0" max="100" step="1"
                                       class="w-full rounded-md border-gray-300 shadow-sm">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Botones -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="?route=dashboard" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-save mr-2"></i>Guardar Precios
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <i class="fas fa-info-circle text-blue-400 text-xl mr-3"></i>
                <div class="text-sm text-blue-700">
                    <p class="font-bold mb-1">Información sobre precios</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Los precios se aplican automáticamente al generar certificados</li>
                        <li>Todos los precios están en Pesos Colombianos (COP)</li>
                        <li>El precio "General" se usa para certificados sin tipo específico</li>
                        <li>Los cambios se reflejan inmediatamente en todo el sistema</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('¿Está seguro de que desea actualizar los precios de los certificados?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
