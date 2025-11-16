<?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Mis Certificados</h1>
        <p class="text-gray-600">Ver certificados solicitados, realizar pagos y descargar documentos.</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['info'])): ?>
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_SESSION['info'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['info']); ?>
    <?php endif; ?>

    <?php if (!empty($misCertificados)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($misCertificados as $cert): ?>
                <?php
                    // Determinar color según estado
                    $estadoBadge = 'bg-gray-100 text-gray-800';
                    $borderColor = 'border-gray-300';
                    if ($cert['estado'] === 'pendiente_pago') {
                        $estadoBadge = 'bg-yellow-100 text-yellow-800';
                        $borderColor = 'border-yellow-300';
                    } elseif ($cert['estado'] === 'generado') {
                        $estadoBadge = 'bg-green-100 text-green-800';
                        $borderColor = 'border-green-300';
                    } elseif ($cert['estado'] === 'descargado') {
                        $estadoBadge = 'bg-blue-100 text-blue-800';
                        $borderColor = 'border-blue-300';
                    }
                ?>
                <div class="bg-white border-2 <?= $borderColor ?> rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Header del card -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4">
                        <h3 class="text-xl font-bold mb-1">
                            <?= htmlspecialchars($cert['tipo_certificado'] ?? 'Certificado', ENT_QUOTES, 'UTF-8') ?>
                        </h3>
                        <p class="text-blue-100 text-sm">ID: #<?= str_pad($cert['id'], 6, '0', STR_PAD_LEFT) ?></p>
                    </div>

                    <!-- Body del card -->
                    <div class="p-4 space-y-3">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500">Para:</p>
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($cert['feligres_nombre'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500">Fecha de solicitud:</p>
                                <p class="font-medium text-gray-800"><?= date('d/m/Y', strtotime($cert['fecha_solicitud'] ?? 'now')) ?></p>
                            </div>
                        </div>

                        <?php if (!empty($cert['relacion'])): ?>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500">Parentesco:</p>
                                <p class="font-medium text-gray-800"><?= htmlspecialchars($cert['relacion'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="pt-2">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $estadoBadge ?>">
                                <?= ucfirst(str_replace('_', ' ', $cert['estado'] ?? 'Desconocido')) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Footer del card con botones -->
                    <div class="border-t border-gray-200 p-4 bg-gray-50">
                        <?php if (($cert['estado'] ?? '') === 'pendiente_pago'): ?>
                            <a href="<?= url('pagos/mis-pagos') ?>"
                               class="block w-full text-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                💳 Realizar Pago
                            </a>
                        <?php elseif (in_array($cert['estado'] ?? '', ['generado', 'descargado'])): ?>
                            <a href="<?= url('certificados/descargar', ['id' => $cert['id']]) ?>"
                               class="block w-full text-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                📥 Descargar PDF
                            </a>
                        <?php else: ?>
                            <div class="text-center text-gray-500 text-sm">En proceso...</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900">No tienes certificados</h3>
            <p class="mt-2 text-gray-500">Puedes solicitar certificados desde la sección "Mis Sacramentos"</p>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
