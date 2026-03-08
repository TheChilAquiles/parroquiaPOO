<?php

/**
 * Vista/reportes_certificados.php - Reporte de Certificados
 */
require_once __DIR__ . '/../helpers.php';

$porTipo = $porTipo ?? [];
$porEstado = $porEstado ?? [];
$tiempos = $tiempos ?? [];
$masSolicitados = $masSolicitados ?? [];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Certificados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="index.php?route=reportes" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← Volver al menú de reportes
            </a>
            <h1 class="text-4xl font-bold text-gray-800">📜 Reporte de Certificados</h1>
        </div>

        <!-- Certificados por Tipo -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Certificados por Tipo</h2>
            <table id="tabla-tipos" class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Tipo</th>
                        <th class="px-4 py-2 text-right">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porTipo as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['tipo_certificado'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right font-semibold"><?= number_format($row['total'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Certificados por Estado -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Certificados por Estado</h2>
            <table id="tabla-estados" class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-right">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porEstado as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['estado'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right font-semibold"><?= number_format($row['total'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Tiempos de Procesamiento -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Tiempos de Procesamiento</h2>
            <table id="tabla-tiempos" class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Tipo</th>
                        <th class="px-4 py-2 text-right">Tiempo Promedio (días)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tiempos as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['tipo_certificado'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right font-semibold"><?= number_format($row['promedio_dias'] ?? 0, 1) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Certificados Más Solicitados -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Top 10 Certificados Más Solicitados</h2>
            <table id="tabla-top" class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Tipo</th>
                        <th class="px-4 py-2 text-right">Solicitudes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($masSolicitados as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['tipo_certificado'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right font-semibold"><?= number_format($row['total_solicitudes'] ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Botones de Exportación -->
        <div class="flex gap-4">
            <button onclick="exportarExcelTodo()" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-file-excel mr-2"></i>Exportar Excel
            </button>
            <a href="index.php?route=reportes/exportarPDF&tipo=certificados" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
            </a>
        </div>
    </div>


    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    
    <script>
        function exportarExcelTodo() {
            // 1. Crear un nuevo libro de Excel en blanco
            var wb = XLSX.utils.book_new();
            
            // 2. Extraer cada tabla y crear una hoja diferente
            var ws1 = XLSX.utils.table_to_sheet(document.getElementById('tabla-tipos'));
            XLSX.utils.book_append_sheet(wb, ws1, "Por Tipo");
            
            var ws2 = XLSX.utils.table_to_sheet(document.getElementById('tabla-estados'));
            XLSX.utils.book_append_sheet(wb, ws2, "Por Estado");
            
            var ws3 = XLSX.utils.table_to_sheet(document.getElementById('tabla-tiempos'));
            XLSX.utils.book_append_sheet(wb, ws3, "Tiempos");
            
            var ws4 = XLSX.utils.table_to_sheet(document.getElementById('tabla-top'));
            XLSX.utils.book_append_sheet(wb, ws4, "Top Solicitados");
            
            // 3. Descargar el archivo mágico
            XLSX.writeFile(wb, 'Reporte_Certificados_<?= date('Y-m-d') ?>.xlsx');
        }
    </script>
</body>

</html>