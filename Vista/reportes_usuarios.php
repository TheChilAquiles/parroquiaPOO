<?php
/**
 * Vista/reportes_usuarios.php - Reportes de Usuarios y Roles
 */
require_once __DIR__ . '/../helpers.php';

$porRol = $porRol ?? [];
$sinDatos = $sinDatos ?? [];
$registros = $registros ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Usuarios</title>
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
                <h1 class="text-4xl font-bold text-gray-900">👤 Reportes de Usuarios y Roles</h1>
                <p class="text-gray-600 mt-2">Análisis de distribución, datos completos y nuevos registros</p>
            </div>

            <!-- Usuarios por Rol -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Distribución por Rol</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Usuarios</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Emails Confirmados</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datos Completos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($porRol)): ?>
                                <?php foreach ($porRol as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                                <?= htmlspecialchars($fila['rol']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="font-bold text-lg"><?= htmlspecialchars($fila['total_usuarios']) ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['emails_confirmados']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['datos_completos']) ?>
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

            <!-- Usuarios sin Datos Completos -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Usuarios sin Datos Completos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email Confirmado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($sinDatos)): ?>
                                <?php foreach ($sinDatos as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($fila['email']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['rol']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php if ($fila['email_confirmed']): ?>
                                                <span class="text-green-600">✓ Sí</span>
                                            <?php else: ?>
                                                <span class="text-red-600">✗ No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $fila['fecha_registro'] ? date('d/m/Y', strtotime($fila['fecha_registro'])) : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Todos los usuarios tienen datos completos</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Nuevos Registros -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Nuevos Registros por Período</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nuevos Usuarios</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($registros)): ?>
                                <?php foreach ($registros as $fila): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= date('d/m/Y', strtotime($fila['fecha'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($fila['rol']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="font-bold text-blue-600"><?= htmlspecialchars($fila['nuevos_usuarios']) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay registros en el período seleccionado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Botones de Exportación -->
            <div class="mt-6 flex gap-4">
                <a href="index.php?route=reportes/exportarCSV&tipo=usuarios" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </a>
                <a href="index.php?route=reportes/exportarPDF&tipo=usuarios" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </a>
            </div>
        </div>
    </div>
</body>
</html>
