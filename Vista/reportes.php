<?php
// Vista/reportes.php
// Variables que deben llegar desde el controlador:
// $reportes (array), $totalReportes (int), $totalValor (float), $pagosCompletados (int)

// Guardas por seguridad si alguien carga la vista sin pasar datos
if (!isset($reportes) || !is_array($reportes)) $reportes = [];
if (!isset($totalReportes)) $totalReportes = count($reportes);
if (!isset($totalValor)) $totalValor = array_sum(array_map(function($r){ return isset($r['valor']) ? floatval($r['valor']) : 0.0; }, $reportes));
if (!isset($pagosCompletados)) {
    $pagosCompletados = 0;
    foreach ($reportes as $r) {
        $estadoPago = strtolower(trim($r['estado_pago'] ?? ''));
        if (in_array($estadoPago, ['completo', 'pagado', 'paid', 'complete'])) $pagosCompletados++;
    }
}

// Helper para fechas seguras
function formatoFechaSegura($fecha, $formato = 'd/m/Y') {
    if (empty($fecha) || $fecha === '0000-00-00 00:00:00') return '-';
    $ts = strtotime($fecha);
    return $ts ? date($formato, $ts) : $fecha;
}
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
                            <!-- icono búsqueda -->
                        </div>
                        <input type="text" id="searchInput" placeholder="Buscar reportes..." class="w-full pl-12 pr-4 py-4 bg-transparent border-0 focus:outline-none text-slate-700 placeholder-slate-400 text-lg">
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700" data-filter="all">Todos</button>
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700" data-filter="pagado">Pagados</button>
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700" data-filter="pendiente">Pendientes</button>
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700" data-filter="activo">Activos</button>
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
                        <?php if (count($reportes) > 0): ?>
                            <?php foreach ($reportes as $fila): ?>
                                <?php
                                    $estadoPagoAttr = strtolower($fila['estado_pago'] ?? 'pendiente');
                                    $estadoRegistroAttr = strtolower($fila['estado_registro'] ?? 'inactivo');
                                ?>
                                <tr class="table-row border-b border-slate-100"
                                    data-estado-pago="<?= htmlspecialchars($estadoPagoAttr) ?>"
                                    data-estado-registro="<?= htmlspecialchars($estadoRegistroAttr) ?>">

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
                                            <div class="font-medium"><?= formatoFechaSegura($fila['fecha_reporte'], 'd/m/Y') ?></div>
                                            <div class="text-xs text-slate-400 mt-1"><?= formatoFechaSegura($fila['fecha_reporte'], 'H:i') ?></div>
                                        </div>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 text-center border-r">
                                        <?php
                                            $estado_registro = htmlspecialchars($fila['estado_registro'] ?? 'Inactivo');
                                            $registro_class = (strtolower($estado_registro) === 'activo') ?
                                                'bg-gradient-to-r from-green-50 to-green-100 text-green-800 border-green-200' :
                                                'bg-gradient-to-r from-red-50 to-red-100 text-red-800 border-red-200';
                                            $registro_dot_class = (strtolower($estado_registro) === 'activo') ? 'bg-green-500' : 'bg-red-500';
                                        ?>
                                        <span class="status-badge inline-flex items-center px-3 py-2 rounded-full text-xs font-bold border-2 <?= $registro_class ?>">
                                            <span class="w-2 h-2 rounded-full mr-2 <?= $registro_dot_class ?>"></span>
                                            <?= $estado_registro ?>
                                        </span>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 text-right border-r">
                                        <div class="text-lg lg:text-xl font-black text-slate-900">$<?= number_format(floatval($fila['valor'] ?? 0), 0, ',', '.') ?></div>
                                        <div class="text-xs text-slate-500 mt-1">Certificado: <?= htmlspecialchars($fila['certificado_id']) ?></div>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 text-center border-r">
                                        <?php
                                            $estado_pago = htmlspecialchars($fila['estado_pago'] ?? 'Pendiente');
                                            $pago_class = (in_array(strtolower($estado_pago), ['pagado','completo','paid','complete'])) ?
                                                'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 border-blue-200' :
                                                'bg-gradient-to-r from-yellow-50 to-yellow-100 text-yellow-800 border-yellow-200';
                                        ?>
                                        <div class="flex flex-col items-center space-y-2">
                                            <span class="status-badge inline-flex items-center px-3 py-2 rounded-full text-xs font-bold border-2 <?= $pago_class ?>">
                                                <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <?php if (in_array(strtolower($estado_pago), ['pagado','completo','paid','complete'])): ?>
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    <?php else: ?>
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    <?php endif; ?>
                                                </svg>
                                                <?= $estado_pago ?>
                                            </span>

                                            <?php if (in_array(strtolower($estado_pago), ['pagado','completo','paid','complete']) && !empty($fila['certificado_id'])): ?>
                                                <a href="ver_certificado.php?id=<?= urlencode($fila['certificado_id']) ?>" target="_blank" class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-white bg-teal-500 hover:bg-teal-600 transition shadow-md">
                                                    Ver Certificado
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($fila['fecha_pago']) && $fila['fecha_pago'] !== '0000-00-00 00:00:00'): ?>
                                                <div class="text-xs text-slate-500 mt-1"><?= formatoFechaSegura($fila['fecha_pago'], 'd/m/Y') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td class="px-4 lg:px-6 py-6 text-center">
                                        <a href="editar_reporte.php?id=<?= urlencode($fila['id_reporte']) ?>" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition transform hover:scale-105" title="Editar Reporte #<?= htmlspecialchars($fila['id_reporte']) ?>">
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

    <script>
    (function(){
        const rows = () => Array.from(document.querySelectorAll('#tableBody tr'));
        const searchInput = document.getElementById('searchInput');
        const filterButtons = document.querySelectorAll('.filter-button');

        function applyFilters() {
            const q = (searchInput.value || '').toLowerCase().trim();
            const activeBtn = document.querySelector('.filter-button.active');
            const filter = activeBtn ? activeBtn.dataset.filter : 'all';

            rows().forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = text.includes(q);

                const estadoPago = (row.dataset.estadoPago || '').toLowerCase();
                const estadoRegistro = (row.dataset.estadoRegistro || '').toLowerCase();

                let matchesFilter = false;
                if (filter === 'all') {
                    matchesFilter = true;
                } else if (filter === 'pagado') {
                    matchesFilter = ['pagado','completo','paid','complete'].includes(estadoPago);
                } else if (filter === 'pendiente') {
                    matchesFilter = ['pendiente','pending'].includes(estadoPago);
                } else if (filter === 'activo') {
                    matchesFilter = (estadoRegistro === 'activo');
                }

                row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
            });
        }

        // Search -> apply
        if (searchInput) {
            searchInput.addEventListener('input', () => applyFilters());
        }

        // Filter buttons
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => {
                    b.classList.remove('active','bg-blue-500','text-white');
                    b.classList.add('text-slate-700');
                });
                btn.classList.add('active','bg-blue-500','text-white');
                btn.classList.remove('text-slate-700');
                applyFilters();
            });
        });

        // Al cargar, seleccionar "Todos"
        document.addEventListener('DOMContentLoaded', () => {
            const allButton = document.querySelector('.filter-button[data-filter="all"]');
            if (allButton) {
                allButton.classList.add('active','bg-blue-500','text-white');
                allButton.classList.remove('text-slate-700');
            }
            applyFilters();
        });

    })();
    </script>
</body>
</html>
