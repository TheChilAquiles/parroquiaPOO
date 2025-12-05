DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Dashboard - Professional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-grid-pattern {
            background-image: linear-gradient(to right, #f8fafc 1px, transparent 1px),
                linear-gradient(to bottom, #f8fafc 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-slate-50 bg-grid-pattern">
    <!-- Main Content -->
    <main class="min-h-screen p-4 md:p-8">
        <!-- Header Section -->
        <section class="mb-6 md:mb-8 rounded-2xl bg-white p-6 md:p-8 shadow-sm border border-slate-200 card-hover">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <div class="flex items-start space-x-4 md:space-x-6">
                    <!-- Facebook Icon -->
                    <div class="relative">
                        <div class="flex h-16 w-16 md:h-20 md:w-20 items-center justify-center rounded-2xl bg-blue from-[#1877f2] to-[#0d5cb6] shadow-lg">
                            <span class="text-2xl md:text-3xl font-black text-blue">f</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 flex h-6 w-6 md:h-8 md:w-8 items-center justify-center rounded-full bg-emerald-500 border-2 border-white">
                            <svg class="h-3 w-3 md:h-4 md:w-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Content Info -->
                    <div class="flex-1 min-w-0">
                        <div class="mb-3 flex flex-col sm:flex-row sm:items-center gap-2">
                            <h1 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight truncate">Parroquia San José</h1>
                            <span class="inline-flex items-center self-start rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 border border-slate-200">
                                <svg class="w-3 h-3 mr-1.5" fill="#1877f2" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                Página verificada
                            </span>
                        </div>

                        <div class="space-y-2">
                            <a id="fbLink"
                                href="https://www.facebook.com/francisco.deasis.79274"
                                class="inline-flex items-center text-sm text-slate-600 hover:text-[#1877f2] transition-colors duration-200 underline-offset-2 hover:underline break-all">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                <span class="truncate">facebook.com/francisco.deasis.79274</span>
                            </a>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-slate-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Bosa, Bogotá
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Actualizado hace 2 horas
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <button id="btnCopyLink"
                        class="inline-flex items-center justify-center space-x-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:border-slate-400 hover:shadow-md flex-1 min-w-[140px]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                        </svg>
                        <span>Copiar enlace</span>
                    </button>

                    <a id="btnContact"
                        href="#contact-section"
                        class="inline-flex items-center justify-center space-x-2 rounded-xl bg-[#1877f2] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d5cb6] hover:shadow-md flex-1 min-w-[140px]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        <span>Contactar</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Additional Contact Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Contact Methods Card -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 card-hover">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Medios de Contacto</h3>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Correo Electrónico</p>
                                <p class="text-xs text-slate-500">parroquiasanjose@email.com</p>
                            </div>
                        </div>
                        <button id="btnCopiarCorreo" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Copiar
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Mensajería</p>
                                <p class="text-xs text-slate-500">WhatsApp disponible</p>
                            </div>
                        </div>
                        <button id="btnContactarWhatsApp" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Contactar
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Video Llamadas</p>
                                <p class="text-xs text-slate-500">Programar cita previa</p>
                            </div>
                        </div>
                        <button id="btnAgendarVideo" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Agendar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Service Hours Card -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 card-hover">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Horarios de Servicio</h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Atención General</span>
                            <span class="text-sm font-medium text-slate-900">8:00 AM - 5:00 PM</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Atención Especial</span>
                            <span class="text-sm font-medium text-slate-900">9:00 AM - 1:00 PM</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Servicios de Emergencia</span>
                            <span class="text-sm font-medium text-slate-900">24/7</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                            <div class="bg-red-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <div class="flex items-center space-x-2 text-sm text-slate-600">
                            <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Abierto ahora • Cierra a las 5:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <section id="contact-section" class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <!-- Section Header -->
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 md:px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Información de Contacto</h2>
                        <p class="text-sm text-slate-600 mt-1">Dirección física y medios de comunicación directos</p>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8 space-y-8">
                <!-- Address Item -->
                <div class="group flex flex-col lg:flex-row lg:items-start gap-6 rounded-xl p-6 transition-all duration-200 hover:bg-slate-50/80 border border-slate-200/50 card-hover">
                    <div class="relative w-full lg:w-auto">
                        <img src="https://images.unsplash.com/photo-1545235617-9465d2a55698?q=80&w=256&auto=format&fit=crop"
                            alt="Bosa San José"
                            class="h-48 lg:h-20 lg:w-28 rounded-xl object-cover shadow-md w-full" />
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-t from-slate-900/30 to-transparent"></div>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-900">Ubicación Principal</h3>
                                </div>

                                <div class="space-y-1">
                                    <p id="direccion" class="text-base text-slate-700 font-medium">Calle 86 a sur #81-23, Bogotá</p>
                                    <p class="text-sm text-slate-500">Bosa San José • Zona residencial</p>
                                </div>

                                <div class="flex items-center space-x-4 text-sm text-slate-500">
                                    <span class="flex items-center space-x-1.5">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12,6 12,12 16,14" />
                                        </svg>
                                        <span>Abierto ahora • ~23 min de tráfico</span>
                                    </span>
                                </div>
                            </div>

                            <span class="inline-flex items-center self-start space-x-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 border border-emerald-200">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span>Activo</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <a id="btnMapa"
                            href="#"
                            target="_blank"
                            class="inline-flex items-center justify-center space-x-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:shadow-md">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span>Ver en Mapa</span>
                        </a>
                        <button id="btnDetalles"
                            class="inline-flex items-center justify-center space-x-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-slate-800 hover:shadow-md">
                            <span>Ver detalles</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9,18 15,12 9,6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Phone Item -->
                <div class="group flex flex-col lg:flex-row lg:items-start gap-6 rounded-xl p-6 transition-all duration-200 hover:bg-slate-50/80 border border-slate-200/50 card-hover">
                    <div class="flex h-48 lg:h-20 lg:w-28 items-center justify-center rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 shadow-md w-full lg:w-auto">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-md">
                            <svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                        </div>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <div class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-900">Teléfono de Contacto</h3>
                                </div>

                                <div class="space-y-1">
                                    <p id="telefono" class="text-xl font-semibold text-slate-900 tracking-tight">601 402 3526</p>
                                    <p class="text-sm text-slate-500">Atención: Lunes a Viernes • 8:00 AM - 5:00 PM</p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 border border-blue-200">
                                        Atención preferencial
                                    </span>
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 border border-emerald-200">
                                        Respuesta en 24h
                                    </span>
                                </div>
                            </div>

                            <span class="inline-flex items-center self-start rounded-full bg-sky-50 px-3 py-1.5 text-sm font-medium text-sky-700 border border-sky-200">
                                Disponible ahora
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <button id="btnCopiarTelefono"
                            class="inline-flex items-center justify-center space-x-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:shadow-md">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                            </svg>
                            <span>Copiar número</span>
                        </button>
                        <a id="btnLlamar"
                            href="tel:6014023526"
                            class="inline-flex items-center justify-center space-x-2 rounded-xl bg-[#1877f2] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d5cb6] hover:shadow-md">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            <span>Llamar ahora</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Note -->
        <div class="mt-8 text-center">
            <p class="text-sm text-slate-500">Última actualización: Hoy a las 11:30 AM • <span class="text-emerald-600 font-medium">Datos sincronizados</span></p>
        </div>
    </main>

    <!-- Professional Modal -->
    <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-200 p-6">
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-900 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Detalles de ubicación</h3>
                </div>
                <button id="closeModal"
                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-600 transition-colors hover:bg-slate-50">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6">
                <div class="space-y-4">
                    <p class="text-sm leading-relaxed text-slate-600">
                        Sede parroquial Bosa San José. Atención presencial en horarios de oficina.
                        Estacionamiento cercano y acceso a transporte público. Para trámites se
                        recomienda llevar documento de identidad.
                    </p>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <h4 class="text-sm font-semibold text-slate-900 mb-2">Horarios de atención</h4>
                        <ul class="text-sm text-slate-600 space-y-1">
                            <li class="flex justify-between">
                                <span>Lunes - Viernes</span>
                                <span class="font-medium">8:00 AM - 5:00 PM</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Sábados</span>
                                <span class="font-medium">9:00 AM - 1:00 PM</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Domingos</span>
                                <span class="font-medium">Cerrado</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 border-t border-slate-200 p-6">
                <button id="closeModal2"
                    class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                    Cerrar
                </button>
                <a id="modalMaps"
                    target="_blank"
                    class="inline-flex items-center space-x-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-slate-800">
                    <span>Abrir en Maps</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                        <polyline points="15,3 21,3 21,9" />
                        <line x1="10" y1="14" x2="21" y2="3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Professional Toast -->
    <div id="toast" class="pointer-events-none fixed bottom-6 right-6 z-50 hidden">
        <div class="rounded-xl bg-slate-900 px-4 py-3 text-sm font-medium text-white shadow-lg border border-slate-700 flex items-center space-x-2">
            <svg class="h-4 w-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="toast-message">Copiado al portapapeles</span>
        </div>
    </div>

    <script>
        const $ = (id) => document.getElementById(id);

        // Copy Facebook link functionality
        const btnCopyLink = $('btnCopyLink');

        btnCopyLink.addEventListener('click', async () => {
            const fbLink = 'https://www.facebook.com/francisco.deasis.79274';
            try {
                await navigator.clipboard.writeText(fbLink);
                showToast('Enlace de Facebook copiado al portapapeles');
            } catch (error) {
                showToast('No se pudo copiar el enlace');
            }
        });

        // Maps URLs
        const direccion = $('direccion').innerText;
        const mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(direccion);
        $('btnMapa').href = mapsUrl;
        $('modalMaps').href = mapsUrl;

        // Phone functionality
        const tel = $('telefono').innerText.replace(/\s/g, '');
        $('btnLlamar').href = 'tel:' + tel;

        // Copy phone number
        const btnCopiarTelefono = $('btnCopiarTelefono');
        if (btnCopiarTelefono) {
            btnCopiarTelefono.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(tel);
                    showToast('Número de teléfono copiado al portapapeles');
                } catch (error) {
                    showToast('No se pudo copiar el número');
                }
            });
        }

        // Copy email
        const btnCopiarCorreo = $('btnCopiarCorreo');
        if (btnCopiarCorreo) {
            btnCopiarCorreo.addEventListener('click', async () => {
                const correo = 'parroquiasanjose@email.com';
                try {
                    await navigator.clipboard.writeText(correo);
                    showToast('Correo electrónico copiado al portapapeles');
                } catch (error) {
                    showToast('No se pudo copiar el correo');
                }
            });
        }

        // WhatsApp contact
        const btnContactarWhatsApp = $('btnContactarWhatsApp');
        if (btnContactarWhatsApp) {
            btnContactarWhatsApp.addEventListener('click', () => {
                showToast('Redirigiendo a WhatsApp...');
                setTimeout(() => {
                    window.open('https://wa.me/573014023526', '_blank');
                }, 500);
            });
        }

        // Video call scheduling
        const btnAgendarVideo = $('btnAgendarVideo');
        if (btnAgendarVideo) {
            btnAgendarVideo.addEventListener('click', () => {
                showToast('Función de agendar video llamada activada');
            });
        }

        // Enhanced Modal
        const modal = $('modal');
        const openModal = () => {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        $('btnDetalles').addEventListener('click', openModal);
        $('closeModal').addEventListener('click', closeModal);
        $('closeModal2').addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        // Enhanced Toast
        function showToast(message) {
            const toast = $('toast');
            const messageElement = toast.querySelector('.toast-message');
            messageElement.textContent = message;

            toast.classList.remove('hidden');

            clearTimeout(showToast._timeout);
            showToast._timeout = setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Add subtle animation to cards on load
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';

                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + index * 50);
            });
        });
    </script>
</body>

</html>