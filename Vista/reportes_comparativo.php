<?php
/**
 * Vista/reportes_comparativo.php - Reportes Comparativos
 */
require_once __DIR__ . '/../helpers.php';

$comparativo = $comparativo ?? [];
$anio1 = $_GET['anio1'] ?? date('Y') - 1;
$anio2 = $_GET['anio2'] ?? date('Y');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Comparativos</title>
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
                <h1 class="text-4xl font-bold text-gray-900">📊 Análisis Comparativo</h1>
                <p class="text-gray-600 mt-2">Comparación año vs año: <?= $anio1 ?> vs <?= $anio2 ?></p>
            </div>

            <!-- Selector de Años -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Seleccionar Años a Comparar</h3>
                <form method="GET" action="index.php" class="flex gap-4">
                    <input type="hidden" name="route" value="reportes/reporteComparativo">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Año 1</label>
                        <input type="number" name="anio1" value="<?= $anio1 ?>" class="px-4 py-2 border border-gray-300 rounded-lg" min="2000" max="<?= date('Y') ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Año 2</label>
                        <input type="number" name="anio2" value="<?= $anio2 ?>" class="px-4 py-2 border border-gray-300 rounded-lg" min="2000" max="<?= date('Y') ?>">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Comparar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Comparación de Certificados -->
            <?php if (isset($comparativo['certificados'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📜 Certificados</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Año <?= $anio1 ?></p>
                        <p class="text-3xl font-bold text-blue-600"><?= number_format($comparativo['certificados'][$anio1] ?? 0) ?></p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Año <?= $anio2 ?></p>
                        <p class="text-3xl font-bold text-green-600"><?= number_format($comparativo['certificados'][$anio2] ?? 0) ?></p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Crecimiento</p>
                        <p class="text-3xl font-bold <?= ($comparativo['certificados']['crecimiento'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= number_format($comparativo['certificados']['crecimiento'] ?? 0, 2) ?>%
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Tendencia: <?= ucfirst($comparativo['certificados']['tendencia'] ?? 'estable') ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Comparación de Ingresos -->
            <?php if (isset($comparativo['ingresos'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">💰 Ingresos</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Año <?= $anio1 ?></p>
                        <p class="text-3xl font-bold text-blue-600">$<?= number_format($comparativo['ingresos'][$anio1] ?? 0) ?></p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Año <?= $anio2 ?></p>
                        <p class="text-3xl font-bold text-green-600">$<?= number_format($comparativo['ingresos'][$anio2] ?? 0) ?></p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Crecimiento</p>
                        <p class="text-3xl font-bold <?= ($comparativo['ingresos']['crecimiento'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= number_format($comparativo['ingresos']['crecimiento'] ?? 0, 2) ?>%
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Tendencia: <?= ucfirst($comparativo['ingresos']['tendencia'] ?? 'estable') ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Botones de Exportación -->
            <div class="mt-6 flex gap-4">
                <a href="index.php?route=reportes/exportarCSV&tipo=comparativo&anio1=<?= $anio1 ?>&anio2=<?= $anio2 ?>" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </a>
                <a href="index.php?route=reportes/exportarPDF&tipo=comparativo&anio1=<?= $anio1 ?>&anio2=<?= $anio2 ?>" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </a>
            </div>
        </div>
    </div>
</body>
</html>
