<main class="mx-auto max-w-7xl px-4 py-8">
    <div class="flex justify-between items-center mb-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Grupos Parroquiales</h1>
        <button id="btnCrearGrupo" class="px-6 py-3 bg-purple-600 text-white rounded-lg shadow-md hover:bg-purple-700 transition duration-200">
            Crear Grupo
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($grupos)) : ?>
            <?php foreach ($grupos as $grupo) : ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($grupo['nombre']); ?></h2>
                        <p class="text-gray-600 mb-4">
                            <?php echo "Descripción del grupo..." ?>
                        </p>
                        <a href="?menu-item=Grupos&action=ver&id=<?php echo htmlspecialchars($grupo['id']); ?>" class="inline-block px-4 py-2 bg-purple-600 text-white rounded-lg shadow-sm hover:bg-purple-700 transition duration-200">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-gray-500 text-center col-span-full">No hay grupos parroquiales registrados.</p>
        <?php endif; ?>
    </div>
</main>

<div id="modalCrearGrupo" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Crear Nuevo Grupo</h3>
            <div class="mt-2 px-7 py-3">
                <form action="?menu-item=Grupos&action=crear" method="POST">
                    <div class="mb-4">
                        <label for="nombre_grupo" class="block text-gray-700 font-bold mb-2">Nombre del Grupo</label>
                        <input type="text" id="nombre_grupo" name="nombre_grupo" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200">
                            Guardar Grupo
                        </button>
                        <button type="button" id="btnCerrarModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalCrearGrupo');
        const btnCrearGrupo = document.getElementById('btnCrearGrupo');
        const btnCerrarModal = document.getElementById('btnCerrarModal');

        btnCrearGrupo.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });

        btnCerrarModal.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Cierra el modal si se hace clic fuera de él
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>