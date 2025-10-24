<?php
/**
 * Vista/noticiaAdministrador.php - REFACTORIZADA
 * 
 * Vista administrativa para gestión de noticias
 * CRUD completo: Crear, Leer, Actualizar, Eliminar
 * 
 * Variables esperadas:
 * - $noticias: Array de noticias desde el controlador
 * - $mensaje: Array con tipo y texto de notificación
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Noticias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .modal { transition: all 0.3s ease-in-out; }
        .fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans">

    <!-- ====================================================================
            SECCIÓN PRINCIPAL: GESTIÓN DE NOTICIAS
    ==================================================================== -->
    <div class="max-w-7xl mx-auto p-4 sm:p-8">
        <div class="bg-white p-6 rounded-3xl shadow-2xl mb-8">

            <!-- Encabezado -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div>
                    <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-slate-700 to-slate-900">
                        Gestión de Noticias
                    </h2>
                    <p class="text-slate-500 mt-1">Crea, edita y comparte las últimas novedades</p>
                </div>

                <!-- Botón crear noticia -->
                <button 
                    onclick="openCreateModal()" 
                    class="mt-4 md:mt-0 flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-full shadow-lg hover:bg-indigo-700 transition-transform duration-300 transform hover:scale-105"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Crear Noticia</span>
                </button>
            </div>

            <!-- Mensajes de feedback -->
            <?php if (isset($mensaje['texto'])): ?>
                <div class="mb-4 p-4 rounded-xl font-medium fade-in <?= $mensaje['tipo'] === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-400' : 'bg-red-50 text-red-800 border-l-4 border-red-400' ?> shadow-sm">
                    <?= htmlspecialchars($mensaje['texto'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <!-- Barra de búsqueda -->
            <div class="mb-6 flex items-center gap-4">
                <form action="?route=noticias" method="POST" class="flex-grow relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input 
                        type="text" 
                        name="buscar" 
                        placeholder="Buscar por título o descripción..." 
                        class="w-full p-3 pl-10 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-400 transition" 
                        value="<?= isset($_POST['buscar']) ? htmlspecialchars($_POST['buscar'], ENT_QUOTES, 'UTF-8') : '' ?>"
                    >
                </form>

                <!-- Botón limpiar búsqueda -->
                <?php if (!empty($_POST['buscar'])): ?>
                    <form action="?route=noticias" method="POST">
                        <button type="submit" class="px-5 py-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 transition">
                            Limpiar
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Grid de noticias -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (!empty($noticias)): ?>
                    <?php foreach ($noticias as $noticia): ?>
                        <div class="bg-white rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                            
                            <!-- Imagen de la noticia -->
                            <div class="relative">
                                <img 
                                    src="<?= htmlspecialchars($noticia['imagen'], ENT_QUOTES, 'UTF-8') ?>" 
                                    alt="<?= htmlspecialchars($noticia['titulo'], ENT_QUOTES, 'UTF-8') ?>" 
                                    class="w-full h-48 object-cover"
                                    onerror="this.src='assets/img/noticias/default.jpg'"
                                >
                                
                                <!-- Badge de fecha -->
                                <div class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm text-slate-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    <?= htmlspecialchars((new DateTime($noticia['fecha_publicacion']))->format('d M, Y'), ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </div>

                            <!-- Contenido de la tarjeta -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 truncate mb-2">
                                    <?= htmlspecialchars($noticia['titulo'], ENT_QUOTES, 'UTF-8') ?>
                                </h3>
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                    <?= htmlspecialchars($noticia['descripcion'], ENT_QUOTES, 'UTF-8') ?>
                                </p>

                                <!-- Acciones -->
                                <div class="flex items-center justify-between space-x-2 pt-4 border-t border-gray-100">
                                    
                                    <!-- Botón editar -->
                                    <button 
                                        onclick='openEditModal(<?= json_encode([
                                            "id" => $noticia["id"],
                                            "titulo" => $noticia["titulo"],
                                            "descripcion" => $noticia["descripcion"],
                                            "imagen" => $noticia["imagen"]
                                        ]) ?>)'
                                        class="flex items-center gap-2 w-full justify-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-full hover:bg-blue-700 transition duration-300 shadow-lg"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        </svg>
                                        Editar
                                    </button>

                                    <!-- Botón eliminar -->
                                    <button 
                                        onclick="confirmDelete(<?= $noticia['id'] ?>)" 
                                        class="flex items-center gap-2 w-full justify-center px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 transition duration-300 shadow-lg"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <!-- Estado vacío -->
                    <div class="col-span-full bg-slate-50 p-12 rounded-3xl shadow-inner text-center">
                        <svg class="mx-auto h-16 w-16 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18V7.125c0-.621.504-1.125 1.125-1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V7.5z" />
                        </svg>
                        <p class="text-slate-600 font-medium text-lg mt-4">Aún no hay noticias publicadas.</p>
                        <p class="text-slate-500 text-sm">¡Sé el primero en crear una!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ====================================================================
            MODAL DE CREAR/EDITAR NOTICIA
    ==================================================================== -->
    <div id="noticiaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-lg mx-4 transform scale-95 transition-all duration-300">
            
            <!-- Encabezado del modal -->
            <div class="flex justify-between items-center mb-6">
                <h3 id="modalTitle" class="text-2xl font-bold text-gray-800">Crear Noticia</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-3xl font-light">&times;</button>
            </div>

            <!-- Formulario -->
            <form id="noticiaForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="id" id="noticiaId">
                <input type="hidden" name="imagen_actual" id="imagenActual">

                <!-- Título -->
                <div>
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input 
                        type="text" 
                        id="titulo" 
                        name="titulo" 
                        required 
                        class="w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    >
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea 
                        id="descripcion" 
                        name="descripcion" 
                        rows="4" 
                        required 
                        class="w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    ></textarea>
                </div>

                <!-- Imagen -->
                <div>
                    <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                    <input 
                        id="imagen" 
                        name="imagen"