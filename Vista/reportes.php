<?php
/**
 * Vista/reportes.php - Vista Principal del Módulo de Reportes
 * Variables requeridas desde el controlador:
 * - $reportes (array): Lista de reportes
 * - $totalReportes (int): Cantidad total de reportes
 * - $totalValor (float): Suma total de valores
 * - $pagosCompletados (int): Cantidad de pagos completados
 * - $pagosPendientes (int): Cantidad de pagos pendientes (opcional)
 * - $reportesActivos (int): Cantidad de reportes activos (opcional)
 */

// Guardas de seguridad
$reportes = $reportes ?? [];
$totalReportes = $totalReportes ?? 0;
$totalValor = $totalValor ?? 0.0;
$pagosCompletados = $pagosCompletados ?? 0;
$pagosPendientes = $pagosPendientes ?? 0;

// Helper para formateo de fechas
require_once __DIR__ . '/../helpers.php';
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes con Pagos - Dashboard Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Tus estilos exactamente igual (omito por brevedad en ejemplo),
           copia aquí tal cual los tenías. */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        *{ font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        .gradient-bg{ background: linear-gradient(135deg,#e0f2fe 0%,#f1f5f9 50%,#e2e8f0 100%); }
        .glass-card{ background: rgba(255,255,255,0.98); backdrop-filter: blur(25px); border: 1px solid rgba(255,255,255,0.5); }
        .table-header{ background: rgba(255,255,255,0.95); position: sticky; top: 0; z-index: 10; border-bottom: 2px solid #e2e8f0;}
        .table-row{ transition: all .3s cubic-bezier(.4,0,.2,1); position: relative;}
        .table-row::before{ content:''; position:absolute; left:0; top:0; bottom:0; width:0; background: linear-gradient(90deg, rgba(59,130,246,0.05), rgba(59,130,246,0.02)); transition: width .3s ease;}
        .table-row:hover::before{ width:100%; }
        .status-badge{ position: relative; overflow: hidden; }
        .filter-button.active{ background: #3b82f6 !important; color: white !important; border-color: #3b82f6 !important; box-shadow:0 4px 14px rgba(59,130,246,.25);}
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <div class="relative z-10 py-12 px-4 sm:px-6 lg:px-8">
        <div class="glass-card w-full max-w-[95rem] mx-auto rounded-3xl p-8 lg:p-12 xl:p-16">
            <div class="text-center mb-12 lg:mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 lg:w-24 lg:h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl mb-6">
                    <!-- icono -->
                </div>

                <h1 class="text-4xl font-black text-slate-800 mb-4">
                    Historial de Reportes
                </h1>
                <p class="text-lg text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    Visualización detallada y elegante de los reportes con sus pagos asociados
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8 max-w-4xl mx-auto">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                        <div class="text-2xl font-bold text-slate-800"><?= htmlspecialchars($totalReportes) ?></div>
                        <div class="text-sm font-medium text-blue-600">Total Reportes</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
                        <div class="text-2xl font-bold text-slate-800"><?= htmlspecialchars($pagosCompletados) ?></div>
                        <div class="text-sm font-medium text-green-600">Pagos Completados</div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
                        <div class="text-2xl font-bold text-slate-800">$<?= number_format($totalValor, 0, ',', '.') ?></div>
                        <div class="text-sm font-medium text-yellow-600">Valor Total</div>
                    </div>
                </div>
            </div>

            <div class="mb-8 lg:mb-12">
                <div class="flex flex-col lg:flex-row gap-4 lg:gap-6 items-stretch lg:items-center justify-between">
                    <div class="search-box relative flex-1 max-w-md rounded-2xl overflow-hidden">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Buscar reportes..." class="w-full pl-12 pr-4 py-4 bg-transparent border-0 focus:outline-none text-slate-700 placeholder-slate-400 text-lg">
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50" data-filter="all">Todos</button>
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50" data-filter="pagado">Pagados</button>
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50" data-filter="pendiente">Pendientes</button>
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50" data-filter="activo">Activos</button>
                        <button id="btnExportarPDF" class="px-6 py-3 rounded-xl font-semibold text-white bg-red-600 hover:bg-red-700 transition">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Exportar PDF
                        </button>
                    </div>
                </div>
            </div>

            <div class="premium-shadow rounded-3xl overflow-hidden border border-slate-200">
                <div class="overflow-x-auto table-container">
                    <table class="min-w-full divide-y-0">
                        <thead class="table-header">
                            <tr>
                                <th class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase tracking-widest border-r">ID</th>
                                <th class="px-4 lg:px-6 py-6 text-left text-xs font-bold text-slate-600 uppercase border-r">Título</th>
                                <th class="px-4 lg:px-6 py-6 text-left text-xs font-bold text-slate-600 uppercase border-r hidden lg:table-cell">Descripción</th>
                                <th class="px-4 lg:px-6 py-6 text-left text-xs font-bold text-slate-600 uppercase border-r">Categoría</th>
                                <th class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase border-r hidden sm:table-cell">Fecha</th>
                                <th class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase border-r">Estado</th>
                                <th class="px-4 lg:px-6 py-6 text-right text-xs font-bold text-slate-600 uppercase border-r">Valor</th>
                                <th class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase border-r">Pago</th>
                                <th class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase">Acciones ⚙️</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y-0" id="tableBody">
                        <?php if (!empty($reportes)): ?>
                            <?php foreach ($reportes as $fila): ?>
                                <tr class="table-row border-b border-slate-100"
                                    data-estado-pago="<?= e(strtolower($fila['estado_pago'] ?? 'pendiente')) ?>"
                                    data-estado-registro="<?= e(strtolower($fila['estado_registro'] ?? 'inactivo')) ?>">

                                    <td class="px-4 lg:px-6 py-6 text-center border-r">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                            <span class="text-sm font-black text-white"><?= htmlspecialchars($fila['id_reporte']) ?></span>
                                        </div>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 border-r">
                                        <div class="text-sm lg:text-base font-bold text-slate-900 mb-1"><?= htmlspecialchars($fila['titulo']) ?></div>
                                        <div class="text-xs text-slate-500">ID Pago: <?= htmlspecialchars($fila['id_pago']) ?></div>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 border-r hidden lg:table-cell">
                                        <div class="text-sm text-slate-600 max-w-xs truncate" title="<?= htmlspecialchars($fila['descripcion']) ?>">
                                            <?= htmlspecialchars($fila['descripcion']) ?>
                                        </div>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 border-r">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            <?= htmlspecialchars($fila['categoria']) ?>
                                        </span>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 text-center border-r hidden sm:table-cell">
                                        <div class="text-sm text-slate-600">
                                            <?php
                                            $fechaReporte = $fila['fecha_reporte'] ?? '';
                                            if (!empty($fechaReporte) && $fechaReporte !== '0000-00-00 00:00:00') {
                                                $timestamp = strtotime($fechaReporte);
                                                if ($timestamp) {
                                                    echo '<div class="font-medium">' . date('d/m/Y', $timestamp) . '</div>';
                                                    echo '<div class="text-xs text-slate-400 mt-1">' . date('H:i', $timestamp) . '</div>';
                                                } else {
                                                    echo '<div class="text-slate-400">-</div>';
                                                }
                                            } else {
                                                echo '<div class="text-slate-400">-</div>';
                                            }
                                            ?>
                                        </div>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 text-center border-r">
                                        <?php
                                            $estadoRegistro = $fila['estado_registro'] ?? 'inactivo';
                                            $esActivo = strtolower($estadoRegistro) === 'activo';
                                            $registroClass = $esActivo
                                                ? 'bg-gradient-to-r from-green-50 to-green-100 text-green-800 border-green-200'
                                                : 'bg-gradient-to-r from-red-50 to-red-100 text-red-800 border-red-200';
                                            $registroDotClass = $esActivo ? 'bg-green-500' : 'bg-red-500';
                                        ?>
                                        <span class="status-badge inline-flex items-center px-3 py-2 rounded-full text-xs font-bold border-2 <?= $registroClass ?>">
                                            <span class="w-2 h-2 rounded-full mr-2 <?= $registroDotClass ?>"></span>
                                            <?= e(ucfirst($estadoRegistro)) ?>
                                        </span>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 text-right border-r">
                                        <div class="text-lg lg:text-xl font-black text-slate-900">$<?= number_format(floatval($fila['valor'] ?? 0), 0, ',', '.') ?></div>
                                        <div class="text-xs text-slate-500 mt-1">Certificado: <?= htmlspecialchars($fila['certificado_id']) ?></div>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 text-center border-r">
                                        <?php
                                            $estadoPago = $fila['estado_pago'] ?? 'pendiente';
                                            $estadosPagados = ['pagado', 'completo', 'paid', 'complete'];
                                            $estaPagado = in_array(strtolower($estadoPago), $estadosPagados);
                                            $pagoClass = $estaPagado
                                                ? 'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 border-blue-200'
                                                : 'bg-gradient-to-r from-yellow-50 to-yellow-100 text-yellow-800 border-yellow-200';
                                        ?>
                                        <div class="flex flex-col items-center space-y-2">
                                            <span class="status-badge inline-flex items-center px-3 py-2 rounded-full text-xs font-bold border-2 <?= $pagoClass ?>">
                                                <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <?php if ($estaPagado): ?>
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    <?php else: ?>
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    <?php endif; ?>
                                                </svg>
                                                <?= e(ucfirst($estadoPago)) ?>
                                            </span>

                                            <?php if ($estaPagado && !empty($fila['certificado_id'])): ?>
                                                <a href="index.php?route=certificados/ver&id=<?= urlencode($fila['certificado_id']) ?>" target="_blank" class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-white bg-teal-500 hover:bg-teal-600 transition shadow-md">
                                                    Ver Certificado
                                                </a>
                                            <?php endif; ?>

                                            <?php
                                            $fechaPago = $fila['fecha_pago'] ?? '';
                                            if (!empty($fechaPago) && $fechaPago !== '0000-00-00 00:00:00') {
                                                $timestampPago = strtotime($fechaPago);
                                                if ($timestampPago) {
                                                    echo '<div class="text-xs text-slate-500 mt-1">' . date('d/m/Y', $timestampPago) . '</div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 text-center">
                                        <a href="index.php?route=reportes/editar&id=<?= urlencode($fila['id_reporte']) ?>" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition transform hover:scale-105" title="Editar Reporte #<?= e($fila['id_reporte']) ?>">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-20">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="w-24 h-24 bg-gradient-to-r from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center">
                                            <!-- icono vacio -->
                                        </div>
                                        <div class="text-center">
                                            <h3 class="text-xl font-bold text-slate-900 mb-2">No hay reportes disponibles</h3>
                                            <p class="text-slate-500 max-w-md">Comienza creando un nuevo reporte para verlo aquí en este hermoso dashboard.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-12 text-center">
                <div class="inline-flex items-center space-x-8 text-sm text-slate-500">
                    <span class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span>Sistema actualizado en tiempo real</span>
                    </span>
                    <span class="hidden sm:flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span>Datos seguros y encriptados</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de sesión para JavaScript -->
    <?php if (isset($_SESSION['success'])): ?>
        <div data-mensaje-success="<?= e($_SESSION['success']) ?>" style="display:none;"></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div data-mensaje-error="<?= e($_SESSION['error']) ?>" style="display:none;"></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Scripts externos -->
    <script src="assets/js/reportes.js"></script>
</body>
</html>
