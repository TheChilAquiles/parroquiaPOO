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

// Mapa de rutas amigables - Restored
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

// Mapa de íconos Material Design
$icons_map = [
    'Inicio' => 'home',
    'Dashboard' => 'dashboard',
    'Feligreses' => 'groups',
    'Libros' => 'menu_book',
    'Noticias' => 'campaign',
    'Información' => 'info',
    'Informacion' => 'info',
    'Sacramentos' => 'spa',
    'Grupos' => 'diversity_3',
    'Reportes' => 'assessment',
    'Pagos' => 'payments',
    'Certificados' => 'workspace_premium',
    'Configuración' => 'settings',
    'Configuracion' => 'settings',
    'Contacto' => 'contact_support',
    'pqrs' => 'feedback',
    'Perfil' => 'person',
    'Manual' => 'help_center',
    'Salir' => 'logout',
    'Login' => 'login',
    'Registro' => 'person_add',
];

// Obtener el ícono correspondiente
$icon = $icons_map[$item] ?? 'circle'; // Icono por defecto

// Obtener la ruta del menú
$menu_route = $routes_map[$item] ?? strtolower($item);

// Determinar si el enlace está activo
$current_route = $_GET['route'] ?? 'inicio';
$is_active = (strpos($current_route, $menu_route) === 0);

// Clases para el estado activo/inactivo del botón base
// Si es móvil, botón ancho completo y texto a la izquierda
// Si es desktop, botón cuadrado centrado
$mobile_classes = ($is_mobile ?? false) ? 'w-full justify-start px-4 mb-1' : 'justify-center w-10 h-10';

$active_class = $is_active
    ? 'bg-[#D0B8A8] text-white shadow-md'
    : 'text-gray-600 hover:bg-[#F5EFE7] hover:text-[#ab876f]';
?>

<a href="<?= url($menu_route) ?>"
   class="group relative flex items-center rounded-lg transition-all duration-200 <?= $mobile_classes ?> <?= $active_class ?>">
    
    <!-- Icono -->
    <span class="material-icons text-xl <?= ($is_mobile ?? false) ? 'mr-3' : '' ?>"><?= $icon ?></span>

    <!-- Texto (Solo móvil) -->
    <?php if ($is_mobile ?? false): ?>
        <span class="font-medium text-sm"><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></span>
    <?php endif; ?>

    <!-- Tooltip (Solo desktop) -->
    <?php if (!($is_mobile ?? false)): ?>
        <span class="absolute top-full mt-2 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap z-50 shadow-lg pointer-events-none">
            <?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>
            <!-- Triangulito del tooltip -->
            <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-b-gray-800"></span>
        </span>
    <?php endif; ?>
</a>
