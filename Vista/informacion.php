<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Parroquial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        .timeline-item {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease;
        }
        
        .timeline-item.animate {
            opacity: 1;
            transform: translateY(0);
        }
        
        .ministry-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .ministry-card:hover {
            transform: translateY(-8px) scale(1.02);
        }
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .pulse-glow {
            animation: pulseGlow 2s infinite;
        }
        
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(208, 184, 168, 0.3); }
            50% { box-shadow: 0 0 40px rgba(208, 184, 168, 0.7); }
        }
        
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        
        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Hero Section with Parallax -->
<section class="relative h-screen parallax-bg bg-gradient-to-br from-[#D0B8A8] via-[#b5a394] to-[#ab876f] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 bg-black/40"></div>
    
    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 floating-animation">
        <div class="w-20 h-20 bg-white/10 rounded-full glass-effect"></div>
    </div>
    <div class="absolute bottom-32 right-16 floating-animation" style="animation-delay: -2s">
        <div class="w-16 h-16 bg-white/10 rounded-full glass-effect"></div>
    </div>
    <div class="absolute top-1/2 left-1/4 floating-animation" style="animation-delay: -4s">
        <div class="w-12 h-12 bg-white/10 rounded-full glass-effect"></div>
    </div>
    
    <div class="relative text-center text-white px-4 max-w-4xl">
        <h1 class="text-6xl md:text-7xl font-bold mb-6 drop-shadow-2xl">
            San Francisco de Asís
        </h1>
        <p class="text-2xl md:text-3xl mb-8 opacity-90 font-light">
            Descubre nuestra historia, ministerios y comunidad
        </p>
        <div class="pulse-glow inline-block">
            <button onclick="scrollToSection('historia')" class="bg-white text-[#ab876f] px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition duration-300 shadow-2xl">
                Explorar Nuestra Historia
            </button>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <span class="material-icons text-4xl">keyboard_arrow_down</span>
    </div>
</section>

<!-- Historia Timeline -->
<section id="historia" class="py-20 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16 scroll-reveal">
            <h2 class="text-5xl font-bold text-gray-900 mb-6">Nuestra Historia</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Un viaje de fe que comenzó hace 27 años y continúa transformando vidas
            </p>
        </div>
        
        <div class="relative">
            <!-- Timeline Line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-[#D0B8A8] to-[#ab876f]"></div>
            
            <!-- Timeline Items -->
            <div class="space-y-16">
                <div class="timeline-item flex items-center">
                    <div class="w-1/2 pr-8 text-right">
                        <div class="bg-gradient-to-l from-[#D0B8A8] to-[#ab876f] p-8 rounded-2xl text-white shadow-2xl">
                            <h3 class="text-2xl font-bold mb-4">1996 - Los Inicios</h3>
                            <p class="text-lg opacity-90">
                                Se construyó una capilla en la vereda "San José - Bosa", atendida por sacerdotes 
                                de las parroquias María Inmaculada y Santa María de Caná
                            </p>
                        </div>
                    </div>
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-6 h-6 bg-white border-4 border-[#D0B8A8] rounded-full pulse-glow"></div>
                    <div class="w-1/2 pl-8"></div>
                </div>
                
                <div class="timeline-item flex items-center">
                    <div class="w-1/2 pr-8"></div>
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-6 h-6 bg-white border-4 border-[#ab876f] rounded-full pulse-glow"></div>
                    <div class="w-1/2 pl-8">
                        <div class="bg-gradient-to-r from-[#ab876f] to-[#D0B8A8] p-8 rounded-2xl text-white shadow-2xl">
                            <h3 class="text-2xl font-bold mb-4">2015 - Decreto Oficial</h3>
                            <p class="text-lg opacity-90">
                                El 21 de marzo, mediante el decreto N° 358, Monseñor Daniel Caro Borda 
                                oficializa la creación de nuestra parroquia
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="timeline-item flex items-center">
                    <div class="w-1/2 pr-8 text-right">
                        <div class="bg-gradient-to-l from-[#D0B8A8] to-[#ab876f] p-8 rounded-2xl text-white shadow-2xl">
                            <h3 class="text-2xl font-bold mb-4">2023 - Presente</h3>
                            <p class="text-lg opacity-90">
                                Hoy somos una comunidad vibrante que sirve a cientos de familias 
                                con amor y dedicación franciscana
                            </p>
                        </div>
                    </div>
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-6 h-6 bg-white border-4 border-[#D0B8A8] rounded-full pulse-glow"></div>
                    <div class="w-1/2 pl-8"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Arquitectura del Templo -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16 scroll-reveal">
            <h2 class="text-5xl font-bold text-gray-900 mb-6">Nuestro Templo</h2>
            <p class="text-xl text-gray-600">Cada rincón cuenta una historia de fe y devoción</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="ministry-card bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl cursor-pointer" onclick="openArchitectureModal('altar')">
                <div class="w-20 h-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full flex items-center justify-center mb-6 mx-auto">
                    <span class="material-icons text-3xl text-white">church</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">Altar Mayor</h3>
                <p class="text-gray-600 text-center">Centro de nuestras celebraciones eucarísticas</p>
            </div>
            
            <div class="ministry-card bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl cursor-pointer" onclick="openArchitectureModal('baptistery')">
                <div class="w-20 h-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full flex items-center justify-center mb-6 mx-auto">
                    <span class="material-icons text-3xl text-white">water_drop</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">Baptisterio</h3>
                <p class="text-gray-600 text-center">Donde nacemos a la vida cristiana</p>
            </div>
            
            <div class="ministry-card bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl cursor-pointer" onclick="openArchitectureModal('virgin')">
                <div class="w-20 h-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full flex items-center justify-center mb-6 mx-auto">
                    <span class="material-icons text-3xl text-white">favorite</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-4">Altar de la Virgen</h3>
                <p class="text-gray-600 text-center">Espacio de oración mariana y devoción</p>
            </div>
        </div>
    </div>
</section>

<!-- Ministerios Dinámicos -->
<section id="ministerios" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16 scroll-reveal">
            <h2 class="text-5xl font-bold text-gray-900 mb-6">Ministerios Parroquiales</h2>
            <p class="text-xl text-gray-600 mb-8">Encuentra tu lugar en nuestra comunidad de fe</p>
            
            <!-- Filtros -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button class="filter-btn active bg-[#D0B8A8] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#ab876f] transition duration-300" data-filter="all">
                    Todos los Ministerios
                </button>
                <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-300 transition duration-300" data-filter="liturgico">
                    Litúrgicos
                </button>
                <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-300 transition duration-300" data-filter="pastoral">
                    Pastorales
                </button>
                <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-300 transition duration-300" data-filter="formacion">
                    Formación
                </button>
            </div>
        </div>
        
        <div id="ministries-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Legión de María -->
            <div class="ministry-card ministry-item bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="pastoral" onclick="openMinistryModal('legion')">
                <div class="relative mb-6">
                    <img src="https://i.pinimg.com/originals/90/c9/30/90c930833a634c3ee26925ebdded25cf.png" alt="Legión de María" class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <span class="material-icons text-white text-sm">group</span>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Legión de María</h3>
                <p class="text-gray-600 text-center mb-4 text-sm">Apostolado mariano dedicado a la santificación y evangelización</p>
                <div class="flex items-center justify-center text-sm text-blue-600 font-semibold">
                    <span class="material-icons mr-1 text-lg">schedule</span>
                    Sábados • 4:00 PM
                </div>
                <div class="mt-4 flex justify-center">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">Pastoral</span>
                </div>
            </div>
            
            <!-- Monaguillos -->
            <div class="ministry-card ministry-item bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="liturgico" onclick="openMinistryModal('monaguillos')">
                <div class="relative mb-6">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMc6kqE_oMj4Rpwazy6dHq8iLnjb_7hLPcXg&s" alt="Monaguillos" class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <span class="material-icons text-white text-sm">child_care</span>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Monaguillos</h3>
                <p class="text-gray-600 text-center mb-4 text-sm">Jóvenes servidores del altar en las celebraciones litúrgicas</p>
                <div class="flex items-center justify-center text-sm text-green-600 font-semibold">
                    <span class="material-icons mr-1 text-lg">schedule</span>
                    Viernes • 4:00 PM
                </div>
                <div class="mt-4 flex justify-center">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Litúrgico</span>
                </div>
            </div>
            
            <!-- Lectores -->
            <div class="ministry-card ministry-item bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="liturgico" onclick="openMinistryModal('lectores')">
                <div class="relative mb-6">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0cLzgMrZVQLhFurSO7MqGfUeBGaPWEt6eqA&s" alt="Lectores" class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <span class="material-icons text-white text-sm">menu_book</span>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Lectores</h3>
                <p class="text-gray-600 text-center mb-4 text-sm">Proclamadores de la Palabra de Dios en la liturgia</p>
                <div class="flex items-center justify-center text-sm text-purple-600 font-semibold">
                    <span class="material-icons mr-1 text-lg">schedule</span>
                    Viernes • 4:30 PM
                </div>
                <div class="mt-4 flex justify-center">
                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">Litúrgico</span>
                </div>
            </div>
            
            <!-- Coro -->
            <div class="ministry-card ministry-item bg-gradient-to-br from-orange-50 to-orange-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="liturgico" onclick="openMinistryModal('coro')">
                <div class="relative mb-6">
                    <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center border-4 border-white shadow-lg">
                        <span class="material-icons text-white text-3xl">music_note</span>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                        <span class="material-icons text-white text-sm">queue_music</span>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Coro Parroquial</h3>
                <p class="text-gray-600 text-center mb-4 text-sm">Alabanza y música sacra en nuestras celebraciones</p>
                <div class="flex items-center justify-center text-sm text-orange-600 font-semibold">
                    <span class="material-icons mr-1 text-lg">schedule</span>
                    Jueves • 7:00 PM
                </div>
                <div class="mt-4 flex justify-center">
                    <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-semibold">Litúrgico</span>
                </div>
            </div>
            
            <!-- Catequistas -->
            <div class="ministry-card ministry-item bg-gradient-to-br from-red-50 to-red-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="formacion" onclick="openMinistryModal('catequistas')">
                <div class="relative mb-6">
                    <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center border-4 border-white shadow-lg">
                        <span class="material-icons text-white text-3xl">school</span>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                        <span class="material-icons text-white text-sm">people</span>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Catequistas</h3>
                <p class="text-gray-600 text-center mb-4 text-sm">Formación cristiana para niños, jóvenes y adultos</p>
                <div class="flex items-center justify-center text-sm text-red-600 font-semibold">
                    <span class="material-icons mr-1 text-lg">schedule</span>
                    Domingos • 9:00 AM
                </div>
                <div class="mt-4 flex justify-center">
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">Formación</span>
                </div>
            </div>
            
            <!-- Pastoral Social -->
            <div class="ministry-card ministry-item bg-gradient-to-br from-teal-50 to-teal-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="pastoral" onclick="openMinistryModal('social')">
                <div class="relative mb-6">
                    <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center border-4 border-white shadow-lg">
                        <span class="material-icons text-white text-3xl">volunteer_activism</span>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center">
                        <span class="material-icons text-white text-sm">favorite</span>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Pastoral Social</h3>
                <p class="text-gray-600 text-center mb-4 text-sm">Servicio a los más necesitados de nuestra comunidad</p>
                <div class="flex items-center justify-center text-sm text-teal-600 font-semibold">
                    <span class="material-icons mr-1 text-lg">schedule</span>
                    Sábados • 2:00 PM
                </div>
                <div class="mt-4 flex justify-center">
                    <span class="bg-teal-100 text-teal-800 px-3 py-1 rounded-full text-xs font-semibold">Pastoral</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Centro de Recursos -->
<section class="py-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16 scroll-reveal">
            <h2 class="text-5xl font-bold mb-6">Centro de Recursos</h2>
            <p class="text-xl opacity-90">Todo lo que necesitas para fortalecer tu vida espiritual</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white/10 glass-effect p-8 rounded-2xl text-center hover:bg-white/20 transition duration-300 cursor-pointer">
                <span class="material-icons text-5xl mb-4 block">auto_stories</span>
                <h3 class="text-xl font-bold mb-3">Oraciones</h3>
                <p class="opacity-80">Colección de oraciones para cada momento</p>
            </div>
            
            <div class="bg-white/10 glass-effect p-8 rounded-2xl text-center hover:bg-white/20 transition duration-300 cursor-pointer">
                <span class="material-icons text-5xl mb-4 block">music_note</span>
                <h3 class="text-xl font-bold mb-3">Cantos</h3>
                <p class="opacity-80">Repertorio de música litúrgica y devocional</p>
            </div>
            
            <div class="bg-white/10 glass-effect p-8 rounded-2xl text-center hover:bg-white/20 transition duration-300 cursor-pointer">
                <span class="material-icons text-5xl mb-4 block">calendar_month</span>
                <h3 class="text-xl font-bold mb-3">Calendario</h3>
                <p class="opacity-80">Fechas importantes y celebraciones</p>
            </div>
            
            <div class="bg-white/10 glass-effect p-8 rounded-2xl text-center hover:bg-white/20 transition duration-300 cursor-pointer">
                <span class="material-icons text-5xl mb-4 block">article</span>
                <h3 class="text-xl font-bold mb-3">Documentos</h3>
                <p class="opacity-80">Formularios y recursos pastorales</p>
            </div>
        </div>
    </div>
</section>

<!-- Directorio de Contactos -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16 scroll-reveal">
            <h2 class="text-5xl font-bold text-gray-900 mb-6">Nuestro Equipo</h2>
            <p class="text-xl text-gray-600">Conoce a quienes sirven con amor en nuestra comunidad</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl shadow-xl text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full mx-auto mb-6 flex items-center justify-center">
                    <span class="material-icons text-white text-4xl">person</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Padre José García</h3>
                <p class="text-[#ab876f] font-semibold mb-3">Párroco</p>
                <p class="text-gray-600 text-sm mb-4">Guía espiritual de nuestra comunidad</p>
                <div class="flex justify-center space-x-3">
                    <span class="material-icons text-gray-400">phone</span>
                    <span class="material-icons text-gray-400">email</span>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl shadow-xl text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full mx-auto mb-6 flex items-center justify-center">
                    <span class="material-icons text-white text-4xl">person</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">María González</h3>
                <p class="text-[#ab876f] font-semibold mb-3">Coordinadora Pastoral</p>
                <p class="text-gray-600 text-sm mb-4">Coordinación de ministerios y actividades</p>
                <div class="flex justify-center space-x-3">
                    <span class="material-icons text-gray-400">phone</span>
                    <span class="material-icons text-gray-400">email</span>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl shadow-xl text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full mx-auto mb-6 flex items-center justify-center">
                    <span class="material-icons text-white text-4xl">person</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Carlos Rodríguez</h3>
                <p class="text-[#ab876f] font-semibold mb-3">Coordinador de Liturgia</p>
                <p class="text-gray-600 text-sm mb-4">Responsable de las celebraciones litúrgicas</p>
                <div class="flex justify-center space-x-3">
                    <span class="material-icons text-gray-400">phone</span>
                    <span class="material-icons text-gray-400">email</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal para Ministerios -->
<div id="ministry-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="sticky top-0 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white p-8 rounded-t-3xl">
            <div class="flex justify-between items-center">
                <h2 id="modal-title" class="text-3xl font-bold"></h2>
                <button onclick="closeModal('ministry-modal')" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition duration-300">
                    <span class="material-icons text-white">close</span>
                </button>
            </div>
            <p id="modal-subtitle" class="text-xl opacity-90 mt-2"></p>
        </div>
        
        <div class="p-8">
            <div class="flex items-center justify-center mb-6">
                <img id="modal-image" src="" alt="" class="w-24 h-24 rounded-full object-cover shadow-lg">
            </div>
            
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                        <span class="material-icons mr-2 text-[#ab876f]">info</span>
                        ¿Qué hacemos?
                    </h3>
                    <p id="modal-description" class="text-gray-700 leading-relaxed"></p>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                        <span class="material-icons mr-2 text-[#ab876f]">schedule</span>
                        Horarios y Reuniones
                    </h3>
                    <p id="modal-schedule" class="text-gray-700"></p>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                        <span class="material-icons mr-2 text-[#ab876f]">person_add</span>
                        ¿Cómo participar?
                    </h3>
                    <p id="modal-participation" class="text-gray-700"></p>
                </div>
            </div>
            
            <div class="mt-8 flex justify-center">
                <button class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white px-8 py-4 rounded-xl font-bold text-lg hover:shadow-lg transition duration-300">
                    Quiero Participar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Arquitectura -->
<div id="architecture-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl">
        <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white p-8 rounded-t-3xl">
            <div class="flex justify-between items-center">
                <h2 id="arch-modal-title" class="text-3xl font-bold"></h2>
                <button onclick="closeModal('architecture-modal')" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition duration-300">
                    <span class="material-icons text-white">close</span>
                </button>
            </div>
        </div>
        
        <div class="p-8">
            <div class="text-center mb-6">
                <div class="w-24 h-24 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full flex items-center justify-center mx-auto mb-4">
                    <span id="arch-modal-icon" class="material-icons text-3xl text-white"></span>
                </div>
            </div>
            
            <p id="arch-modal-description" class="text-gray-700 text-lg leading-relaxed text-center"></p>
        </div>
    </div>
</div>

<script>
// Scroll animations
function handleScrollAnimations() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    const scrollReveals = document.querySelectorAll('.scroll-reveal');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate', 'revealed');
            }
        });
    }, {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    });
    
    timelineItems.forEach(item => observer.observe(item));
    scrollReveals.forEach(item => observer.observe(item));
}

// Smooth scroll
function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

// Filter functionality
function initializeFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const ministryItems = document.querySelectorAll('.ministry-item');
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterButtons.forEach(b => {
                b.classList.remove('active', 'bg-[#D0B8A8]', 'text-white');
                b.classList.add('bg-gray-200', 'text-gray-700');
            });
            btn.classList.add('active', 'bg-[#D0B8A8]', 'text-white');
            btn.classList.remove('bg-gray-200', 'text-gray-700');
            
            const filter = btn.getAttribute('data-filter');
            
            ministryItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.opacity = '1';
                    }, 100);
                } else {
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
}

// Ministry Modal Data
const ministryData = {
    'legion': {
        title: 'Legión de María',
        subtitle: 'Apostolado Mariano',
        image: 'https://i.pinimg.com/originals/90/c9/30/90c930833a634c3ee26925ebdded25cf.png',
        description: 'La Legión de María es una asociación de laicos católicos que busca la gloria de Dios a través de la santificación personal y el apostolado activo bajo la dirección de la Santísima Virgen María. Nos dedicamos a la oración, el estudio de la fe católica y las obras de misericordia espiritual y corporal.',
        schedule: 'Reuniones todos los sábados a las 4:00 PM en el salón parroquial. Duración aproximada de 1.5 horas.',
        participation: 'Abierto a todos los católicos mayores de 16 años. Se requiere compromiso semanal y participación en las actividades apostólicas asignadas.'
    },
    'monaguillos': {
        title: 'Monaguillos',
        subtitle: 'Servidores del Altar',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMc6kqE_oMj4Rpwazy6dHq8iLnjb_7hLPcXg&s',
        description: 'Los monaguillos son jóvenes que asisten al sacerdote durante las celebraciones litúrgicas, especialmente en la Santa Misa. Su servicio incluye llevar objetos litúrgicos, encender velas, ayudar en la preparación del altar y participar activamente en la liturgia.',
        schedule: 'Ensayos los viernes a las 4:00 PM. Servicio en misas dominicales y festividades especiales.',
        participation: 'Niños y jóvenes de 8 a 17 años. Se proporciona formación litúrgica básica y el vestuario necesario.'
    },
    'lectores': {
        title: 'Lectores',
        subtitle: 'Proclamadores de la Palabra',
        image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0cLzgMrZVQLhFurSO7MqGfUeBGaPWEt6eqA&s',
        description: 'Los lectores proclaman la Palabra de Dios durante las celebraciones litúrgicas, comunicando las lecturas bíblicas del Antiguo y Nuevo Testamento, así como el Salmo Responsorial. Su ministerio ayuda a que la comunidad reciba y medite la Palabra divina.',
        schedule: 'Formación y ensayos los viernes a las 4:30 PM. Servicio rotativo en todas las misas.',
        participation: 'Jóvenes y adultos con buena dicción y capacidad de lectura en público. Formación bíblica y litúrgica incluida.'
    },
    'coro': {
        title: 'Coro Parroquial',
        subtitle: 'Ministerio de Música Sacra',
        image: '',
        description: 'El coro parroquial anima las celebraciones litúrgicas con cantos sagrados, himnos y música que eleva el espíritu y facilita la oración comunitaria. Contribuimos a crear un ambiente de reverencia y alegría en las celebraciones.',
        schedule: 'Ensayos los jueves a las 7:00 PM. Participación en misas dominicales y celebraciones especiales.',
        participation: 'Abierto a personas con o sin experiencia musical. Proporcionamos formación vocal y conocimiento del repertorio litúrgico.'
    },
    'catequistas': {
        title: 'Catequistas',
        subtitle: 'Formadores en la Fe',
        image: '',
        description: 'Los catequistas acompañan a niños, jóvenes y adultos en su formación cristiana, preparándolos para recibir los sacramentos y crecer en el conocimiento y amor de Jesucristo. Transmiten la doctrina católica con amor y paciencia.',
        schedule: 'Clases los domingos a las 9:00 AM. Reuniones de formación mensual para catequistas.',
        participation: 'Adultos comprometidos con la fe católica. Se proporciona formación catequética y material didáctico.'
    },
    'social': {
        title: 'Pastoral Social',
        subtitle: 'Servicio a los Necesitados',
        image: '',
        description: 'La Pastoral Social se dedica a servir a los más vulnerables de nuestra comunidad, siguiendo el ejemplo de Cristo. Organizamos colectas, visitas a enfermos, apoyo a familias necesitadas y programas de asistencia social.',
        schedule: 'Reuniones los sábados a las 2:00 PM. Actividades de servicio según necesidades de la comunidad.',
        participation: 'Abierto a todos los que deseen servir al prójimo. Espíritu de servicio y disponibilidad de tiempo requeridos.'
    }
};

// Architecture Modal Data
const architectureData = {
    'altar': {
        title: 'Altar Mayor',
        icon: 'church',
        description: 'El altar mayor es el corazón de nuestro templo, donde se celebra diariamente el Santo Sacrificio de la Misa. Construido en mármol con detalles en bronce, representa la mesa del banquete celestial donde Cristo se hace presente en la Eucaristía para alimentar a su pueblo.'
    },
    'baptistery': {
        title: 'Baptisterio',
        icon: 'water_drop',
        description: 'Nuestro baptisterio es el lugar sagrado donde los nuevos cristianos nacen a la vida de la gracia. La pila bautismal, tallada en piedra natural, simboliza el sepulcro místico donde morimos al pecado y resucitamos como hijos de Dios.'
    },
    'virgin': {
        title: 'Altar de la Virgen',
        icon: 'favorite',
        description: 'Este altar lateral está dedicado a la Santísima Virgen María, Madre de Dios y Madre nuestra. Es un espacio de recogimiento y oración mariana, donde los fieles acuden para pedir la intercesión de la Madre celestial y ofrecer sus peticiones.'
    }
};

// Modal functions
function openMinistryModal(ministry) {
    const data = ministryData[ministry];
    if (data) {
        document.getElementById('modal-title').textContent = data.title;
        document.getElementById('modal-subtitle').textContent = data.subtitle;
        document.getElementById('modal-image').src = data.image;
        document.getElementById('modal-description').textContent = data.description;
        document.getElementById('modal-schedule').textContent = data.schedule;
        document.getElementById('modal-participation').textContent = data.participation;
        document.getElementById('ministry-modal').classList.remove('hidden');
        document.getElementById('ministry-modal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function openArchitectureModal(architecture) {
    const data = architectureData[architecture];
    if (data) {
        document.getElementById('arch-modal-title').textContent = data.title;
        document.getElementById('arch-modal-icon').textContent = data.icon;
        document.getElementById('arch-modal-description').textContent = data.description;
        document.getElementById('architecture-modal').classList.remove('hidden');
        document.getElementById('architecture-modal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    handleScrollAnimations();
    initializeFilters();
    
    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
            closeModal(e.target.id);
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('ministry-modal');
            closeModal('architecture-modal');
        }
    });
});
</script>

</body>
</html>