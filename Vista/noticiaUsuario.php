<!-- /**
 * @file noticiaUsuario.php
 * @version 2.3 (Versión final con encabezado completo y comentarios por bloque)
 * @author Samuel Bedoya
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
 */ -->

<!-- ============================================================================
        VISTA PRINCIPAL DE NOTICIAS - VERSIÓN USUARIO
        Interfaz pública para visualizar noticias parroquiales con diseño moderno
        Incluye funcionalidad administrativa para usuarios con rol de Administrador
============================================================================= -->

<main class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">

    <!-- ========================================================================
            HERO SECTION - ENCABEZADO PRINCIPAL
            Banner decorativo con información general y estadísticas en tiempo real
            Incluye elementos animados y diseño responsive
    ========================================================================= -->
    <section class="relative bg-gradient-to-r from-[#D0B8A8] via-[#b5a394] to-[#ab876f] text-white py-20 overflow-hidden">

        <!-- Capa de overlay oscuro para mejorar legibilidad del texto -->
        <div class="absolute inset-0 bg-black/20"></div>

        <!-- Elementos decorativos animados en el fondo -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-16 w-16 h-16 bg-white/10 rounded-full animate-bounce"></div>
        <div class="absolute top-1/2 left-1/4 w-12 h-12 bg-white/10 rounded-full animate-ping"></div>

        <!-- Contenido principal del hero -->
        <div class="relative container mx-auto px-4 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 rounded-full mb-8 backdrop-blur-sm">
                <span class="material-icons text-4xl"></span>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-2xl">
                Noticias Parroquiales
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                Mantente informado sobre los eventos, celebraciones y actividades de nuestra comunidad
            </p>

            <!-- Badges con estadísticas -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full">
                    <span class="flex items-center text-lg font-semibold">
                        <span class="material-icons mr-2"></span>
                        <?= count($noticias) ?> Noticias Publicadas
                    </span>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full">
                    <span class="flex items-center text-lg font-semibold">
                        <span class="material-icons mr-2"></span>
                        Actualizado Hoy
                    </span>
                </div>
            </div>
        </div>

        <!-- Ola SVG decorativa para transición suave entre secciones -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-16 fill-white">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"></path>
            </svg>
        </div>
    </section>

    <!-- ========================================================================
            SECCIÓN DE CONTENIDO PRINCIPAL
            Incluye barra de búsqueda y grid de noticias
    ========================================================================= -->
    <section class="container mx-auto px-4 py-16 -mt-8 relative z-10">

        <!-- ====================================================================
                BARRA DE BÚSQUEDA Y CONTROLES
                Permite filtrar noticias y crear nuevas (solo administradores)
        ==================================================================== -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-12 backdrop-blur-sm">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">

                <!-- Formulario de búsqueda con diseño mejorado -->
                <div class="flex-1 w-full lg:w-auto">
                    <form action="" method="POST" class="relative group">
                        <!-- Icono de búsqueda posicionado absolutamente -->
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <span class="material-icons text-gray-400 group-focus-within:text-[#ab876f] transition-colors duration-300"></span>
                        </div>

                        <!-- Campo de búsqueda con estilos interactivos -->
                        <input type="text"
                            name="buscar"
                            placeholder="Buscar noticias por título, descripción o fecha..."
                            value="<?= htmlspecialchars($_POST['buscar'] ?? '') ?>"
                            class="w-full pl-16 pr-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all duration-300 bg-gray-50 focus:bg-white">

                        <!-- Botón de búsqueda integrado -->
                        <button type="submit" class="absolute inset-y-0 right-0 pr-6 flex items-center">
                            <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                                Buscar
                            </div>
                        </button>
                    </form>
                </div>

                <!-- ============================================================
                        CONTROLES ADICIONALES
                        Botón para limpiar búsqueda y crear noticia (admin)
                ============================================================= -->
                <div class="flex items-center gap-4">
                    <!-- Botón para limpiar búsqueda - solo visible si hay búsqueda activa -->
                    <?php if (!empty($_POST['buscar'])): ?>
                        <form action="" method="POST" class="inline-block">
                            <button type="submit" class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300">
                                <span class="material-icons mr-2"></span>
                                Limpiar búsqueda
                            </button>
                        </form>
                    <?php endif; ?>

                    <!-- Botón crear noticia - solo visible para administradores -->
                    <?php if (isset($_SESSION['user-rol']) && $_SESSION['user-rol'] === 'Administrador'): ?>
                        <button id="openModalBtn"
                            class="flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <span class="material-icons mr-2"></span>
                            Nueva Noticia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ====================================================================
                GRID DE NOTICIAS
                Diseño responsive con cards animadas para cada noticia
        ==================================================================== -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <?php if (!empty($noticias)): ?>
                <?php foreach ($noticias as $index => $noticiaItem): ?>

                    <!-- ========================================================
                            CARD DE NOTICIA INDIVIDUAL
                            Cada card tiene animación de entrada escalonada
                            Efecto hover con transformación 3D
                    ========================================================= -->
                    <article class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 hover:rotate-1 transition-all duration-500 group"
                        style="animation: fadeInUp 0.6s ease-out <?= $index * 0.1 ?>s both">

                        <!-- Contenedor de imagen con overlay y badge de fecha -->
                        <div class="relative overflow-hidden h-64">
                            <!-- Renderiza imagen si existe, sino muestra placeholder -->
                            <?php if (!empty($noticiaItem['imagen']) && file_exists($noticiaItem['imagen'])): ?>
                                <img src="<?= htmlspecialchars($noticiaItem['imagen']) ?>"
                                    alt="<?= htmlspecialchars($noticiaItem['titulo']) ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] flex items-center justify-center">
                                    <span class="material-icons text-white text-6xl opacity-50">image</span>
                                </div>
                            <?php endif; ?>

                            <!-- Badge de fecha con formato legible -->
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm rounded-full px-4 py-2">
                                <span class="text-sm font-semibold text-gray-700 flex items-center">
                                    <span class="material-icons mr-1 text-sm">calendar_today</span>
                                    <?= htmlspecialchars((new DateTime($noticiaItem['fecha_publicacion']))->format('d M, Y')) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Contenido de la card -->
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-[#ab876f] transition-colors duration-300">
                                <?= htmlspecialchars($noticiaItem['titulo']) ?>
                            </h3>

                            <!-- Descripción truncada a 150 caracteres -->
                            <p class="text-gray-600 text-lg leading-relaxed mb-6 line-clamp-3">
                                <?= htmlspecialchars(substr($noticiaItem['descripcion'], 0, 150)) . (strlen($noticiaItem['descripcion']) > 150 ? '...' : '') ?>
                            </p>

                            <!-- Información adicional y botón de leer más -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="material-icons mr-2">visibilidad</span>
                                    <span>Lectura: 2 min</span>
                                </div>
                                <button class="flex items-center text-[#ab876f] hover:text-[#8a6b57] font-semibold transition-colors duration-300 group">
                                    Leer más
                                    <span class="material-icons ml-1 group-hover:translate-x-1 transition-transform duration-300"></span>
                                </button>
                            </div>

                            <!-- ================================================
                                    CONTROLES DE ADMINISTRACIÓN
                                    Botones de editar y eliminar - solo para admin
                            ================================================= -->
                            <?php if (isset($_SESSION['user-rol']) && $_SESSION['user-rol'] === 'Administrador'): ?>
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <div class="flex items-center justify-between gap-3">

                                        <!-- Botón editar con data attributes para el modal -->
                                        <button class="flex-1 open-edit-modal bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center"
                                            data-id="<?= $noticiaItem['id'] ?>"
                                            data-titulo="<?= htmlspecialchars($noticiaItem['titulo']) ?>"
                                            data-descripcion="<?= htmlspecialchars($noticiaItem['descripcion']) ?>"
                                            data-imagen="<?= htmlspecialchars($noticiaItem['imagen']) ?>">
                                            <span class="material-icons mr-2">edit</span>
                                            Editar
                                        </button>

                                        <!-- Formulario de eliminación con confirmación -->
                                        <form class="flex-1 delete-form" method="POST" action="">
                                            <input type="hidden" name="id" value="<?= $noticiaItem['id'] ?>">
                                            <input type="hidden" name="action" value="eliminar">
                                            <button type="button" class="w-full delete-btn bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                                                <span class="material-icons mr-2">delete</span>
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>

            <?php else: ?>
                <!-- ========================================================
                        ESTADO VACÍO
                        Muestra mensaje cuando no hay noticias o sin resultados
                ========================================================= -->
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex items-center justify-center w-32 h-32 bg-gray-100 rounded-full mb-8">
                        <span class="material-icons text-gray-400 text-6xl">inbox</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">
                        <?= !empty($_POST['buscar']) ? 'Sin resultados' : 'No hay noticias disponibles' ?>
                    </h3>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ========================================================================
            MODALES DE ADMINISTRACIÓN
            Solo se renderizan si el usuario tiene rol de Administrador
    ========================================================================= -->
    <?php if (isset($_SESSION['user-rol']) && $_SESSION['user-rol'] === 'Administrador'): ?>

        <!-- ====================================================================
                MODAL DE CREAR/EDITAR NOTICIA
                Modal fullscreen con formulario completo y vista previa de imagen
        ==================================================================== -->
        <div id="noticiaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto transform scale-95 transition-all duration-300" id="modalContent">

                <!-- Encabezado del modal con degradado -->
                <div class="sticky top-0 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white p-8 rounded-t-3xl">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 id="modalTitle" class="text-3xl font-bold mb-2">Crear Nueva Noticia</h3>
                            <p class="opacity-90">Comparte información importante con la comunidad</p>
                        </div>
                        <button id="closeModalBtn" class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                            <span class="material-icons text-2xl">close</span>
                        </button>
                    </div>
                </div>

                <!-- ============================================================
                        FORMULARIO DE NOTICIA
                        Layout en dos columnas con campos y vista previa
                ============================================================= -->
                <form id="noticiaForm" action="#" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    <!-- Campos ocultos para operación de guardado/actualización -->
                    <input type="hidden" name="id" id="noticiaId">
                    <input type="hidden" name="action" value="guardar">
                    <input type="hidden" name="imagen_actual" id="imagenActual">

                    <!-- Grid de dos columnas: formulario y vista previa -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- Columna izquierda: campos de entrada -->
                        <div class="space-y-6">
                            <!-- Campo título -->
                            <div>
                                <label for="titulo" class="block text-lg font-bold text-gray-900 mb-3">Título de la Noticia</label>
                                <input type="text" id="titulo" name="titulo" required
                                    class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all duration-300">
                            </div>

                            <!-- Campo imagen con validaciones de tipo y tamaño -->
                            <div>
                                <label for="imagen" class="block text-lg font-bold text-gray-900 mb-3">Imagen Principal</label>
                                <input type="file" id="imagen" name="imagen" accept="image/*"
                                    class="w-full px-6 py-4 text-lg border-2 border-dashed border-gray-300 rounded-2xl">
                                <p class="text-sm text-gray-500 mt-2">JPG, PNG o GIF. Máximo 5MB</p>
                            </div>
                        </div>

                        <!-- Columna derecha: vista previa de imagen -->
                        <div class="flex items-center justify-center">
                            <img id="imagenPreview" src="" alt="Vista previa"
                                class="max-w-full h-48 object-cover rounded-2xl shadow-lg hidden">
                            <div id="imagenPlaceholder" class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                                <div class="text-center text-gray-400">
                                    <span class="material-icons text-6xl mb-2">image</span>
                                    <p class="font-semibold">Vista previa de la imagen</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campo descripción con contador de caracteres -->
                    <div>
                        <label for="descripcion" class="block text-lg font-bold text-gray-900 mb-3">Contenido de la Noticia</label>
                        <textarea id="descripcion" name="descripcion" rows="6" required
                            class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl resize-none"></textarea>
                        <div id="charCounter" class="text-sm text-gray-500 mt-2 text-right">0/500 caracteres</div>
                    </div>

                    <!-- Botones de acción del formulario -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white text-xl font-bold py-4 px-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 flex items-center justify-center">
                            <span class="material-icons mr-3">publish</span>
                            Publicar Noticia
                        </button>
                        <button type="button" id="cancelBtn" class="flex-1 bg-gray-200 text-gray-800 text-xl font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 flex items-center justify-center">
                            <span class="material-icons mr-3">cancel</span>
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ====================================================================
                MODAL DE CONFIRMACIÓN DE ELIMINACIÓN
                Diálogo de confirmación para prevenir eliminaciones accidentales
        ==================================================================== -->
        <div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md mx-4 transform scale-95 transition-all duration-300" id="deleteModalContent">
                <!-- El contenido se inyecta dinámicamente desde JavaScript -->
            </div>
        </div>

        <!-- ====================================================================
                SCRIPT DE INTERACTIVIDAD
                Maneja toda la lógica del frontend: modales, AJAX, validaciones
        ==================================================================== -->
        <script src="Vista/js/noticiaUsuario.js"></script>
    <?php endif; ?>

</main>