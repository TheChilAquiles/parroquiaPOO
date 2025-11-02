<?php
// Vista/mis-certificados.php
include_once __DIR__ . '/componentes/plantillaTop.php';
?>

<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Mis Certificados</h1>
        <a href="?route=certificados/solicitar"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">
            + Nueva Solicitud
        </a>
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

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <?php if (empty($solicitudes)): ?>
            <div class="p-8 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-lg font-semibold mb-2">No hay solicitudes de certificados</p>
                <p class="mb-4">Aún no ha solicitado ningún certificado sacramental.</p>
                <a href="?route=certificados/solicitar"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md">
                    Solicitar Certificado
                </a>
            </div>
        <?php else: ?>
            <table id="tablaCertificados" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Feligrés
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Relación
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha Sacramento
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha Solicitud
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Vigencia
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($solicitudes as $certificado): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($certificado['feligres_nombre'], ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <?= htmlspecialchars($certificado['relacion'] ?? 'Propio', ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($certificado['tipo_certificado'], ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <?= date('d/m/Y', strtotime($certificado['fecha_sacramento'])) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $estadoClasses = [
                                    'pendiente_pago' => 'bg-yellow-100 text-yellow-800',
                                    'generado' => 'bg-green-100 text-green-800',
                                    'descargado' => 'bg-blue-100 text-blue-800',
                                    'expirado' => 'bg-red-100 text-red-800'
                                ];
                                $estadoTexto = [
                                    'pendiente_pago' => 'Pendiente Pago',
                                    'generado' => 'Listo para Descargar',
                                    'descargado' => 'Descargado',
                                    'expirado' => 'Expirado'
                                ];
                                $estado = $certificado['estado'];
                                $class = $estadoClasses[$estado] ?? 'bg-gray-100 text-gray-800';
                                $texto = $estadoTexto[$estado] ?? ucfirst($estado);
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $class ?>">
                                    <?= htmlspecialchars($texto, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y H:i', strtotime($certificado['fecha_solicitud'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if ($certificado['fecha_expiracion']): ?>
                                    <?= date('d/m/Y', strtotime($certificado['fecha_expiracion'])) ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <?php if ($certificado['estado'] === 'pendiente_pago'): ?>
                                    <a href="?route=pagos/pagar-certificado&id=<?= $certificado['id'] ?>"
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        Pagar
                                    </a>
                                <?php elseif (in_array($certificado['estado'], ['generado', 'descargado'])): ?>
                                    <?php if ($certificado['fecha_expiracion'] >= date('Y-m-d H:i:s')): ?>
                                        <a href="?route=certificados/descargar&id=<?= $certificado['id'] ?>"
                                           class="text-green-600 hover:text-green-900 mr-3">
                                            <svg class="inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Descargar
                                        </a>
                                    <?php else: ?>
                                        <span class="text-red-500">Expirado</span>
                                    <?php endif; ?>
                                <?php elseif ($certificado['estado'] === 'expirado'): ?>
                                    <span class="text-gray-400">Expirado</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php if (!empty($solicitudes)): ?>
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
            <h3 class="font-semibold text-blue-900 mb-2">Información sobre Estados:</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li><strong>Pendiente Pago:</strong> Debe proceder al pago para generar el certificado</li>
                <li><strong>Listo para Descargar:</strong> El certificado está generado y disponible para descarga</li>
                <li><strong>Descargado:</strong> Ya ha descargado este certificado al menos una vez</li>
                <li><strong>Expirado:</strong> Han pasado 30 días desde la generación. Debe solicitar uno nuevo</li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    <?php if (!empty($solicitudes)): ?>
    $('#tablaCertificados').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[5, 'desc']], // Ordenar por fecha de solicitud descendente
        pageLength: 10,
        responsive: true
    });
    <?php endif; ?>
});
</script>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
