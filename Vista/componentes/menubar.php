<!-- HEADER REFACTORIZADO -->
<header class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-3">

            <!-- LOGO Y NOMBRE -->
            <div class="flex items-center space-x-3">
                <a href="<?= url('inicio') ?>" class="flex items-center space-x-3 group">
                    <!-- Logo con tamaño fijo -->
                    <div class="flex-shrink-0">
                        <img class="h-14 w-14 rounded-lg object-cover shadow-sm transition-transform duration-300 group-hover:scale-105"
                             src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-As_mLQ9e2pUmMq1yfIbaHVeZ43CPnSnOOg&s"
                             alt="Logo Parroquia">
                    </div>
                    <!-- Nombre de la parroquia -->
                    <div class="hidden lg:block">
                        <span class="font-bold text-lg text-gray-800">Parroquia San Francisco de Asís</span>
                    </div>
                </a>
            </div>

            <!-- MENÚ NAVEGACIÓN DESKTOP -->
            <nav class="hidden md:flex items-center space-x-1">
                <?php
                if (isset($_SESSION["logged"])) {
                    $menu = [
                        'Dashboard' => ['all'],
                        'Feligreses' => ['Secretario', 'Administrador'],
                        'Libros'  => ['Secretario', 'Administrador'],
                        'Noticias' =>  ['all'],
                        'Información' => ['all'],
                        'Sacramentos' => ['Feligres'],
                        'Grupos' =>  ['Secretario', 'Administrador'],
                        'Reportes' => ['Administrador'],
                        'Pagos' => ['Administrador'],
                        'Certificados' => ['all'],
                        'Configuración' => ['Administrador']
                    ];
                } else {
                    $menu = ['Inicio', 'Noticias', 'Información', 'Contacto'];
                }

                foreach ($menu as $item => $roles) {
                    if (is_array($roles)) {
                        foreach ($roles as $role) {
                            if ($_SESSION["user-rol"] == $role || $role == 'all') {
                                include('link-menu.php');
                                break;
                            }
                        }
                    } else {
                        $item = $roles;
                        include('link-menu.php');
                    }
                }
                ?>
            </nav>

            <!-- MENÚ USUARIO DROPDOWN / LOGIN -->
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION["logged"])): ?>
                    <!-- MENÚ DESPLEGABLE USUARIO -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                @click.away="open = false"
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-[#F5EFE7] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#D0B8A8]">
                            <div class="w-9 h-9 bg-gradient-to-br from-[#D0B8A8] to-[#A89080] rounded-full flex items-center justify-center shadow-sm">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="hidden lg:block font-medium text-gray-700">
                                <?= htmlspecialchars($_SESSION['user-email'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?>
                            </span>
                            <i class="fas fa-chevron-down text-gray-500 text-xs transition-transform duration-200"
                               :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- DROPDOWN MENU -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50"
                             style="display: none;">

                            <!-- Información del usuario -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">
                                    <?= htmlspecialchars($_SESSION['user-email'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Rol: <?= htmlspecialchars($_SESSION['user-rol'] ?? 'Sin rol', ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            </div>

                            <!-- Opciones del menú -->
                            <a href="<?= url('perfil') ?>"
                               class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-[#F5EFE7] transition-colors duration-150">
                                <i class="fas fa-user-circle w-5 text-[#D0B8A8] mr-3"></i>
                                Mi Perfil
                            </a>

                            <a href="<?= url('manual') ?>"
                               class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-[#F5EFE7] transition-colors duration-150">
                                <i class="fas fa-book-open w-5 text-[#D0B8A8] mr-3"></i>
                                Manual de Usuario
                            </a>

                            <div class="border-t border-gray-100 my-2"></div>

                            <a href="<?= url('salir') ?>"
                               class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                                <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                Cerrar Sesión
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- BOTONES LOGIN/REGISTRO -->
                    <div class="hidden md:flex items-center space-x-3">
                        <a href="<?= url('login') ?>"
                           class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            Iniciar Sesión
                        </a>
                        <a href="<?= url('registro') ?>"
                           class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#D0B8A8] to-[#A89080] rounded-lg hover:shadow-lg transition-all duration-200">
                            Registrarse
                        </a>
                    </div>
                <?php endif; ?>

                <!-- BOTÓN HAMBURGUESA MÓVIL -->
                <button id="menu-toggle"
                        class="md:hidden p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#D0B8A8]">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- MENÚ MÓVIL -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 py-4">
            <nav class="flex flex-col space-y-2">
                <?php
                if (isset($_SESSION["logged"])) {
                    $menu_mobile = [
                        'Dashboard' => ['all'],
                        'Feligreses' => ['Secretario', 'Administrador'],
                        'Libros'  => ['Secretario', 'Administrador'],
                        'Noticias' =>  ['all'],
                        'Información' => ['all'],
                        'Sacramentos' => ['Feligres'],
                        'Grupos' =>  ['Secretario', 'Administrador'],
                        'Reportes' => ['Administrador'],
                        'Pagos' => ['Administrador'],
                        'Certificados' => ['all'],
                        'Configuración' => ['Administrador']
                    ];

                    foreach ($menu_mobile as $item => $roles) {
                        if (is_array($roles)) {
                            foreach ($roles as $role) {
                                if ($_SESSION["user-rol"] == $role || $role == 'all') {
                                    include('link-menu.php');
                                    break;
                                }
                            }
                        }
                    }

                    // Opciones de usuario en móvil
                    echo '<div class="border-t border-gray-200 mt-3 pt-3">';
                    echo '<p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase">Cuenta</p>';

                    $user_menu = ['Perfil' => 'perfil', 'Manual' => 'manual', 'Salir' => 'salir'];
                    foreach ($user_menu as $item => $route) {
                        $item_route = $route;
                        include('link-menu.php');
                    }
                    echo '</div>';
                } else {
                    $menu_mobile = ['Inicio', 'Noticias', 'Información', 'Contacto'];
                    foreach ($menu_mobile as $item) {
                        include('link-menu.php');
                    }

                    echo '<div class="border-t border-gray-200 mt-3 pt-3 flex flex-col space-y-2">';
                    echo '<a href="' . url('login') . '" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">Iniciar Sesión</a>';
                    echo '<a href="' . url('registro') . '" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#D0B8A8] to-[#A89080] rounded-lg text-center">Registrarse</a>';
                    echo '</div>';
                }
                ?>
            </nav>
        </div>
    </div>
</header>

<!-- Alpine.js para el dropdown (añadir en el head si no está ya) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Font Awesome para los iconos (añadir en el head si no está ya) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- JS PARA TOGGLE MÓVIL -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('menu-toggle');
        const menu = document.getElementById('mobile-menu');

        if (toggleButton && menu) {
            toggleButton.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>
