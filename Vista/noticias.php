<?php

/**
 * Vista/noticias.php - VISTA UNIFICADA (CON BOXICONS)
 * * Cambio de librería de íconos para evitar errores de renderizado de texto.
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

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body class="bg-gray-100 flex flex-col min-h-screen font-sans text-gray-800">

    <section class="relative bg-gradient-to-r from-[#D0B8A8] via-[#b5a394] to-[#ab876f] text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>

        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-16 w-16 h-16 bg-white/10 rounded-full animate-bounce"></div>

        <div class="relative container mx-auto px-4 text-center z-10">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 rounded-full mb-8 backdrop-blur-sm">
                <i class='bx bx-news text-5xl'></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-2xl">
                Noticias Parroquiales
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                Mantente informado sobre los eventos, celebraciones y actividades de nuestra comunidad
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full flex items-center justify-center">
                    <i class='bx bx-layer mr-2 text-xl'></i>
                    <span class="text-lg font-semibold">
                        <?= count($noticias) ?> Noticias Publicadas
                    </span>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full flex items-center justify-center">
                    <i class='bx bx-time-five mr-2 text-xl'></i>
                    <span class="text-lg font-semibold">
                        Actualizado Hoy
                    </span>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 z-0">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-16 fill-gray-100">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"></path>
            </svg>
        </div>
    </section>

    <section class="container mx-auto px-4 -mt-16 relative z-20 mb-20">

        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-12 backdrop-blur-sm">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">

                <div class="flex-1 w-full lg:w-auto">
                    <form action="<?= url('noticias') ?>" method="POST" class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class='bx bx-search text-2xl text-gray-400 group-focus-within:text-[#ab876f] transition-colors'></i>
                        </div>
                        <input type="text" name="buscar"
                            placeholder="Buscar noticias..."
                            value="<?= htmlspecialchars($_POST['buscar'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full pl-16 pr-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all duration-300 bg-gray-50 focus:bg-white">
                        <button type="submit" class="absolute inset-y-0 right-0 pr-6 flex items-center">
                            <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg transition-all duration-300">
                                Buscar
                            </div>
                        </button>
                    </form>
                </div>

                <div class="flex items-center gap-4">
                    <?php if (!empty($_POST['buscar'])): ?>
                        <form action="<?= url('noticias') ?>" method="POST" class="inline-block">
                            <button type="submit" class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                                <i class='bx bx-x mr-2 text-xl'></i>
                                Limpiar
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if ($esAdmin): ?>
                        <button id="openModalBtn"
                            class="flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <i class='bx bx-plus-circle mr-2 text-xl'></i>
                            Nueva Noticia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (isset($mensaje)): ?>
            <div class="mb-6 p-4 rounded-xl font-medium flex items-center <?= $mensaje['tipo'] === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-400' : 'bg-red-50 text-red-800 border-l-4 border-red-400' ?> shadow-sm">
                <i class='bx <?= $mensaje['tipo'] === 'success' ? 'bx-check-circle' : 'bx-error-circle' ?> mr-3 text-2xl'></i>
                <?= htmlspecialchars($mensaje['texto'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 items-stretch">
            <?php if (!empty($noticias)): ?>
                <?php foreach ($noticias as $index => $noticiaItem): ?>

                    <article class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 hover:rotate-1 transition-all duration-500 group flex flex-col h-full"
                        style="animation: fadeInUp 0.6s ease-out <?= $index * 0.1 ?>s both">

                        <div class="relative overflow-hidden h-64 flex-shrink-0 bg-gray-200">
                            <?php if (!empty($noticiaItem['imagen']) && file_exists($noticiaItem['imagen'])): ?>
                                <img src="<?= htmlspecialchars($noticiaItem['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($noticiaItem['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full bg-gray-300 flex items-center justify-center\'><i class=\'bx bx-image text-5xl text-gray-500\'></i></div>';">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] flex items-center justify-center">
                                    <i class='bx bx-image text-white text-6xl opacity-50'></i>
                                </div>
                            <?php endif; ?>

                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm rounded-full px-4 py-2 shadow-sm">
                                <span class="text-sm font-semibold text-gray-700 flex items-center">
                                    <i class='bx bx-calendar mr-2 text-[#ab876f]'></i>
                                    <?= htmlspecialchars((new DateTime($noticiaItem['fecha_publicacion']))->format('d M, Y'), ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </div>
                        </div>

                        <div class="p-8 flex flex-col flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-[#ab876f] transition-colors">
                                <?= htmlspecialchars($noticiaItem['titulo'], ENT_QUOTES, 'UTF-8') ?>
                            </h3>

                            <p class="text-gray-600 text-lg leading-relaxed mb-6 line-clamp-3 flex-1">
                                <?= htmlspecialchars(substr($noticiaItem['descripcion'], 0, 150), ENT_QUOTES, 'UTF-8') ?>...
                            </p>
                            <div class="mb-4">
                                <button class="view-news-btn text-[#ab876f] font-bold hover:text-[#D0B8A8] transition-colors flex items-center"
                                    data-titulo="<?= htmlspecialchars($noticiaItem['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-descripcion="<?= htmlspecialchars($noticiaItem['descripcion'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-imagen="<?= htmlspecialchars($noticiaItem['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-fecha="<?= htmlspecialchars((new DateTime($noticiaItem['fecha_publicacion']))->format('d M, Y'), ENT_QUOTES, 'UTF-8') ?>">
                                    Leer noticia completa
                                    <i class='bx bx-right-arrow-alt ml-1 text-xl'></i>
                                </button>
                            </div>

                            <div class="flex items-center justify-between mt-auto border-t border-gray-100 pt-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class='bx bx-time mr-2'></i>
                                    <span>Lectura: 2 min</span>
                                </div>
                            </div>

                            <?php if ($esAdmin): ?>
                                <div class="mt-4 pt-2">
                                    <div class="flex items-center justify-between gap-3">
                                        <button class="flex-1 open-edit-modal bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center"
                                            data-id="<?= $noticiaItem['id'] ?>"
                                            data-titulo="<?= htmlspecialchars($noticiaItem['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-descripcion="<?= htmlspecialchars($noticiaItem['descripcion'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-imagen="<?= htmlspecialchars($noticiaItem['imagen'], ENT_QUOTES, 'UTF-8') ?>">
                                            <i class='bx bx-edit mr-2 text-xl'></i>
                                            Editar
                                        </button>

                                        <button class="flex-1 delete-btn bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center"
                                            data-id="<?= $noticiaItem['id'] ?>">
                                            <i class='bx bx-trash mr-2 text-xl'></i>
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex items-center justify-center w-32 h-32 bg-gray-100 rounded-full mb-8">
                        <i class='bx bx-box text-6xl text-gray-400'></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">
                        <?= !empty($_POST['buscar']) ? 'Sin resultados' : 'No hay noticias disponibles' ?>
                    </h3>
                    <p class="text-gray-600">
                        Intenta con otros términos o crea una noticia.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($esAdmin): ?>

        <div id="noticiaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto transform scale-95 transition-all duration-300 modal-content">

                <div class="sticky top-0 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white p-8 rounded-t-3xl z-10">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 id="modalTitle" class="text-3xl font-bold mb-2">Crear Nueva Noticia</h3>
                            <p class="opacity-90">Comparte información importante</p>
                        </div>
                        <button id="closeModalBtn" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-all">
                            <i class='bx bx-x text-2xl'></i>
                        </button>
                    </div>
                </div>

                <form id="noticiaForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    <input type="hidden" name="id" id="noticiaId">
                    <input type="hidden" name="imagen_actual" id="imagenActual">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label for="titulo" class="block text-lg font-bold text-gray-900 mb-3">Título</label>
                                <input type="text" id="titulo" name="titulo" required
                                    class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all">
                            </div>

                            <div>
                                <label for="imagen" class="block text-lg font-bold text-gray-900 mb-3">Imagen</label>
                                <div class="relative border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:bg-gray-50 transition-colors">
                                    <input type="file" id="imagen" name="imagen" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <i class='bx bx-cloud-upload text-4xl text-gray-400 mb-2'></i>
                                    <p class="text-gray-500 font-medium">Click para subir imagen</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG (Max 5MB)</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center">
                            <img id="imagenPreview" src="" alt="Vista previa"
                                class="max-w-full h-48 object-cover rounded-2xl shadow-lg hidden">
                            <div id="imagenPlaceholder" class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                                <div class="text-center text-gray-400">
                                    <i class='bx bx-image text-6xl mb-2'></i>
                                    <p class="font-semibold">Vista previa</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="descripcion" class="block text-lg font-bold text-gray-900 mb-3">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="6" required
                            class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl resize-none focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8]"></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white text-xl font-bold py-4 px-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all flex items-center justify-center">
                            <i class='bx bx-send mr-3'></i>
                            Publicar
                        </button>
                        <button type="button" id="cancelBtn" class="flex-1 bg-gray-200 text-gray-800 text-xl font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all flex items-center justify-center">
                            <i class='bx bx-x-circle mr-3'></i>
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md mx-4 transform scale-95 transition-all duration-300 modal-content">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                        <i class='bx bx-error text-red-600 text-4xl'></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">¿Eliminar noticia?</h3>
                    <p class="text-gray-600 mb-8">Esta acción no se puede deshacer.</p>

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

        <div id="loadingOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl p-8 shadow-2xl">
                <div class="flex items-center space-x-4">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-[#D0B8A8] border-t-transparent"></div>
                    <span class="text-xl font-semibold text-gray-700">Procesando...</span>
                </div>
            </div>
        </div>

        <div id="feedback-container" class="fixed top-4 right-4 z-50 space-y-4"></div>


    <?php endif; ?>

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

    <div id="verNoticiaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col transform scale-95 transition-all duration-300 modal-content">
        
        <div class="relative h-64 md:h-80 flex-shrink-0 bg-gray-200">
            <img id="verImagen" src="" alt="Noticia" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
            
            <button id="closeVerModalBtn" class="absolute top-4 right-4 bg-black/30 hover:bg-black/50 text-white rounded-full p-2 transition-all backdrop-blur-md z-10">
                <i class='bx bx-x text-3xl'></i>
            </button>

            <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                <span id="verFecha" class="bg-[#ab876f] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-2 inline-block shadow-sm">
                    </span>
                <h2 id="verTitulo" class="text-3xl md:text-4xl font-bold drop-shadow-lg leading-tight">
                    </h2>
            </div>
        </div>

        <div class="p-8 overflow-y-auto custom-scrollbar">
            <div id="verDescripcion" class="prose prose-lg max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap">
                </div>
        </div>
        
        <div class="bg-gray-50 px-8 py-4 border-t flex justify-end">
            <button id="closeVerModalBtnBottom" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-xl transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>


        


</body>
<script src="assets/js/noticias.js"></script>
</html>