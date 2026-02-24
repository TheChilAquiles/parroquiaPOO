<main class="min-h-screen">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-SnH5WK+BZxgPHs44uWIX+LLMDJ8f6y/YJ3iEIAgCg1QYI4m7fQ2a4gR2n7aT5D0W2mB75K2I5M94v9j6Z8hYQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- 
    =====================================================
    REEMPLAZA tu <section> hero por este bloque completo
    =====================================================
-->

<section class="relative h-screen flex items-center justify-center overflow-hidden">

    <!-- ===== CARRUSEL DE FONDO ===== -->
    <div id="carousel" class="absolute inset-0 z-0">
        <div class="carousel-slide active" style="background-image: url('assets/img/parroquia1.jpeg');"></div>
        <div class="carousel-slide" style="background-image: url('assets/img/parroquia2.jpeg');"></div>
        <div class="carousel-slide" style="background-image: url('assets/img/parroquia3.jpeg');"></div>
        <div class="carousel-slide" style="background-image: url('assets/img/parroquia4.jpeg');"></div>
    </div>

    <!-- Overlay oscuro encima de las imágenes -->
    <div class="absolute inset-0 bg-black/50 z-10"></div>

    <!-- ===== FLECHA IZQUIERDA ===== -->
    <button onclick="changeSlide(-1)" class="carousel-arrow left-4 md:left-8" aria-label="Anterior">
        <span class="material-icons text-3xl">chevron_left</span>
    </button>

    <!-- ===== FLECHA DERECHA ===== -->
    <button onclick="changeSlide(1)" class="carousel-arrow right-4 md:right-8" aria-label="Siguiente">
        <span class="material-icons text-3xl">chevron_right</span>
    </button>

    <!-- ===== CONTENIDO CENTRAL ===== -->
    <div class="relative z-20 text-center text-white px-4 max-w-4xl">
        <h1 class="text-6xl md:text-7xl font-bold mb-6 drop-shadow-2xl">
            San Francisco de Asís
        </h1>
        <p class="text-2xl md:text-3xl mb-8 opacity-90 font-light">
            Descubre nuestra historia, ministerios y comunidad de fe
        </p>
        <div class="pulse-glow inline-block">
            <a href="#historia" class="bg-white text-[#ab876f] px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition duration-300 shadow-2xl inline-flex items-center justify-center cursor-pointer">
                <span class="material-icons mr-2">church</span>
                Explorar Nuestra Historia
            </a>
        </div>
    </div>

    <!-- ===== PUNTOS INDICADORES ===== -->
    <div class="absolute bottom-20 left-1/2 -translate-x-1/2 z-20 flex gap-3">
        <button class="carousel-dot active" onclick="goToSlide(0)"></button>
        <button class="carousel-dot" onclick="goToSlide(1)"></button>
        <button class="carousel-dot" onclick="goToSlide(2)"></button>
        <button class="carousel-dot" onclick="goToSlide(3)"></button>
    </div>

    <!-- Flecha de scroll hacia abajo -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce z-20">
        <span class="material-icons text-4xl">keyboard_arrow_down</span>
    </div>

</section>


<!-- =====================================================
     ESTILOS — pega esto dentro de tu <style> o en estilos.css
     ===================================================== -->
<style>
/* --- Slides de fondo --- */
.carousel-slide {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}
.carousel-slide.active {
    opacity: 1;
}

/* --- Flechas de navegación --- */
.carousel-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 30;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(6px);
    border: 2px solid rgba(255, 255, 255, 0.4);
    color: white;
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}
.carousel-arrow:hover {
    background: rgba(208, 184, 168, 0.5);
    transform: translateY(-50%) scale(1.1);
}

/* --- Puntos indicadores --- */
.carousel-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    border: 2px solid rgba(255,255,255,0.6);
    cursor: pointer;
    transition: background 0.3s, transform 0.3s;
}
.carousel-dot.active {
    background: white;
    transform: scale(1.3);
}
</style>


<!-- =====================================================
     JAVASCRIPT — pega esto antes de </body> o en home.js
     ===================================================== -->
<script>
(function() {
    const slides = document.querySelectorAll('.carousel-slide');
    const dots   = document.querySelectorAll('.carousel-dot');
    let current  = 0;
    let timer;

    function showSlide(index) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('active');

        current = (index + slides.length) % slides.length;

        slides[current].classList.add('active');
        dots[current].classList.add('active');
    }

    function autoPlay() {
        timer = setInterval(() => showSlide(current + 1), 5000);
    }

    function resetTimer() {
        clearInterval(timer);
        autoPlay();
    }

    // Exponer funciones globales que usa el HTML inline
    window.changeSlide = function(dir) {
        showSlide(current + dir);
        resetTimer();
    };

    window.goToSlide = function(index) {
        showSlide(index);
        resetTimer();
    };

    // Soporte para swipe en móvil
    let touchStartX = 0;
    const section = document.querySelector('#carousel').parentElement;
    section.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; });
    section.addEventListener('touchend',   e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            changeSlide(diff > 0 ? 1 : -1);
        }
    });

    autoPlay();
})();
</script>

    <section id="historia" class="py-20 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-5xl font-bold text-gray-900 mb-6">Nuestra Historia</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Un viaje de fe que comenzó hace 27 años y continúa transformando vidas en la comunidad de Bosa
                </p>
            </div>

            <div class="relative">
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-[#D0B8A8] to-[#ab876f]"></div>

                <div class="space-y-16">
                    <div class="timeline-item flex items-center">
                        <div class="w-1/2 pr-8 text-right">
                            <div class="bg-gradient-to-l from-[#D0B8A8] to-[#ab876f] p-8 rounded-2xl text-white shadow-2xl">
                                <h3 class="text-2xl font-bold mb-4">1996 - Los Humildes Inicios</h3>
                                <p class="text-lg opacity-90">
                                    Se construyó una pequeña capilla en la vereda "San José - Bosa" con el esfuerzo de 50 familias pioneras.
                                    Los primeros servicios fueron atendidos por los sacerdotes de las parroquias María Inmaculada y Santa María de Caná,
                                    quienes viajaban cada domingo para celebrar la Eucaristía.
                                </p>
                            </div>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-10 h-10 bg-[#D0B8A8] border-4 border-white rounded-full pulse-glow flex items-center justify-center">
                            <span class="material-icons text-xl text-white">house</span>
                        </div>
                        <div class="w-1/2 pl-8"></div>
                    </div>

                    <div class="timeline-item flex items-center">
                        <div class="w-1/2 pr-8"></div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-10 h-10 bg-[#ab876f] border-4 border-white rounded-full pulse-glow flex items-center justify-center">
                            <span class="material-icons text-xl text-white">groups</span>
                        </div>
                        <div class="w-1/2 pl-8">
                            <div class="bg-gradient-to-r from-[#ab876f] to-[#D0B8A8] p-8 rounded-2xl text-white shadow-2xl">
                                <h3 class="text-2xl font-bold mb-4">2003 - Crecimiento de la Comunidad</h3>
                                <p class="text-lg opacity-90">
                                    La comunidad creció a 200 familias. Se construyó el primer salón parroquial y se establecieron
                                    los primeros grupos: Legión de María, Catequesis infantil y el coro parroquial "Voces de Asís".
                                    El padre Miguel Santamaría se convirtió en nuestro primer párroco permanente.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item flex items-center">
                        <div class="w-1/2 pr-8 text-right">
                            <div class="bg-gradient-to-l from-[#D0B8A8] to-[#ab876f] p-8 rounded-2xl text-white shadow-2xl">
                                <h3 class="text-2xl font-bold mb-4">2010 - Ampliación del Templo</h3>
                                <p class="text-lg opacity-90">
                                    Gracias a las donaciones y el trabajo comunitario, se amplió la capacidad del templo a 300 personas.
                                    Se instaló el nuevo altar de mármol traído desde Boyacá y se inauguró el baptisterio actual.
                                    La comunidad alcanzó las 400 familias registradas.
                                </p>
                            </div>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-10 h-10 bg-[#D0B8A8] border-4 border-white rounded-full pulse-glow flex items-center justify-center">
                            <span class="material-icons text-xl text-white">build</span>
                        </div>
                        <div class="w-1/2 pl-8"></div>
                    </div>

                    <div class="timeline-item flex items-center">
                        <div class="w-1/2 pr-8"></div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-10 h-10 bg-[#ab876f] border-4 border-white rounded-full pulse-glow flex items-center justify-center">
                            <span class="material-icons text-xl text-white">gavel</span>
                        </div>
                        <div class="w-1/2 pl-8">
                            <div class="bg-gradient-to-r from-[#ab876f] to-[#D0B8A8] p-8 rounded-2xl text-white shadow-2xl">
                                <h3 class="text-2xl font-bold mb-4">2015 - Decreto Oficial</h3>
                                <p class="text-lg opacity-90">
                                    El 21 de marzo, mediante el decreto N° 358, Monseñor Daniel Caro Borda, Obispo de la Diócesis de Soacha,
                                    oficializó la creación de nuestra parroquia. Ese mismo año se estableció la Pastoral Social y
                                    se inició el programa de becas "Manos Franciscanas".
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item flex items-center">
                        <div class="w-1/2 pr-8 text-right">
                            <div class="bg-gradient-to-l from-[#D0B8A8] to-[#ab876f] p-8 rounded-2xl text-white shadow-2xl">
                                <h3 class="text-2xl font-bold mb-4">2020 - Innovación Digital</h3>
                                <p class="text-lg opacity-90">
                                    Durante la pandemia, implementamos misas virtuales y la plataforma digital "Parroquia Conectada".
                                    Se creó el grupo de jóvenes "Francisco Digital" y se modernizó completamente la catequesis
                                    con recursos multimedia y aplicaciones interactivas.
                                </p>
                            </div>
                        </div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-10 h-10 bg-[#D0B8A8] border-4 border-white rounded-full pulse-glow flex items-center justify-center">
                            <span class="material-icons text-xl text-white">devices</span>
                        </div>
                        <div class="w-1/2 pl-8"></div>
                    </div>

                    <div class="timeline-item flex items-center">
                        <div class="w-1/2 pr-8"></div>
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-10 h-10 bg-[#ab876f] border-4 border-white rounded-full pulse-glow flex items-center justify-center">
                            <span class="material-icons text-xl text-white">eco</span>
                        </div>
                        <div class="w-1/2 pl-8">
                            <div class="bg-gradient-to-r from-[#ab876f] to-[#D0B8A8] p-8 rounded-2xl text-white shadow-2xl">
                                <h3 class="text-2xl font-bold mb-4">2023 - Presente</h3>
                                <p class="text-lg opacity-90">
                                    Hoy somos una comunidad vibrante de 800 familias con 12 ministerios activos,
                                    un centro educativo que atiende 150 niños en vulnerabilidad, y proyectos ambientales
                                    que nos han convertido en la primera "Parroquia Verde" de Bosa. ¡Nuestra historia continúa!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-5xl font-bold text-gray-900 mb-6">Espacios Sagrados</h2>
                <p class="text-xl text-gray-600">Cada rincón de nuestro templo cuenta una historia de fe y devoción</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="ministry-card bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl cursor-pointer" onclick="openArchitectureModal('altar')">
                    <div class="w-20 h-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="material-icons text-3xl text-white">church</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-4">Altar Mayor</h3>
                    <p class="text-gray-600 text-center text-sm">Centro de nuestras celebraciones eucarísticas</p>
                </div>

                <div class="ministry-card bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl cursor-pointer" onclick="openArchitectureModal('baptistery')">
                    <div class="w-20 h-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="material-icons text-3xl text-white">water_drop</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-4">Baptisterio</h3>
                    <p class="text-gray-600 text-center text-sm">Donde nacemos a la vida cristiana</p>
                </div>

                <div class="ministry-card bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl cursor-pointer" onclick="openArchitectureModal('virgin')">
                    <div class="w-20 h-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="material-icons text-3xl text-white">favorite</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-4">Altar Mariano</h3>
                    <p class="text-gray-600 text-center text-sm">Espacio de oración y devoción mariana</p>
                </div>

                <div class="ministry-card bg-white p-8 rounded-2xl shadow-xl hover:shadow-2xl cursor-pointer" onclick="openArchitectureModal('chapel')">
                    <div class="w-20 h-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full flex items-center justify-center mb-6 mx-auto">
                        <span class="material-icons text-3xl text-white">local_fire_department</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-4">Capilla de Adoración</h3>
                    <p class="text-gray-600 text-center text-sm">Oración silenciosa ante el Santísimo</p>
                </div>
            </div>
        </div>
    </section>

    <section id="ministerios" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-5xl font-bold text-gray-900 mb-6">Ministerios Parroquiales</h2>
                <p class="text-xl text-gray-600 mb-8">Encuentra tu lugar en nuestra comunidad de fe</p>

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
                    <button class="filter-btn bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-300 transition duration-300" data-filter="social">
                        Social
                    </button>
                </div>
            </div>

            <div id="ministries-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="ministry-card ministry-item bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="pastoral" onclick="openMinistryModal('legion')">
                    <div class="relative mb-6">
                        <img src="https://i.pinimg.com/originals/90/c9/30/90c930833a634c3ee26925ebdded25cf.png" alt="Legión de María" class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">group</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Legión de María</h3>
                    <p class="text-gray-600 text-center mb-4 text-sm">Apostolado mariano dedicado a la santificación y evangelización</p>
                    <div class="flex items-center justify-center text-sm text-blue-600 font-semibold mb-2">
                        <span class="material-icons mr-1 text-lg">schedule</span>
                        Sábados • 4:00 PM
                    </div>
                    <div class="flex items-center justify-center text-sm text-blue-600 font-semibold mb-4">
                        <span class="material-icons mr-1 text-lg">people</span>
                        35 Legionarios Activos
                    </div>
                    <div class="flex justify-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">Pastoral</span>
                    </div>
                </div>

                <div class="ministry-card ministry-item bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="liturgico" onclick="openMinistryModal('monaguillos')">
                    <div class="relative mb-6">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMc6kqE_oMj4Rpwazy6dHq8iLnjb_7hLPcXg&s" alt="Monaguillos" class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">child_care</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Monaguillos</h3>
                    <p class="text-gray-600 text-center mb-4 text-sm">Jóvenes servidores del altar en las celebraciones litúrgicas</p>
                    <div class="flex items-center justify-center text-sm text-green-600 font-semibold mb-2">
                        <span class="material-icons mr-1 text-lg">schedule</span>
                        Viernes • 4:00 PM
                    </div>
                    <div class="flex items-center justify-center text-sm text-green-600 font-semibold mb-4">
                        <span class="material-icons mr-1 text-lg">people</span>
                        18 Monaguillos (8-17 años)
                    </div>
                    <div class="flex justify-center">
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Litúrgico</span>
                    </div>
                </div>

                <div class="ministry-card ministry-item bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="liturgico" onclick="openMinistryModal('lectores')">
                    <div class="relative mb-6">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0cLzgMrZVQLhFurSO7MqGfUeBGaPWEt6eqA&s" alt="Lectores" class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">menu_book</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Lectores</h3>
                    <p class="text-gray-600 text-center mb-4 text-sm">Proclamadores de la Palabra de Dios en la liturgia</p>
                    <div class="flex items-center justify-center text-sm text-purple-600 font-semibold mb-2">
                        <span class="material-icons mr-1 text-lg">schedule</span>
                        Viernes • 4:30 PM
                    </div>
                    <div class="flex items-center justify-center text-sm text-purple-600 font-semibold mb-4">
                        <span class="material-icons mr-1 text-lg">people</span>
                        25 Lectores Certificados
                    </div>
                    <div class="flex justify-center">
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">Litúrgico</span>
                    </div>
                </div>

                <div class="ministry-card ministry-item bg-gradient-to-br from-orange-50 to-orange-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="liturgico" onclick="openMinistryModal('coro')">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="material-icons text-white text-3xl">music_note</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">queue_music</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Voces de Asís</h3>
                    <p class="text-gray-600 text-center mb-4 text-sm">Coro parroquial con alabanza y música sacra</p>
                    <div class="flex items-center justify-center text-sm text-orange-600 font-semibold mb-2">
                        <span class="material-icons mr-1 text-lg">schedule</span>
                        Jueves • 7:00 PM
                    </div>
                    <div class="flex items-center justify-center text-sm text-orange-600 font-semibold mb-4">
                        <span class="material-icons mr-1 text-lg">people</span>
                        22 Coristas + Director
                    </div>
                    <div class="flex justify-center">
                        <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-semibold">Litúrgico</span>
                    </div>
                </div>

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
                    <div class="flex items-center justify-center text-sm text-red-600 font-semibold mb-2">
                        <span class="material-icons mr-1 text-lg">schedule</span>
                        Domingos • 9:00 AM
                    </div>
                    <div class="flex items-center justify-center text-sm text-red-600 font-semibold mb-4">
                        <span class="material-icons mr-1 text-lg">people</span>
                        15 Catequistas + 180 Niños
                    </div>
                    <div class="flex justify-center">
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">Formación</span>
                    </div>
                </div>

                <div class="ministry-card ministry-item bg-gradient-to-br from-teal-50 to-teal-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="social" onclick="openMinistryModal('social')">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="material-icons text-white text-3xl">volunteer_activism</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">favorite</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Manos Franciscanas</h3>
                    <p class="text-gray-600 text-center mb-4 text-sm">Servicio a los más necesitados de nuestra comunidad</p>
                    <div class="flex items-center justify-center text-sm text-teal-600 font-semibold mb-2">
                        <span class="material-icons mr-1 text-lg">schedule</span>
                        Sábados • 2:00 PM
                    </div>
                    <div class="flex items-center justify-center text-sm text-teal-600 font-semibold mb-4">
                        <span class="material-icons mr-1 text-lg">people</span>
                        40 Voluntarios Activos
                    </div>
                    <div class="flex justify-center">
                        <span class="bg-teal-100 text-teal-800 px-3 py-1 rounded-full text-xs font-semibold">Social</span>
                    </div>
                </div>

                <div class="ministry-card ministry-item bg-gradient-to-br from-pink-50 to-pink-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="pastoral" onclick="openMinistryModal('juvenil')">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="material-icons text-white text-3xl">groups</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">celebration</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Francisco Digital</h3>
                    <p class="text-gray-600 text-center mb-4 text-sm">Pastoral juvenil moderna con tecnología y fe</p>
                    <div class="flex items-center justify-center text-sm text-pink-600 font-semibold mb-2">
                        <span class="material-icons mr-1 text-lg">schedule</span>
                        Sábados • 6:00 PM
                    </div>
                    <div class="flex items-center justify-center text-sm text-pink-600 font-semibold mb-4">
                        <span class="material-icons mr-1 text-lg">people</span>
                        45 Jóvenes (14-28 años)
                    </div>
                    <div class="flex justify-center">
                        <span class="bg-pink-100 text-pink-800 px-3 py-1 rounded-full text-xs font-semibold">Pastoral</span>
                    </div>
                </div>

                <div class="ministry-card ministry-item bg-gradient-to-br from-indigo-50 to-indigo-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="liturgico" onclick="openMinistryModal('musica')">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="material-icons text-white text-3xl">library_music</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">piano</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Ministerio Musical</h3>
                    <p class="text-gray-600 text-center mb-4 text-sm">Instrumentistas y cantores para todas las celebraciones</p>
                    <div class="flex items-center justify-center text-sm text-indigo-600 font-semibold mb-2">
                        <span class="material-icons mr-1 text-lg">schedule</span>
                        Miércoles • 7:30 PM
                    </div>
                    <div class="flex items-center justify-center text-sm text-indigo-600 font-semibold mb-4">
                        <span class="material-icons mr-1 text-lg">people</span>
                        12 Músicos Profesionales
                    </div>
                    <div class="flex justify-center">
                        <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-semibold">Litúrgico</span>
                    </div>
                </div>

                <div class="ministry-card ministry-item bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 rounded-2xl shadow-xl cursor-pointer" data-category="formacion" onclick="openMinistryModal('oracion')">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center border-4 border-white shadow-lg">
                            <span class="material-icons text-white text-3xl">self_improvement</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">star</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 text-center mb-3">Corazones en Oración</h3>
                    <p class="text-gray-600 text-center mb-4 text-sm">Grupo de oración carismática y adoración</p>
                    <div class="flex items-center justify-center text-sm text-yellow-600 font-semibold mb-2">
                        <span class="material-icons mr-1 text-lg">schedule</span>
                        Martes • 7:00 PM
                    </div>
                    <div class="flex items-center justify-center text-sm text-yellow-600 font-semibold mb-4">
                        <span class="material-icons mr-1 text-lg">people</span>
                        60 Miembros Regulares
                    </div>
                    <div class="flex justify-center">
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">Formación</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-5xl font-bold mb-6">Centro de Recursos Digitales</h2>
                <p class="text-xl opacity-90">Todo lo que necesitas para fortalecer tu vida espiritual al alcance de un clic</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white/10 glass-effect p-8 rounded-2xl text-center hover:bg-white/20 transition duration-300 cursor-pointer group">
                    <div class="group-hover:scale-110 transition duration-300">
                        <span class="material-icons text-5xl mb-4 block">auto_stories</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Biblioteca Oracional</h3>
                    <p class="opacity-80 text-sm mb-4">200+ oraciones para cada momento y necesidad</p>
                    <div class="flex justify-center">
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs">24/7 Disponible</span>
                    </div>
                </div>

                <div class="bg-white/10 glass-effect p-8 rounded-2xl text-center hover:bg-white/20 transition duration-300 cursor-pointer group">
                    <div class="group-hover:scale-110 transition duration-300">
                        <span class="material-icons text-5xl mb-4 block">music_note</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Himnario Digital</h3>
                    <p class="opacity-80 text-sm mb-4">Repertorio completo con partituras y audios</p>
                    <div class="flex justify-center">
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs">500+ Cantos</span>
                    </div>
                </div>

                <div class="bg-white/10 glass-effect p-8 rounded-2xl text-center hover:bg-white/20 transition duration-300 cursor-pointer group">
                    <div class="group-hover:scale-110 transition duration-300">
                        <span class="material-icons text-5xl mb-4 block">calendar_month</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Calendario Litúrgico</h3>
                    <p class="opacity-80 text-sm mb-4">Fechas importantes y celebraciones del año</p>
                    <div class="flex justify-center">
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs">Actualizado</span>
                    </div>
                </div>

                <div class="bg-white/10 glass-effect p-8 rounded-2xl text-center hover:bg-white/20 transition duration-300 cursor-pointer group">
                    <div class="group-hover:scale-110 transition duration-300">
                        <span class="material-icons text-5xl mb-4 block">description</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Documentos Pastorales</h3>
                    <p class="opacity-80 text-sm mb-4">Formularios, certificados y recursos</p>
                    <div class="flex justify-center">
                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs">Descarga PDF</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-5xl font-bold text-gray-900 mb-6">Nuestro Equipo Pastoral</h2>
                <p class="text-xl text-gray-600">Conoce a quienes sirven con amor y dedicación en nuestra comunidad franciscana</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl shadow-xl text-center hover:shadow-2xl transition duration-300">
                    <div class="w-32 h-32 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full mx-auto mb-6 flex items-center justify-center">
                        <span class="material-icons text-white text-4xl">person</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Padre José María García</h3>
                    <p class="text-[#ab876f] font-semibold mb-3">Párroco</p>
                    <p class="text-gray-600 text-sm mb-4">12 años guiando nuestra comunidad franciscana</p>
                    <div class="flex justify-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-[#D0B8A8] rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">phone</span>
                        </div>
                        <div class="w-8 h-8 bg-[#D0B8A8] rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">email</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Especialidad: Pastoral Familiar</p>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl shadow-xl text-center hover:shadow-2xl transition duration-300">
                    <div class="w-32 h-32 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full mx-auto mb-6 flex items-center justify-center">
                        <span class="material-icons text-white text-4xl">person</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Hermana María Esperanza</h3>
                    <p class="text-[#ab876f] font-semibold mb-3">Coordinadora Pastoral</p>
                    <p class="text-gray-600 text-sm mb-4">Coordinación de ministerios y actividades comunitarias</p>
                    <div class="flex justify-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-[#D0B8A8] rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">phone</span>
                        </div>
                        <div class="w-8 h-8 bg-[#D0B8A8] rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">email</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Especialidad: Pastoral Social</p>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl shadow-xl text-center hover:shadow-2xl transition duration-300">
                    <div class="w-32 h-32 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full mx-auto mb-6 flex items-center justify-center">
                        <span class="material-icons text-white text-4xl">person</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Diácono Carlos Alberto</h3>
                    <p class="text-[#ab876f] font-semibold mb-3">Coordinador de Liturgia</p>
                    <p class="text-gray-600 text-sm mb-4">Responsable de las celebraciones litúrgicas y formación</p>
                    <div class="flex justify-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-[#D0B8A8] rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">phone</span>
                        </div>
                        <div class="w-8 h-8 bg-[#D0B8A8] rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">email</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Especialidad: Liturgia y Sacramentos</p>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl shadow-xl text-center hover:shadow-2xl transition duration-300">
                    <div class="w-32 h-32 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full mx-auto mb-6 flex items-center justify-center">
                        <span class="material-icons text-white text-4xl">person</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Ana Sofía Mendoza</h3>
                    <p class="text-[#ab876f] font-semibold mb-3">Secretaria Parroquial</p>
                    <p class="text-gray-600 text-sm mb-4">Atención al público y administración parroquial</p>
                    <div class="flex justify-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-[#D0B8A8] rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">phone</span>
                        </div>
                        <div class="w-8 h-8 bg-[#D0B8A8] rounded-full flex items-center justify-center">
                            <span class="material-icons text-white text-sm">email</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Horario: Lunes a Viernes 8AM-5PM</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gradient-to-r from-gray-900 to-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 scroll-reveal">
                <h2 class="text-5xl font-bold mb-6">Nuestra Comunidad en Números</h2>
                <p class="text-xl opacity-90">27 años de servicio se reflejan en estas cifras que nos llenan de orgullo</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl md:text-6xl font-bold text-[#D0B8A8] mb-4 flex items-center justify-center">
                        <span class="material-icons text-5xl mr-2">home_work</span>
                        800+
                    </div>
                    <p class="text-lg font-semibold mb-2">Familias Registradas</p>
                    <p class="text-sm opacity-75">Crecimiento del 15% anual</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl md:text-6xl font-bold text-[#D0B8A8] mb-4 flex items-center justify-center">
                        <span class="material-icons text-5xl mr-2">handshake</span>
                        12
                    </div>
                    <p class="text-lg font-semibold mb-2">Ministerios Activos</p>
                    <p class="text-sm opacity-75">300+ voluntarios comprometidos</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl md:text-6xl font-bold text-[#D0B8A8] mb-4 flex items-center justify-center">
                        <span class="material-icons text-5xl mr-2">volunteer_activism</span>
                        2,500
                    </div>
                    <p class="text-lg font-semibold mb-2">Personas en Misas Dominicales</p>
                    <p class="text-sm opacity-75">Promedio mensual</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl md:text-6xl font-bold text-[#D0B8A8] mb-4 flex items-center justify-center">
                        <i class="fa-solid fa-cross text-5xl mr-2"></i>
                        450
                    </div>
                    <p class="text-lg font-semibold mb-2">Sacramentos Anuales</p>
                    <p class="text-sm opacity-75">Bautizos, matrimonios, confirmaciones</p>
                </div>
            </div>
        </div>
    </section>

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

                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-[#ab876f]">star</span>
                            Logros Recientes
                        </h3>
                        <p id="modal-achievements" class="text-gray-700"></p>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <button class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white px-8 py-4 rounded-xl font-bold text-lg hover:shadow-lg transition duration-300 flex items-center">
                        <span class="material-icons mr-2">person_add</span>
                        Quiero Participar
                    </button>
                </div>
            </div>
        </div>
    </div>

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

                <p id="arch-modal-description" class="text-gray-700 text-lg leading-relaxed text-center mb-6"></p>

                <div id="arch-modal-details" class="bg-gray-50 p-6 rounded-xl">
                    <h4 class="font-bold text-gray-900 mb-3">Detalles Arquitectónicos:</h4>
                    <div id="arch-modal-specs" class="text-sm text-gray-600"></div>
                </div>
            </div>
        </div>
    </div>

</main>