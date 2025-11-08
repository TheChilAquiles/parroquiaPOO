<?php
spl_autoload_register(function ($clase) {
    $directorios = [
        __DIR__ . '/Modelo/',
        __DIR__ . '/Controlador/',
    ];

    foreach ($directorios as $dir) {
        $archivo = $dir . $clase . '.php';
        if (file_exists($archivo)) {
            require_once $archivo;
            return;
        }
    }
});

// Cargar Logger manualmente (siempre disponible)
require_once __DIR__ . '/Modelo/Logger.php';