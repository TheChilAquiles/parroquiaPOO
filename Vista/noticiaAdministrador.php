<div class="max-w-4xl mx-auto p-4 bg-white shadow-md rounded-lg mb-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-center text-gray-800">Lista de Noticias</h2>
        <button id="openModalBtn" class="px-4 py-2 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 transition duration-300">
            Crear Nueva Noticia
        </button>
    </div>

    <?php if (isset($mensaje['texto'])): ?>
        <div class="mb-4 p-4 rounded-lg <?= $mensaje['tipo'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= htmlspecialchars($mensaje['texto']) ?>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($noticias)): ?>
                    <?php foreach ($noticias as $noticiaItem): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($noticiaItem['titulo']) ?></td>
                            <td class="px-6 py-4 whitespace-now-text-sm text-gray-500"><?= htmlspecialchars($noticiaItem['descripcion']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <img src="<?= htmlspecialchars($noticiaItem['imagen']) ?>" alt="Imagen de la noticia" class="h-10 w-auto object-cover rounded-md">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($noticiaItem['estado_registro']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button class="text-indigo-600 hover:text-indigo-900 transition duration-300 open-edit-modal"
                                            data-id="<?= $noticiaItem['id'] ?>"
                                            data-titulo="<?= htmlspecialchars($noticiaItem['titulo']) ?>"
                                            data-descripcion="<?= htmlspecialchars($noticiaItem['descripcion']) ?>"
                                            data-imagen="<?= htmlspecialchars($noticiaItem['imagen']) ?>">
                                        Editar
                                    </button>

                                    <form action="index.php?menu-item=Noticias" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta noticia?');">
                                        <input type="hidden" name="id" value="<?= $noticiaItem['id'] ?>">
                                        <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('eliminar') ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition duration-300">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay noticias para mostrar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="noticiaModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Crear Noticia</h3>
            <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        
        <form id="noticiaForm" action="index.php?menu-item=Noticias" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="id" id="noticiaId">
            <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('guardar') ?>">
            <input type="hidden" name="imagen_actual" id="imagenActual">
            
            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" id="titulo" name="titulo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea id="descripcion" name="descripcion" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div>
                <label for="imagen" class="block text-sm font-medium text-gray-700">Imagen</label>
                <input type="file" id="imagen" name="imagen" class="mt-1 block w-full text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                <p class="mt-2 text-sm text-gray-500">Deja este campo vacío si no quieres cambiar la imagen.</p>
                <img id="imagenPreview" src="" alt="Vista previa" class="mt-2 h-20 w-auto object-cover rounded-md hidden">
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition duration-300">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('noticiaModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const noticiaForm = document.getElementById('noticiaForm');
    const modalTitle = document.getElementById('modalTitle');
    const noticiaId = document.getElementById('noticiaId');
    const tituloInput = document.getElementById('titulo');
    const descripcionInput = document.getElementById('descripcion');
    const imagenInput = document.getElementById('imagen');
    const imagenPreview = document.getElementById('imagenPreview');
    const imagenActualInput = document.getElementById('imagenActual');
    const editButtons = document.querySelectorAll('.open-edit-modal');
    const actionInput = document.querySelector('input[name="<?= md5('action') ?>"]');

    // Abre el modal para crear una nueva noticia
    openModalBtn.addEventListener('click', () => {
        modalTitle.textContent = 'Crear Noticia';
        noticiaId.value = '';
        noticiaForm.reset();
        imagenPreview.classList.add('hidden');
        imagenActualInput.value = '';
        actionInput.value = '<?= md5('guardar') ?>';
        modal.classList.remove('hidden', 'opacity-0');
        modal.classList.add('flex', 'opacity-100');
    });

    // Cierra el modal
    closeModalBtn.addEventListener('click', () => {
        modal.classList.remove('flex', 'opacity-100');
        modal.classList.add('hidden', 'opacity-0');
    });

    // Abre el modal para editar una noticia
    editButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const id = e.target.dataset.id;
            const titulo = e.target.dataset.titulo;
            const descripcion = e.target.dataset.descripcion;
            const imagen = e.target.dataset.imagen;

            modalTitle.textContent = 'Editar Noticia';
            noticiaId.value = id;
            tituloInput.value = titulo;
            descripcionInput.value = descripcion;
            imagenActualInput.value = imagen; // Guarda la URL de la imagen actual
            actionInput.value = '<?= md5('guardar') ?>';
            
            // Muestra la imagen actual si existe
            if (imagen && imagen.length > 0) {
                imagenPreview.src = imagen;
                imagenPreview.classList.remove('hidden');
            } else {
                imagenPreview.classList.add('hidden');
            }

            modal.classList.remove('hidden', 'opacity-0');
            modal.classList.add('flex', 'opacity-100');
        });
    });

    // Cierra el modal al hacer clic fuera
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('flex', 'opacity-100');
            modal.classList.add('hidden', 'opacity-0');
        }
    });

    // Vista previa de la nueva imagen
    imagenInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagenPreview.src = e.target.result;
                imagenPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>