<?php
/**
 * Vista/reportes_ejecutivo.php - Dashboard Ejecutivo
 */
require_once __DIR__ . '/../helpers.php';

$resumen = $resumen ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Ejecutivo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="index.php?route=reportes" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                    ← Volver a Reportes
                </a>
                <h1 class="text-4xl font-bold text-gray-900">🎯 Dashboard Ejecutivo</h1>
                <p class="text-gray-600 mt-2">Resumen general completo del sistema parroquial</p>
            </div>

            <!-- Métricas Principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Certificados -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Certificados Totales</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?= number_format($resumen['certificados_total'] ?? 0) ?>
                            </p>
                        </div>
                        <div class="text-5xl">📜</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Último mes: <span class="font-semibold text-blue-600"><?= number_format($resumen['certificados_mes'] ?? 0) ?></span>
                        </p>
                    </div>
                </div>

                <!-- Feligreses -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Feligreses Registrados</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?= number_format($resumen['feligreses_total'] ?? 0) ?>
                            </p>
                        </div>
                        <div class="text-5xl">👥</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Base de datos completa
                        </p>
                    </div>
                </div>

                <!-- Usuarios -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Usuarios del Sistema</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?= number_format($resumen['usuarios_total'] ?? 0) ?>
                            </p>
                        </div>
                        <div class="text-5xl">👤</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Último mes: <span class="font-semibold text-purple-600"><?= number_format($resumen['usuarios_mes'] ?? 0) ?></span>
                        </p>
                    </div>
                </div>

                <!-- Sacramentos -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Sacramentos Registrados</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?= number_format($resumen['sacramentos_total'] ?? 0) ?>
                            </p>
                        </div>
                        <div class="text-5xl">⛪</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Registros históricos
                        </p>
                    </div>
                </div>

                <!-- Libros -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Libros Parroquiales</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?= number_format($resumen['libros_total'] ?? 0) ?>
                            </p>
                        </div>
                        <div class="text-5xl">📚</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Gestión documental
                        </p>
                    </div>
                </div>

                <!-- Tasa de Conversión -->
                <?php if (isset($resumen['tasa_conversion'])): ?>
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Tasa de Conversión</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?= number_format($resumen['tasa_conversion']['tasa_generacion'] ?? 0, 1) ?>%
                            </p>
                        </div>
                        <div class="text-5xl">📈</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600">
                            Solicitudes → Certificados
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Resumen de Actividad -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Resumen de Actividad</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Actividad del Último Mes</h3>
                        <ul class="space-y-3">
                            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Nuevos Certificados</span>
                                <span class="font-bold text-blue-600"><?= number_format($resumen['certificados_mes'] ?? 0) ?></span>
                            </li>
                            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Nuevos Usuarios</span>
                                <span class="font-bold text-purple-600"><?= number_format($resumen['usuarios_mes'] ?? 0) ?></span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Totales Generales</h3>
                        <ul class="space-y-3">
                            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Total Certificados</span>
                                <span class="font-bold text-gray-900"><?= number_format($resumen['certificados_total'] ?? 0) ?></span>
                            </li>
                            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Total Feligreses</span>
                                <span class="font-bold text-gray-900"><?= number_format($resumen['feligreses_total'] ?? 0) ?></span>
                            </li>
                            <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Total Sacramentos</span>
                                <span class="font-bold text-gray-900"><?= number_format($resumen['sacramentos_total'] ?? 0) ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="mt-8 flex gap-4">
                <a href="index.php?route=reportes/exportarCSV&tipo=ejecutivo" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-excel mr-2"></i>Exportar Excel
                </a>
                <a href="index.php?route=reportes/exportarPDF&tipo=ejecutivo" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-file-pdf mr-2"></i>Exportar PDF
                </a>
            </div>
        </div>
    </div>
</body>
</html>
