<main class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <!-- Header Hero Section -->
    <section class="relative bg-gradient-to-r from-[#D0B8A8] via-[#b5a394] to-[#ab876f] text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-16 w-16 h-16 bg-white/10 rounded-full animate-bounce"></div>
        <div class="absolute top-1/2 left-1/4 w-12 h-12 bg-white/10 rounded-full animate-ping"></div>
        
        <div class="relative container mx-auto px-4 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 rounded-full mb-8 backdrop-blur-sm">
                <span class="material-icons text-4xl">newspaper</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-2xl">
                Noticias Parroquiales
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                Mantente informado sobre los eventos, celebraciones y actividades de nuestra comunidad
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full">
                    <span class="flex items-center text-lg font-semibold">
                        <span class="material-icons mr-2">event</span>
                        <?= count($noticias) ?> Noticias Publicadas
                    </span>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full">
                    <span class="flex items-center text-lg font-semibold">
                        <span class="material-icons mr-2">schedule</span>
                        Actualizado Hoy
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Wave Decoration -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-16 fill-white">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"></path>
            </svg>
        </div>
    </section>

    <!-- Content Section -->
    <section class="container mx-auto px-4 py-16 -mt-8 relative z-10">
        <!-- Search and Filter Bar -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-12 backdrop-blur-sm">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                <div class="flex-1 w-full lg:w-auto">
                    <form action="" method="POST" class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <span class="material-icons text-gray-400 group-focus-within:text-[#ab876f] transition-colors duration-300">search</span>
                        </div>
                        <input type="text" 
                               name="buscar" 
                               placeholder="Buscar noticias por título, descripción o fecha..." 
                               value="<?= htmlspecialchars($_POST['buscar'] ?? '') ?>"
                               class="w-full pl-16 pr-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all duration-300 bg-gray-50 focus:bg-white">
                        <button type="submit" 
                                class="absolute inset-y-0 right-0 pr-6 flex items-center">
                            <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                                Buscar
                            </div>
                        </button>
                    </form>
                </div>
                
                <div class="flex items-center gap-4">
                    <?php if (!empty($_POST['buscar'])): ?>
                        <form action="" method="POST" class="inline-block">
                            <button type="submit" 
                                    class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-300">
                                <span class="material-icons mr-2">clear</span>
                                Limpiar búsqueda
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['user-id'])): ?>
                        <button id="openModalBtn" 
                                class="flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            <span class="material-icons mr-2">add</span>
                            Nueva Noticia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if (isset($mensaje['texto'])): ?>
            <div class="mb-8 animate-pulse">
                <div class="<?= $mensaje['tipo'] === 'success' ? 'bg-green-50 border-l-4 border-green-400 text-green-700' : 'bg-red-50 border-l-4 border-red-400 text-red-700' ?> p-6 rounded-2xl shadow-lg">
                    <div class="flex items-center">
                        <span class="material-icons mr-3 text-2xl">
                            <?= $mensaje['tipo'] === 'success' ? 'check_circle' : 'error' ?>
                        </span>
                        <p class="text-lg font-semibold"><?= htmlspecialchars($mensaje['texto']) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <?php if (!empty($noticias)): ?>
                <?php foreach ($noticias as $index => $noticiaItem): ?>
                    <article class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 hover:rotate-1 transition-all duration-500 group"
                             style="animation: fadeInUp 0.6s ease-out <?= $index * 0.1 ?>s both">
                        <!-- Image Container -->
                        <div class="relative overflow-hidden h-64">
                            <?php if (!empty($noticiaItem['imagen']) && file_exists($noticiaItem['imagen'])): ?>
                                <img src="<?= htmlspecialchars($noticiaItem['imagen']) ?>" 
                                     alt="<?= htmlspecialchars($noticiaItem['titulo']) ?>" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] flex items-center justify-center">
                                    <span class="material-icons text-white text-6xl opacity-50">image</span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Overlay with date -->
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm rounded-full px-4 py-2">
                                <span class="text-sm font-semibold text-gray-700 flex items-center">
                                    <span class="material-icons mr-1 text-sm">calendar_today</span>
                                    <?= htmlspecialchars($noticiaItem['fecha_publicacion'] ?? 'Hoy') ?>
                                </span>
                            </div>
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 right-4 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white text-xs font-bold px-3 py-1 rounded-full">
                                NOTICIA
                            </div>
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 line-clamp-2 group-hover:text-[#ab876f] transition-colors duration-300">
                                <?= htmlspecialchars($noticiaItem['titulo']) ?>
                            </h3>
                            
                            <p class="text-gray-600 text-lg leading-relaxed mb-6 line-clamp-3">
                                <?= htmlspecialchars(substr($noticiaItem['descripcion'], 0, 150)) ?>
                                <?= strlen($noticiaItem['descripcion']) > 150 ? '...' : '' ?>
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="material-icons mr-2">visibility</span>
                                    <span>Lectura: 2 min</span>
                                </div>
                                
                                <button class="flex items-center text-[#ab876f] hover:text-[#8a6b57] font-semibold transition-colors duration-300 group">
                                    Leer más
                                    <span class="material-icons ml-1 group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                                </button>
                            </div>
                            
                            <?php if (isset($_SESSION['user-id'])): ?>
                                <!-- Admin Controls -->
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <div class="flex items-center justify-between gap-3">
                                        <button class="flex-1 open-edit-modal bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center"
                                                data-id="<?= $noticiaItem['id'] ?>"
                                                data-titulo="<?= htmlspecialchars($noticiaItem['titulo']) ?>"
                                                data-descripcion="<?= htmlspecialchars($noticiaItem['descripcion']) ?>"
                                                data-imagen="<?= htmlspecialchars($noticiaItem['imagen']) ?>">
                                            <span class="material-icons mr-2">edit</span>
                                            Editar
                                        </button>
                                        
                                        <form class="flex-1 delete-form" method="POST" action="">
                                            <input type="hidden" name="id" value="<?= $noticiaItem['id'] ?>">
                                            <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('eliminar') ?>">
                                            <input type="hidden" name="menu-item" value="Noticias">
                                            <button type="button" 
                                                    class="w-full delete-btn bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
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
                <!-- Empty State -->
                <div class="col-span-full text-center py-20">
                    <div class="inline-flex items-center justify-center w-32 h-32 bg-gray-100 rounded-full mb-8">
                        <span class="material-icons text-gray-400 text-6xl">inbox</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">
                        <?= !empty($_POST['buscar']) ? 'Sin resultados' : 'No hay noticias disponibles' ?>
                    </h3>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        <?= !empty($_POST['buscar']) 
                            ? 'No encontramos noticias que coincidan con tu búsqueda. Intenta con otros términos.' 
                            : 'Aún no se han publicado noticias. Vuelve pronto para ver las últimas novedades.' ?>
                    </p>
                    <?php if (!empty($_POST['buscar'])): ?>
                        <form action="" method="POST" class="inline-block">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white px-8 py-4 rounded-2xl font-bold shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                                <span class="material-icons mr-2">refresh</span>
                                Ver todas las noticias
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination (if needed) -->
        <?php if (count($noticias) > 12): ?>
            <div class="mt-16 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <button class="px-4 py-2 rounded-xl bg-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <span class="material-icons">chevron_left</span>
                    </button>
                    <button class="px-6 py-2 rounded-xl bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white font-bold shadow-lg">1</button>
                    <button class="px-6 py-2 rounded-xl bg-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">2</button>
                    <button class="px-6 py-2 rounded-xl bg-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">3</button>
                    <button class="px-4 py-2 rounded-xl bg-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <span class="material-icons">chevron_right</span>
                    </button>
                </nav>
            </div>
        <?php endif; ?>
    </section>

    <?php if (isset($_SESSION['user-id'])): ?>
        <!-- Modal para Crear/Editar Noticia -->
        <div id="noticiaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto transform scale-95 transition-all duration-300"
                 id="modalContent">
                <div class="sticky top-0 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white p-8 rounded-t-3xl">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 id="modalTitle" class="text-3xl font-bold mb-2">Crear Nueva Noticia</h3>
                            <p class="opacity-90">Comparte información importante con la comunidad</p>
                        </div>
                        <button id="closeModalBtn" 
                                class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                            <span class="material-icons text-2xl">close</span>
                        </button>
                    </div>
                </div>

                <form id="noticiaForm" action="#" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    <input type="hidden" name="id" id="noticiaId">
                    <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('guardar') ?>">
                    <input type="hidden" name="menu-item" value="Noticias">
                    <input type="hidden" name="imagen_actual" id="imagenActual">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label for="titulo" class="block text-lg font-bold text-gray-900 mb-3">
                                    <span class="material-icons mr-2 align-middle">title</span>
                                    Título de la Noticia
                                </label>
                                <input type="text" id="titulo" name="titulo" required
                                       class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all duration-300"
                                       placeholder="Ej: Celebración de la Fiesta Patronal">
                            </div>

                            <div>
                                <label for="imagen" class="block text-lg font-bold text-gray-900 mb-3">
                                    <span class="material-icons mr-2 align-middle">image</span>
                                    Imagen Principal
                                </label>
                                <div class="relative">
                                    <input type="file" id="imagen" name="imagen" accept="image/*"
                                           class="w-full px-6 py-4 text-lg border-2 border-dashed border-gray-300 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-[#D0B8A8] file:text-white file:font-semibold">
                                    <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none">
                                        <span class="material-icons text-gray-400">cloud_upload</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">JPG, PNG o GIF. Máximo 5MB</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-center">
                            <img id="imagenPreview" src="" alt="Vista previa" 
                                 class="max-w-full h-48 object-cover rounded-2xl shadow-lg hidden">
                            <div id="imagenPlaceholder" 
                                 class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                                <div class="text-center text-gray-400">
                                    <span class="material-icons text-6xl mb-2">image</span>
                                    <p class="font-semibold">Vista previa de la imagen</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="descripcion" class="block text-lg font-bold text-gray-900 mb-3">
                            <span class="material-icons mr-2 align-middle">description</span>
                            Contenido de la Noticia
                        </label>
                        <textarea id="descripcion" name="descripcion" rows="6" required
                                  class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition-all duration-300 resize-none"
                                  placeholder="Describe los detalles importantes de tu noticia..."></textarea>
                        <div id="charCounter" class="text-sm text-gray-500 mt-2 text-right">0/500 caracteres</div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white text-xl font-bold py-4 px-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 flex items-center justify-center">
                            <span class="material-icons mr-3">publish</span>
                            Publicar Noticia
                        </button>
                        <button type="button" id="cancelBtn"
                                class="flex-1 bg-gray-200 text-gray-800 text-xl font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 flex items-center justify-center">
                            <span class="material-icons mr-3">cancel</span>
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Modal de Confirmación de Eliminación -->
    <div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md mx-4 transform scale-95 transition-all duration-300"
             id="deleteModalContent">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6">
                    <span class="material-icons text-red-500 text-3xl">delete_forever</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">¿Eliminar Noticia?</h3>
                <p class="text-gray-600 mb-8">Esta acción no se puede deshacer. La noticia será eliminada permanentemente.</p>
                <div class="flex space-x-4">
                    <button id="cancelDeleteBtn" 
                            class="flex-1 bg-gray-200 text-gray-800 font-bold py-3 px-6 rounded-2xl hover:bg-gray-300 transition-all duration-300">
                        Cancelar
                    </button>
                    <button id="confirmDeleteBtn" 
                            class="flex-1 bg-red-500 text-white font-bold py-3 px-6 rounded-2xl hover:bg-red-600 transition-all duration-300">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

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
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .news-card-hover {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .news-card-hover:hover {
            transform: translateY(-12px) rotate(2deg);
        }
    </style>

    <script>
        // Variables globales del sistema
        let currentDeleteForm = null;
        let isEditMode = false;

        // Inicialización cuando el DOM está listo
        document.addEventListener('DOMContentLoaded', function() {
            initializeEventListeners();
            initializeAnimations();
            initializeFormValidation();
        });

        // Configurar todos los event listeners
        function initializeEventListeners() {
            // Modal de crear/editar noticia
            const openModalBtn = document.getElementById('openModalBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const noticiaModal = document.getElementById('noticiaModal');

            if (openModalBtn) {
                openModalBtn.addEventListener('click', () => openCreateModal());
            }

            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', () => closeNoticiaModal());
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => closeNoticiaModal());
            }

            // Botones de editar
            document.addEventListener('click', function(e) {
                if (e.target.closest('.open-edit-modal')) {
                    const btn = e.target.closest('.open-edit-modal');
                    openEditModal(btn);
                }

                if (e.target.closest('.delete-btn')) {
                    e.preventDefault();
                    const form = e.target.closest('.delete-form');
                    openDeleteConfirmation(form);
                }
            });

            // Preview de imagen
            const imagenInput = document.getElementById('imagen');
            if (imagenInput) {
                imagenInput.addEventListener('change', handleImagePreview);
            }

            // Contador de caracteres
            const descripcionTextarea = document.getElementById('descripcion');
            if (descripcionTextarea) {
                descripcionTextarea.addEventListener('input', updateCharCounter);
            }

            // Modal de eliminación
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', closeDeleteModal);
            }

            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', executeDelete);
            }

            // Cerrar modales con Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeNoticiaModal();
                    closeDeleteModal();
                }
            });

            // Cerrar modales clickeando fuera
            if (noticiaModal) {
                noticiaModal.addEventListener('click', function(e) {
                    if (e.target === noticiaModal) {
                        closeNoticiaModal();
                    }
                });
            }

            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === deleteModal) {
                        closeDeleteModal();
                    }
                });
            }
        }

        // Inicializar animaciones de entrada
        function initializeAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);

            // Observar todas las tarjetas de noticias
            document.querySelectorAll('[style*="animation"]').forEach(el => {
                el.style.animationPlayState = 'paused';
                observer.observe(el);
            });
        }

        // Validación de formularios
        function initializeFormValidation() {
            const form = document.getElementById('noticiaForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateForm()) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Mostrar indicador de carga
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalContent = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<span class="material-icons mr-2 animate-spin">hourglass_empty</span>Publicando...';
                        submitBtn.disabled = true;
                        
                        // Restaurar después de 10 segundos por seguridad
                        setTimeout(() => {
                            submitBtn.innerHTML = originalContent;
                            submitBtn.disabled = false;
                        }, 10000);
                    }
                });
            }
        }

        // Funciones de modal
        function openCreateModal() {
            isEditMode = false;
            document.getElementById('modalTitle').textContent = 'Crear Nueva Noticia';
            document.getElementById('noticiaForm').reset();
            document.getElementById('noticiaId').value = '';
            document.getElementById('imagenActual').value = '';
            hideImagePreview();
            showModal('noticiaModal');
        }

        function openEditModal(button) {
            isEditMode = true;
            const data = button.dataset;
            
            document.getElementById('modalTitle').textContent = 'Editar Noticia';
            document.getElementById('noticiaId').value = data.id;
            document.getElementById('titulo').value = data.titulo;
            document.getElementById('descripcion').value = data.descripcion;
            document.getElementById('imagenActual').value = data.imagen;
            
            // Mostrar imagen actual
            if (data.imagen) {
                showImagePreview(data.imagen);
            }
            
            updateCharCounter();
            showModal('noticiaModal');
        }

        function closeNoticiaModal() {
            hideModal('noticiaModal');
            setTimeout(() => {
                document.getElementById('noticiaForm').reset();
                hideImagePreview();
            }, 300);
        }

        function openDeleteConfirmation(form) {
            currentDeleteForm = form;
            showModal('deleteConfirmationModal');
        }

        function closeDeleteModal() {
            currentDeleteForm = null;
            hideModal('deleteConfirmationModal');
        }

        function executeDelete() {
            if (currentDeleteForm) {
                // Mostrar indicador de carga
                const deleteBtn = document.getElementById('confirmDeleteBtn');
                const originalText = deleteBtn.textContent;
                deleteBtn.innerHTML = '<span class="material-icons mr-2 animate-spin">hourglass_empty</span>Eliminando...';
                deleteBtn.disabled = true;
                
                // Enviar formulario
                currentDeleteForm.submit();
            }
        }

        // Funciones de utilidad para modales
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = modal.querySelector('[id$="Content"], [id$="modalContent"]') || modal.firstElementChild;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                if (content) {
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }
            }, 10);
        }

        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = modal.querySelector('[id$="Content"], [id$="modalContent"]') || modal.firstElementChild;
            
            if (content) {
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
            }
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        // Funciones de imagen
        function handleImagePreview(event) {
            const file = event.target.files[0];
            if (file) {
                // Validar tamaño (5MB máximo)
                if (file.size > 5 * 1024 * 1024) {
                    alert('La imagen es demasiado grande. El tamaño máximo es 5MB.');
                    event.target.value = '';
                    return;
                }

                // Validar tipo
                if (!file.type.startsWith('image/')) {
                    alert('Por favor selecciona un archivo de imagen válido.');
                    event.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview(e.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                hideImagePreview();
            }
        }

        function showImagePreview(src) {
            const preview = document.getElementById('imagenPreview');
            const placeholder = document.getElementById('imagenPlaceholder');
            
            if (preview && placeholder) {
                preview.src = src;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        }

        function hideImagePreview() {
            const preview = document.getElementById('imagenPreview');
            const placeholder = document.getElementById('imagenPlaceholder');
            
            if (preview && placeholder) {
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }

        // Contador de caracteres
        function updateCharCounter() {
            const textarea = document.getElementById('descripcion');
            const counter = document.getElementById('charCounter');
            
            if (textarea && counter) {
                const currentLength = textarea.value.length;
                const maxLength = 500;
                
                counter.textContent = `${currentLength}/${maxLength} caracteres`;
                
                if (currentLength > maxLength * 0.9) {
                    counter.className = 'text-sm text-red-500 mt-2 text-right font-semibold';
                } else if (currentLength > maxLength * 0.7) {
                    counter.className = 'text-sm text-yellow-500 mt-2 text-right font-semibold';
                } else {
                    counter.className = 'text-sm text-gray-500 mt-2 text-right';
                }
            }
        }

        // Validación de formulario
        function validateForm() {
            const titulo = document.getElementById('titulo').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            
            if (titulo.length < 5) {
                alert('El título debe tener al menos 5 caracteres.');
                document.getElementById('titulo').focus();
                return false;
            }
            
            if (descripcion.length < 20) {
                alert('La descripción debe tener al menos 20 caracteres.');
                document.getElementById('descripcion').focus();
                return false;
            }
            
            if (descripcion.length > 500) {
                alert('La descripción no puede exceder los 500 caracteres.');
                document.getElementById('descripcion').focus();
                return false;
            }
            
            return true;
        }

        // Efectos adicionales
        document.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('[class*="animate-"]');
            
            // Parallax suave para elementos animados
            parallaxElements.forEach((element, index) => {
                const speed = 0.05 * (index + 1);
                const yPos = scrolled * speed;
                element.style.transform += ` translateY(${yPos}px)`;
            });
        });

        // Auto-focus en campos del modal
        document.addEventListener('transitionend', function(e) {
            if (e.target.id === 'modalContent' && !e.target.classList.contains('scale-95')) {
                const firstInput = e.target.querySelector('input[type="text"]');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            }
        });

        // Variables globales definidas por PHP
        window.actionHash = '<?= md5('action') ?>';
        window.editActionHash = '<?= md5('editar') ?>';
    </script>
</main>