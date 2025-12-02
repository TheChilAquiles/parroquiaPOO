<?php
/**
 * Vista/reportes_feligreses.php - Reporte de Feligreses
 */
require_once __DIR__ . '/../helpers.php';

$porTipoDoc = $porTipoDoc ?? [];
$masActivos = $masActivos ?? [];
$nuevos = $nuevos ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Feligreses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="index.php?route=reportes" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← Volver al menú de reportes
            </a>
            <h1 class="text-4xl font-bold text-gray-800">👥 Reporte de Feligreses</h1>
        </div>

        <!-- Feligreses por Tipo de Documento -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Distribución por Tipo de Documento</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Tipo Documento</th>
                        <th class="px-4 py-2 text-right">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porTipoDoc as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['tipo_documento'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right font-semibold"><?= number_format($row['total'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Feligreses Más Activos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Top 10 Feligreses Más Activos</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Documento</th>
                        <th class="px-4 py-2 text-right">Certificados Solicitados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($masActivos as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['nombre_completo'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['numero_documento'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right font-semibold"><?= number_format($row['total_certificados'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Nuevos Feligreses -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Nuevos Feligreses (Últimos 30 días)</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Documento</th>
                        <th class="px-4 py-2 text-left">Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($nuevos as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['nombre_completo'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['numero_documento'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2"><?= formatearFecha($row['fecha_registro'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Botones de Exportación -->
        <div class="flex gap-4">
            <a href="index.php?route=reportes/exportarCSV&tipo=feligreses" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-file-excel mr-2"></i>Exportar Excel
            </a>
            <a href="index.php?route=reportes/exportarPDF&tipo=feligreses" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
            </a>
        </div>
    </div>
</body>
</html>
