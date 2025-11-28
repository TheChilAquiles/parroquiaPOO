


    <style>

        * {
            font-family: 'Inter', sans-serif;
        }

        .fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-card {
            transition: all 0.3s ease;
        }

        .section-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(171, 135, 111, 0.15);
        }

        .code-preview {
            background: linear-gradient(135deg, #f5f3f0 0%, #e8e4df 100%);
        }
    </style>


<body class="bg-gradient-to-br from-gray-50 to-gray-100">

    <!-- Hero Section -->
    <header class="relative bg-gradient-to-r from-[#D0B8A8] via-[#b5a394] to-[#ab876f] text-white overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>

        <!-- Elementos decorativos -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-16 w-16 h-16 bg-white/10 rounded-full animate-bounce"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-20 text-center">
            <div
                class="inline-flex items-center justify-center w-24 h-24 bg-white/20 rounded-full mb-8 backdrop-blur-sm">
                <span class="material-icons text-5xl">menu_book</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-2xl">
                Manual de Usuario
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                Guía completa del Sistema de Gestión Parroquial San Francisco de Asís
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#inicio"
                    class="bg-white text-[#ab876f] px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition duration-300 shadow-xl">
                    Comenzar
                </a>
                <a href="#secciones"
                    class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-[#ab876f] transition duration-300">
                    Ver Secciones
                </a>
            </div>
        </div>

        <!-- Ola decorativa -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-16 fill-white">
                <path
                    d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z">
                </path>
            </svg>
        </div>
    </header>

    <!-- Navegación Rápida -->
    <nav id="inicio" class="sticky top-0 z-40 bg-white shadow-md">
        <div class="max-w-full justify-self-center mx-auto px-6">
            <div class="flex overflow-x-auto py-4 space-x-6 scrollbar-hide">
                <a href="#home"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full bg-[#D0B8A8] text-white hover:bg-[#ab876f] transition whitespace-nowrap">
                    <span class="material-icons">home</span>
                    <span>Inicio</span>
                </a>
                <a href="#historia"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-[#D0B8A8] hover:text-white transition whitespace-nowrap">
                    <span class="material-icons">history</span>
                    <span>Historia</span>
                </a>
                <a href="#noticias"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-[#D0B8A8] hover:text-white transition whitespace-nowrap">
                    <span class="material-icons">article</span>
                    <span>Noticias</span>
                </a>
                <a href="#contacto"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-[#D0B8A8] hover:text-white transition whitespace-nowrap">
                    <span class="material-icons">phone</span>
                    <span>Contacto</span>
                </a>
                <a href="#dashboard"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-[#D0B8A8] hover:text-white transition whitespace-nowrap">
                    <span class="material-icons">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="#grupos"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-[#D0B8A8] hover:text-white transition whitespace-nowrap">
                    <span class="material-icons">groups</span>
                    <span>Grupos</span>
                </a>
                <a href="#reportes"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-[#D0B8A8] hover:text-white transition whitespace-nowrap">
                    <span class="material-icons">assessment</span>
                    <span>Reportes</span>
                </a>
                <a href="#pagos"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-[#D0B8A8] hover:text-white transition whitespace-nowrap">
                    <span class="material-icons">payments</span>
                    <span>Pagos</span>
                </a>
                <a href="#auth"
                    class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-100 hover:bg-[#D0B8A8] hover:text-white transition whitespace-nowrap">
                    <span class="material-icons">lock</span>
                    <span>Autenticación</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-6 py-16">

        <!-- Introducción -->
        <section id="secciones" class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Secciones del Sistema</h2>
                <p class="text-xl text-gray-600">Conoce todas las funcionalidades disponibles</p>
            </div>
        </section>

        <!-- 1. PÁGINA DE INICIO -->
        <section id="home" class="mb-20 fade-in">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden section-card">
                <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] p-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="material-icons text-3xl">home</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">Página de Inicio</h3>
                            <p class="text-lg opacity-90">Vista principal del sitio web</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">info</span>
                                ¿Qué es?
                            </h4>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                La página de inicio es el punto de entrada principal del sitio web. Presenta información
                                general sobre la Parroquia San Francisco de Asís, incluyendo la misión, horarios de
                                misa, servicios disponibles y formas de contacto.
                            </p>

                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">checklist</span>
                                Componentes Principales
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">check_circle</span>
                                    <span><strong>Hero Section:</strong> Presentación principal con imagen de
                                        fondo</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">check_circle</span>
                                    <span><strong>Sobre Nosotros:</strong> Historia y misión de la parroquia</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">check_circle</span>
                                    <span><strong>Horarios:</strong> Información de misas y confesiones</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">check_circle</span>
                                    <span><strong>Servicios:</strong> Bautizos, confirmaciones, matrimonios</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">check_circle</span>
                                    <span><strong>Contacto:</strong> Dirección, teléfono y email</span>
                                </li>
                            </ul>
                        </div>

                        <div class="code-preview p-6 rounded-2xl">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Vista Previa Visual</h4>
                            <div class="bg-white rounded-xl overflow-hidden shadow-lg">
                                <div
                                    class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] h-32 flex items-center justify-center text-white">
                                    <div class="text-center">
                                        <h5 class="text-2xl font-bold">San Francisco de Asís</h5>
                                        <p class="text-sm opacity-90">Bienvenidos</p>
                                    </div>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                                    <div class="h-3 bg-gray-200 rounded w-full"></div>
                                    <div class="h-3 bg-gray-200 rounded w-5/6"></div>
                                    <div class="grid grid-cols-3 gap-2 mt-4">
                                        <div class="h-16 bg-blue-100 rounded"></div>
                                        <div class="h-16 bg-green-100 rounded"></div>
                                        <div class="h-16 bg-purple-100 rounded"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h5 class="font-semibold text-gray-900 mb-2">Características Clave:</h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-[#ab876f] text-sm">star</span>
                                        <span>Diseño responsive para móviles</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-[#ab876f] text-sm">star</span>
                                        <span>Navegación intuitiva con scroll suave</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="material-icons text-[#ab876f] text-sm">star</span>
                                        <span>Enlaces directos a secciones importantes</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-blue-50 rounded-xl border-l-4 border-blue-500">
                        <h5 class="font-bold text-blue-900 mb-2 flex items-center">
                            <span class="material-icons mr-2">lightbulb</span>
                            Consejo para Usuarios
                        </h5>
                        <p class="text-blue-800">
                            Usa el botón "Ver Todas las Noticias" para mantenerte actualizado sobre eventos y
                            actividades de la parroquia. Los horarios de misa están siempre visibles en la sección
                            central.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. SECCIÓN HISTORIA -->
        <section id="historia" class="mb-20 fade-in" style="animation-delay: 0.1s">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden section-card">
                <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] p-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="material-icons text-3xl">history</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">Nuestra Historia</h3>
                            <p class="text-lg opacity-90">Línea de tiempo interactiva</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">info</span>
                                ¿Qué es?
                            </h4>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                Una línea de tiempo visual que narra la historia de 27 años de la Parroquia San
                                Francisco de Asís, desde sus humildes inicios en 1996 hasta la actualidad. Cada hito
                                importante está destacado con iconos y descripciones detalladas.
                            </p>

                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">timeline</span>
                                Hitos Históricos
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="material-icons text-blue-600 mr-2 mt-0.5">house</span>
                                    <span><strong>1996:</strong> Construcción de la primera capilla (50 familias)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">groups</span>
                                    <span><strong>2003:</strong> Crecimiento a 200 familias y primer párroco</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-purple-600 mr-2 mt-0.5">build</span>
                                    <span><strong>2010:</strong> Ampliación del templo (300 personas)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-red-600 mr-2 mt-0.5">gavel</span>
                                    <span><strong>2015:</strong> Decreto oficial de creación</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-indigo-600 mr-2 mt-0.5">devices</span>
                                    <span><strong>2020:</strong> Transformación digital</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-teal-600 mr-2 mt-0.5">eco</span>
                                    <span><strong>2023:</strong> Primera "Parroquia Verde" de Bosa</span>
                                </li>
                            </ul>
                        </div>

                        <div class="code-preview p-6 rounded-2xl">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Diseño Visual</h4>
                            <div class="bg-white rounded-xl overflow-hidden shadow-lg p-4">
                                <div class="relative">
                                    <div
                                        class="absolute left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-[#D0B8A8] to-[#ab876f]">
                                    </div>

                                    <!-- Timeline items -->
                                    <div class="space-y-8">
                                        <div class="flex items-center relative">
                                            <div class="w-1/2 pr-8 text-right">
                                                <div
                                                    class="bg-gradient-to-l from-[#D0B8A8] to-[#ab876f] p-3 rounded-lg text-white text-xs">
                                                    <strong>1996</strong> - Los inicios
                                                </div>
                                            </div>
                                            <div
                                                class="absolute left-1/2 w-4 h-4 bg-[#D0B8A8] rounded-full -ml-2 border-2 border-white">
                                            </div>
                                            <div class="w-1/2"></div>
                                        </div>

                                        <div class="flex items-center relative">
                                            <div class="w-1/2"></div>
                                            <div
                                                class="absolute left-1/2 w-4 h-4 bg-[#ab876f] rounded-full -ml-2 border-2 border-white">
                                            </div>
                                            <div class="w-1/2 pl-8">
                                                <div
                                                    class="bg-gradient-to-r from-[#ab876f] to-[#D0B8A8] p-3 rounded-lg text-white text-xs">
                                                    <strong>2023</strong> - Actualidad
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h5 class="font-semibold text-gray-900 mb-2">Espacios Sagrados:</h5>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="p-3 bg-white rounded-lg shadow text-center">
                                        <span class="material-icons text-[#ab876f]">church</span>
                                        <p class="mt-1 font-medium">Altar Mayor</p>
                                    </div>
                                    <div class="p-3 bg-white rounded-lg shadow text-center">
                                        <span class="material-icons text-[#ab876f]">water_drop</span>
                                        <p class="mt-1 font-medium">Baptisterio</p>
                                    </div>
                                    <div class="p-3 bg-white rounded-lg shadow text-center">
                                        <span class="material-icons text-[#ab876f]">favorite</span>
                                        <p class="mt-1 font-medium">Altar Mariano</p>
                                    </div>
                                    <div class="p-3 bg-white rounded-lg shadow text-center">
                                        <span class="material-icons text-[#ab876f]">local_fire_department</span>
                                        <p class="mt-1 font-medium">Adoración</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-purple-50 rounded-xl border-l-4 border-purple-500">
                        <h5 class="font-bold text-purple-900 mb-2 flex items-center">
                            <span class="material-icons mr-2">auto_stories</span>
                            Dato Interesante
                        </h5>
                        <p class="text-purple-800">
                            La parroquia creció de 50 familias fundadoras en 1996 a más de 800 familias activas hoy.
                            Cada card de la línea de tiempo es interactiva y muestra más detalles al hacer clic.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. SISTEMA DE NOTICIAS -->
        <section id="noticias" class="mb-20 fade-in" style="animation-delay: 0.2s">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden section-card">
                <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] p-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="material-icons text-3xl">article</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">Sistema de Noticias</h3>
                            <p class="text-lg opacity-90">Gestión completa de publicaciones</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">info</span>
                                ¿Qué es?
                            </h4>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                Sistema completo para gestionar noticias y anuncios parroquiales. Los administradores
                                pueden crear, editar y eliminar noticias, mientras que todos los usuarios pueden buscar
                                y visualizar las publicaciones en un diseño moderno tipo tarjetas.
                            </p>

                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">settings</span>
                                Funciones Principales
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">add_circle</span>
                                    <span><strong>Crear:</strong> Publicar noticias con título, descripción e
                                        imagen</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-blue-600 mr-2 mt-0.5">edit</span>
                                    <span><strong>Editar:</strong> Modificar contenido existente (solo admin)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-red-600 mr-2 mt-0.5">delete</span>
                                    <span><strong>Eliminar:</strong> Borrar noticias con confirmación</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-purple-600 mr-2 mt-0.5">search</span>
                                    <span><strong>Buscar:</strong> Sistema de búsqueda por título/descripción</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-orange-600 mr-2 mt-0.5">image</span>
                                    <span><strong>Imágenes:</strong> Subida de imágenes con vista previa</span>
                                </li>
                            </ul>
                        </div>

                        <div class="code-preview p-6 rounded-2xl">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Interfaz Visual</h4>

                            <!-- Card de noticia simulada -->
                            <div class="bg-white rounded-2xl overflow-hidden shadow-xl">
                                <div class="h-32 bg-gradient-to-br from-blue-400 to-blue-600"></div>
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">27 Oct,
                                            2025</span>
                                        <span class="text-xs text-gray-500">Lectura: 2 min</span>
                                    </div>
                                    <h5 class="font-bold text-gray-900 mb-2">Título de la Noticia</h5>
                                    <p class="text-sm text-gray-600">Descripción breve del contenido...</p>

                                    <!-- Botones admin -->
                                    <div class="flex gap-2 mt-4">
                                        <button
                                            class="flex-1 bg-blue-500 text-white py-2 rounded-lg text-sm font-semibold flex items-center justify-center">
                                            <span class="material-icons text-sm mr-1">edit</span>
                                            Editar
                                        </button>
                                        <button
                                            class="flex-1 bg-red-500 text-white py-2 rounded-lg text-sm font-semibold flex items-center justify-center">
                                            <span class="material-icons text-sm mr-1">delete</span>
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h5 class="font-semibold text-gray-900 mb-3">Roles de Usuario:</h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                        <span>👤 Administrador/Secretario</span>
                                        <span class="text-green-700 font-semibold">✓ Control Total</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 bg-blue-50 rounded">
                                        <span>👥 Usuarios Generales</span>
                                        <span class="text-blue-700 font-semibold">👁 Solo Lectura</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-green-50 rounded-xl border-l-4 border-green-500">
                        <h5 class="font-bold text-green-900 mb-2 flex items-center">
                            <span class="material-icons mr-2">tips_and_updates</span>
                            Mejores Prácticas
                        </h5>
                        <p class="text-green-800">
                            Al crear noticias, usa imágenes de alta calidad (máx 5MB) y títulos descriptivos. El sistema
                            valida automáticamente los formularios y muestra notificaciones de éxito/error después de
                            cada acción.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. INFORMACIÓN DE CONTACTO -->
        <section id="contacto" class="mb-20 fade-in" style="animation-delay: 0.3s">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden section-card">
                <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] p-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="material-icons text-3xl">phone</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">Información de Contacto</h3>
                            <p class="text-lg opacity-90">Facebook y datos de ubicación</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">info</span>
                                ¿Qué es?
                            </h4>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                Dashboard estilo profesional que muestra la información de contacto de la parroquia
                                enlazada con Facebook. Incluye dirección física con integración a Google Maps y número
                                telefónico con funciones de llamada directa y copia rápida.
                            </p>

                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">touch_app</span>
                                Funciones Interactivas
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="material-icons text-blue-600 mr-2 mt-0.5">facebook</span>
                                    <span><strong>Enlace a Facebook:</strong> Acceso directo a la página oficial</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">map</span>
                                    <span><strong>Ver en Mapa:</strong> Abre Google Maps con la ubicación exacta</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-orange-600 mr-2 mt-0.5">phone</span>
                                    <span><strong>Llamar Directamente:</strong> Inicia llamada desde el
                                        dispositivo</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-purple-600 mr-2 mt-0.5">content_copy</span>
                                    <span><strong>Copiar al Portapapeles:</strong> Copia teléfono con un clic</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-red-600 mr-2 mt-0.5">bookmark</span>
                                    <span><strong>Guardar/Compartir:</strong> Funciones para guardar o compartir
                                        info</span>
                                </li>
                            </ul>
                        </div>

                        <div class="code-preview p-6 rounded-2xl">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Elementos de Contacto</h4>

                            <!-- Dirección -->
                            <div class="bg-white rounded-xl p-4 shadow-lg mb-4">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons text-slate-600">location_on</span>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="font-bold text-gray-900">Bosa San José</h5>
                                        <p class="text-sm text-gray-600">Calle 86 a sur #81-23</p>
                                        <div class="flex gap-2 mt-2">
                                            <button class="text-xs bg-slate-900 text-white px-3 py-1 rounded-lg">Ver
                                                Mapa</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="bg-white rounded-xl p-4 shadow-lg">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="material-icons text-slate-600">phone</span>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="font-bold text-gray-900">Teléfono</h5>
                                        <p class="text-sm text-gray-700 font-mono">601 402 3526</p>
                                        <div class="flex gap-2 mt-2">
                                            <button
                                                class="text-xs bg-slate-200 text-gray-800 px-3 py-1 rounded-lg">Copiar</button>
                                            <button
                                                class="text-xs bg-slate-900 text-white px-3 py-1 rounded-lg">Llamar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 p-3 bg-blue-50 rounded-lg">
                                <h5 class="text-sm font-semibold text-blue-900 mb-2">💡 Características:</h5>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li>✓ Modal con detalles adicionales</li>
                                    <li>✓ Notificaciones toast al copiar</li>
                                    <li>✓ Diseño responsive optimizado</li>
                                    <li>✓ Integración nativa con apps de mapas/teléfono</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-cyan-50 rounded-xl border-l-4 border-cyan-500">
                        <h5 class="font-bold text-cyan-900 mb-2 flex items-center">
                            <span class="material-icons mr-2">smartphone</span>
                            Experiencia Móvil
                        </h5>
                        <p class="text-cyan-800">
                            En dispositivos móviles, los botones de "Llamar" y "Ver en Mapa" abren automáticamente las
                            aplicaciones nativas del teléfono (Teléfono, Google Maps, etc.), proporcionando una
                            experiencia fluida y rápida.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. DASHBOARD ADMINISTRATIVO -->
        <section id="dashboard" class="mb-20 fade-in" style="animation-delay: 0.4s">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden section-card">
                <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] p-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="material-icons text-3xl">dashboard</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">Dashboard Administrativo</h3>
                            <p class="text-lg opacity-90">Panel de control y estadísticas</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">info</span>
                                ¿Qué es?
                            </h4>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                Panel de control completo que muestra estadísticas en tiempo real de todo el sistema
                                parroquial. Conecta directamente con la base de datos para mostrar métricas actualizadas
                                de usuarios, libros, documentos, reportes, pagos y contactos mediante gráficos
                                interactivos.
                            </p>

                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">analytics</span>
                                Módulos de Estadísticas
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="material-icons text-blue-600 mr-2 mt-0.5">people</span>
                                    <span><strong>Usuarios:</strong> Total, roles y feligreses registrados</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-yellow-600 mr-2 mt-0.5">book</span>
                                    <span><strong>Libros:</strong> Total, tipos y registros en biblioteca</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-red-600 mr-2 mt-0.5">description</span>
                                    <span><strong>Documentos:</strong> Tipos y cantidad total gestionada</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-purple-600 mr-2 mt-0.5">bar_chart</span>
                                    <span><strong>Reportes:</strong> Total generados con categorías</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">payments</span>
                                    <span><strong>Pagos:</strong> Estados (completos, cancelados, pendientes)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-teal-600 mr-2 mt-0.5">contacts</span>
                                    <span><strong>Contactos:</strong> Base de datos de la comunidad</span>
                                </li>
                            </ul>
                        </div>

                        <div class="code-preview p-6 rounded-2xl">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Visualización de Datos</h4>

                            <!-- Gráficos simulados -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-white p-3 rounded-lg shadow">
                                    <div class="text-center">
                                        <div
                                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <span class="material-icons text-blue-600">people</span>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900">850</p>
                                        <p class="text-xs text-gray-600">Usuarios</p>
                                    </div>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow">
                                    <div class="text-center">
                                        <div
                                            class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <span class="material-icons text-green-600">check_circle</span>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900">245</p>
                                        <p class="text-xs text-gray-600">Completos</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tipos de gráficos -->
                            <div class="bg-white rounded-lg p-4 shadow">
                                <h5 class="font-semibold text-gray-900 mb-3 text-sm">Tipos de Gráficos:</h5>
                                <div class="space-y-2 text-xs">
                                    <div class="flex items-center justify-between p-2 bg-blue-50 rounded">
                                        <span>📊 Gráficos de Barras</span>
                                        <span class="text-blue-700 font-semibold">Usuarios, Reportes</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 bg-purple-50 rounded">
                                        <span>🍩 Gráficos Donut</span>
                                        <span class="text-purple-700 font-semibold">Libros, Pagos</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                        <span>🥧 Gráficos de Pastel</span>
                                        <span class="text-green-700 font-semibold">Documentos</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 bg-indigo-50 rounded">
                                        <span>📡 Gráfico Radar</span>
                                        <span class="text-indigo-700 font-semibold">Resumen General</span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-4 p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-200">
                                <p class="text-xs text-purple-900">
                                    <strong>⚡ Actualización:</strong> Los datos se actualizan automáticamente cada 5
                                    minutos
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 grid md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                            <h5 class="font-bold text-blue-900 mb-2 flex items-center">
                                <span class="material-icons mr-2 text-sm">speed</span>
                                Rendimiento
                            </h5>
                            <p class="text-sm text-blue-800">Usa Chart.js para renderizado rápido de gráficos con
                                animaciones fluidas</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                            <h5 class="font-bold text-green-900 mb-2 flex items-center">
                                <span class="material-icons mr-2 text-sm">security</span>
                                Seguridad
                            </h5>
                            <p class="text-sm text-green-800">Conexión PDO con manejo de errores y consultas preparadas
                            </p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                            <h5 class="font-bold text-purple-900 mb-2 flex items-center">
                                <span class="material-icons mr-2 text-sm">responsive</span>
                                Responsive
                            </h5>
                            <p class="text-sm text-purple-800">Adaptable a tablets y móviles con grid responsive</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. GESTIÓN DE GRUPOS -->
        <section id="grupos" class="mb-20 fade-in" style="animation-delay: 0.5s">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden section-card">
                <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] p-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="material-icons text-3xl">groups</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">Gestión de Grupos</h3>
                            <p class="text-lg opacity-90">Administración de grupos parroquiales</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">info</span>
                                ¿Qué es?
                            </h4>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                Sistema completo para crear y administrar grupos parroquiales (coros, catequesis, grupos
                                juveniles, etc.). Permite gestionar miembros, visualizar información detallada y
                                realizar operaciones CRUD completas con confirmaciones de seguridad.
                            </p>

                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">checklist</span>
                                Operaciones Disponibles
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">add_circle</span>
                                    <span><strong>Crear Grupo:</strong> Modal rápido con validación de nombre
                                        único</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-blue-600 mr-2 mt-0.5">visibility</span>
                                    <span><strong>Ver Detalles:</strong> Vista completa con listado de miembros</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-purple-600 mr-2 mt-0.5">edit</span>
                                    <span><strong>Editar:</strong> Modificar nombre y configuración del grupo</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-red-600 mr-2 mt-0.5">delete</span>
                                    <span><strong>Eliminar:</strong> Borrado con confirmación de dos pasos</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-orange-600 mr-2 mt-0.5">person_add</span>
                                    <span><strong>Gestionar Miembros:</strong> Agregar/eliminar participantes</span>
                                </li>
                            </ul>
                        </div>

                        <div class="code-preview p-6 rounded-2xl">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Card de Grupo</h4>

                            <!-- Card simulada -->
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                                <div class="p-5">
                                    <div class="flex items-start justify-between mb-4">
                                        <div
                                            class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <span class="material-icons text-purple-600">groups</span>
                                        </div>
                                        <span
                                            class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                                            25 miembros
                                        </span>
                                    </div>

                                    <h5 class="font-bold text-gray-900 mb-4">Coro Parroquial</h5>

                                    <div class="space-y-2">
                                        <button
                                            class="w-full bg-purple-600 text-white py-2 rounded-lg text-sm font-semibold">
                                            Ver Detalles
                                        </button>
                                        <div class="flex gap-2">
                                            <button class="flex-1 bg-blue-100 text-blue-600 py-2 rounded-lg text-sm">
                                                <span class="material-icons text-xs">edit</span>
                                            </button>
                                            <button class="flex-1 bg-red-100 text-red-600 py-2 rounded-lg text-sm">
                                                <span class="material-icons text-xs">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h5 class="font-semibold text-gray-900 mb-3">Características de Seguridad:</h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center space-x-2 p-2 bg-yellow-50 rounded">
                                        <span class="material-icons text-yellow-600 text-sm">warning</span>
                                        <span>Confirmación antes de eliminar</span>
                                    </div>
                                    <div class="flex items-center space-x-2 p-2 bg-blue-50 rounded">
                                        <span class="material-icons text-blue-600 text-sm">verified</span>
                                        <span>Validación de formularios HTML5</span>
                                    </div>
                                    <div class="flex items-center space-x-2 p-2 bg-green-50 rounded">
                                        <span class="material-icons text-green-600 text-sm">notifications</span>
                                        <span>Notificaciones de éxito/error</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-indigo-50 rounded-xl border-l-4 border-indigo-500">
                        <h5 class="font-bold text-indigo-900 mb-2 flex items-center">
                            <span class="material-icons mr-2">keyboard</span>
                            Atajos de Teclado
                        </h5>
                        <p class="text-indigo-800 mb-3">
                            El sistema incluye accesibilidad mejorada con atajos de teclado:
                        </p>
                        <div class="grid md:grid-cols-2 gap-2 text-sm text-indigo-900">
                            <div class="flex items-center space-x-2">
                                <kbd class="px-2 py-1 bg-white rounded shadow text-xs">ESC</kbd>
                                <span>Cerrar modal</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <kbd class="px-2 py-1 bg-white rounded shadow text-xs">ENTER</kbd>
                                <span>Confirmar acción</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. SISTEMA DE REPORTES -->
        <section id="reportes" class="mb-20 fade-in" style="animation-delay: 0.6s">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden section-card">
                <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] p-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="material-icons text-3xl">assessment</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">Sistema de Reportes</h3>
                            <p class="text-lg opacity-90">Gestión avanzada con pagos integrados</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">info</span>
                                ¿Qué es?
                            </h4>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                Dashboard profesional que muestra reportes con sus pagos asociados en tiempo real.
                                Permite gestionar completamente los reportes con sistema CRUD, búsqueda avanzada,
                                filtros por estado y visualización elegante con estadísticas integradas.
                            </p>

                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">widgets</span>
                                Funcionalidades CRUD
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">add_box</span>
                                    <span><strong>Crear:</strong> Nuevo reporte con título, descripción, categoría y
                                        valor</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-blue-600 mr-2 mt-0.5">visibility</span>
                                    <span><strong>Leer:</strong> Visualización completa con estado de pago y
                                        certificado</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-orange-600 mr-2 mt-0.5">edit</span>
                                    <span><strong>Actualizar:</strong> Editar datos y cambiar estados</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-red-600 mr-2 mt-0.5">delete_forever</span>
                                    <span><strong>Eliminar:</strong> Borrado con confirmación de seguridad</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-purple-600 mr-2 mt-0.5">filter_alt</span>
                                    <span><strong>Filtrar:</strong> Por estado de pago (pagado/pendiente/activo)</span>
                                </li>
                            </ul>
                        </div>

                        <div class="code-preview p-6 rounded-2xl">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Panel de Estadísticas</h4>

                            <!-- Estadísticas simuladas -->
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200 text-center">
                                    <div class="text-2xl font-bold text-slate-800">45</div>
                                    <div class="text-xs font-medium text-blue-600">Total</div>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200 text-center">
                                    <div class="text-2xl font-bold text-slate-800">38</div>
                                    <div class="text-xs font-medium text-green-600">Pagados</div>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-200 text-center">
                                    <div class="text-2xl font-bold text-slate-800">$2.5M</div>
                                    <div class="text-xs font-medium text-yellow-600">Total</div>
                                </div>
                            </div>

                            <!-- Tabla simulada -->
                            <div class="bg-white rounded-lg shadow p-3">
                                <div class="flex items-center justify-between mb-2 pb-2 border-b">
                                    <span class="text-xs font-bold text-gray-600">ID</span>
                                    <span class="text-xs font-bold text-gray-600">Título</span>
                                    <span class="text-xs font-bold text-gray-600">Estado</span>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span
                                            class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">1</span>
                                        <span class="text-gray-700 flex-1 mx-2">Reporte Ejemplo</span>
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Pagado</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span
                                            class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">2</span>
                                        <span class="text-gray-700 flex-1 mx-2">Otro Reporte</span>
                                        <span
                                            class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Pendiente</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                <h5 class="text-sm font-semibold text-blue-900 mb-2">✨ Características Premium:</h5>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li>✓ Diseño glass-morphism moderno</li>
                                    <li>✓ Búsqueda en tiempo real</li>
                                    <li>✓ Filtros inteligentes</li>
                                    <li>✓ Animaciones suaves (hover effects)</li>
                                    <li>✓ Ver certificados directamente</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-emerald-50 rounded-xl border-l-4 border-emerald-500">
                        <h5 class="font-bold text-emerald-900 mb-2 flex items-center">
                            <span class="material-icons mr-2">trending_up</span>
                            Integración con Pagos
                        </h5>
                        <p class="text-emerald-800">
                            Cada reporte está vinculado a un pago específico. El sistema muestra el estado del pago
                            (completo/pendiente) y permite acceder directamente al certificado asociado cuando el pago
                            está completado. Los filtros permiten ver reportes por estado de pago.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. GESTIÓN DE PAGOS -->
        <section id="pagos" class="mb-20 fade-in" style="animation-delay: 0.7s">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden section-card">
                <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] p-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="material-icons text-3xl">payments</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">Gestión de Pagos</h3>
                            <p class="text-lg opacity-90">Control financiero con diseño premium</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">info</span>
                                ¿Qué es?
                            </h4>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                Sistema completo de gestión financiera con diseño vibrante y gradientes coloridos.
                                Controla todos los pagos de certificados con información detallada: valor, estado, tipo
                                de pago, fecha y certificado asociado. Incluye CRUD completo y estadísticas en tiempo
                                real.
                            </p>

                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">account_balance_wallet</span>
                                Operaciones Disponibles
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">add_circle</span>
                                    <span><strong>Agregar Pago:</strong> Registro con certificado, valor y tipo de
                                        pago</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-blue-600 mr-2 mt-0.5">visibility</span>
                                    <span><strong>Ver Detalles:</strong> Información completa con fecha y estado</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-orange-600 mr-2 mt-0.5">edit</span>
                                    <span><strong>Editar:</strong> Actualizar valores, estados y tipos</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-red-600 mr-2 mt-0.5">delete</span>
                                    <span><strong>Eliminar:</strong> Borrado con confirmación y limpieza de
                                        reportes</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-purple-600 mr-2 mt-0.5">search</span>
                                    <span><strong>Buscar:</strong> Filtrado instantáneo por cualquier campo</span>
                                </li>
                            </ul>
                        </div>

                        <div class="code-preview p-6 rounded-2xl">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Tarjetas de Estadísticas</h4>

                            <!-- Cards de estadísticas -->
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <div
                                    class="bg-gradient-to-br from-indigo-100 to-blue-100 rounded-xl p-3 border-l-4 border-blue-400 text-center">
                                    <div class="text-2xl font-black text-gray-800">156</div>
                                    <div class="text-xs font-semibold text-gray-600">Total</div>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl p-3 border-l-4 border-emerald-400 text-center">
                                    <div class="text-2xl font-black text-emerald-700">142</div>
                                    <div class="text-xs font-semibold text-gray-600">Pagados</div>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-purple-100 to-purple-100 rounded-xl p-3 border-l-4 border-purple-400 text-center">
                                    <div class="text-xl font-black text-purple-700">$8.2M</div>
                                    <div class="text-xs font-semibold text-gray-600">Valor</div>
                                </div>
                            </div>

                            <!-- Tipos de pago -->
                            <div class="bg-white rounded-lg shadow-lg p-4">
                                <h5 class="font-semibold text-gray-900 mb-3 text-sm">Tipos de Pago Soportados:</h5>
                                <div class="space-y-2 text-xs">
                                    <div class="flex items-center justify-between p-2 bg-green-50 rounded">
                                        <span>💵 Efectivo</span>
                                        <span class="text-green-700 font-bold">Tipo 1</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 bg-blue-50 rounded">
                                        <span>💳 Tarjeta Crédito/Débito</span>
                                        <span class="text-blue-700 font-bold">Tipos 2-3</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 bg-purple-50 rounded">
                                        <span>🏦 Transferencia</span>
                                        <span class="text-purple-700 font-bold">Tipo 4</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 bg-orange-50 rounded">
                                        <span>📝 Cheque</span>
                                        <span class="text-orange-700 font-bold">Tipo 5</span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-4 p-3 bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg border border-pink-200">
                                <p class="text-xs text-pink-900">
                                    <strong>🎨 Diseño Único:</strong> Usa gradientes vibrantes (amarillo, naranja, rosa,
                                    púrpura) con animación de fondo y efectos glass-morphism para una experiencia visual
                                    premium.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 grid md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gradient-to-br from-yellow-50 to-orange-100 rounded-xl">
                            <h5 class="font-bold text-orange-900 mb-2 flex items-center">
                                <span class="material-icons mr-2 text-sm">auto_awesome</span>
                                Efectos Visuales
                            </h5>
                            <p class="text-sm text-orange-800">Animaciones de shimmer en botones, hover effects en filas
                                y gradientes dinámicos en el fondo</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-pink-50 to-rose-100 rounded-xl">
                            <h5 class="font-bold text-rose-900 mb-2 flex items-center">
                                <span class="material-icons mr-2 text-sm">trending_up</span>
                                Estadísticas
                            </h5>
                            <p class="text-sm text-rose-800">Calcula automáticamente totales, pagos completados y
                                valores acumulados</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-indigo-100 rounded-xl">
                            <h5 class="font-bold text-indigo-900 mb-2 flex items-center">
                                <span class="material-icons mr-2 text-sm">security</span>
                                Seguridad
                            </h5>
                            <p class="text-sm text-indigo-800">Validación de fechas, manejo seguro de NULL y
                                confirmación antes de eliminar</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 9. SISTEMA DE AUTENTICACIÓN -->
        <section id="auth" class="mb-20 fade-in" style="animation-delay: 0.8s">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden section-card">
                <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] p-8 text-white">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <span class="material-icons text-3xl">lock</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">Sistema de Autenticación</h3>
                            <p class="text-lg opacity-90">Login y Registro seguros</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">info</span>
                                ¿Qué es?
                            </h4>
                            <p class="text-gray-700 leading-relaxed mb-6">
                                Sistema completo de autenticación con dos vistas: Login (inicio de sesión) y Registro
                                (crear cuenta). Incluye validación en tiempo real con JavaScript, manejo seguro de
                                errores PHP y uso de rutas modernas (?route=login/procesar).
                            </p>

                            <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-[#ab876f]">verified_user</span>
                                Características de Seguridad
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-2 mt-0.5">check_circle</span>
                                    <span><strong>Validación Dual:</strong> JavaScript frontend + PHP backend</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-blue-600 mr-2 mt-0.5">email</span>
                                    <span><strong>Emails Verificados:</strong> Validación de formato (@, dominio,
                                        etc.)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-purple-600 mr-2 mt-0.5">password</span>
                                    <span><strong>Contraseñas Seguras:</strong> Confirmación y encriptación</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-orange-600 mr-2 mt-0.5">error</span>
                                    <span><strong>Mensajes de Error:</strong> Feedback claro con $_SESSION</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="material-icons text-red-600 mr-2 mt-0.5">security</span>
                                    <span><strong>XSS Protection:</strong> htmlspecialchars en todas las salidas</span>
                                </li>
                            </ul>
                        </div>

                        <div class="code-preview p-6 rounded-2xl">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Formularios</h4>

                            <!-- Login simulado -->
                            <div class="bg-white rounded-xl shadow-lg p-5 mb-4">
                                <h5 class="text-lg font-bold text-gray-900 mb-4 text-center">Iniciar Sesión</h5>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Correo electrónico</label>
                                        <input type="email" placeholder="usuario@ejemplo.com"
                                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm" disabled>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-700">Contraseña</label>
                                        <input type="password" placeholder="••••••••"
                                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm" disabled>
                                    </div>
                                    <button class="w-full bg-emerald-500 text-white py-2 rounded font-semibold text-sm">
                                        Entrar
                                    </button>
                                </div>
                            </div>

                            <!-- Registro simulado -->
                            <div class="bg-white rounded-xl shadow-lg p-5">
                                <h5 class="text-lg font-bold text-gray-900 mb-4 text-center">Registrarse</h5>
                                <div class="space-y-2">
                                    <input type="email" placeholder="Email"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-xs" disabled>
                                    <input type="password" placeholder="Contraseña"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-xs" disabled>
                                    <input type="password" placeholder="Confirmar"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-xs" disabled>
                                    <button class="w-full bg-emerald-500 text-white py-2 rounded font-semibold text-xs">
                                        Registrar
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-200">
                                <h5 class="text-sm font-semibold text-red-900 mb-2">⚠️ Validaciones JavaScript:</h5>
                                <ul class="text-xs text-red-800 space-y-1">
                                    <li>✓ Campos obligatorios</li>
                                    <li>✓ Formato de email correcto</li>
                                    <li>✓ Contraseñas coincidentes</li>
                                    <li>✓ Feedback visual con bordes rojos</li>
                                    <li>✓ Prevención de envío si hay errores</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-blue-50 rounded-xl border-l-4 border-blue-500">
                        <h5 class="font-bold text-blue-900 mb-2 flex items-center">
                            <span class="material-icons mr-2">route</span>
                            Sistema de Rutas Moderno
                        </h5>
                        <p class="text-blue-800 mb-3">
                            Usa el parámetro ?route= para enrutamiento limpio:
                        </p>
                        <div class="grid md:grid-cols-2 gap-3 text-sm">
                            <div class="bg-white p-3 rounded-lg">
                                <code class="text-blue-600 font-mono text-xs">?route=login/procesar</code>
                                <p class="text-gray-600 text-xs mt-1">Procesa el inicio de sesión</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg">
                                <code class="text-purple-600 font-mono text-xs">?route=registro/procesar</code>
                                <p class="text-gray-600 text-xs mt-1">Procesa el registro</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Footer -->
        <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-black py-2">
                <div class="border-t border-white/20  text-center opacity-90">
                    <p class="text-sm mt-2">Manual de Usuario v1.0 | Última actualización: Octubre 2025</p>
                </div>
        </div>
    </main>




    <script>
        // Animación de entrada para secciones
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll para navegación
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Resaltar navegación activa
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('nav a[href^="#"]');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('bg-[#D0B8A8]', 'text-white');
                link.classList.add('bg-gray-100');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.remove('bg-gray-100');
                    link.classList.add('bg-[#D0B8A8]', 'text-white');
                }
            });
        });

        // Botón de volver arriba
        const scrollTopBtn = document.createElement('button');
        scrollTopBtn.innerHTML = '<span class="material-icons">arrow_upward</span>';
        scrollTopBtn.className = 'fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-all duration-300 z-50 opacity-0';
        scrollTopBtn.id = 'scrollTopBtn';
        document.body.appendChild(scrollTopBtn);

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTopBtn.style.opacity = '1';
            } else {
                scrollTopBtn.style.opacity = '0';
            }
        });

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Contador de secciones visitadas
        const visitedSections = new Set();
        const totalSections = 9; // ✅ Actualizado de 6 a 9

        sections.forEach(section => {
            const sectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        visitedSections.add(entry.target.id);
                        updateProgress();
                    }
                });
            }, { threshold: 0.5 });

            sectionObserver.observe(section);
        });

        function updateProgress() {
            const progress = (visitedSections.size / totalSections) * 100;
            console.log(`📖 Progreso del manual: ${Math.round(progress)}%`);
        }

        console.log('📚 Manual de Usuario cargado correctamente');
        console.log('💡 Tip: Usa la navegación superior para saltar entre secciones');
        console.log('🎯 Total de secciones: 9 (Home, Historia, Noticias, Contacto, Dashboard, Grupos, Reportes, Pagos, Auth)');
    </script>
