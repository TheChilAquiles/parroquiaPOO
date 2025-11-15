<?php
// Vista de configuraciones del sistema
if (!isset($_SESSION['logged']) || $_SESSION['user-rol'] !== 'Administrador') {
    header('Location: ?route=login');
    exit;
}

$categoriasTitulos = [
    'parroquia' => 'Información de la Parroquia',
    'certificados' => 'Configuración de Certificados',
    'sistema' => 'Configuración del Sistema',
    'firmantes' => 'Firmantes de Certificados',
    'pagos' => 'Configuración de Pagos',
    'notificaciones' => 'Notificaciones'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuraciones del Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-cog text-blue-600 text-2xl mr-3"></i>
                    <h1 class="text-xl font-bold text-gray-800">Configuraciones del Sistema</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="?route=dashboard" class="text-gray-600 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="?route=admin/precios" class="text-gray-600 hover:text-blue-600">
                        <i class="fas fa-dollar-sign mr-2"></i>Precios
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulario de Configuraciones -->
        <form method="POST" action="?route=admin/configuraciones" class="space-y-6">
            <?php foreach ($configuraciones as $categoria => $configs): ?>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <?php
                        $iconos = [
                            'parroquia' => 'fa-church',
                            'certificados' => 'fa-certificate',
                            'sistema' => 'fa-server',
                            'firmantes' => 'fa-signature',
                            'pagos' => 'fa-credit-card',
                            'notificaciones' => 'fa-bell'
                        ];
                        ?>
                        <i class="fas <?= $iconos[$categoria] ?? 'fa-cog' ?> text-blue-600 mr-3"></i>
                        <?= $categoriasTitulos[$categoria] ?? ucfirst($categoria) ?>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($configs as $config): ?>
                            <?php if (!$config['editable']) continue; ?>

                            <div class="space-y-2">
                                <label for="config_<?= $config['clave'] ?>" class="block text-sm font-medium text-gray-700">
                                    <?= htmlspecialchars($config['descripcion'] ?: $config['clave']) ?>
                                </label>

                                <?php if ($config['tipo'] === 'booleano'): ?>
                                    <select name="config[<?= $config['clave'] ?>]"
                                            id="config_<?= $config['clave'] ?>"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="1" <?= $config['valor'] == '1' ? 'selected' : '' ?>>Sí / Activo</option>
                                        <option value="0" <?= $config['valor'] == '0' ? 'selected' : '' ?>>No / Inactivo</option>
                                    </select>

                                <?php elseif ($config['tipo'] === 'numero'): ?>
                                    <input type="number"
                                           name="config[<?= $config['clave'] ?>]"
                                           id="config_<?= $config['clave'] ?>"
                                           value="<?= htmlspecialchars($config['valor']) ?>"
                                           step="0.01"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                                <?php elseif ($config['clave'] === 'pago_gateway'): ?>
                                    <select name="config[<?= $config['clave'] ?>]"
                                            id="config_<?= $config['clave'] ?>"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <?php foreach ($proveedoresPago as $key => $proveedor): ?>
                                            <option value="<?= $key ?>" <?= $config['valor'] === $key ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($proveedor['name']) ?>
                                                (<?= $proveedor['status'] === 'available' ? 'Disponible' : 'No implementado' ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                <?php elseif ($config['clave'] === 'pago_modo'): ?>
                                    <select name="config[<?= $config['clave'] ?>]"
                                            id="config_<?= $config['clave'] ?>"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="sandbox" <?= $config['valor'] === 'sandbox' ? 'selected' : '' ?>>Pruebas (Sandbox)</option>
                                        <option value="production" <?= $config['valor'] === 'production' ? 'selected' : '' ?>>Producción</option>
                                    </select>

                                <?php else: ?>
                                    <input type="<?= $config['tipo'] === 'email' ? 'email' : 'text' ?>"
                                           name="config[<?= $config['clave'] ?>]"
                                           id="config_<?= $config['clave'] ?>"
                                           value="<?= htmlspecialchars($config['valor']) ?>"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <?php endif; ?>

                                <p class="text-xs text-gray-500">
                                    Tipo: <?= $config['tipo'] ?> | Última actualización: <?= date('d/m/Y H:i', strtotime($config['fecha_actualizacion'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4">
                <a href="?route=dashboard" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </button>
            </div>
        </form>

        <!-- Información adicional -->
        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Información Importante</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Para configurar las credenciales de la pasarela de pago (API keys), edite el archivo <code class="bg-yellow-100 px-1">.env</code></li>
                            <li>Los cambios en la configuración de pagos pueden requerir reiniciar el sistema</li>
                            <li>Asegúrese de tener una copia de seguridad antes de cambiar configuraciones críticas</li>
                            <li>Las configuraciones se guardan en la base de datos y pueden ser auditadas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Confirmación antes de enviar
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('¿Está seguro de que desea actualizar las configuraciones del sistema?')) {
                e.preventDefault();
            }
        });

        // Highlight de cambios
        document.querySelectorAll('input, select, textarea').forEach(element => {
            const originalValue = element.value;
            element.addEventListener('change', function() {
                if (this.value !== originalValue) {
                    this.classList.add('border-yellow-400', 'bg-yellow-50');
                } else {
                    this.classList.remove('border-yellow-400', 'bg-yellow-50');
                }
            });
        });
    </script>
</body>
</html>
