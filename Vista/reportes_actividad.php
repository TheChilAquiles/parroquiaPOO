<?php
/**
 * Vista/reportes_actividad.php - Reporte de Actividad del Sistema
 */
require_once __DIR__ . '/../helpers.php';

$usuariosActivos = $usuariosActivos ?? [];
$actividadRol = $actividadRol ?? [];
$tasaConversion = $tasaConversion ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Actividad del Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="index.php?route=reportes" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← Volver al menú de reportes
            </a>
            <h1 class="text-4xl font-bold text-gray-800">📈 Reporte de Actividad del Sistema</h1>
        </div>

        <!-- Usuarios Más Activos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Top 10 Usuarios Más Activos</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Usuario</th>
                        <th class="px-4 py-2 text-left">Rol</th>
                        <th class="px-4 py-2 text-right">Certificados Generados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuariosActivos as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['nombre_usuario'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['rol'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right font-semibold"><?= number_format($row['total_certificados'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Actividad por Rol -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Actividad por Rol</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Rol</th>
                        <th class="px-4 py-2 text-right">Usuarios</th>
                        <th class="px-4 py-2 text-right">Certificados Generados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actividadRol as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['rol'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right"><?= number_format($row['total_usuarios'] ?? 0) ?></td>
                            <td class="px-4 py-2 text-right font-semibold"><?= number_format($row['total_certificados'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Tasa de Conversión -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Tasa de Conversión (Solicitudes → Certificados)</h2>
            <?php if (!empty($tasaConversion)): ?>
                <?php $tasa = $tasaConversion[0] ?? []; ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <div class="text-gray-600 mb-2">Solicitudes Totales</div>
                        <div class="text-3xl font-bold text-blue-600"><?= number_format($tasa['total_solicitudes'] ?? 0) ?></div>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg">
                        <div class="text-gray-600 mb-2">Certificados Completados</div>
                        <div class="text-3xl font-bold text-green-600"><?= number_format($tasa['certificados_completados'] ?? 0) ?></div>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <div class="text-gray-600 mb-2">Tasa de Conversión</div>
                        <div class="text-3xl font-bold text-purple-600"><?= number_format($tasa['tasa_conversion'] ?? 0, 1) ?>%</div>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-gray-500">No hay datos disponibles</p>
            <?php endif; ?>
        </div>

        <!-- Botones de Exportación -->
        <div class="flex gap-4">
            <a href="index.php?route=reportes/exportarCSV&tipo=actividad" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-file-excel mr-2"></i>Exportar Excel
            </a>
            <a href="index.php?route=reportes/exportarPDF&tipo=actividad" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
            </a>
        </div>
    </div>
</body>
</html>
