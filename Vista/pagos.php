<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pagos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #ffeaa7, #fab1a0, #fd79a8, #fdcb6e);
            background-size: 400% 400%;
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            border: 2px solid rgba(255, 255, 255, 0.8);
            box-shadow:
                0 35px 70px -12px rgba(0, 0, 0, 0.12),
                0 8px 32px -8px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .hover-lift:hover {
            transform: translateY(-3px) scale(1.02);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 20px 40px -8px rgba(0, 0, 0, 0.15);
        }

        .shimmer {
            position: relative;
            overflow: hidden;
        }

        .shimmer::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.7), transparent);
            animation: shimmerMove 2s infinite;
        }

        @keyframes shimmerMove {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">

    <div class="min-h-screen py-8 px-4">
        <div class="max-w-7xl mx-auto">

            <!-- Header con efecto flotante -->
            <div class="text-center mb-10 floating">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-pink-400 via-[#D0B8A8] to-indigo-400 rounded-full shadow-2xl mb-6 border-4 border-white">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h1 class="text-6xl font-black text-gray-800 mb-3 tracking-tight">
                    Sistema de Pagos
                </h1>
                <p class="text-xl text-gray-700 font-medium">
                    Administración moderna y eficiente
                </p>
            </div>

            <!-- Mensajes de sesión -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-8 p-5 rounded-2xl border-2 bg-emerald-50 border-emerald-300 text-emerald-800 font-semibold text-center">
                    <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-8 p-5 rounded-2xl border-2 bg-red-50 border-red-300 text-red-800 font-semibold text-center">
                    <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="glass-card hover-lift rounded-3xl p-8 border-l-4 border-blue-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Pagos</p>
                            <p class="text-4xl font-black text-gray-800"><?= htmlspecialchars($estadisticas['total'] ?? 0, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card hover-lift rounded-3xl p-8 border-l-4 border-green-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Completados</p>
                            <p class="text-4xl font-black text-emerald-700"><?= htmlspecialchars($estadisticas['completados'] ?? 0, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="glass-card hover-lift rounded-3xl p-8 border-l-4 border-[#D0B8A8]">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Valor Total</p>
                            <p class="text-4xl font-black text-[#ab876f]">$<?= number_format($estadisticas['valor_total'] ?? 0, 0, ',', '.') ?></p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="glass-card rounded-3xl p-10 hover-lift">

                <!-- Controles -->
                <div class="mb-10 flex flex-col lg:flex-row gap-6 items-stretch lg:items-center justify-between">
                    <!-- Búsqueda -->
                    <div class="relative flex-1 max-w-lg">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center">
                            <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Buscar pagos por cualquier campo..."
                               class="w-full pl-14 pr-5 py-4 bg-gray-50 rounded-2xl border-2 border-gray-200 focus:border-indigo-400 focus:outline-none text-gray-800 font-medium placeholder-gray-500 transition-all">
                    </div>

                    <!-- Botón agregar -->
                    <a href="<?= url('pagos/crear') ?>"
                       class="shimmer inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-500 via-[#D0B8A8] to-pink-500 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nuevo Pago
                    </a>
                </div>

                <!-- Tabla -->
                <div class="overflow-hidden rounded-3xl border-2 border-gray-200 shadow-2xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gradient-to-r from-gray-50 via-gray-100 to-gray-50">
                                <tr>
                                    <th class="px-8 py-6 text-center text-sm font-black text-gray-700 uppercase tracking-wider">ID</th>
                                    <th class="px-8 py-6 text-left text-sm font-black text-gray-700 uppercase tracking-wider">Certificado</th>
                                    <th class="px-8 py-6 text-right text-sm font-black text-gray-700 uppercase tracking-wider">Valor</th>
                                    <th class="px-8 py-6 text-center text-sm font-black text-gray-700 uppercase tracking-wider">Estado</th>
                                    <th class="px-8 py-6 text-center text-sm font-black text-gray-700 uppercase tracking-wider hidden sm:table-cell">Fecha</th>
                                    <th class="px-8 py-6 text-center text-sm font-black text-gray-700 uppercase tracking-wider hidden md:table-cell">Método</th>
                                    <th class="px-8 py-6 text-center text-sm font-black text-gray-700 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white" id="tableBody">
                                <?php if (!empty($pagos)): ?>
                                    <?php foreach ($pagos as $pago): ?>
                                        <tr class="border-b-2 border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-[#F5F0EB] transition-all duration-300">
                                            <!-- ID -->
                                            <td class="px-8 py-6 text-center">
                                                <div class="w-14 h-14 bg-gradient-to-br from-indigo-400 to-[#F5F0EB]0 rounded-full flex items-center justify-center shadow-lg mx-auto">
                                                    <span class="text-lg font-black text-white"><?= htmlspecialchars($pago['id'], ENT_QUOTES, 'UTF-8') ?></span>
                                                </div>
                                            </td>

                                            <!-- Certificado -->
                                            <td class="px-8 py-6">
                                                <div class="text-lg font-bold text-gray-800 mb-1">
                                                    Certificado #<?= htmlspecialchars($pago['certificado_id'], ENT_QUOTES, 'UTF-8') ?>
                                                </div>
                                                <div class="text-sm text-gray-600 font-medium">Pago ID: <?= htmlspecialchars($pago['id'], ENT_QUOTES, 'UTF-8') ?></div>
                                            </td>

                                            <!-- Valor -->
                                            <td class="px-8 py-6 text-right">
                                                <div class="text-2xl font-black text-gray-800">
                                                    $<?= number_format($pago['valor'], 0, ',', '.') ?>
                                                </div>
                                            </td>

                                            <!-- Estado -->
                                            <td class="px-8 py-6 text-center">
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border-2 shadow-md
                                                    <?= strtolower($pago['estado']) === 'pagado' ?
                                                        'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-amber-100 text-amber-800 border-amber-300' ?>">
                                                    <?= htmlspecialchars($pago['estado'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>

                                            <!-- Fecha -->
                                            <td class="px-8 py-6 text-center hidden sm:table-cell">
                                                <?php
                                                $fecha = $pago['fecha_pago'];
                                                $esFechaValida = $fecha && $fecha !== '0000-00-00 00:00:00' && $fecha !== '0000-00-00' && strtotime($fecha) > 0;

                                                if ($esFechaValida):
                                                ?>
                                                    <div class="text-lg font-bold text-gray-800"><?= date('d/m/Y', strtotime($fecha)) ?></div>
                                                    <div class="text-sm text-gray-600 font-medium"><?= date('H:i', strtotime($fecha)) ?></div>
                                                <?php else: ?>
                                                    <span class="text-gray-400 font-medium">Sin fecha</span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Método de pago -->
                                            <td class="px-3 py-6 text-center hidden md:table-cell whitespace-nowrap">
                                                <span class="inline-block px-3 py-1 rounded-lg text-sm font-bold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300">
                                                    <?= htmlspecialchars($pago['metodo_de_pago'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>

                                            <!-- Acciones -->
                                            <td class="px-8 py-6 text-center">
                                                <div class="flex items-center justify-center space-x-3">
                                                    <a href="<?= url('pagos/actualizar', ['id' => $pago['id'], ENT_QUOTES, 'UTF-8']) ?>"
                                                       class="px-4 py-3 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-sm font-bold rounded-xl hover:scale-110 transition-transform shadow-lg">
                                                        Editar
                                                    </a>

                                                    <form method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este pago?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= htmlspecialchars($pago['id'], ENT_QUOTES, 'UTF-8') ?>">
                                                        <button type="submit"
                                                                class="px-4 py-3 bg-gradient-to-r from-red-400 to-red-600 text-white text-sm font-bold rounded-xl hover:scale-110 transition-transform shadow-lg">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-20">
                                            <div class="flex flex-col items-center space-y-6">
                                                <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center shadow-xl">
                                                    <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <h3 class="text-2xl font-black text-gray-800 mb-2">Sin pagos registrados</h3>
                                                    <p class="text-gray-600 font-medium">Agrega tu primer pago para comenzar</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                if (row.cells.length > 1) {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                }
            });
        });
    </script>
</body>
</html>
