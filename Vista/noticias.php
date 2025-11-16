<?php
/**
 * Vista/noticias.php - VISTA UNIFICADA
 * 
 * Vista única para gestión de noticias
 * - Muestra noticias públicas para todos
 * - Controles CRUD para Administradores/Secretarios
 * 
 * Variables esperadas:
 * - $noticias: Array de noticias desde NoticiasController
 * - $mensaje: Array con tipo y texto (opcional)
 */

// Verificar si el usuario tiene permisos de administración
$esAdmin = isset($_SESSION['user-rol']) && 
           ($_SESSION['user-rol'] === 'Administrador' || $_SESSION['user-rol'] === 'Secretario');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias - Parroquia</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-100">

<!-- ============================================================================
        HERO SECTION - ENCABEZADO PRINCIPAL
============================================================================= -->
<section class="relative bg-gradient-to-r from-[#D0B8A8] via-[#b5a394] to-[#ab876f] text-white py-20 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    
    <!-- Elementos decorativos -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
    <div class="absolute bottom-20 right-16 w-16 h-16 bg-white/10 rounded-full animate-bounce"></div>
    
    <div class="relative container mx-auto px-4 text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 rounded-full mb-8 backdrop-blur-sm">
            <span class="material-icons text-5xl">article</span>
        </div>
        <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-2xl">
            Noticias Parroquiales
        </h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
            Mantente informado sobre los eventos, celebraciones y actividades de nuestra comunidad
        </p>

        <!-- Estadísticas -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full">
                <span class="flex items-center text-lg font-semibold">
                    <span class="material-icons mr-2">newspaper</span>
                    <?= count($noticias) ?> Noticias Publicadas
                </span>
            </div>
            <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full">
                <span class="flex items-center text-lg font-semibold">
                    <span class="material-icons mr-2">update</span>
                    Actualizado Hoy
                </span>
            </div>
        </div>
    </div>

    <!-- Ola decorativa -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-16 fill-white">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"></path>
        </svg>
    </div>
</section>

<!-- ============================================================================
        SECCIÓN DE CONTENIDO
============================================================================= -->
<section class="container mx-auto px-4 py-16 -mt-8 relative z-10">

    <!-- BARRA DE BÚSQUEDA Y CONTROLES -->
    <div class="bg-white rounded-3xl shadow-2xl p-8 mb-12 backdrop-blur-sm">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
            
            <!-- Formulario de búsqueda -->
            <div class="flex-1 w-full lg:w-auto">
                <form action="<?= url('noticias') ?>" method="POST" class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <span class="material-icons text-gray-400 group-focus-within:text-[#ab876f] transition-colors">search</span>
                    </div>
                    <input type="text" name="buscar"
                        placeholder="Buscar noticias por título o descripción..."
                        value="<?= htmlspecialchars($_POST['buscar'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        class="w-full pl-16 pr-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all duration-300 bg-gray-50 focus:bg-white">
                    <button type="submit" class="absolute inset-y-0 right-0 pr-6 flex items-center">
                        <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg transition-all duration-300">
                            Buscar
                        </div>
                    </button>
                </form>
            </div>

            <!-- Controles adicionales -->
            <div class="flex items-center gap-4">
                <?php if (!empty($_POST['buscar'])): ?>
                    <form action="<?= url('noticias') ?>" method="POST" class="inline-block">
                        <button type="submit" class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            <span class="material-icons mr-2">clear</span>
                            Limpiar búsqueda
                        </button>
                    </form>
                <?php endif; ?>

                <?php if ($esAdmin): ?>
                    <button id="openModalBtn"
                        class="flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <span class="material-icons mr-2">add_circle</span>
                        Nueva Noticia
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mensajes de feedback -->
    <?php if (isset($mensaje)): ?>
        <div class="mb-6 p-4 rounded-xl font-medium <?= $mensaje['tipo'] === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-400' : 'bg-red-50 text-red-800 border-l-4 border-red-400' ?> shadow-sm">
            <?= htmlspecialchars($mensaje['texto'], ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- GRID DE NOTICIAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        <?php if (!empty($noticias)): ?>
            <?php foreach ($noticias as $index => $noticiaItem): ?>
                <article class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 hover:rotate-1 transition-all duration-500 group"
                    style="animation: fadeInUp 0.6s ease-out <?= $index * 0.1 ?>s both">

                    <!-- Imagen -->
                    <div class="relative overflow-hidden h-64">
                        <?php if (!empty($noticiaItem['imagen']) && file_exists($noticiaItem['imagen'])): ?>
                            <img src="<?= htmlspecialchars($noticiaItem['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($noticiaItem['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                onerror="this.src='assets/img/noticias/default.jpg'">
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] flex items-center justify-center">
                                <span class="material-icons text-white text-6xl opacity-50">image</span>
                            </div>
                        <?php endif; ?>

                        <!-- Badge de fecha -->
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm rounded-full px-4 py-2">
                            <span class="text-sm font-semibold text-gray-700 flex items-center">
                                <span class="material-icons mr-1 text-sm">calendar_today</span>
                                <?= htmlspecialchars((new DateTime($noticiaItem['fecha_publicacion']))->format('d M, Y'), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-[#ab876f] transition-colors">
                            <?= htmlspecialchars($noticiaItem['titulo'], ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p class="text-gray-600 text-lg leading-relaxed mb-6 line-clamp-3">
                            <?= htmlspecialchars(substr($noticiaItem['descripcion'], 0, 150), ENT_QUOTES, 'UTF-8') ?>
                            <?= strlen($noticiaItem['descripcion']) > 150 ? '...' : '' ?>
                        </p>

                        <!-- Botón leer más -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500">
                                <span class="material-icons mr-2">visibility</span>
                                <span>Lectura: 2 min</span>
                            </div>
                        </div>

                        <!-- CONTROLES DE ADMINISTRACIÓN -->
                        <?php if ($esAdmin): ?>
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <div class="flex items-center justify-between gap-3">
                                    <!-- Botón editar -->
                                    <button class="flex-1 open-edit-modal bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center"
                                        data-id="<?= $noticiaItem['id'] ?>"
                                        data-titulo="<?= htmlspecialchars($noticiaItem['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                                        data-descripcion="<?= htmlspecialchars($noticiaItem['descripcion'], ENT_QUOTES, 'UTF-8') ?>"
                                        data-imagen="<?= htmlspecialchars($noticiaItem['imagen'], ENT_QUOTES, 'UTF-8') ?>">
                                        <span class="material-icons mr-2">edit</span>
                                        Editar
                                    </button>

                                    <!-- Botón eliminar -->
                                    <button class="flex-1 delete-btn bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center"
                                        data-id="<?= $noticiaItem['id'] ?>">
                                        <span class="material-icons mr-2">delete</span>
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>

        <?php else: ?>
            <!-- Estado vacío -->
            <div class="col-span-full text-center py-20">
                <div class="inline-flex items-center justify-center w-32 h-32 bg-gray-100 rounded-full mb-8">
                    <span class="material-icons text-gray-400 text-6xl">inbox</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-4">
                    <?= !empty($_POST['buscar']) ? 'Sin resultados' : 'No hay noticias disponibles' ?>
                </h3>
                <p class="text-gray-600">
                    <?= !empty($_POST['buscar']) ? 'Intenta con otros términos de búsqueda' : 'Sé el primero en crear una noticia' ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ============================================================================
        MODALES DE ADMINISTRACIÓN (Solo para admins)
============================================================================= -->
<?php if ($esAdmin): ?>

<!-- MODAL CREAR/EDITAR -->
<div id="noticiaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto transform scale-95 transition-all duration-300 modal-content">
        
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white p-8 rounded-t-3xl z-10">
            <div class="flex justify-between items-center">
                <div>
                    <h3 id="modalTitle" class="text-3xl font-bold mb-2">Crear Nueva Noticia</h3>
                    <p class="opacity-90">Comparte información importante con la comunidad</p>
                </div>
                <button id="closeModalBtn" class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-all">
                    <span class="material-icons text-2xl">close</span>
                </button>
            </div>
        </div>

        <!-- Formulario -->
        <form id="noticiaForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            <input type="hidden" name="id" id="noticiaId">
            <input type="hidden" name="imagen_actual" id="imagenActual">

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <!-- Título -->
                    <div>
                        <label for="titulo" class="block text-lg font-bold text-gray-900 mb-3">Título</label>
                        <input type="text" id="titulo" name="titulo" required
                            class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all">
                    </div>

                    <!-- Imagen -->
                    <div>
                        <label for="imagen" class="block text-lg font-bold text-gray-900 mb-3">Imagen</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*"
                            class="w-full px-6 py-4 text-lg border-2 border-dashed border-gray-300 rounded-2xl">
                        <p class="text-sm text-gray-500 mt-2">JPG, PNG o GIF. Máximo 5MB</p>
                    </div>
                </div>

                <!-- Vista previa -->
                <div class="flex items-center justify-center">
                    <img id="imagenPreview" src="" alt="Vista previa"
                        class="max-w-full h-48 object-cover rounded-2xl shadow-lg hidden">
                    <div id="imagenPlaceholder" class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <span class="material-icons text-6xl mb-2">image</span>
                            <p class="font-semibold">Vista previa</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-lg font-bold text-gray-900 mb-3">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="6" required
                    class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl resize-none focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8]"></textarea>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" class="flex-1 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white text-xl font-bold py-4 px-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all flex items-center justify-center">
                    <span class="material-icons mr-3">publish</span>
                    Publicar
                </button>
                <button type="button" id="cancelBtn" class="flex-1 bg-gray-200 text-gray-800 text-xl font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all flex items-center justify-center">
                    <span class="material-icons mr-3">cancel</span>
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL DE ELIMINACIÓN -->
<div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md mx-4 transform scale-95 transition-all duration-300 modal-content">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                <span class="material-icons text-red-600 text-4xl">warning</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">¿Eliminar noticia?</h3>
            <p class="text-gray-600 mb-8">Esta acción no se puede deshacer. La noticia se eliminará permanentemente.</p>
            
            <div class="flex gap-4">
                <button id="cancelDeleteBtn" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-xl hover:bg-gray-300 transition">
                    Cancelar
                </button>
                <button id="confirmDeleteBtn" data-id="" class="flex-1 px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- OVERLAY DE CARGA -->
<div id="loadingOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-8 shadow-2xl">
        <div class="flex items-center space-x-4">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-[#D0B8A8] border-t-transparent"></div>
            <span class="text-xl font-semibold text-gray-700">Procesando...</span>
        </div>
    </div>
</div>

<!-- Contenedor de notificaciones -->
<div id="feedback-container" class="fixed top-4 right-4 z-50 space-y-4"></div>

<!-- JavaScript -->
<script src="assets/js/noticias.js"></script>

<?php endif; ?>

<!-- Keyframes CSS -->
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

</body>
</html>