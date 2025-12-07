<?php
/**
 * Vista/reportes.php - Menú Principal de Reportes Analíticos
 */
require_once __DIR__ . '/../helpers.php';
?>


    <main class="max-w-7xl mx-auto p-4 md:p-8 w-full">

        <!-- Header Section -->
        <section class="mb-8 rounded-3xl bg-white p-6 md:p-8 shadow-lg border border-stone-200">
            <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-[#F4EBE7] border border-[#E6D5CC] flex-shrink-0">
                    <svg class="h-10 w-10 text-[#8D7B68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>

                <div class="flex-1 space-y-2">
                    <h1 class="text-3xl font-bold text-[#5A4D41] tracking-tight">Reportes Analíticos</h1>
                    <p class="text-lg text-gray-600 font-medium leading-relaxed">
                        Sistema Integral de Análisis y Estadísticas.
                        <span class="block text-gray-500 text-base">Acceda a información detallada de cada módulo.</span>
                    </p>
                </div>

                <div class="hidden md:block">
                    <a href="index.php?route=dashboard" class="inline-flex items-center space-x-2 rounded-full bg-[#F4EBE7] px-5 py-3 border border-[#E6D5CC] transition-all hover:bg-[#E6D5CC]">
                        <svg class="h-5 w-5 text-[#8D7B68]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="text-sm font-bold text-[#8D7B68]">Volver al Dashboard</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Grid de Reportes -->
        <section class="rounded-3xl bg-white shadow-lg border border-stone-200 overflow-hidden">
            <div class="border-b border-stone-200 bg-[#F9F5F3] px-6 py-6 md:px-8 md:py-6">
                <h2 class="text-xl font-bold text-[#5A4D41] flex items-center gap-3">
                    <svg class="w-6 h-6 text-[#8D7B68]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Categorías de Reportes
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

                    <!-- Reporte de Libros -->
                    <a href="index.php?route=reportes/reporteLibros" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center border border-yellow-200 group-hover:bg-yellow-200 transition-colors">
                                <svg class="w-7 h-7 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Libros Parroquiales</h3>
                        <p class="text-sm text-gray-600 font-medium">Distribución por tipo, capacidad y libros activos</p>
                    </a>

                    <!-- Reporte de Usuarios -->
                    <a href="index.php?route=reportes/reporteUsuarios" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center border border-blue-200 group-hover:bg-blue-200 transition-colors">
                                <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Usuarios y Roles</h3>
                        <p class="text-sm text-gray-600 font-medium">Distribución por rol, datos incompletos y nuevos registros</p>
                    </a>

                    <!-- Reporte de Noticias -->
                    <a href="index.php?route=reportes/reporteNoticias" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center border border-red-200 group-hover:bg-red-200 transition-colors">
                                <svg class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Noticias</h3>
                        <p class="text-sm text-gray-600 font-medium">Publicaciones por período y autores más activos</p>
                    </a>

                    <!-- Reporte de Contactos -->
                    <a href="index.php?route=reportes/reporteContactos" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-pink-100 rounded-xl flex items-center justify-center border border-pink-200 group-hover:bg-pink-200 transition-colors">
                                <svg class="w-7 h-7 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Contactos</h3>
                        <p class="text-sm text-gray-600 font-medium">Mensajes recibidos y análisis por período</p>
                    </a>

                    <!-- Reporte Comparativo -->
                    <a href="index.php?route=reportes/reporteComparativo" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center border border-orange-200 group-hover:bg-orange-200 transition-colors">
                                <svg class="w-7 h-7 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Comparativo Anual</h3>
                        <p class="text-sm text-gray-600 font-medium">Comparación año contra año con métricas de crecimiento</p>
                    </a>

                    <!-- Reporte Ejecutivo -->
                    <a href="index.php?route=reportes/reporteEjecutivo" class="group bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200 transition-all hover:shadow-lg hover:border-[#8D7B68] hover:scale-[1.02]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-[#E6D5CC] rounded-xl flex items-center justify-center border border-[#D0B8A8] group-hover:bg-[#D0B8A8] transition-colors">
                                <svg class="w-7 h-7 text-[#8D7B68]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <svg class="h-5 w-5 text-[#8D7B68] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-[#5A4D41] mb-2">Dashboard Ejecutivo</h3>
                        <p class="text-sm text-gray-600 font-medium">Resumen general con métricas clave del sistema</p>
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
                        <br>Puede exportar cualquier reporte a formato PDF o Excel para su análisis posterior.
                    </p>
                </div>
            </div>
        </section>

    </main>
