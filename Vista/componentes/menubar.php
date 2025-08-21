<header class="flex px-2 items-center border border-gray-400/70 rounded bg-white">

    <div class=" py-2">
        <div class="h-20 w-20 bg-green-500 "></div>
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
                    ];
                } else {

                    $menu = [
                        'Inicio',
                        'Noticias',
                        'Galeria',
                        'Informacion',
                        'PQRS',
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