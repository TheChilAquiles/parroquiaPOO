<?php
/**
 * Vista/reportes_financiero.php - Reporte Financiero
 */
require_once __DIR__ . '/../helpers.php';

$ingresos = $ingresos ?? [];
$estadoPagos = $estadoPagos ?? [];
$metodosPago = $metodosPago ?? [];
$valoresPromedio = $valoresPromedio ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Financiero</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="index.php?route=reportes" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← Volver al menú de reportes
            </a>
            <h1 class="text-4xl font-bold text-gray-800">💰 Reporte Financiero</h1>
        </div>

        <!-- Ingresos por Concepto -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Ingresos por Concepto</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Concepto</th>
                        <th class="px-4 py-2 text-right">Total Ingresos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ingresos as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['concepto'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right font-semibold">$<?= number_format($row['total_ingresos'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Estado de Pagos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Estado de Pagos</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-right">Cantidad</th>
                        <th class="px-4 py-2 text-right">Monto Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estadoPagos as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['estado'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right"><?= number_format($row['cantidad'] ?? 0) ?></td>
                            <td class="px-4 py-2 text-right font-semibold">$<?= number_format($row['total'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Métodos de Pago -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Métodos de Pago</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Método</th>
                        <th class="px-4 py-2 text-right">Cantidad</th>
                        <th class="px-4 py-2 text-right">Monto Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($metodosPago as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['metodo_pago'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right"><?= number_format($row['cantidad'] ?? 0) ?></td>
                            <td class="px-4 py-2 text-right font-semibold">$<?= number_format($row['total'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Valores Promedio -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Valores Promedio por Concepto</h2>
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Concepto</th>
                        <th class="px-4 py-2 text-right">Valor Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($valoresPromedio as $row): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['concepto'] ?? 'N/A') ?></td>
                            <td class="px-4 py-2 text-right font-semibold">$<?= number_format($row['promedio'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        
    </div>
</body>
</html>
