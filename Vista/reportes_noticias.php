<?php
/**
 * Vista/reportes_noticias.php - Reportes de Noticias
 */
require_once __DIR__ . '/../helpers.php';

$porPeriodo = $porPeriodo ?? [];
$autoresActivos = $autoresActivos ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Noticias</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="index.php?route=reportes" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                    ← Volver a Reportes
                </a>
                <h1 class="text-4xl font-bold text-gray-900">📰 Reportes de Noticias</h1>
                <p class="text-gray-600 mt-2">Análisis de publicaciones y autores activos</p>
            </div>

            <!-- Noticias por Período -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Publicaciones por Período</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Noticias</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($porPeriodo)): ?>
                                <?php foreach ($porPeriodo as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= date('d/m/Y', strtotime($fila['fecha'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="font-bold text-blue-600 text-lg"><?= htmlspecialchars($fila['total_noticias']) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">No hay datos disponibles</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Autores Más Activos -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Autores Más Activos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Autor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Noticias</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Última Publicación</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($autoresActivos)): ?>
                                <?php foreach ($autoresActivos as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($fila['autor']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-semibold">
                                                <?= htmlspecialchars($fila['total_noticias']) ?> noticias
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('d/m/Y', strtotime($fila['ultima_publicacion'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay datos disponibles</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Botones de Exportación -->
            <div class="mt-6 flex gap-4">
                <a href="index.php?route=reportes/exportarCSV&tipo=noticias" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </a>
                <a href="index.php?route=reportes/exportarPDF&tipo=noticias" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </a>
            </div>
        </div>
    </div>
</body>
</html>
