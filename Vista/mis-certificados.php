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
                <div class="border-2 rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($cert['tipo_certificado'] ?? 'Certificado', ENT_QUOTES, 'UTF-8') ?></h3>
                    <p>Para: <?= htmlspecialchars($cert['feligres_nombre'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></p>
                    <p>Fecha: <?= date('d/m/Y', strtotime($cert['fecha_solicitud'] ?? 'now')) ?></p>
                    <?php if (($cert['estado'] ?? '') === 'pendiente_pago'): ?>
                        <a href="?route=pagos/crear&certificado_id=<?= $cert['id'] ?>" class="btn">Pagar</a>
                    <?php elseif (in_array($cert['estado'] ?? '', ['generado', 'descargado'])): ?>
                        <a href="?route=certificados/descargar&id=<?= $cert['id'] ?>" class="btn">Descargar</a>
                    <?php endif; ?>
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
