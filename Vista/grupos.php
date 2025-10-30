<!-- Vista/grupos.php - ACTUALIZADA PARA MVC -->

<!-- SISTEMA DE NOTIFICACIONES -->
<?php
if (isset($_SESSION['mensaje'])) {
    $tipo_clase = $_SESSION['tipo_mensaje'] == 'success' 
        ? 'bg-green-100 border-green-400 text-green-700' 
        : ($_SESSION['tipo_mensaje'] == 'error' 
            ? 'bg-red-100 border-red-400 text-red-700' 
            : 'bg-blue-100 border-blue-400 text-blue-700');
    
    echo '<div class="' . $tipo_clase . ' border px-4 py-3 rounded mb-4">' 
         . htmlspecialchars($_SESSION['mensaje']) . '</div>';
    
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}
?>

<!-- SECCIÓN PRINCIPAL -->
<main class="mx-auto max-w-7xl px-4 py-8">
    
    <!-- ENCABEZADO -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Grupos Parroquiales</h1>
            <p class="text-gray-600 mt-2">Gestiona los grupos y comunidades de la parroquia</p>
        </div>
        
        <button id="btnCrearGrupo" 
                class="px-6 py-3 bg-purple-600 text-white rounded-lg shadow-md hover:bg-purple-700 transition duration-200 font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Crear Grupo
        </button>
    </div>

    <!-- GRID DE GRUPOS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($grupos)) : ?>
            <?php foreach ($grupos as $grupo) : ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <!-- ENCABEZADO DE CARD -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-shrink-0 h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            
                            <div class="ml-4 flex-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?php echo $grupo['total_miembros']; ?> 
                                    miembro<?php echo $grupo['total_miembros'] != 1 ? 's' : ''; ?>
                                </span>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            <?php echo htmlspecialchars($grupo['nombre']); ?>
                        </h2>

                        <!-- CONTROLES DE ACCIÓN -->
                        <div class="flex gap-2">
                            <!-- ✅ NUEVO: Botón Ver con ruta MVC -->
                            <a href="?route=grupos/ver&id=<?php echo htmlspecialchars($grupo['id']); ?>"
                               class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg shadow-sm hover:bg-purple-700 transition duration-200 font-medium text-center">
                                Ver Detalles
                            </a>

                            <div class="flex gap-1">
                                <!-- ✅ NUEVO: Botón Editar con ruta MVC -->
                                <a href="?route=grupos/editar&id=<?php echo htmlspecialchars($grupo['id']); ?>"
                                   class="px-3 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition duration-200"
                                   title="Editar grupo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- ✅ NUEVO: Botón Eliminar con confirmación -->
                                <button onclick="eliminarGrupo(<?php echo $grupo['id']; ?>, '<?php echo htmlspecialchars($grupo['nombre'], ENT_QUOTES); ?>')"
                                        class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition duration-200"
                                        title="Eliminar grupo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
        <?php else : ?>
            <!-- ESTADO VACÍO -->
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-gray-500 text-lg mb-2">No hay grupos parroquiales registrados</p>
                    <p class="text-gray-400 text-sm">Crea el primer grupo para comenzar</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- MODAL DE CREACIÓN -->
<div id="modalCrearGrupo" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center">Crear Nuevo Grupo</h3>
            
            <!-- ✅ NUEVO: Formulario con ruta MVC -->
            <form action="?route=grupos/crear" method="POST">
                <div class="mb-4">
                    <label for="nombre_grupo" class="block text-gray-700 font-medium mb-2">
                        Nombre del Grupo *
                    </label>
                    <input type="text"
                           id="nombre_grupo"
                           name="nombre_grupo"
                           required
                           maxlength="255"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Ej: Coro Parroquial, Grupo de Jóvenes...">
                </div>

                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                        Crear Grupo
                    </button>
                    <button type="button" 
                            id="btnCerrarModal" 
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200 font-medium">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalCrearGrupo');
        const btnCrearGrupo = document.getElementById('btnCrearGrupo');
        const btnCerrarModal = document.getElementById('btnCerrarModal');
        const inputNombre = document.getElementById('nombre_grupo');

        btnCrearGrupo.addEventListener('click', function() {
            modal.classList.remove('hidden');
            inputNombre.focus();
        });

        function cerrarModal() {
            modal.classList.add('hidden');
            inputNombre.value = '';
        }

        btnCerrarModal.addEventListener('click', cerrarModal);

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                cerrarModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                cerrarModal();
            }
        });
    });

    /**
     * ✅ NUEVO: Eliminar con ruta MVC
     */
    function eliminarGrupo(grupoId, nombreGrupo) {
        if (confirm(`¿Estás seguro de que deseas eliminar el grupo "${nombreGrupo}"?\n\nEsta acción eliminará el grupo y todos sus miembros.`)) {
            // Redirigir a la página de confirmación
            window.location.href = `?route=grupos/eliminar&id=${grupoId}`;
        }
    }
</script>