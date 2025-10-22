<?php
/**
 * Vista/componentes/link-menu.php
 * 
 * Componente refactorizado para generar enlaces del menú
 * Adaptado para trabajar con el nuevo sistema de rutas
 */
?>

<?php
// Variables que deben estar disponibles:
// $route - ruta actual (ej: 'noticias')
// $item - nombre del item del menú (ej: 'Noticias')
// $current_route - ruta actual para comparación

if (!isset($route) || !isset($item)) {
    return;
}

// Determinar la ruta basada en el item del menú
$routes_map = [
    'Inicio' => 'inicio',
    'Dashboard' => 'dashboard',
    'Feligreses' => 'feligres',
    'Libros' => 'libros',
    'Noticias' => 'noticias',
    'Información' => 'informacion',
    'Informacion' => 'informacion',
    'Sacramentos' => 'sacramentos',
    'Grupos' => 'grupos',
    'Reportes' => 'reportes',
    'Pagos' => 'pagos',
    'Certificados' => 'certificados',
    'Contacto' => 'contacto',
    'Perfil' => 'perfil',
    'Salir' => 'salir',
    'Login' => 'login',
    'Registro' => 'registro',
];

$menu_route = $routes_map[$item] ?? strtolower($item);
$current_route = $_GET['route'] ?? 'inicio';
$is_active = (strpos($current_route, $menu_route) === 0);
$active_class = $is_active ? 'bg-[#DFD3C3]' : '';
?>

<a href="?route=<?php echo htmlspecialchars($menu_route, ENT_QUOTES, 'UTF-8'); ?>" 
   class="py-2 px-4 hover:bg-[#DFD3C3] cursor-pointer rounded transition-colors duration-200 <?php echo $active_class; ?>">
    <?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>
</a>