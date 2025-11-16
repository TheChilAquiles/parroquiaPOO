<?php
/**
 * Vista/componentes/link-menu.php
 *
 * Componente refactorizado para generar enlaces del menú
 * Usa sistema de rutas amigables y Tailwind CSS
 * Compatible con esquema de colores de la app (#D0B8A8, #F5EFE7, etc.)
 */

if (!isset($item)) {
    return;
}

// Mapa de rutas amigables
$routes_map = [
    'Inicio' => 'inicio',
    'Dashboard' => 'dashboard',
    'Feligreses' => 'feligreses',
    'Libros' => 'libros',
    'Noticias' => 'noticias',
    'Información' => 'informacion',
    'Informacion' => 'informacion',
    'Sacramentos' => 'sacramentos',
    'Grupos' => 'grupos',
    'Reportes' => 'reportes',
    'Pagos' => 'pagos',
    'Certificados' => 'certificados',
    'Configuración' => 'configuracion',
    'Configuracion' => 'configuracion',
    'Contacto' => 'contacto',
    'Perfil' => 'perfil',
    'Manual' => 'manual',
    'Salir' => 'salir',
    'Login' => 'login',
    'Registro' => 'registro',
];

// Obtener la ruta del menú
$menu_route = $routes_map[$item] ?? strtolower($item);

// Determinar si el enlace está activo
$current_route = $_GET['route'] ?? 'inicio';
$is_active = (strpos($current_route, $menu_route) === 0);

// Clases para el estado activo/inactivo
$active_class = $is_active
    ? 'bg-[#DFD3C3] text-gray-900 font-semibold'
    : 'text-gray-700 hover:bg-[#F5EFE7] hover:text-gray-900';
?>

<a href="<?= url($menu_route) ?>"
   class="px-4 py-2 text-sm rounded-lg transition-all duration-200 <?= $active_class ?>">
    <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
</a>
