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

<main class="max-w-7xl mx-auto p-4 md:p-8 w-full">


    <!-- Mensajes -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-emerald-800 font-medium"><?= htmlspecialchars($_SESSION['success']) ?></span>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-red-800 font-medium"><?= htmlspecialchars($_SESSION['error']) ?></span>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Formulario -->
    <form method="POST" action="<?= url('admin/configuraciones') ?>" class="space-y-6">
        
        <?php foreach ($configuraciones as $categoria => $configs): ?>
            <section class="rounded-3xl bg-white shadow-lg border border-stone-200 overflow-hidden">
                <div class="border-b border-stone-200 bg-[#F9F5F3] px-6 py-6 md:px-8 md:py-6">
                    <h2 class="text-xl font-bold text-[#5A4D41] flex items-center gap-3">
                        <?php
                        $iconos = [
                            'parroquia' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>',
                            'certificados' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>',
                            'sistema' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>',
                            'firmantes' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>',
                            'pagos' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>',
                            'notificaciones' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>'
                        ];
                        echo $iconos[$categoria] ?? '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>';
                        ?>
                        <span class="text-[#8D7B68]"><?= $categoriasTitulos[$categoria] ?? ucfirst($categoria) ?></span>
                    </h2>
                </div>

                <div class="p-6 md:p-8 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($configs as $config): ?>
                            <?php if (!$config['editable']) continue; ?>

                            <div class="space-y-3">
                                <label for="config_<?= $config['clave'] ?>" class="block text-lg font-bold text-[#5A4D41]">
                                    <?= htmlspecialchars($config['descripcion'] ?: $config['clave']) ?>
                                </label>

                                <?php if ($config['tipo'] === 'booleano'): ?>
                                    <div class="relative">
                                        <select name="config[<?= $config['clave'] ?>]"
                                                id="config_<?= $config['clave'] ?>"
                                                class="w-full appearance-none rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 shadow-sm transition-all focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC] cursor-pointer">
                                            <option value="1" <?= $config['valor'] == '1' ? 'selected' : '' ?>>Sí / Activo</option>
                                            <option value="0" <?= $config['valor'] == '0' ? 'selected' : '' ?>>No / Inactivo</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-[#8D7B68]">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>

                                <?php elseif ($config['tipo'] === 'numero'): ?>
                                    <input type="number"
                                           name="config[<?= $config['clave'] ?>]"
                                           id="config_<?= $config['clave'] ?>"
                                           value="<?= htmlspecialchars($config['valor']) ?>"
                                           step="0.01"
                                           class="w-full rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 placeholder-gray-400 shadow-sm focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC]">

                                <?php elseif ($config['clave'] === 'pago_gateway'): ?>
                                    <div class="relative">
                                        <select name="config[<?= $config['clave'] ?>]"
                                                id="config_<?= $config['clave'] ?>"
                                                class="w-full appearance-none rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 shadow-sm transition-all focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC] cursor-pointer">
                                            <?php foreach ($proveedoresPago as $key => $proveedor): ?>
                                                <option value="<?= $key ?>" <?= $config['valor'] === $key ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($proveedor['name']) ?>
                                                    (<?= $proveedor['status'] === 'available' ? 'Disponible' : 'No implementado' ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-[#8D7B68]">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>

                                <?php elseif ($config['clave'] === 'pago_modo'): ?>
                                    <div class="relative">
                                        <select name="config[<?= $config['clave'] ?>]"
                                                id="config_<?= $config['clave'] ?>"
                                                class="w-full appearance-none rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 shadow-sm transition-all focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC] cursor-pointer">
                                            <option value="sandbox" <?= $config['valor'] === 'sandbox' ? 'selected' : '' ?>>Pruebas (Sandbox)</option>
                                            <option value="production" <?= $config['valor'] === 'production' ? 'selected' : '' ?>>Producción</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-[#8D7B68]">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>

                                <?php else: ?>
                                    <input type="<?= $config['tipo'] === 'email' ? 'email' : 'text' ?>"
                                           name="config[<?= $config['clave'] ?>]"
                                           id="config_<?= $config['clave'] ?>"
                                           value="<?= htmlspecialchars($config['valor']) ?>"
                                           class="w-full rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 placeholder-gray-400 shadow-sm focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC]">
                                <?php endif; ?>

                                <p class="text-sm text-gray-500 font-medium flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Actualizado: <?= date('d/m/Y H:i', strtotime($config['fecha_actualizacion'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>

        <!-- Botones de acción -->
        <div class="flex flex-col-reverse md:flex-row items-center justify-between gap-6 pt-6 rounded-3xl bg-white p-6 md:p-8 shadow-lg border border-stone-200">
            <a href="<?= url('dashboard') ?>" class="w-full md:w-auto inline-flex items-center justify-center space-x-3 rounded-xl border border-stone-300 bg-white px-8 py-4 text-base font-bold text-gray-700 shadow-sm transition-all hover:bg-[#F9F5F3] hover:border-[#8D7B68] hover:scale-[1.02] focus:ring-4 focus:ring-[#E6D5CC]">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Cancelar</span>
            </a>

            <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center space-x-3 rounded-xl bg-gradient-to-r from-[#D0B8A8] to-[#8D7B68] px-10 py-5 text-lg font-bold text-white shadow-lg transition-transform hover:from-[#C8B6A6] hover:to-[#7a6a58] hover:scale-[1.02] active:scale-95 focus:ring-4 focus:ring-[#E6D5CC]">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                <span>GUARDAR CAMBIOS</span>
            </button>
        </div>

    </form>

    <!-- Info Footer -->
    <section class="mt-8 rounded-3xl bg-gradient-to-r from-[#8D7B68] to-[#6b5d4f] p-8 shadow-xl">
        <div class="flex flex-col md:flex-row items-start gap-6">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 flex-shrink-0">
                <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-bold text-white">Información Importante</h3>
                <p class="text-lg text-[#F4EBE7] leading-relaxed font-medium">
                    Las configuraciones se guardan en la base de datos. Para configurar credenciales sensibles (como API keys de pasarelas de pago), por seguridad se recomienda editar el archivo <code class="bg-white/10 px-2 py-1 rounded text-white font-mono">.env</code> en el servidor.
                </p>
            </div>
        </div>
    </section>

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
                this.classList.add('border-yellow-400', 'ring-2', 'ring-yellow-200');
                this.classList.remove('border-stone-300');
            } else {
                this.classList.remove('border-yellow-400', 'ring-2', 'ring-yellow-200');
                this.classList.add('border-stone-300');
            }
        });
    });
</script>

</body>
</html>
