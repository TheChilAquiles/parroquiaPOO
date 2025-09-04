<div class="max-w-7xl mx-auto p-8">
        <div class="bg-white p-6 rounded-3xl shadow-2xl mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold text-gray-800">
                    Noticias
                </h2>
                <?php if (isset($_SESSION['user-id'])): ?>
                    <button id="openModalBtn" class="mt-4 md:mt-0 px-8 py-3 bg-indigo-600 text-white font-bold rounded-full shadow-lg hover:bg-indigo-700 transition-transform duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                        Crear Nueva Noticia
                    </button>
                <?php endif; ?>
            </div>

            <?php if (isset($mensaje['texto'])): ?>
                <div class="mb-4 p-4 rounded-xl font-medium <?= $mensaje['tipo'] === 'success' ? 'bg-green-100 text-green-700 border-l-4 border-green-500' : 'bg-red-100 text-red-700 border-l-4 border-red-500' ?> shadow-md">
                    <?= htmlspecialchars($mensaje['texto']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($noticias)): ?>
                <?php foreach ($noticias as $noticiaItem): ?>
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <img src="/<?= htmlspecialchars($noticiaItem['imagen']) ?>" alt="Imagen de la noticia" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 truncate mb-2"><?= htmlspecialchars($noticiaItem['titulo']) ?></h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4"><?= htmlspecialchars($noticiaItem['descripcion']) ?></p>
                            <p class="text-gray-400 text-xs mb-4">Publicado: <?= htmlspecialchars(date('d/m/Y', strtotime($noticiaItem['estado_registro']))) ?></p>
                            <?php if (isset($_SESSION['user-id'])): ?>
                                <div class="flex items-center justify-between space-x-2">
                                    <button class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-full hover:bg-blue-700 transition duration-300 open-edit-modal shadow-lg"
                                        data-id="<?= $noticiaItem['id'] ?>"
                                        data-titulo="<?= htmlspecialchars($noticiaItem['titulo']) ?>"
                                        data-descripcion="<?= htmlspecialchars($noticiaItem['descripcion']) ?>"
                                        data-imagen="<?= htmlspecialchars($noticiaItem['imagen']) ?>">
                                        Editar
                                    </button>
                                    <form class="flex-1 delete-form" method="POST" action="index.php">
                                        <input type="hidden" name="id" value="<?= $noticiaItem['id'] ?>">
                                        <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('eliminar') ?>">
                                        <input type="hidden" name="menu-item" value="Noticias">
                                        <button type="button" class="w-full px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 transition duration-300 delete-btn shadow-lg">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full bg-white p-8 rounded-xl shadow-md text-center">
                    <p class="text-gray-500 font-medium text-lg">No hay noticias para mostrar.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para crear/editar -->
    <?php if (isset($_SESSION['user-id'])): ?>
        <div id="noticiaModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-opacity-60 hidden opacity-0 transition-opacity duration-300">
            <div class="modal-content bg-white rounded-3xl shadow-2xl p-8 w-full max-w-lg mx-4 md:mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modalTitle" class="text-2xl font-bold text-gray-800">Crear Noticia</h3>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 text-3xl font-light leading-none">&times;</button>
                </div>

                <form id="noticiaForm" action="index.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="id" id="noticiaId">
                    <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('guardar') ?>" id="actionInput">
                    <input type="hidden" name="menu-item" value="Noticias">
                    <input type="hidden" name="imagen_actual" id="imagenActual">

                    <div>
                        <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" id="titulo" name="titulo" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300 p-2">
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300 p-2"></textarea>
                    </div>

                    <div>
                        <label for="imagen" class="block text-sm font-medium text-gray-700">Imagen</label>
                        <input type="file" id="imagen" name="imagen" class="mt-1 block w-full text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none file:bg-gray-200 file:border-0 file:rounded-xl file:py-2 file:px-4 file:text-sm file:font-semibold">
                        <p class="mt-2 text-xs text-gray-500">Deja este campo vacío si no quieres cambiar la imagen.</p>
                        <img id="imagenPreview" src="" alt="Vista previa" class="mt-4 h-28 w-auto object-cover rounded-xl shadow-md hidden">
                    </div>

                    <div class="flex justify-end space-x-4 pt-4">
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 transition-transform duration-300 transform hover:scale-105">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Modal de confirmación para eliminar -->
    <div id="deleteConfirmationModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden opacity-0 transition-opacity duration-300">
        <div class="modal-content bg-white rounded-3xl shadow-2xl p-8 w-full max-w-sm mx-4 md:mx-auto text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Confirmar Eliminación</h3>
            <p class="text-gray-600 mb-6">¿Estás seguro de que deseas eliminar esta noticia?</p>
            <div class="flex justify-center space-x-4">
                <button id="cancelDeleteBtn" class="px-6 py-2 bg-gray-300 text-gray-800 font-semibold rounded-full hover:bg-gray-400 transition-colors duration-300">Cancelar</button>
                <button id="confirmDeleteBtn" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-full hover:bg-red-700 transition-colors duration-300">Eliminar</button>
            </div>
        </div>
    </div>

    <script>
        // Aquí definimos variables globales de JavaScript que el archivo externo `noticias.js` podrá leer.
        // Esto es la forma correcta de pasar valores dinámicos del servidor (PHP) al cliente (JavaScript).
        window.actionHash = '<?= md5('action') ?>';
        window.editActionHash = '<?= md5('editar') ?>';
    </script>
    
    <!-- Enlace al archivo JavaScript externo -->
    <script src="Vista/js/noticias.js"></script>