<?php

/**
 * @file vistaNoticias.php
 * @version 1.2 (Versión final con encabezado completo y comentarios por bloque)
 * @author Tu Nombre (tu.email@example.com)
 * @date 2025-10-01
 * @brief Vista para la gestión completa de noticias (CRUD: Crear, Leer, Actualizar, Eliminar).
 *
 * Esta vista renderiza la interfaz de usuario para mostrar una lista de noticias.
 * Permite a los usuarios autenticados crear, editar y eliminar noticias a través de modales
 * interactivos manejados con JavaScript y peticiones AJAX.
 *
 * @dependency noticias.php - El controlador principal que carga esta vista e inicializa la sesión.
 * @dependency Vista/js/noticiaAdministrador.js - Script que maneja la interactividad del frontend.
 * @dependency TailwindCSS - Framework de CSS para el diseño de la interfaz.
 */

// ============================================================================
// VALIDACIÓN DE SESIÓN
// ============================================================================
// Es crucial que la sesión se inicie en el archivo principal que carga esta vista.
// Por ejemplo, en 'noticias.php' debería haber un session_start(); al principio.
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Noticias</title>

    <!-- Framework CSS Tailwind desde CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Transiciones suaves para modales */
        .modal,
        .modal-content {
            transition: all 0.3s ease-in-out;
        }
    </style>

    <script>
        /**
         * Configuración global de la aplicación
         * Define endpoints y tokens de seguridad accesibles desde JavaScript
         */
        window.appConfig = {
            ajaxUrl: 'Vista/ajaxNoticias.php', // Endpoint para las peticiones asíncronas.
            csrfToken: '' // Reservado para un futuro token de seguridad.
        };
    </script>
</head>

<body class="bg-slate-100 font-sans">

    <!-- ========================================================================
         OVERLAY DE CARGA
         Indicador visual que se muestra durante operaciones asíncronas
    ========================================================================= -->
    <div id="loadingOverlay" class="fixed inset-0 z-[101] flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-white"></div>
    </div>

    <!-- ========================================================================
         CONTENEDOR DE NOTIFICACIONES
         Área donde se renderizan mensajes de feedback al usuario
    ========================================================================= -->
    <div id="feedback-container" class="fixed top-5 right-5 z-[100] w-full max-w-sm"></div>


    <!-- ========================================================================
         SECCIÓN PRINCIPAL: GESTIÓN DE NOTICIAS
    ========================================================================= -->
    <div class="max-w-7xl mx-auto p-4 sm:p-8 flex-1">
        <div class="bg-white p-6 rounded-3xl shadow-2xl mb-8">

            <!-- ================================================================
                 ENCABEZADO CON TÍTULO Y BOTÓN DE CREACIÓN
                 El botón solo es visible para usuarios autenticados
            ================================================================= -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <div>
                    <h2 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-slate-700 to-slate-900">
                        Gestión de Noticias
                    </h2>
                    <p class="text-slate-500 mt-1">Crea, edita y comparte las últimas novedades.</p>
                </div>

                <!-- Botón de crear: solo visible si hay sesión activa -->
                <?php if (isset($_SESSION['user-id'])): ?>
                    <button id="openModalBtn" class="mt-4 md:mt-0 flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-full shadow-lg hover:bg-indigo-700 transition-transform duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Crear Noticia</span>
                    </button>
                <?php endif; ?>
            </div>

            <!-- ================================================================
                 MENSAJES DE FEEDBACK DEL SERVIDOR
                 Muestra notificaciones de éxito o error después de operaciones
            ================================================================= -->
            <?php if (isset($mensaje['texto'])): ?>
                <div class="mb-4 p-4 rounded-xl font-medium <?= $mensaje['tipo'] === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-400' : 'bg-red-50 text-red-800 border-l-4 border-red-400' ?> shadow-sm">
                    <?= htmlspecialchars($mensaje['texto']) ?>
                </div>
            <?php endif; ?>

            <!-- ================================================================
                 BARRA DE BÚSQUEDA Y FILTRADO
                 Permite buscar noticias por título o descripción
            ================================================================= -->
            <div class="mb-6 flex items-center gap-4">
                <form action="noticias.php" method="POST" class="flex-grow relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" name="buscar" placeholder="Buscar por título o descripción..." class="w-full p-3 pl-10 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-400 transition" value="<?= htmlspecialchars($_POST['buscar'] ?? '') ?>">
                </form>

                <!-- Botón para limpiar la búsqueda -->
                <form action="noticias.php" method="POST">
                    <button type="submit" class="px-5 py-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 transition">
                        Limpiar
                    </button>
                </form>
            </div>

            <!-- ================================================================
                 GRID DE NOTICIAS
                 Renderiza las tarjetas de noticias en layout responsive
            ================================================================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (!empty($noticias)): ?>
                    <?php foreach ($noticias as $noticiaItem): ?>

                        <!-- ====================================================
                             TARJETA INDIVIDUAL DE NOTICIA
                             Muestra imagen, título, descripción y acciones
                        ===================================================== -->
                        <div class="bg-white rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 group">

                            <!-- Imagen de la noticia con fecha superpuesta -->
                            <div class="relative">
                                <img src="<?= htmlspecialchars($noticiaItem['imagen']) ?>" alt="Imagen de la noticia" class="w-full h-48 object-cover">

                                <!-- Badge de fecha en formato legible -->
                                <div class="absolute top-4 right-4 bg-white/80 backdrop-blur-sm text-slate-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    <?php
                                    $fecha = new DateTime($noticiaItem['fecha_publicacion']);
                                    echo $fecha->format('d M, Y');
                                    ?>
                                </div>
                            </div>

                            <!-- Contenido de la tarjeta -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 truncate mb-2 group-hover:text-indigo-600 transition"><?= htmlspecialchars($noticiaItem['titulo']) ?></h3>
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4"><?= htmlspecialchars($noticiaItem['descripcion']) ?></p>

                                <!-- ============================================
                                     ACCIONES DE ADMINISTRACIÓN
                                     Botones de editar y eliminar (solo usuarios autenticados)
                                ============================================= -->
                                <?php if (isset($_SESSION['user-id'])): ?>
                                    <div class="flex items-center justify-between space-x-2 pt-4 border-t border-gray-100">

                                        <!-- Botón editar: abre modal con datos precargados -->
                                        <button class="flex items-center gap-2 w-full justify-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-full hover:bg-blue-700 transition duration-300 open-edit-modal shadow-lg"
                                            data-id="<?= $noticiaItem['id'] ?>"
                                            data-titulo="<?= htmlspecialchars($noticiaItem['titulo']) ?>"
                                            data-descripcion="<?= htmlspecialchars($noticiaItem['descripcion']) ?>"
                                            data-imagen="<?= htmlspecialchars($noticiaItem['imagen']) ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                            Editar
                                        </button>

                                        <!-- Botón eliminar: requiere confirmación antes de ejecutar -->
                                        <form class="w-full delete-form" method="POST" action="noticias.php">
                                            <input type="hidden" name="id" value="<?= $noticiaItem['id'] ?>">
                                            <input type="hidden" name="action" value="eliminar">
                                            <button type="button" class="flex items-center gap-2 w-full justify-center px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 transition duration-300 delete-btn shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <!-- ====================================================
                            ESTADO VACÍO
                            Mensaje mostrado cuando no hay noticias disponibles
                    ===================================================== -->
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


    <!-- ========================================================================
            MODAL DE CREAR/EDITAR NOTICIA
            Formulario modal reutilizable para crear nuevas noticias o editar existentes
            Solo visible para usuarios autenticados
    ========================================================================= -->
    <?php if (isset($_SESSION['user-id'])): ?>
        <div id="noticiaModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden opacity-0">
            <div class="modal-content bg-white rounded-3xl shadow-2xl p-8 w-full max-w-lg mx-4 transform scale-95">

                <!-- Encabezado del modal -->
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-2xl font-bold text-gray-800">Crear Noticia</h3>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 text-3xl font-light leading-none">&times;</button>
                </div>

                <!-- ============================================================
                        FORMULARIO DE NOTICIA
                        Campos: título, descripción e imagen
                        Los campos hidden se llenan dinámicamente en modo edición
                ============================================================= -->
                <form id="noticiaForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <!-- Campos ocultos para identificar la operación -->
                    <input type="hidden" name="id" id="noticiaId">
                    <input type="hidden" name="action" value="guardar">
                    <input type="hidden" name="imagen_actual" id="imagenActual">

                    <!-- Campo: Título -->
                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                        <input type="text" id="titulo" name="titulo" required class="w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    <!-- Campo: Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required class="w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
                    </div>

                    <!-- Campo: Imagen con zona de drag & drop -->
                    <div>
                        <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="imagen" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                        <span>Sube un archivo</span>
                                        <input id="imagen" name="imagen" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">o arrástralo aquí</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 10MB</p>
                            </div>
                        </div>

                        <!-- Vista previa de la imagen seleccionada -->
                        <img id="imagenPreview" src="" alt="Vista previa" class="mt-4 h-32 w-auto object-cover rounded-xl shadow-md hidden">
                    </div>

                    <!-- Botón de envío -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 transition-transform duration-300 transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span>Guardar Cambios</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- ========================================================================
            MODAL DE CONFIRMACIÓN DE ELIMINACIÓN
            Diálogo de confirmación para prevenir eliminaciones accidentales
    ========================================================================= -->
    <div id="deleteConfirmationModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden opacity-0">
        <div class="modal-content bg-white rounded-3xl shadow-2xl p-8 w-full max-w-sm mx-4 text-center transform scale-95">

            <!-- Icono de advertencia -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <!-- Mensaje de confirmación -->
            <h3 class="text-xl font-bold text-gray-800 mt-5 mb-2">Confirmar Eliminación</h3>
            <p class="text-gray-600 mb-6">¿Estás seguro? Esta acción no se puede deshacer.</p>

            <!-- Botones de acción -->
            <div class="flex justify-center space-x-4">
                <button id="cancelDeleteBtn" class="px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded-full hover:bg-gray-300 transition-colors duration-300">Cancelar</button>
                <button id="confirmDeleteBtn" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-full hover:bg-red-700 transition-colors duration-300">Sí, Eliminar</button>
            </div>
        </div>
    </div>


    <!-- ========================================================================
            SCRIPT DE INTERACTIVIDAD
            Maneja toda la lógica del frontend: modales, AJAX, validaciones
    ========================================================================= -->
    <script src="Vista/js/noticiaAdministrador.js"></script>

</body>

</html>