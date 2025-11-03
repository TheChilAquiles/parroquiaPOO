<?php
/**
 * Vista/componentes/link-menu.php
 * 
 * Componente refactorizado para generar enlaces del menú
 * Adaptado para trabajar con el nuevo sistema de rutas
 * Usa Tailwind CSS
 */
?>

<?php
// Variables que deben estar disponibles:
// $item - nombre del item del menú (ej: 'Noticias')

if (!isset($item)) {
    return;
}

// Determinar la ruta basada en el item del menú
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
    'Contacto' => 'contacto',
    'Perfil' => 'perfil',
    'Salir' => 'salir',
    'Login' => 'login',
    'Registro' => 'registro',
];

$menu_route = $routes_map[$item] ?? strtolower($item);
$current_route = $_GET['route'] ?? 'inicio';
$is_active = (strpos($current_route, $menu_route) === 0);
$active_class = $is_active ? 'bg-[#DFD3C3] text-gray-900' : 'text-gray-700 hover:bg-[#E8DFD5]';
?>

<a href="?route=<?php echo htmlspecialchars($menu_route, ENT_QUOTES, 'UTF-8'); ?>" 
   class="py-2 px-4 cursor-pointer rounded transition-colors duration-200 <?php echo $active_class; ?>">
    <?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>
</a>