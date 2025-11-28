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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<main class="min-h-screen relative bg-gradient-to-br from-[#D0B8A8] via-[#b5a394] to-[#ab876f] overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-black/20 fixed"></div>
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-[#8D7B68] rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob fixed"></div>
    <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-[#C8B6A6] rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000 fixed"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white/30 backdrop-blur-md border border-white/20 shadow-xl rounded-2xl p-6">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="p-3 bg-white/20 rounded-full mr-4">
                    <span class="material-icons text-white text-2xl">settings</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Configuraciones</h1>
                    <p class="text-white/80">Administración del sistema</p>
                </div>
            </div>
            <a href="<?= url('dashboard') ?>" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl border border-white/40 shadow-sm backdrop-blur-sm transition-all flex items-center gap-2">
                <span class="material-icons text-sm">arrow_back</span>
                Volver al Dashboard
            </a>
        </div>

        <!-- Mensajes -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500/20 border border-green-500/50 text-white px-4 py-3 rounded-xl relative mb-6 backdrop-blur-sm flex items-center gap-2 animate-fade-in-down">
                <span class="material-icons">check_circle</span>
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-white px-4 py-3 rounded-xl relative mb-6 backdrop-blur-sm flex items-center gap-2 animate-fade-in-down">
                <span class="material-icons">error</span>
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulario -->
        <form method="POST" action="<?= url('admin/configuraciones') ?>" class="space-y-8">
            
            <?php foreach ($configuraciones as $categoria => $configs): ?>
                <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-xl rounded-2xl p-6 md:p-8">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center border-b border-white/20 pb-4">
                        <?php
                        $iconos = [
                            'parroquia' => 'church',
                            'certificados' => 'verified',
                            'sistema' => 'dns',
                            'firmantes' => 'draw',
                            'pagos' => 'payments',
                            'notificaciones' => 'notifications'
                        ];
                        ?>
                        <span class="material-icons mr-3 text-white/90"><?= $iconos[$categoria] ?? 'settings' ?></span>
                        <?= $categoriasTitulos[$categoria] ?? ucfirst($categoria) ?>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($configs as $config): ?>
                            <?php if (!$config['editable']) continue; ?>

                            <div class="space-y-2 group">
                                <label for="config_<?= $config['clave'] ?>" class="block text-sm font-medium text-white ml-1 group-hover:text-white/90 transition-colors">
                                    <?= htmlspecialchars($config['descripcion'] ?: $config['clave']) ?>
                                </label>

                                <?php if ($config['tipo'] === 'booleano'): ?>
                                    <div class="relative">
                                        <select name="config[<?= $config['clave'] ?>]"
                                                id="config_<?= $config['clave'] ?>"
                                                class="block w-full pl-4 pr-10 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all backdrop-blur-sm appearance-none cursor-pointer hover:bg-white/20">
                                            <option value="1" <?= $config['valor'] == '1' ? 'selected' : '' ?> class="text-gray-800">Sí / Activo</option>
                                            <option value="0" <?= $config['valor'] == '0' ? 'selected' : '' ?> class="text-gray-800">No / Inactivo</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
                                            <span class="material-icons">expand_more</span>
                                        </div>
                                    </div>

                                <?php elseif ($config['tipo'] === 'numero'): ?>
                                    <input type="number"
                                           name="config[<?= $config['clave'] ?>]"
                                           id="config_<?= $config['clave'] ?>"
                                           value="<?= htmlspecialchars($config['valor']) ?>"
                                           step="0.01"
                                           class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all backdrop-blur-sm hover:bg-white/20">

                                <?php elseif ($config['clave'] === 'pago_gateway'): ?>
                                    <div class="relative">
                                        <select name="config[<?= $config['clave'] ?>]"
                                                id="config_<?= $config['clave'] ?>"
                                                class="block w-full pl-4 pr-10 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all backdrop-blur-sm appearance-none cursor-pointer hover:bg-white/20">
                                            <?php foreach ($proveedoresPago as $key => $proveedor): ?>
                                                <option value="<?= $key ?>" <?= $config['valor'] === $key ? 'selected' : '' ?> class="text-gray-800">
                                                    <?= htmlspecialchars($proveedor['name']) ?>
                                                    (<?= $proveedor['status'] === 'available' ? 'Disponible' : 'No implementado' ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
                                            <span class="material-icons">expand_more</span>
                                        </div>
                                    </div>

                                <?php elseif ($config['clave'] === 'pago_modo'): ?>
                                    <div class="relative">
                                        <select name="config[<?= $config['clave'] ?>]"
                                                id="config_<?= $config['clave'] ?>"
                                                class="block w-full pl-4 pr-10 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all backdrop-blur-sm appearance-none cursor-pointer hover:bg-white/20">
                                            <option value="sandbox" <?= $config['valor'] === 'sandbox' ? 'selected' : '' ?> class="text-gray-800">Pruebas (Sandbox)</option>
                                            <option value="production" <?= $config['valor'] === 'production' ? 'selected' : '' ?> class="text-gray-800">Producción</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
                                            <span class="material-icons">expand_more</span>
                                        </div>
                                    </div>

                                <?php else: ?>
                                    <input type="<?= $config['tipo'] === 'email' ? 'email' : 'text' ?>"
                                           name="config[<?= $config['clave'] ?>]"
                                           id="config_<?= $config['clave'] ?>"
                                           value="<?= htmlspecialchars($config['valor']) ?>"
                                           class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all backdrop-blur-sm hover:bg-white/20">
                                <?php endif; ?>

                                <p class="text-xs text-white/60 flex items-center gap-1">
                                    <span class="material-icons text-[12px]">schedule</span>
                                    Actualizado: <?= date('d/m/Y H:i', strtotime($config['fecha_actualizacion'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4 sticky bottom-6 z-20">
                <div class="bg-white/30 backdrop-blur-xl border border-white/20 shadow-2xl rounded-2xl p-2 flex space-x-4">
                    <a href="<?= url('dashboard') ?>" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all flex items-center gap-2 border border-white/10">
                        <span class="material-icons text-sm">close</span>
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-3 bg-white/30 hover:bg-white/40 text-white font-bold rounded-xl border border-white/40 shadow-lg backdrop-blur-sm transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center gap-2">
                        <span class="material-icons text-sm">save</span>
                        Guardar Cambios
                    </button>
                </div>
            </div>

        </form>

        <!-- Información adicional -->
        <div class="mt-8 bg-blue-500/20 border border-blue-500/30 p-6 rounded-2xl backdrop-blur-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="material-icons text-blue-200">info</span>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-100">Información Importante</h3>
                    <div class="mt-2 text-sm text-blue-50/80">
                        <p>Las configuraciones se guardan en la base de datos. Para configurar credenciales sensibles (como API keys de pasarelas de pago), por seguridad se recomienda editar el archivo <code class="bg-blue-900/30 px-2 py-0.5 rounded text-blue-100">.env</code> en el servidor.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

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
                this.classList.add('border-yellow-400', 'bg-yellow-500/20');
                this.classList.remove('border-white/20', 'bg-white/10');
            } else {
                this.classList.remove('border-yellow-400', 'bg-yellow-500/20');
                this.classList.add('border-white/20', 'bg-white/10');
            }
        });
    });
</script>

</body>
</html>
