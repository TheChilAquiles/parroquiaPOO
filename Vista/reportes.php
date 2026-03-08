<?php
/**
 * Vista/reportes.php - Menú Principal de Reportes Analíticos
 */
require_once __DIR__ . '/../helpers.php';
?>


    <main class="max-w-7xl mx-auto p-4 md:p-8 w-full">

  

        <!-- Grid de Reportes -->
        <section class="rounded-3xl bg-white shadow-lg border border-stone-200 overflow-hidden">
            <div class="border-b border-stone-200 bg-[#F9F5F3] px-6 py-6 md:px-8 md:py-6">
                <h2 class="text-xl font-bold text-[#5A4D41] flex items-center gap-3">
                    <svg class="w-6 h-6 text-[#8D7B68]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Reportes Analíticos
                </h2>
                <p class="mt-1 text-base text-gray-600">Seleccione el tipo de reporte que desea consultar</p>
            </div>

            <div class="p-6 md:p-8 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Reporte de Certificados -->
                    <a href="index.php?route=reportes/reporteCertificados" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center border border-blue-200 group-hover:bg-blue-200 transition-colors">
                                <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Certificados</h3>
                        <p class="text-sm text-gray-600 font-medium">Análisis de certificados por tipo, estado y tiempos de procesamiento</p>
                    </a>

                    <!-- Reporte de Feligreses -->
                    <a href="index.php?route=reportes/reporteFeligreses" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center border border-teal-200 group-hover:bg-teal-200 transition-colors">
                                <svg class="w-7 h-7 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Feligreses</h3>
                        <p class="text-sm text-gray-600 font-medium">Distribución por tipo de documento y feligreses más activos</p>
                    </a>

                    <!-- Reporte de Sacramentos -->
                    <a href="index.php?route=reportes/reporteSacramentos" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center border border-purple-200 group-hover:bg-purple-200 transition-colors">
                                <svg class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Sacramentos</h3>
                        <p class="text-sm text-gray-600 font-medium">Estadísticas por tipo de sacramento, libro y tendencias mensuales</p>
                    </a>

                    <!-- Reporte Financiero -->
                    <a href="index.php?route=reportes/reporteFinanciero" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center border border-green-200 group-hover:bg-green-200 transition-colors">
                                <svg class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Financiero</h3>
                        <p class="text-sm text-gray-600 font-medium">Ingresos por concepto, métodos de pago y valores promedio</p>
                    </a>

                    <!-- Reporte de Actividad -->
                    <a href="index.php?route=reportes/reporteActividad" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center border border-indigo-200 group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Actividad del Sistema</h3>
                        <p class="text-sm text-gray-600 font-medium">Usuarios más activos, actividad por rol y tasas de conversión</p>
                    </a>

                    

                    

                    

                </div>
            </div>
        </section>

        <!-- Info Footer -->
        <section class="mt-8 rounded-3xl bg-gradient-to-r from-[#8D7B68] to-[#6b5d4f] p-8 shadow-xl">
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 flex-shrink-0">
                    <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-xl font-bold text-white">Información Importante</h3>
                    <p class="text-lg text-[#F4EBE7] leading-relaxed font-medium">
                        Los reportes se generan en tiempo real basándose en los datos actuales del sistema.
                    </p>
                </div>
            </div>
        </section>

    </main>
