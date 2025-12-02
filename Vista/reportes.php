<?php
/**
 * Vista/reportes.php - Menú Principal de Reportes Analíticos
 */
require_once __DIR__ . '/../helpers.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Analíticos - Sistema Parroquial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #D0B8A8 0%, #ab876f 100%);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-xl">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-chart-line mr-3"></i>Reportes Analíticos
                    </h1>
                    <p class="text-blue-100 text-lg">Sistema Integral de Análisis y Estadísticas</p>
                </div>
                <a href="index.php?route=dashboard" class="px-6 py-3 bg-white/20 hover:bg-white/30 rounded-lg transition backdrop-blur-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Volver al Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Seleccione una Categoría de Reporte</h2>
            <p class="text-gray-600">Acceda a análisis detallados y estadísticas de cada módulo del sistema</p>
        </div>

        <!-- Grid de Reportes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Reporte de Certificados -->
            <a href="index.php?route=reportes/reporteCertificados" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-certificate text-blue-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Certificados</h3>
                <p class="text-gray-600 text-sm">Análisis de certificados por tipo, estado y tiempos de procesamiento</p>
            </a>

            <!-- Reporte de Feligreses -->
            <a href="index.php?route=reportes/reporteFeligreses" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-teal-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Feligreses</h3>
                <p class="text-gray-600 text-sm">Distribución por tipo de documento y feligreses más activos</p>
            </a>

            <!-- Reporte de Sacramentos -->
            <a href="index.php?route=reportes/reporteSacramentos" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-church text-purple-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Sacramentos</h3>
                <p class="text-gray-600 text-sm">Estadísticas por tipo de sacramento, libro y tendencias mensuales</p>
            </a>

            <!-- Reporte Financiero -->
            <a href="index.php?route=reportes/reporteFinanciero" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Financiero</h3>
                <p class="text-gray-600 text-sm">Ingresos por concepto, métodos de pago y valores promedio</p>
            </a>

            <!-- Reporte de Actividad -->
            <a href="index.php?route=reportes/reporteActividad" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-bar text-indigo-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Actividad del Sistema</h3>
                <p class="text-gray-600 text-sm">Usuarios más activos, actividad por rol y tasas de conversión</p>
            </a>

            <!-- Reporte de Libros -->
            <a href="index.php?route=reportes/reporteLibros" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-book text-yellow-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Libros Parroquiales</h3>
                <p class="text-gray-600 text-sm">Distribución por tipo, capacidad y libros activos</p>
            </a>

            <!-- Reporte de Usuarios -->
            <a href="index.php?route=reportes/reporteUsuarios" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0.6s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-shield text-blue-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Usuarios y Roles</h3>
                <p class="text-gray-600 text-sm">Distribución por rol, datos incompletos y nuevos registros</p>
            </a>

            <!-- Reporte de Noticias -->
            <a href="index.php?route=reportes/reporteNoticias" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0.7s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-newspaper text-red-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Noticias</h3>
                <p class="text-gray-600 text-sm">Publicaciones por período y autores más activos</p>
            </a>

            <!-- Reporte de Contactos -->
            <a href="index.php?route=reportes/reporteContactos" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0.8s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-pink-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope text-pink-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Contactos</h3>
                <p class="text-gray-600 text-sm">Mensajes recibidos y análisis por período</p>
            </a>

            <!-- Reporte Comparativo -->
            <a href="index.php?route=reportes/reporteComparativo" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 0.9s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-balance-scale text-orange-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Comparativo Anual</h3>
                <p class="text-gray-600 text-sm">Comparación año contra año con métricas de crecimiento</p>
            </a>

            <!-- Reporte Ejecutivo -->
            <a href="index.php?route=reportes/reporteEjecutivo" class="bg-white p-6 rounded-2xl shadow-lg border border-slate-200 card-hover fade-in" style="animation-delay: 1s">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-crown text-gray-600 text-2xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Dashboard Ejecutivo</h3>
                <p class="text-gray-600 text-sm">Resumen general con métricas clave del sistema</p>
            </a>
        </div>
    </main>

    <script>
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
    </script>
</body>
</html>
