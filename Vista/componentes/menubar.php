<!-- HEADER -->
<header class="md:flex justify-between items-center px-4 py-2 border border-gray-400/70 rounded bg-white">
    <div class="flex items-center justify-between">

        <!-- LOGO -->
        <div class="flex items-center space-x-2">
            <img class="h-16 w-16 rounded-lg object-cover" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-As_mLQ9e2pUmMq1yfIbaHVeZ43CPnSnOOg&s" alt="Logo">
            <span class=" font-semibold text-lg md:hidden inline ">Parroquia San Francisco de Asís</span>
        </div>

        <!-- BOTÓN HAMBURGUESA (solo visible en móviles) -->
        <button id="menu-toggle" class="md:hidden focus:outline-none">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- MENÚ RESPONSIVE (oculto por defecto en móvil) -->
    <div id="mobile-menu" class="hidden w-full ml-10 md:flex md:items-center md:justify-between md:space-x-8 flex-col md:flex-row space-y-4 md:space-y-0">

        <!-- MENU IZQUIERDA -->
        <div class="flex flex-col md:flex-row md:space-x-4 items-center text-sm md:text-base">
            <?php
            if (isset($_SESSION["logged"])) {
                $menu = [
                    'Dashboard' => ['all'],
                    'Feligreses' => ['Secretario', 'Administrador'],
                    'Libros'  => ['Secretario', 'Administrador'],
                    'Noticias' =>  ['all'],
                    'Informacion' => ['all'],
                    'Sacramentos' => ['Feligres'],
                    'Grupos' =>  ['Secretario', 'Administrador'],
                    'Reportes' => ['Administrador'],
                    'Pagos' => ['Administrador'],
                    'Certificados' => ['all'],
                    'Admin' => ['Administrador'],
                    'manual' => ['all']

                ];
            } else {
                $menu = ['Inicio', 'Noticias', 'Informacion', 'Contacto', 'manual'];
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
        </div>

        <!-- MENU DERECHO (Login / Perfil) -->
        <div class="flex flex-col md:flex-row md:space-x-4 items-center text-sm md:text-base">
            <?php
            $acceso = isset($_SESSION["logged"]) ? ['Perfil' , 'Salir' ] : ['Login', 'Registro'];
            foreach ($acceso as $item) {
                include('link-menu.php');
            }
            ?>
        </div>

    </div>
</header>

<!-- JS PARA TOGGLE -->
<script>
    const toggleButton = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');

    toggleButton.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>
