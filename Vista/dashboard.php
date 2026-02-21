<div class="bg-gradient-to-br from-[#F9F5F3] to-[#F4EBE7] min-h-screen">
    <!-- Header Superior -->
    <header class="bg-gradient-to-r from-[#8D7B68] to-[#6b5d4f] text-white shadow-xl">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-church mr-3"></i>Dashboard Parroquia
                    </h1>
                    <p class="text-[#F4EBE7] text-lg">Panel de Control y Estadísticas</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-[#F4EBE7]">Última actualización</div>
                    <div class="text-xl font-semibold" id="fechaHora"></div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Resumen Ejecutivo -->
        <section class="mb-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Sistema</h3>
                            <p class="text-3xl font-bold text-[#5A4D41] stat-number mt-2">
                                <?= htmlspecialchars($totales['usuarios_sistema'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-sm text-gray-600 mt-1">Usuarios activos en el sistema</p>
                        </div>
                        <div class="w-12 h-12 bg-[#F4EBE7] border border-[#E6D5CC] rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-[#8D7B68] text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Recursos</h3>
                            <p class="text-3xl font-bold text-[#5A4D41] stat-number mt-2">
                                <?= htmlspecialchars($totales['recursos'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-sm text-gray-600 mt-1">Libros y documentos</p>
                        </div>
                        <div class="w-12 h-12 bg-[#F4EBE7] border border-[#E6D5CC] rounded-full flex items-center justify-center">
                            <i class="fas fa-book text-[#8D7B68] text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Actividad</h3>
                            <p class="text-3xl font-bold text-[#5A4D41] stat-number mt-2">
                                <?= htmlspecialchars($totales['actividad'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-sm text-gray-600 mt-1">Reportes y transacciones</p>
                        </div>
                        <div class="w-12 h-12 bg-[#F4EBE7] border border-[#E6D5CC] rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-[#8D7B68] text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>



        <!-- Estadísticas Detalladas -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-[#5A4D41] mb-8">
                <i class="fas fa-analytics mr-2 text-[#8D7B68]"></i>Estadísticas Detalladas
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <!-- Usuarios -->
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 0.3s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-[#F4EBE7] border border-[#E6D5CC] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-[#8D7B68] text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Usuarios</h3>
                        <p class="text-3xl font-bold text-[#5A4D41] stat-number">
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
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 0.4s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-[#F4EBE7] border border-[#E6D5CC] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-[#8D7B68] text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Libros</h3>
                        <p class="text-3xl font-bold text-[#5A4D41] stat-number">
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
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 0.5s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-[#F4EBE7] border border-[#E6D5CC] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-alt text-[#8D7B68] text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Documentos</h3>
                        <p class="text-3xl font-bold text-[#5A4D41] stat-number">
                            <?= htmlspecialchars($estadisticas['documentos']['total'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="mt-2 text-xs text-gray-500">
                            <div>Tipos:
                                <?= htmlspecialchars($estadisticas['documentos']['tipos'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Reportes -->
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 0.6s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-[#F4EBE7] border border-[#E6D5CC] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-bar text-[#8D7B68] text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Reportes</h3>
                        <p class="text-3xl font-bold text-[#5A4D41] stat-number">
                            <?= htmlspecialchars($estadisticas['reportes']['total'], ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="mt-2 text-xs text-gray-500">
                            <div>Categorías:
                                <?= htmlspecialchars($estadisticas['reportes']['categorias'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagos -->
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 0.7s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-[#F4EBE7] border border-[#E6D5CC] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-dollar-sign text-[#8D7B68] text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Pagos</h3>
                        <p class="text-3xl font-bold text-[#5A4D41] stat-number">
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
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 0.8s">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-[#F4EBE7] border border-[#E6D5CC] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-address-book text-[#8D7B68] text-xl"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Contactos</h3>
                        <p class="text-3xl font-bold text-[#5A4D41] stat-number">
                            <?= htmlspecialchars($estadisticas['contactos']['total'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gráficos -->
        <section>
            <h2 class="text-2xl font-bold text-[#5A4D41] mb-8">
                <i class="fas fa-chart-pie mr-2 text-[#8D7B68]"></i>Mi Actividad
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gráfico Mis Certificados -->
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 0.9s">
                    <h3 class="text-lg font-semibold text-[#5A4D41] mb-4 text-center">
                        <i class="fas fa-certificate mr-2 text-[#8D7B68]"></i>Mis Certificados
                    </h3>
                    <div class="chart-container">
                        <canvas id="graficoCertificados"></canvas>
                    </div>
                </div>

                <!-- Gráfico Estados de Pagos -->
                <div class="bg-white p-6 rounded-3xl shadow-lg border border-stone-200 card-hover fade-in"
                    style="animation-delay: 1.0s">
                    <h3 class="text-lg font-semibold text-[#5A4D41] mb-4 text-center">
                        <i class="fas fa-dollar-sign mr-2 text-[#8D7B68]"></i>Estados de Pagos
                    </h3>
                    <div class="chart-container">
                        <canvas id="graficoPagos"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        }
        actualizarFecha();
        setInterval(actualizarFecha, 60000);

        const animationConfig = { duration: 1500, easing: 'easeInOutCubic' };

        // ==========================================
        // GRÁFICO 1: MIS CERTIFICADOS
        // ==========================================
        const ctxCertificados = document.getElementById('graficoCertificados');
        if (ctxCertificados) {
            new Chart(ctxCertificados, {
                type: 'doughnut',
                data: {
                    labels: ['Aprobados', 'Pendientes', 'Rechazados'],
                    datasets: [{
                        // Usamos ?? 0 para evitar errores de sintaxis si PHP falla
                        data: [
                            <?= (int) ($estadisticas['certificados']['aprobados'] ?? 0) ?>,
                            <?= (int) ($estadisticas['certificados']['pendientes'] ?? 0) ?>,
                            <?= (int) ($estadisticas['certificados']['rechazados'] ?? 0) ?>
                        ],
                        backgroundColor: ['#10B981', '#8D7B68', '#EF4444'],
                        borderWidth: 3,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    animation: animationConfig,
                    cutout: '60%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 13 } } }
                    }
                }
            });
        }

        // ==========================================
        // GRÁFICO 2: ESTADOS DE PAGOS
        // ==========================================
        const ctxPagos = document.getElementById('graficoPagos');
        if (ctxPagos) {
            new Chart(ctxPagos, {
                type: 'doughnut',
                data: {
                    labels: ['Completos', 'Cancelados', 'Pendientes'],
                    datasets: [{
                        data: [
                            <?= (int) ($estadisticas['pagos']['completos'] ?? 0) ?>, 
                            <?= (int) ($estadisticas['pagos']['cancelados'] ?? 0) ?>, 
                            <?= (int) ($estadisticas['pagos']['pendientes'] ?? 0) ?>
                        ],
                        backgroundColor: ['#10B981', '#EF4444', '#8D7B68'],
                        borderWidth: 3,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    animation: animationConfig,
                    cutout: '50%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 13 } } }
                    }
                }
            });
        }

        // Animaciones de entrada de las tarjetas (fade-in)
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

        // Animación de los números contadores
        document.addEventListener('DOMContentLoaded', function () {
            function animateCounter(element, target, duration = 2000) {
                const startTimestamp = performance.now();
                const startValue = 0;
                function step(currentTime) {
                    const elapsed = currentTime - startTimestamp;
                    const progress = Math.min(elapsed / duration, 1);
                    const ease = 1 - Math.pow(1 - progress, 4);
                    const current = Math.floor(startValue + (target - startValue) * ease);
                    element.textContent = current;
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        element.textContent = target; 
                    }
                }
                window.requestAnimationFrame(step);
            }

            const counters = document.querySelectorAll('.stat-number');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent) || 0;
                if (target > 0) {
                    counter.textContent = '0';
                    setTimeout(() => animateCounter(counter, target), 500);
                }
            });
        });

        console.log('Dashboard Parroquia inicializado correctamente ✅');
    </script>
</div>