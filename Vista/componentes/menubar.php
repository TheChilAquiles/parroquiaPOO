<header class="flex px-2 items-center border border-gray-400/70 rounded bg-white">

    <div class=" py-2">
        <div class="h-20 w-20">
            <img class="mr-18 h-18 w-18 rounded-lg object-cover" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-As_mLQ9e2pUmMq1yfIbaHVeZ43CPnSnOOg&s" alt="Logo de la Parroquia San Francisco de Asís">
        </div>
        
    </div>

    <div class="w-full h-20 rounded flex justify-between mx-10 h-full">
        <div class="flex items-center">

            <?php
                if (isset($_SESSION["logged"])) {

                    $menu = [
                        'Dashboard',
                        'Feligreses',
                        'Libros',
                        'Noticias',
                        'Informacion',
                        'Grupos',
                        'Reportes'
                    ];
                } else {

                    $menu = [
                        'Inicio',
                        'Noticias',
                        'Informacion',
                        'Contacto',
                    ];
                }

                foreach ($menu as $item) {

                    include('link-menu.php');
                };

            ?>

        </div>

        <div class="flex items-center">

            <?php

            if (isset($_SESSION["logged"])) {
                $acceso = [
                    'Salir',
                ];
            } else {

                $acceso = [
                    'Login',
                    'Registro'
                ];
            }

            foreach ($acceso as $item) {

                include('link-menu.php');
            };
            ?>
        </div>

    </div>

</header>