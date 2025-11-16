<?php include_once __DIR__ . '/../componentes/plantillaTop.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="mb-8">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Panel de Configuración</h1>
        <p class="text-gray-600 mt-2">Administra las configuraciones del sistema parroquial</p>
    </div>

    <!-- Estadísticas del Sistema -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Feligreses -->
        <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-[#D0B8A8]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Feligreses</p>
                    <p class="text-3xl font-bold text-[#ab876f] mt-2"><?= number_format($stats['total_feligreses'] ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-[#F5F0EB] rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#ab876f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Usuarios -->
        <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-[#8B6F47]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Usuarios</p>
                    <p class="text-3xl font-bold text-[#8B6F47] mt-2"><?= number_format($stats['total_usuarios'] ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-[#E8DFD5] rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#8B6F47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Certificados -->
        <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-[#DFD3C3]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Certificados</p>
                    <p class="text-3xl font-bold text-[#5D4E37] mt-2"><?= number_format($stats['total_certificados'] ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-[#F5F0EB] rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#5D4E37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ingresos Totales -->
        <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Ingresos</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">$<?= number_format($stats['ingresos_totales'] ?? 0) ?></p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
        <div class="px-6 py-4 bg-gradient-to-r from-[#F5F0EB] to-[#E8DFD5] border-b border-[#DFD3C3]">
            <h2 class="text-xl font-semibold text-gray-800">Configuraciones del Sistema</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Configuraciones Generales -->
                <a href="<?= url('configuracion/configuraciones') ?>"
                   class="block p-6 bg-gradient-to-br from-[#F5F0EB] to-white rounded-lg border-2 border-[#DFD3C3] hover:border-[#D0B8A8] hover:shadow-lg transition duration-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[#D0B8A8] rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Configuraciones Generales</h3>
                    <p class="text-sm text-gray-600">Ajusta las configuraciones básicas del sistema</p>
                </a>

                <!-- Precios de Certificados -->
                <a href="<?= url('configuracion/precios') ?>"
                   class="block p-6 bg-gradient-to-br from-[#F5F0EB] to-white rounded-lg border-2 border-[#DFD3C3] hover:border-[#D0B8A8] hover:shadow-lg transition duration-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[#8B6F47] rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Precios de Certificados</h3>
                    <p class="text-sm text-gray-600">Administra los precios de los certificados sacramentales</p>
                </a>

                <!-- Pasarela de Pagos -->
                <a href="<?= url('configuracion/pasarela-pagos') ?>"
                   class="block p-6 bg-gradient-to-br from-[#F5F0EB] to-white rounded-lg border-2 border-[#DFD3C3] hover:border-[#D0B8A8] hover:shadow-lg transition duration-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[#ab876f] rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pasarela de Pagos</h3>
                    <p class="text-sm text-gray-600">Configura la pasarela de pagos y métodos de pago</p>
                </a>

                <!-- Gestión de Usuarios -->
                <a href="<?= url('feligreses') ?>"
                   class="block p-6 bg-gradient-to-br from-[#F5F0EB] to-white rounded-lg border-2 border-[#DFD3C3] hover:border-[#D0B8A8] hover:shadow-lg transition duration-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[#5D4E37] rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Gestión de Feligreses</h3>
                    <p class="text-sm text-gray-600">Administra los feligreses registrados</p>
                </a>

                <!-- Reportes -->
                <a href="<?= url('reportes') ?>"
                   class="block p-6 bg-gradient-to-br from-[#F5F0EB] to-white rounded-lg border-2 border-[#DFD3C3] hover:border-[#D0B8A8] hover:shadow-lg transition duration-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[#C4A68A] rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Reportes</h3>
                    <p class="text-sm text-gray-600">Genera reportes y estadísticas del sistema</p>
                </a>

                <!-- Grupos Parroquiales -->
                <a href="<?= url('grupos') ?>"
                   class="block p-6 bg-gradient-to-br from-[#F5F0EB] to-white rounded-lg border-2 border-[#DFD3C3] hover:border-[#D0B8A8] hover:shadow-lg transition duration-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-[#6B5437] rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Grupos Parroquiales</h3>
                    <p class="text-sm text-gray-600">Gestiona los grupos y comunidades</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Información del Sistema -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#F5F0EB] to-[#E8DFD5] border-b border-[#DFD3C3]">
            <h2 class="text-xl font-semibold text-gray-800">Información del Sistema</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="font-semibold text-gray-700">Versión del Sistema:</span>
                    <span class="text-gray-600">1.0.0</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="font-semibold text-gray-700">PHP Version:</span>
                    <span class="text-gray-600"><?= phpversion() ?></span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="font-semibold text-gray-700">Base de Datos:</span>
                    <span class="text-gray-600">MySQL</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="font-semibold text-gray-700">Última Actualización:</span>
                    <span class="text-gray-600"><?= date('d/m/Y') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../componentes/plantillaBottom.php'; ?>
