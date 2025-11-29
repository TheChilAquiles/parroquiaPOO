<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Parroquia - Panel de Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .fade-in {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #D0B8A8 0%, #ab876f 100%);
        }

        .chart-container {
            position: relative;
            height: 250px;
        }

        .stat-number {
            font-feature-settings: 'tnum';
            letter-spacing: -0.05em;
        }

        .loading {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <!-- Header Superior -->
    <header class="gradient-bg text-white shadow-xl">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-church mr-3"></i>Dashboard Parroquia
                    </h1>
                    <p class="text-blue-100 text-lg">Panel de Control y Estadísticas</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-blue-100">Última actualización</div>
                    <div class="text-xl font-semibold" id="fechaHora"></div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Resumen Ejecutivo -->
        <section class="mb-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Sistema</h3>
                            <p class="text-3xl font-bold text-gray-900 stat-number mt-2">
                                <?= htmlspecialchars($totales['usuarios_sistema'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-sm text-gray-600 mt-1">Usuarios activos en el sistema</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Recursos</h3>
                            <p class="text-3xl font-bold text-gray-900 stat-number mt-2">
                                <?= htmlspecialchars($totales['recursos'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-sm text-gray-600 mt-1">Libros y documentos</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-book text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Actividad</h3>
                            <p class="text-3xl font-bold text-gray-900 stat-number mt-2">
                                <?= htmlspecialchars($totales['actividad'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-sm text-gray-600 mt-1">Reportes y transacciones</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- Estadísticas Detalladas -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">
                <i class="fas fa-analytics mr-2 text-blue-600"></i>Estadísticas Detalladas
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <!-- Usuarios -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 0.3s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Usuarios</h3>
                        <p class="text-3xl font-bold text-gray-900 stat-number">
                            <?= htmlspecialchars($estadisticas['usuarios']['total'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="mt-2 text-xs text-gray-500">
                            <div>Roles: <?= htmlspecialchars($estadisticas['usuarios']['roles'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <div>Feligreses:
                                <?= htmlspecialchars($estadisticas['usuarios']['feligreses'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Libros -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 0.4s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Libros</h3>
                        <p class="text-3xl font-bold text-gray-900 stat-number">
                            <?= htmlspecialchars($estadisticas['libros']['total'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="mt-2 text-xs text-gray-500">
                            <div>Tipos: <?= htmlspecialchars($estadisticas['libros']['tipos'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <div>Registros:
                                <?= htmlspecialchars($estadisticas['libros']['registros'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Documentos -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 0.5s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-alt text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Documentos</h3>
                        <p class="text-3xl font-bold text-gray-900 stat-number">
                            <?= htmlspecialchars($estadisticas['documentos']['total'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="mt-2 text-xs text-gray-500">
                            <div>Tipos:
                                <?= htmlspecialchars($estadisticas['documentos']['tipos'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Reportes -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 0.6s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Reportes</h3>
                        <p class="text-3xl font-bold text-gray-900 stat-number">
                            <?= htmlspecialchars($estadisticas['reportes']['total'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="mt-2 text-xs text-gray-500">
                            <div>Categorías:
                                <?= htmlspecialchars($estadisticas['reportes']['categorias'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagos -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 0.7s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Pagos</h3>
                        <p class="text-3xl font-bold text-gray-900 stat-number">
                            <?= htmlspecialchars($estadisticas['pagos']['total'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="mt-2 text-xs text-gray-500">
                            <div class="text-green-600">✓ Completos:
                                <?= htmlspecialchars($estadisticas['pagos']['completos'], ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="text-red-600">✗ Cancelados:
                                <?= htmlspecialchars($estadisticas['pagos']['cancelados'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Contactos -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 0.8s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-address-book text-teal-600 text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Contactos</h3>
                        <p class="text-3xl font-bold text-gray-900 stat-number">
                            <?= htmlspecialchars($estadisticas['contactos']['total'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gráficos -->
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-8">
                <i class="fas fa-chart-pie mr-2 text-purple-600"></i>Análisis Visual
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
                <!-- Gráfico Usuarios -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 0.9s">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                        <i class="fas fa-users mr-2 text-blue-600"></i>Gestión de Usuarios
                    </h3>
                    <div class="chart-container">
                        <canvas id="graficoUsuarios"></canvas>
                    </div>
                </div>

                <!-- Gráfico Libros -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 1.0s">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                        <i class="fas fa-book mr-2 text-yellow-600"></i>Biblioteca Parroquial
                    </h3>
                    <div class="chart-container">
                        <canvas id="graficoLibros"></canvas>
                    </div>
                </div>

                <!-- Gráfico Documentos -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 1.1s">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                        <i class="fas fa-file-alt mr-2 text-red-600"></i>Documentación
                    </h3>
                    <div class="chart-container">
                        <canvas id="graficoDocumento"></canvas>
                    </div>
                </div>

                <!-- Gráfico Reportes -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 1.2s">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                        <i class="fas fa-chart-bar mr-2 text-purple-600"></i>Sistema de Reportes
                    </h3>
                    <div class="chart-container">
                        <canvas id="graficoReportes"></canvas>
                    </div>
                </div>

                <!-- Gráfico Pagos -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 1.3s">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                        <i class="fas fa-dollar-sign mr-2 text-green-600"></i>Estados de Pagos
                    </h3>
                    <div class="chart-container">
                        <canvas id="graficoPagos"></canvas>
                    </div>
                </div>

                <!-- Gráfico General -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in"
                    style="animation-delay: 1.4s">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                        <i class="fas fa-chart-area mr-2 text-indigo-600"></i>Resumen General
                    </h3>
                    <div class="chart-container">
                        <canvas id="graficoGeneral"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </main>



    <!-- Scripts -->
    <script>
        // Configuración global de Chart.js
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;
        Chart.defaults.plugins.legend.position = 'bottom';
        Chart.defaults.plugins.legend.labels.padding = 20;
        Chart.defaults.plugins.legend.labels.usePointStyle = true;

        // Actualizar fecha y hora
        function actualizarFecha() {
            const ahora = new Date();
            document.getElementById('fechaHora').textContent = ahora.toLocaleString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        actualizarFecha();
        setInterval(actualizarFecha, 60000);

        // Configuraciones de animación
        const animationConfig = {
            duration: 1500,
            easing: 'easeInOutCubic'
        };

        // Gráfico de Usuarios
        new Chart(document.getElementById('graficoUsuarios'), {
            type: 'bar',
            data: {
                labels: ['Usuarios', 'Roles', 'Feligreses'],
                datasets: [{
                    data: [<?= (int) $estadisticas['usuarios']['total'] ?>, <?= (int) $estadisticas['usuarios']['roles'] ?>, <?= (int) $estadisticas['usuarios']['feligreses'] ?>],
                    backgroundColor: ['#3B82F6', '#EF4444', '#06B6D4'],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                animation: animationConfig,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#374151',
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6' },
                        ticks: { color: '#6B7280' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6B7280' }
                    }
                }
            }
        });

        // Gráfico de Libros
        new Chart(document.getElementById('graficoLibros'), {
            type: 'doughnut',
            data: {
                labels: ['Total', 'Tipos', 'Registros'],
                datasets: [{
                    data: [<?= (int) $estadisticas['libros']['total'] ?>, <?= (int) $estadisticas['libros']['tipos'] ?>, <?= (int) $estadisticas['libros']['registros'] ?>],
                    backgroundColor: ['#F59E0B', '#8B5CF6', '#F97316'],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                animation: animationConfig,
                cutout: '60%',
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                }
            }
        });

        // Gráfico de Documentos
        new Chart(document.getElementById('graficoDocumento'), {
            type: 'pie',
            data: {
                labels: ['Tipos de Documento', 'Total Documentos'],
                datasets: [{
                    data: [<?= (int) $estadisticas['documentos']['tipos'] ?>, <?= (int) $estadisticas['documentos']['total'] ?>],
                    backgroundColor: ['#3B82F6', '#EF4444'],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                animation: animationConfig,
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                }
            }
        });

        // Gráfico de Reportes
        new Chart(document.getElementById('graficoReportes'), {
            type: 'bar',
            data: {
                labels: ['Total Reportes', 'Categorías'],
                datasets: [{
                    data: [<?= (int) $estadisticas['reportes']['total'] ?>, <?= (int) $estadisticas['reportes']['categorias'] ?>],
                    backgroundColor: ['#8B5CF6', '#A855F7'],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                animation: animationConfig,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6' },
                        ticks: { color: '#6B7280' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6B7280' }
                    }
                }
            }
        });

        // Gráfico de Pagos
        new Chart(document.getElementById('graficoPagos'), {
            type: 'doughnut',
            data: {
                labels: ['Completos', 'Cancelados', 'Pendientes'],
                datasets: [{
                    data: [<?= (int) $estadisticas['pagos']['completos'] ?>, <?= (int) $estadisticas['pagos']['cancelados'] ?>, <?= (int) $estadisticas['pagos']['pendientes'] ?>],
                    backgroundColor: ['#10B981', '#EF4444', '#F59E0B'],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                animation: animationConfig,
                cutout: '50%',
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                }
            }
        });

        // Gráfico General
        new Chart(document.getElementById('graficoGeneral'), {
            type: 'radar',
            data: {
                labels: ['Usuarios', 'Libros', 'Documentos', 'Reportes', 'Pagos', 'Contactos'],
                datasets: [{
                    label: 'Actividad General',
                    data: [
                        <?= (int) $estadisticas['usuarios']['total'] ?>,
                        <?= (int) $estadisticas['libros']['total'] ?>,
                        <?= (int) $estadisticas['documentos']['total'] ?>,
                        <?= (int) $estadisticas['reportes']['total'] ?>,
                        <?= (int) $estadisticas['pagos']['total'] ?>,
                        <?= (int) $estadisticas['contactos']['total'] ?>
                    ],
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderColor: '#6366F1',
                    borderWidth: 2,
                    pointBackgroundColor: '#6366F1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                animation: animationConfig,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6' },
                        ticks: { color: '#6B7280', display: false },
                        pointLabels: { color: '#6B7280', font: { size: 12 } }
                    }
                }
            }
        });

        // Animaciones de entrada
        const fadeElements = document.querySelectorAll('.fade-in');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        fadeElements.forEach(el => observer.observe(el));

        // Efectos adicionales
        document.addEventListener('DOMContentLoaded', function () {
            // Contador animado para números grandes
            function animateCounter(element, target, duration = 2000) {
                let start = 0;
                const increment = target / (duration / 16);
                const timer = setInterval(() => {
                    start += increment;
                    if (start >= target) {
                        element.textContent = target;
                        clearInterval(timer);
                    } else {
                        element.textContent = Math.floor(start);
                    }
                }, 16);
            }

            // Animar contadores principales
            const counters = document.querySelectorAll('.stat-number');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent);
                if (target > 0) {
                    counter.textContent = '0';
                    setTimeout(() => animateCounter(counter, target), 500);
                }
            });

            // Tooltip para gráficos
            const chartContainers = document.querySelectorAll('.chart-container');
            chartContainers.forEach(container => {
                container.addEventListener('mouseenter', function () {
                    this.style.transform = 'scale(1.02)';
                    this.style.transition = 'transform 0.3s ease';
                });

                container.addEventListener('mouseleave', function () {
                    this.style.transform = 'scale(1)';
                });
            });
        });

        // Manejo de errores para gráficos
        window.addEventListener('error', function (e) {
            if (e.target.tagName === 'CANVAS') {
                console.error('Error en gráfico:', e);
                e.target.parentElement.innerHTML =
                    '<div class="flex items-center justify-center h-full text-gray-400">' +
                    '<i class="fas fa-exclamation-triangle mr-2"></i>Error al cargar gráfico</div>';
            }
        });

        // Responsive behavior
        window.addEventListener('resize', function () {
            Chart.instances.forEach(chart => {
                chart.resize();
            });
        });

        console.log('Dashboard Parroquia inicializado correctamente ✅');
    </script>
</body>

</html>