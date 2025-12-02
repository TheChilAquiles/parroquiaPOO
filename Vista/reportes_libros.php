<?php
/**
 * Vista/reportes_libros.php - Reportes de Libros Parroquiales
 */
require_once __DIR__ . '/../helpers.php';

$porTipo = $porTipo ?? [];
$capacidad = $capacidad ?? [];
$activos = $activos ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Libros Parroquiales</title>
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
                <h1 class="text-4xl font-bold text-gray-900">📚 Reportes de Libros Parroquiales</h1>
                <p class="text-gray-600 mt-2">Análisis de distribución, capacidad y estado de los libros</p>
            </div>

            <!-- Libros por Tipo -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Distribución por Tipo</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo de Libro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Libros</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Libros Activos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($porTipo)): ?>
                                <?php foreach ($porTipo as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($fila['tipo_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['total_libros']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['libros_activos']) ?>
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

            <!-- Análisis de Capacidad -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Análisis de Capacidad</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actas Usadas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acta Inicial</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acta Final</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($capacidad)): ?>
                                <?php foreach ($capacidad as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($fila['tipo_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['numero_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="font-semibold text-blue-600"><?= htmlspecialchars($fila['actas_usadas']) ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['acta_inicial'] ?? '-') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['acta_final'] ?? '-') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay datos disponibles</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Libros Activos -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Libros Activos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Registros</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Último Registro</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($activos)): ?>
                                <?php foreach ($activos as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($fila['tipo_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['numero_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['total_registros']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $fila['ultimo_registro'] ? date('d/m/Y', strtotime($fila['ultimo_registro'])) : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay datos disponibles</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
<?php
/**
 * Vista/reportes_libros.php - Reportes de Libros Parroquiales
 */
require_once __DIR__ . '/../helpers.php';

$porTipo = $porTipo ?? [];
$capacidad = $capacidad ?? [];
$activos = $activos ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Libros Parroquiales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="index.php?route=reportes" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                    ← Volver a Reportes
                </a>
                <h1 class="text-4xl font-bold text-gray-900">📚 Reportes de Libros Parroquiales</h1>
                <p class="text-gray-600 mt-2">Análisis de distribución, capacidad y estado de los libros</p>
            </div>

            <!-- Libros por Tipo -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Distribución por Tipo</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo de Libro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Libros</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Libros Activos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($porTipo)): ?>
                                <?php foreach ($porTipo as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($fila['tipo_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['total_libros']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['libros_activos']) ?>
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

            <!-- Análisis de Capacidad -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Análisis de Capacidad</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actas Usadas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acta Inicial</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acta Final</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($capacidad)): ?>
                                <?php foreach ($capacidad as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($fila['tipo_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['numero_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="font-semibold text-blue-600"><?= htmlspecialchars($fila['actas_usadas']) ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['acta_inicial'] ?? '-') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['acta_final'] ?? '-') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay datos disponibles</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Libros Activos -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Libros Activos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Registros</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Último Registro</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($activos)): ?>
                                <?php foreach ($activos as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($fila['tipo_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['numero_libro']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['total_registros']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $fila['ultimo_registro'] ? date('d/m/Y', strtotime($fila['ultimo_registro'])) : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay datos disponibles</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Botones de Exportación -->
            <div class="mt-6 flex gap-4">
                <a href="index.php?route=reportes/exportarExcel&tipo=libros" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </a>
                <a href="index.php?route=reportes/exportarPDF&tipo=libros" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </a>
            </div>
        </div>
    </div>
</body>
</html>
