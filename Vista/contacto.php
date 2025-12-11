<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Character encoding for Spanish characters -->
    <meta charset="UTF-8">
    <!-- Responsive design configuration for mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title shown in browser tab -->
    <title>Contacto</title>
    <!-- Load Tailwind CSS framework from CDN (external library for styling) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Import Google Inter font family with different weights */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        /* Apply Inter font to entire body */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Create grid pattern background using gradients */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, #f8fafc 1px, transparent 1px),
                linear-gradient(to bottom, #f8fafc 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Card hover effect class - defines transition duration */
        .card-hover {
            transition: all 0.3s ease;
        }

        /* When hovering over cards, move them up and add shadow (visual feedback) */
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-slate-50 bg-grid-pattern">
    <!-- Main Content container - full screen height with padding -->
    <main class="min-h-screen p-4 md:p-8">
        <!-- Header Section - top card with main information -->
        <section class="mb-6 md:mb-8 rounded-2xl bg-white p-6 md:p-8 shadow-sm border border-slate-200 card-hover">
            <!-- Flex container that stacks vertically on mobile, horizontally on desktop -->
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <!-- Left side: Logo and basic info -->
                <div class="flex items-start space-x-4 md:space-x-6">
                    <!-- Facebook Icon with verification badge -->
                    <div class="relative">
                        <!-- Blue square with Facebook 'f' logo -->
                        <div class="flex h-16 w-16 md:h-20 md:w-20 items-center justify-center rounded-2xl bg-blue from-[#1877f2] to-[#0d5cb6] shadow-lg">
                            <span class="text-2xl md:text-3xl font-black text-blue">f</span>
                        </div>
                        <!-- Green checkmark badge (absolute positioning - positioned relative to parent) -->
                        <div class="absolute -bottom-2 -right-2 flex h-6 w-6 md:h-8 md:w-8 items-center justify-center rounded-full bg-emerald-500 border-2 border-white">
                            <!-- SVG checkmark icon -->
                            <svg class="h-3 w-3 md:h-4 md:w-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Content Info - page name and verification badge -->
                    <div class="flex-1 min-w-0">
                        <!-- Title row with name and badge -->
                        <div class="mb-3 flex flex-col sm:flex-row sm:items-center gap-2">
                            <!-- Page title with text truncation if too long -->
                            <h1 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight truncate">Parroquia San José</h1>
                            <!-- Verified badge with Facebook icon -->
                            <span class="inline-flex items-center self-start rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 border border-slate-200">
                                <!-- Facebook logo SVG -->
                                <svg class="w-3 h-3 mr-1.5" fill="#1877f2" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                Página verificada
                            </span>
                        </div>

                        <!-- Additional details section -->
                        <div class="space-y-2">
                            <!-- Facebook profile link - clickable URL -->
                            <a id="fbLink"
                                href="https://www.facebook.com/francisco.deasis.79274"
                                class="inline-flex items-center text-sm text-slate-600 hover:text-[#1877f2] transition-colors duration-200 underline-offset-2 hover:underline break-all">
                                <!-- Facebook icon -->
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                <!-- URL text with truncation -->
                                <span class="truncate">facebook.com/francisco.deasis.79274</span>
                            </a>

                            <!-- Location and last update info -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-slate-500">
                                <!-- Location with pin icon -->
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Bosa, Bogotá
                                </span>
                                <!-- Last update time with clock icon -->
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

                <!-- Action Buttons on the right side -->
                <div class="flex flex-wrap gap-3">
                    <!-- Copy link button - white with border -->
                    <button id="btnCopyLink"
                        class="inline-flex items-center justify-center space-x-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:border-slate-400 hover:shadow-md flex-1 min-w-[140px]">
                        <!-- Copy icon SVG -->
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                        </svg>
                        <span>Copiar enlace</span>
                    </button>

                    <!-- Contact button - blue Facebook color -->
                    <a id="btnContact"
                        href="#contact-section"
                        class="inline-flex items-center justify-center space-x-2 rounded-xl bg-[#1877f2] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d5cb6] hover:shadow-md flex-1 min-w-[140px]">
                        <!-- Phone icon SVG -->
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        <span>Contactar</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Additional Contact Information - 2 column grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Contact Methods Card (left column) -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 card-hover">
                <!-- Card header with icon and title -->
                <div class="flex items-center space-x-3 mb-6">
                    <!-- Blue mail icon background -->
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Medios de Contacto</h3>
                </div>

                <!-- List of contact methods -->
                <div class="space-y-4">
                    <!-- Email contact method -->
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50">
                        <div class="flex items-center space-x-3">
                            <!-- Email icon in circular background -->
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <!-- Email label and address -->
                            <div>
                                <p class="text-sm font-medium text-slate-900">Correo Electrónico</p>
                                <p class="text-xs text-slate-500">parroquiasanjose@email.com</p>
                            </div>
                        </div>
                        <!-- Copy email button -->
                        <button id="btnCopiarCorreo" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Copiar
                        </button>
                    </div>

                    <!-- WhatsApp contact method -->
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50">
                        <div class="flex items-center space-x-3">
                            <!-- Message icon in green circular background -->
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <!-- WhatsApp label -->
                            <div>
                                <p class="text-sm font-medium text-slate-900">Mensajería</p>
                                <p class="text-xs text-slate-500">WhatsApp disponible</p>
                            </div>
                        </div>
                        <!-- Contact via WhatsApp button -->
                        <button id="btnContactarWhatsApp" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Contactar
                        </button>
                    </div>

                    <!-- Video call contact method -->
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50">
                        <div class="flex items-center space-x-3">
                            <!-- Video icon in purple circular background -->
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <!-- Video call label -->
                            <div>
                                <p class="text-sm font-medium text-slate-900">Video Llamadas</p>
                                <p class="text-xs text-slate-500">Programar cita previa</p>
                            </div>
                        </div>
                        <!-- Schedule video call button -->
                        <button id="btnAgendarVideo" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Agendar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Service Hours Card (right column) -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-200 card-hover">
                <!-- Card header with clock icon -->
                <div class="flex items-center space-x-3 mb-6">
                    <!-- Yellow/amber clock icon background -->
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Horarios de Servicio</h3>
                </div>

                <!-- Service hours with progress bars -->
                <div class="space-y-4">
                    <!-- General attention hours -->
                    <div class="space-y-2">
                        <!-- Hours label and time -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Atención General</span>
                            <span class="text-sm font-medium text-slate-900">8:00 AM - 5:00 PM</span>
                        </div>
                        <!-- Progress bar showing 85% (visual representation of availability) -->
                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>

                    <!-- Special attention hours -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Atención Especial</span>
                            <span class="text-sm font-medium text-slate-900">9:00 AM - 1:00 PM</span>
                        </div>
                        <!-- Progress bar showing 60% -->
                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>

                    <!-- Emergency services - 24/7 -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Servicios de Emergencia</span>
                            <span class="text-sm font-medium text-slate-900">24/7</span>
                        </div>
                        <!-- Progress bar showing 100% (always available) -->
                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                            <div class="bg-red-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>

                    <!-- Current status indicator -->
                    <div class="pt-4 border-t border-slate-200">
                        <div class="flex items-center space-x-2 text-sm text-slate-600">
                            <!-- Green checkmark icon -->
                            <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Abierto ahora • Cierra a las 5:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information Section - full width -->
        <section id="contact-section" class="rounded-2xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <!-- Section Header with gradient background -->
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 md:px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Información de Contacto</h2>
                        <p class="text-sm text-slate-600 mt-1">Dirección física y medios de comunicación directos</p>
                    </div>
                </div>
            </div>

            <!-- Section content with contact cards -->
            <div class="p-6 md:p-8 space-y-8">
                <!-- Address Item - first contact card -->
                <div class="group flex flex-col lg:flex-row lg:items-start gap-6 rounded-xl p-6 transition-all duration-200 hover:bg-slate-50/80 border border-slate-200/50 card-hover">
                    <!-- Location image -->
                    <div class="relative w-full lg:w-auto">
                        <!-- Image from Unsplash (external image source) -->
                        <img src="https://images.unsplash.com/photo-1545235617-9465d2a55698?q=80&w=256&auto=format&fit=crop"
                            alt="Bosa San José"
                            class="h-48 lg:h-20 lg:w-28 rounded-xl object-cover shadow-md w-full" />
                        <!-- Dark gradient overlay on image -->
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-t from-slate-900/30 to-transparent"></div>
                    </div>

                    <!-- Address details -->
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <div class="space-y-3">
                                <!-- Location icon and title -->
                                <div class="flex items-center space-x-2">
                                    <!-- Dark location pin icon -->
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-900">Ubicación Principal</h3>
                                </div>

                                <!-- Full address text -->
                                <div class="space-y-1">
                                    <!-- Main address (referenced by JavaScript via ID) -->
                                    <p id="direccion" class="text-base text-slate-700 font-medium">Calle 86 a sur #81-23, Bogotá</p>
                                    <p class="text-sm text-slate-500">Bosa San José • Zona residencial</p>
                                </div>

                                <!-- Status and travel time info -->
                                <div class="flex items-center space-x-4 text-sm text-slate-500">
                                    <span class="flex items-center space-x-1.5">
                                        <!-- Clock icon -->
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12,6 12,12 16,14" />
                                        </svg>
                                        <span>Abierto ahora • ~23 min de tráfico</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Active status badge (green) -->
                            <span class="inline-flex items-center self-start space-x-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 border border-emerald-200">
                                <!-- Green dot indicator -->
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span>Activo</span>
                            </span>
                        </div>
                    </div>

                    <!-- Action buttons for address -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <!-- View on map button (link - will be populated by JavaScript) -->
                        <a id="btnMapa"
                            href="#"
                            target="_blank"
                            class="inline-flex items-center justify-center space-x-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:shadow-md">
                            <!-- Map pin icon -->
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span>Ver en Mapa</span>
                        </a>
                        <!-- View details button (opens modal) -->
                        <button id="btnDetalles"
                            class="inline-flex items-center justify-center space-x-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-slate-800 hover:shadow-md">
                            <span>Ver detalles</span>
                            <!-- Right arrow icon -->
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9,18 15,12 9,6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Phone Item - second contact card -->
                <div class="group flex flex-col lg:flex-row lg:items-start gap-6 rounded-xl p-6 transition-all duration-200 hover:bg-slate-50/80 border border-slate-200/50 card-hover">
                    <!-- Phone icon illustration (decorative) -->
                    <div class="flex h-48 lg:h-20 lg:w-28 items-center justify-center rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 shadow-md w-full lg:w-auto">
                        <!-- White circle with phone icon inside -->
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-md">
                            <svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Phone number details -->
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <div class="space-y-3">
                                <!-- Phone icon and title -->
                                <div class="flex items-center space-x-2">
                                    <!-- Dark phone icon background -->
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-900">Teléfono de Contacto</h3>
                                </div>

                                <!-- Phone number display -->
                                <div class="space-y-1">
                                    <!-- Phone number (referenced by JavaScript via ID) -->
                                    <p id="telefono" class="text-xl font-semibold text-slate-900 tracking-tight">601 402 3526</p>
                                    <p class="text-sm text-slate-500">Atención: Lunes a Viernes • 8:00 AM - 5:00 PM</p>
                                </div>

                                <!-- Service badges -->
                                <div class="flex flex-wrap gap-2">
                                    <!-- Blue badge for preferential attention -->
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 border border-blue-200">
                                        Atención preferencial
                                    </span>
                                    <!-- Green badge for response time -->
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 border border-emerald-200">
                                        Respuesta en 24h
                                    </span>
                                </div>
                            </div>

                            <!-- Available now badge (light blue) -->
                            <span class="inline-flex items-center self-start rounded-full bg-sky-50 px-3 py-1.5 text-sm font-medium text-sky-700 border border-sky-200">
                                Disponible ahora
                            </span>
                        </div>
                    </div>

                    <!-- Action buttons for phone -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <!-- Copy phone number button -->
                        <button id="btnCopiarTelefono"
                            class="inline-flex items-center justify-center space-x-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:shadow-md">
                            <!-- Copy icon -->
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                            </svg>
                            <span>Copiar número</span>
                        </button>
                        <!-- Call now button (link - will call the number on mobile) -->
                        <a id="btnLlamar"
                            href="tel:6014023526"
                            class="inline-flex items-center justify-center space-x-2 rounded-xl bg-[#1877f2] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-[#0d5cb6] hover:shadow-md">
                            <!-- Phone icon -->
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            <span>Llamar ahora</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>


    </main>

    <!-- Professional Modal - hidden by default (Encapsulation: modal state is private until triggered) -->
    <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <!-- Dark overlay background with blur effect -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <!-- Modal card (positioned relatively to overlay) -->
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-200 p-6">
                <!-- Title section with icon -->
                <div class="flex items-center space-x-3">
                    <!-- Location pin icon -->
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-900 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Detalles de ubicación</h3>
                </div>
                <!-- Close button (X) -->
                <button id="closeModal"
                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 bg-white text-slate-600 transition-colors hover:bg-slate-50">
                    <!-- X icon -->
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content - information text -->
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Description paragraph -->
                    <p class="text-sm leading-relaxed text-slate-600">
                        Sede parroquial Bosa San José. Atención presencial en horarios de oficina.
                        Estacionamiento cercano y acceso a transporte público. Para trámites se
                        recomienda llevar documento de identidad.
                    </p>
                    <!-- Office hours information card -->
                    <div class="rounded-lg bg-slate-50 p-4">
                        <h4 class="text-sm font-semibold text-slate-900 mb-2">Horarios de atención</h4>
                        <!-- Hours list -->
                        <ul class="text-sm text-slate-600 space-y-1">
                            <!-- Weekday hours -->
                            <li class="flex justify-between">
                                <span>Lunes - Viernes</span>
                                <span class="font-medium">8:00 AM - 5:00 PM</span>
                            </li>
                            <!-- Saturday hours -->
                            <li class="flex justify-between">
                                <span>Sábados</span>
                                <span class="font-medium">9:00 AM - 1:00 PM</span>
                            </li>
                            <!-- Sunday closed -->
                            <li class="flex justify-between">
                                <span>Domingos</span>
                                <span class="font-medium">Cerrado</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Modal Footer - action buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-slate-200 p-6">
                <!-- Close button (text button) -->
                <button id="closeModal2"
                    class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                    Cerrar
                </button>
                <!-- Open in Maps button (link - will be populated by JavaScript) -->
                <a id="modalMaps"
                    target="_blank"
                    class="inline-flex items-center space-x-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-slate-800">
                    <span>Abrir en Maps</span>
                    <!-- External link icon -->
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                        <polyline points="15,3 21,3 21,9" />
                        <line x1="10" y1="14" x2="21" y2="3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Professional Toast - notification popup (hidden by default) -->
    <div id="toast" class="pointer-events-none fixed bottom-6 right-6 z-50 hidden">
        <!-- Toast card with dark background -->
        <div class="rounded-xl bg-slate-900 px-4 py-3 text-sm font-medium text-white shadow-lg border border-slate-700 flex items-center space-x-2">
            <!-- Success checkmark icon (green) -->
            <svg class="h-4 w-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <!-- Toast message text (will be changed by JavaScript) -->
            <span class="toast-message">Copiado al portapapeles</span>
        </div>
    </div>

    <!-- JavaScript code section -->
    <script>
        // Helper function: shorthand for document.getElementById (makes code cleaner)
        const $ = (id) => document.getElementById(id);

        // Copy Facebook link functionality
        // Get the copy link button element
        const btnCopyLink = $('btnCopyLink');

        // Add click event listener to copy button (Event Handling)
        btnCopyLink.addEventListener('click', async () => {
            // Store Facebook URL in a constant
            const fbLink = 'https://www.facebook.com/francisco.deasis.79274';
            try {
                // Try to copy text to clipboard using browser API (async operation)
                await navigator.clipboard.writeText(fbLink);
                // Show success message
                showToast('Enlace de Facebook copiado al portapapeles');
            } catch (error) {
                // Show error message if copy fails
                showToast('No se pudo copiar el enlace');
            }
        });

        // Maps URLs configuration
        // Get address text from HTML element
        const direccion = $('direccion').innerText;
        // Build Google Maps search URL with encoded address
        const mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(direccion);
        // Set href attribute for map button
        $('btnMapa').href = mapsUrl;
        // Set href attribute for modal map button
        $('modalMaps').href = mapsUrl;

        // Phone functionality
        // Get phone number text and remove spaces
        const tel = $('telefono').innerText.replace(/\s/g, '');
        // Set tel: link for call button (mobile devices will open phone dialer)
        $('btnLlamar').href = 'tel:' + tel;

        // Copy phone number functionality
        const btnCopiarTelefono = $('btnCopiarTelefono');
        // Check if button exists before adding event listener (defensive programming)
        if (btnCopiarTelefono) {
            // Add click event to copy phone number
            btnCopiarTelefono.addEventListener('click', async () => {
                try {
                    // Copy phone number to clipboard
                    await navigator.clipboard.writeText(tel);
                    // Show success toast
                    showToast('Número de teléfono copiado al portapapeles');
                } catch (error) {
                    // Show error toast
                    showToast('No se pudo copiar el número');
                }
            });
        }

        // Copy email functionality
        const btnCopiarCorreo = $('btnCopiarCorreo');
        // Check if button exists
        if (btnCopiarCorreo) {
            // Add click event to copy email
            btnCopiarCorreo.addEventListener('click', async () => {
                // Store email address in constant
                const correo = 'parroquiasanjose@email.com';
                try {
                    // Copy email to clipboard
                    await navigator.clipboard.writeText(correo);
                    // Show success message
                    showToast('Correo electrónico copiado al portapapeles');
                } catch (error) {
                    // Show error message
                    showToast('No se pudo copiar el correo');
                }
            });
        }

        // WhatsApp contact functionality
        const btnContactarWhatsApp = $('btnContactarWhatsApp');
        // Check if button exists
        if (btnContactarWhatsApp) {
            // Add click event to open WhatsApp
            btnContactarWhatsApp.addEventListener('click', () => {
                // Show loading message
                showToast('Redirigiendo a WhatsApp...');
                // Wait 500ms then open WhatsApp Web with phone number
                setTimeout(() => {
                    // Open WhatsApp in new tab
                    window.open('https://wa.me/573014023526', '_blank');
                }, 500);
            });
        }

        // Video call scheduling functionality
        const btnAgendarVideo = $('btnAgendarVideo');
        // Check if button exists
        if (btnAgendarVideo) {
            // Add click event for video scheduling
            btnAgendarVideo.addEventListener('click', () => {
                // Show activation message (placeholder - could open scheduling system)
                showToast('Función de agendar video llamada activada');
            });
        }

        // Enhanced Modal functionality (Modal pattern - shows/hides overlay)
        // Get modal element
        const modal = $('modal');
        
        // Function to open modal
        const openModal = () => {
            // Remove 'hidden' class to show modal
            modal.classList.remove('hidden');
            // Disable body scroll when modal is open (UX improvement)
            document.body.style.overflow = 'hidden';
        };

        // Function to close modal
        const closeModal = () => {
            // Add 'hidden' class to hide modal
            modal.classList.add('hidden');
            // Re-enable body scroll
            document.body.style.overflow = 'auto';
        };

        // Add click event to "View details" button to open modal
        $('btnDetalles').addEventListener('click', openModal);
        // Add click event to close button (X icon)
        $('closeModal').addEventListener('click', closeModal);
        // Add click event to "Close" text button
        $('closeModal2').addEventListener('click', closeModal);

        // Click outside modal to close (click on dark overlay)
        modal.addEventListener('click', (e) => {
            // If clicked element is the modal itself (not children), close it
            if (e.target === modal) closeModal();
        });

        // Enhanced Toast notification function
        // Parameter: message string to display
        function showToast(message) {
            // Get toast element
            const toast = $('toast');
            // Get the message text element inside toast
            const messageElement = toast.querySelector('.toast-message');
            // Update message text
            messageElement.textContent = message;

            // Show toast by removing 'hidden' class
            toast.classList.remove('hidden');

            // Clear any existing timeout (prevents multiple toasts overlapping)
            clearTimeout(showToast._timeout);
            // Set new timeout to hide toast after 3 seconds
            showToast._timeout = setTimeout(() => {
                // Hide toast
                toast.classList.add('hidden');
            }, 3000);
        }

        // Keyboard shortcuts (accessibility improvement)
        // Listen for keyboard events on entire document
        document.addEventListener('keydown', (e) => {
            // If Escape key pressed and modal is visible
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                // Close the modal
                closeModal();
            }
        });

        // Add subtle animation to cards on page load (DOM manipulation)
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', () => {
            // Select all elements with 'card-hover' class
            const cards = document.querySelectorAll('.card-hover');
            // Loop through each card with index
            cards.forEach((card, index) => {
                // Initially hide card (opacity 0)
                card.style.opacity = '0';
                // Initially move card down 10px
                card.style.transform = 'translateY(10px)';

                // Stagger animation: wait 100ms + (index * 50ms) before animating
                setTimeout(() => {
                    // Set transition for smooth animation
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    // Fade in card
                    card.style.opacity = '1';
                    // Move card to original position
                    card.style.transform = 'translateY(0)';
                }, 100 + index * 50); // Creates cascade effect
            });
        });
    </script>
</body>

</html>