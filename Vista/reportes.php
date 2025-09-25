<?php
// --- CONFIGURACIÓN DE LA BASE DE DATOS ---
$host = "localhost";
$dbname = "parroquia";
$username = "root";
$password = "";

try {
    // Se establece la conexión PDO
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- CONSULTA SQL MEJORADA ---
    $sql = "SELECT r.id AS id_reporte, r.titulo, r.descripcion, r.categoria, r.fecha AS fecha_reporte, r.estado_registro,
                   p.id AS id_pago, p.certificado_id, p.valor, p.estado AS estado_pago, p.fecha_pago, p.tipo_pago_id
            FROM reportes r
            INNER JOIN pagos p ON r.id_pagos = p.id";

    $stmt = $conexion->query($sql);
    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Nota: Mantén este código solo en desarrollo
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

/**
 * Función auxiliar para obtener el total de reportes pagados
 */
function contarPagados(array $reportes): int {
    $count = 0;
    foreach ($reportes as $reporte) {
        // Usa null-coalescing para evitar warnings si la columna no existe
        if (isset($reporte['estado_pago']) && $reporte['estado_pago'] === 'Pagado') {
            $count++;
        }
    }
    return $count;
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
        /* Estilos CSS MEJORADOS */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
        }
        
        .gradient-bg {
            /* Diseño de fondo más vibrante */
            background: linear-gradient(135deg, #e0f2fe 0%, #f1f5f9 50%, #e2e8f0 100%);
            position: relative;
            overflow-x: hidden;
        }
        
        .gradient-bg::before {
            /* Ajuste de colores para que el fondo se sienta más profesional */
            background: 
                radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(202, 138, 4, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .glass-card {
            /* Mayor definición para el efecto de cristal */
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.3),
                        inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }
        
        /* ... (Animaciones floating-element sin cambios) ... */

        .table-header {
            /* Fondo de cabecera más nítido */
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 2px solid #e2e8f0;
        }

        /* ELIMINACIÓN DEL DESVANECIMIENTO (SCROLL-FADE) */
        /* Eliminamos la máscara de CSS */
        /* .scroll-fade { 
            mask-image: none !important; 
        } */
        
        /* Otros estilos menores para mantener el diseño */
        .filter-button.active {
            background: #3b82f6 !important; 
            color: white !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.25);
        }
        
        .status-badge { position: relative; overflow: hidden; }
        /* ... (el resto de estilos de hover de fila y badge) ... */

        .table-row {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        
        .table-row::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.05), rgba(59, 130, 246, 0.02));
            transition: width 0.3s ease;
        }
        
        .table-row:hover::before {
            width: 100%;
        }
        
        .table-row:hover {
            background: rgba(248, 250, 252, 0.8);
            transform: translateX(2px); /* Un hover más sutil */
            box-shadow: -2px 0 15px -2px rgba(59, 130, 246, 0.1);
        }

        .decorative-element {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            pointer-events: none;
            animation: float 8s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-10px) rotate(1deg); }
            66% { transform: translateY(-5px) rotate(-1deg); }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <div class="decorative-element w-32 h-32 bg-blue-500 top-10 left-10" style="animation-delay: 0s;"></div>
    <div class="decorative-element w-24 h-24 bg-green-500 top-1/3 right-20" style="animation-delay: -2s;"></div>
    <div class="decorative-element w-20 h-20 bg-yellow-500 bottom-20 left-1/4" style="animation-delay: -4s;"></div>
    <div class="decorative-element w-28 h-28 bg-purple-500 bottom-10 right-10" style="animation-delay: -6s;"></div>

    <div class="relative z-10 py-12 px-4 sm:px-6 lg:px-8"> 
        <div class="glass-card w-full max-w-[95rem] mx-auto rounded-3xl p-8 lg:p-12 xl:p-16">
            
            <div class="text-center mb-12 lg:mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 lg:w-24 lg:h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl mb-6">
                    <svg class="w-10 h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-black text-slate-800 mb-4 text-glow">
                    <span class="bg-gradient-to-r from-slate-700 via-slate-800 to-slate-900 bg-clip-text text-transparent">
                        Historial de Reportes
                    </span>
                </h1>
                
                <p class="text-lg sm:text-xl lg:text-2xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    Visualización detallada y elegante de los reportes con sus pagos asociados
                </p>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8 max-w-4xl mx-auto">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                        <div class="text-2xl font-bold text-slate-800"><?= count($reportes) ?></div>
                        <div class="text-sm font-medium text-blue-600">Total Reportes</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
                        <div class="text-2xl font-bold text-slate-800">
                            <?= contarPagados($reportes) ?>
                        </div>
                        <div class="text-sm font-medium text-green-600">Pagos Completados</div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
                        <div class="text-2xl font-bold text-slate-800">
                            $<?= number_format(array_sum(array_column($reportes, 'valor')), 0, ',', '.') ?>
                        </div>
                        <div class="text-sm font-medium text-yellow-600">Valor Total</div>
                    </div>
                </div>
            </div>

            <div class="mb-8 lg:mb-12">
                <div class="flex flex-col lg:flex-row gap-4 lg:gap-6 items-stretch lg:items-center justify-between">
                    <div class="search-box relative flex-1 max-w-md rounded-2xl overflow-hidden">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Buscar reportes..." 
                               class="w-full pl-12 pr-4 py-4 bg-transparent border-0 focus:outline-none text-slate-700 placeholder-slate-400 text-lg">
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700" data-filter="all">
                            Todos
                        </button>
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700" data-filter="pagado">
                            Pagados
                        </button>
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700" data-filter="pendiente">
                            Pendientes
                        </button>
                        <button class="filter-button px-6 py-3 rounded-xl font-semibold text-slate-700" data-filter="activo">
                            Activos
                        </button>
                    </div>
                </div>
            </div>

            <div class="premium-shadow rounded-3xl overflow-hidden border border-slate-200">
                <div class="overflow-x-auto table-container">
                    <table class="min-w-full divide-y-0">
                        <thead class="table-header">
                            <tr>
                                <th scope="col" class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase tracking-widest border-r border-slate-200/50"> ID </th>
                                <th scope="col" class="px-4 lg:px-6 py-6 text-left text-xs font-bold text-slate-600 uppercase tracking-widest border-r border-slate-200/50"> Título </th>
                                <th scope="col" class="px-4 lg:px-6 py-6 text-left text-xs font-bold text-slate-600 uppercase tracking-widest border-r border-slate-200/50 hidden lg:table-cell"> Descripción </th>
                                <th scope="col" class="px-4 lg:px-6 py-6 text-left text-xs font-bold text-slate-600 uppercase tracking-widest border-r border-slate-200/50"> Categoría </th>
                                <th scope="col" class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase tracking-widest border-r border-slate-200/50 hidden sm:table-cell"> Fecha </th>
                                <th scope="col" class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase tracking-widest border-r border-slate-200/50"> Estado </th>
                                <th scope="col" class="px-4 lg:px-6 py-6 text-right text-xs font-bold text-slate-600 uppercase tracking-widest border-r border-slate-200/50"> Valor </th>
                                <th scope="col" class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase tracking-widest border-r border-slate-200/50"> Pago </th>
                                <th scope="col" class="px-4 lg:px-6 py-6 text-center text-xs font-bold text-slate-600 uppercase tracking-widest"> Acciones ⚙️ </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y-0" id="tableBody">
                            <?php if (count($reportes) > 0): ?>
                                <?php foreach ($reportes as $fila): ?>
                                    <tr class="table-row border-b border-slate-100" 
                                        data-estado-pago="<?= strtolower($fila['estado_pago'] ?? 'pendiente') ?>" 
                                        data-estado-registro="<?= strtolower($fila['estado_registro'] ?? 'inactivo') ?>">
                                        
                                        <td class="px-4 lg:px-6 py-6 whitespace-nowrap text-center border-r border-slate-100/50">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                                <span class="text-sm font-black text-white"><?= htmlspecialchars($fila['id_reporte']) ?></span>
                                            </div>
                                        </td>

                                        <td class="px-4 lg:px-6 py-6 border-r border-slate-100/50">
                                            <div class="text-sm lg:text-base font-bold text-slate-900 mb-1"><?= htmlspecialchars($fila['titulo']) ?></div>
                                            <div class="text-xs text-slate-500">ID Pago: <?= htmlspecialchars($fila['id_pago']) ?></div>
                                        </td>

                                        <td class="px-4 lg:px-6 py-6 border-r border-slate-100/50 hidden lg:table-cell">
                                            <div class="text-sm text-slate-600 max-w-xs truncate" title="<?= htmlspecialchars($fila['descripcion']) ?>">
                                                <?= htmlspecialchars($fila['descripcion']) ?>
                                            </div>
                                        </td>

                                        <td class="px-4 lg:px-6 py-6 border-r border-slate-100/50">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                <?= htmlspecialchars($fila['categoria']) ?>
                                            </span>
                                        </td>

                                        <td class="px-4 lg:px-6 py-6 text-center border-r border-slate-100/50 hidden sm:table-cell">
                                            <div class="text-sm text-slate-600">
                                                <div class="font-medium"><?= date('d/m/Y', strtotime($fila['fecha_reporte'])) ?></div>
                                                <div class="text-xs text-slate-400 mt-1"><?= date('H:i', strtotime($fila['fecha_reporte'])) ?></div>
                                            </div>
                                        </td>

                                        <td class="px-4 lg:px-6 py-6 text-center border-r border-slate-100/50">
                                            <?php
                                                $estado_registro = htmlspecialchars($fila['estado_registro'] ?? 'Inactivo');
                                                $registro_class = $estado_registro === 'Activo' ? 
                                                    'bg-gradient-to-r from-green-50 to-green-100 text-green-800 border-green-200' : 
                                                    'bg-gradient-to-r from-red-50 to-red-100 text-red-800 border-red-200';
                                                $registro_dot_class = $estado_registro === 'Activo' ? 'bg-green-500' : 'bg-red-500';
                                            ?>
                                            <span class="status-badge inline-flex items-center px-3 py-2 rounded-full text-xs font-bold border-2 <?= $registro_class ?>">
                                                <span class="w-2 h-2 rounded-full mr-2 <?= $registro_dot_class ?>"></span>
                                                <?= $estado_registro ?>
                                            </span>
                                        </td>

                                        <td class="px-4 lg:px-6 py-6 text-right border-r border-slate-100/50">
                                            <div class="text-lg lg:text-xl font-black text-slate-900">$<?= number_format($fila['valor'], 0, ',', '.') ?></div>
                                            <div class="text-xs text-slate-500 mt-1">Certificado: <?= htmlspecialchars($fila['certificado_id']) ?></div>
                                        </td>

                                        <td class="px-4 lg:px-6 py-6 text-center border-r border-slate-100/50">
                                            <?php
                                                $estado_pago = htmlspecialchars($fila['estado_pago'] ?? 'Pendiente');
                                                $pago_class = $estado_pago === 'Pagado' ? 
                                                    'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-800 border-blue-200' : 
                                                    'bg-gradient-to-r from-yellow-50 to-yellow-100 text-yellow-800 border-yellow-200';
                                            ?>
                                            <div class="flex flex-col items-center space-y-2">
                                                <span class="status-badge inline-flex items-center px-3 py-2 rounded-full text-xs font-bold border-2 <?= $pago_class ?>">
                                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <?php if ($estado_pago === 'Pagado'): ?>
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        <?php else: ?>
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                        <?php endif; ?>
                                                    </svg>
                                                    <?= $estado_pago ?>
                                                </span>
                                                
                                                <?php if ($estado_pago === 'Pagado' && !empty($fila['certificado_id'])): ?>
                                                    <a href="ver_certificado.php?id=<?= urlencode($fila['certificado_id']) ?>"
                                                       target="_blank" 
                                                       class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md text-white bg-teal-500 hover:bg-teal-600 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        Ver Certificado
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (isset($fila['fecha_pago']) && $fila['fecha_pago']): ?>
                                                    <div class="text-xs text-slate-500 mt-1">
                                                        <?= date('d/m/Y', strtotime($fila['fecha_pago'])) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 lg:px-6 py-6 text-center">
                                            <a href="editar_reporte.php?id=<?= urlencode($fila['id_reporte']) ?>" 
                                               class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:scale-105"
                                               title="Editar Reporte #<?= htmlspecialchars($fila['id_reporte']) ?>">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7 1l-1 1H8l-.5.5L7 16l1-1h1l1-1v-1l.5-.5L14 11l-3-3zM15.5 2.5a2.121 2.121 0 013 3L11 13l-3 1 1-3 7.5-7.5z"></path></svg>
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
                                                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
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
        // Enhanced Search Functionality (Sin cambios)
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const isVisible = text.includes(searchTerm);
                row.style.display = isVisible ? '' : 'none';
            });
            // Re-ejecutar el filtro activo para que el motor de búsqueda funcione sobre el subconjunto.
            document.querySelector('.filter-button.active').click(); 
        });

        // Enhanced Filter Functionality
        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.filter-button').forEach(btn => {
                    btn.classList.remove('active', 'bg-blue-500', 'text-white');
                    btn.classList.add('text-slate-700');
                });
                
                this.classList.add('active', 'bg-blue-500', 'text-white');
                this.classList.remove('text-slate-700');
                
                const filter = this.dataset.filter;
                const rows = document.querySelectorAll('#tableBody tr');
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                
                rows.forEach(row => {
                    let showRow = false;
                    const textContent = row.textContent.toLowerCase();
                    
                    // 1. Aplicar filtro de búsqueda primero
                    const matchesSearch = textContent.includes(searchTerm);

                    // 2. Aplicar filtro de botón
                    if (matchesSearch) {
                        if (filter === 'all') {
                            showRow = true;
                        } else if (filter === 'pagado') {
                            showRow = row.dataset.estadoPago === 'pagado';
                        } else if (filter === 'pendiente') {
                            showRow = row.dataset.estadoPago === 'pendiente';
                        } else if (filter === 'activo') {
                            showRow = row.dataset.estadoRegistro === 'activo';
                        }
                    }
                    
                    row.style.display = showRow ? '' : 'none';
                });
            });
        });

        // Initialize filter buttons and apply initial filter
        document.addEventListener('DOMContentLoaded', function() {
            // CORRECCIÓN: Asegurar que el botón 'Todos' se vea activo al cargar.
            const allFilterButton = document.querySelector('.filter-button[data-filter="all"]');
            if (allFilterButton) {
                allFilterButton.click(); 
            }
        });
    </script>
</body>
</html>