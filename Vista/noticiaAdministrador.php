<div class="max-w-7xl mx-auto p-8 bg-gray-100 min-h-screen">
    <div class="bg-white p-6 rounded-3xl shadow-xl mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">Panel de Noticias</h2>
            <button id="openModalBtn" class="mt-4 md:mt-0 px-6 py-3 bg-indigo-600 text-white font-bold rounded-full shadow-lg hover:bg-indigo-700 transition-transform duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                Crear Nueva Noticia
            </button>
        </div>

        <?php if (isset($mensaje['texto'])): ?>
            <div class="mb-4 p-4 rounded-xl font-medium <?= $mensaje['tipo'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= htmlspecialchars($mensaje['texto']) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if (!empty($noticias)): ?>
            <?php foreach ($noticias as $noticiaItem): ?>
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <img src="<?= htmlspecialchars($noticiaItem['imagen']) ?>" alt="Imagen de la noticia" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 truncate mb-2"><?= htmlspecialchars($noticiaItem['titulo']) ?></h3>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4"><?= htmlspecialchars($noticiaItem['descripcion']) ?></p>
                        <p class="text-gray-400 text-xs mb-4">Publicado: <?= htmlspecialchars(date('d/m/Y', strtotime($noticiaItem['estado_registro']))) ?></p>
                        <div class="flex items-center justify-between space-x-2">
                            <button class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-full hover:bg-blue-700 transition duration-300 open-edit-modal"
                                    data-id="<?= $noticiaItem['id'] ?>"
                                    data-titulo="<?= htmlspecialchars($noticiaItem['titulo']) ?>"
                                    data-descripcion="<?= htmlspecialchars($noticiaItem['descripcion']) ?>"
                                    data-imagen="<?= htmlspecialchars($noticiaItem['imagen']) ?>">
                                Editar
                            </button>
                            <form action="index.php?menu-item=Noticias" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta noticia?');">
                                <input type="hidden" name="id" value="<?= $noticiaItem['id'] ?>">
                                <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('eliminar') ?>">
                                <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 transition duration-300">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full bg-white p-6 rounded-xl shadow-md text-center">
                <p class="text-gray-500 font-medium">No hay noticias para mostrar.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div id="noticiaModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-lg mx-4 md:mx-auto transform scale-95 transition-transform duration-300">
        <div class="flex justify-between items-center mb-6">
            <h3 id="modalTitle" class="text-2xl font-bold text-gray-800">Crear Noticia</h3>
            <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 text-3xl font-light leading-none">&times;</button>
        </div>
        
        <form id="noticiaForm" action="index.php?menu-item=Noticias" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" id="noticiaId">
            <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('guardar') ?>">
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

<script src="/js/noticias.js"></script>
