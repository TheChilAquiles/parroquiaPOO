<!-- /**
 * @file grupos.php
 * @version 2.3
 * @author Samuel Bedoya
 * @brief Vista principal para listado y gestión de grupos parroquiales
 * 
 * Muestra un grid responsive de todos los grupos activos con información básica,
 * controles CRUD y un modal para creación rápida de grupos.
 * 
 * @dependencies
 * - TailwindCSS para estilos
 * - JavaScript vanilla para interactividad de modales
 * - Variables PHP: $grupos (array de grupos desde controlador)
 * - Sesión PHP para mensajes de feedback
 * 
 * @features
 * - Grid responsive de cards de grupos
 * - Modal de creación inline
 * - Confirmación JavaScript para eliminación
 * - Sistema de notificaciones con sesión
 * - Accesibilidad con navegación por teclado
 */ -->

<!-- ============================================================================
     SISTEMA DE NOTIFICACIONES
     Muestra mensajes de feedback almacenados en sesión tras operaciones CRUD
============================================================================= -->
<?php
// Renderiza notificaciones según tipo (success, error, info)
if (isset($_SESSION['mensaje'])) {
    // Determina clases CSS según el tipo de mensaje
    $tipo_clase = $_SESSION['tipo_mensaje'] == 'success' 
        ? 'bg-green-100 border-green-400 text-green-700' 
        : ($_SESSION['tipo_mensaje'] == 'error' 
            ? 'bg-red-100 border-red-400 text-red-700' 
            : 'bg-blue-100 border-blue-400 text-blue-700');
    
    echo '<div class="' . $tipo_clase . ' border px-4 py-3 rounded mb-4">' 
         . htmlspecialchars($_SESSION['mensaje']) . '</div>';
    
    // Limpia las variables de sesión después de mostrar
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}
?>

<!-- ============================================================================
     SECCIÓN PRINCIPAL
     Container principal con encabezado y grid de grupos
============================================================================= -->
<main class="mx-auto max-w-7xl px-4 py-8">
    
    <!-- ========================================================================
         ENCABEZADO CON TÍTULO Y BOTÓN DE ACCIÓN
         Layout flexible que se adapta a móvil/escritorio
    ========================================================================= -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Grupos Parroquiales</h1>
            <p class="text-gray-600 mt-2">Gestiona los grupos y comunidades de la parroquia</p>
        </div>
        
        <!-- Botón para abrir modal de creación -->
        <button id="btnCrearGrupo" 
                class="px-6 py-3 bg-purple-600 text-white rounded-lg shadow-md hover:bg-purple-700 transition duration-200 font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Crear Grupo
        </button>
    </div>

    <!-- ========================================================================
         GRID DE GRUPOS
         Layout responsive: 1 columna móvil, 2 en tablet, 3 en desktop
    ========================================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($grupos)) : ?>
            <!-- ================================================================
                 ITERACIÓN DE GRUPOS
                 Cada grupo se renderiza como una card individual
            ================================================================= -->
            <?php foreach ($grupos as $grupo) : ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <!-- ====================================================
                             ENCABEZADO DE CARD
                             Icono, nombre y badge de cantidad de miembros
                        ===================================================== -->
                        <div class="flex items-start justify-between mb-4">
                            <!-- Icono decorativo de grupo -->
                            <div class="flex-shrink-0 h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            
                            <!-- Badge con contador de miembros -->
                            <div class="ml-4 flex-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?php echo $grupo['total_miembros']; ?> 
                                    miembro<?php echo $grupo['total_miembros'] != 1 ? 's' : ''; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Nombre del grupo con truncado en 2 líneas -->
                        <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            <?php echo htmlspecialchars($grupo['nombre']); ?>
                        </h2>

                        <!-- ====================================================
                             CONTROLES DE ACCIÓN
                             Botones para ver, editar y eliminar
                        ===================================================== -->
                        <div class="flex gap-2">
                            <!-- Botón principal: Ver detalles -->
                            <form method="POST" action="index.php" class="flex-1">
                                <input type="hidden" name="menu-item" value="Grupos">
                                <input type="hidden" name="action" value="ver">
                                <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo['id']); ?>">
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg shadow-sm hover:bg-purple-700 transition duration-200 font-medium">
                                    Ver Detalles
                                </button>
                            </form>

                            <!-- Botones secundarios: Editar y Eliminar -->
                            <div class="flex gap-1">
                                <!-- Botón editar -->
                                <form method="POST" action="index.php" style="display: inline;">
                                    <input type="hidden" name="menu-item" value="Grupos">
                                    <input type="hidden" name="action" value="editar">
                                    <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo['id']); ?>">
                                    <button type="submit" 
                                            class="px-3 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition duration-200" 
                                            title="Editar grupo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </form>

                                <!-- Botón eliminar con confirmación JavaScript -->
                                <button onclick="eliminarGrupo(<?php echo $grupo['id']; ?>, '<?php echo htmlspecialchars($grupo['nombre']); ?>')"
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
            <!-- ================================================================
                 ESTADO VACÍO
                 Muestra mensaje cuando no hay grupos registrados
            ================================================================= -->
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

<!-- ============================================================================
     MODAL DE CREACIÓN DE GRUPO
     Overlay fullscreen con formulario centrado
============================================================================= -->
<div id="modalCrearGrupo" class="fixed inset-0 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center">Crear Nuevo Grupo</h3>
            
            <!-- ================================================================
                 FORMULARIO DE CREACIÓN
                 Envía POST al controlador con action='crear'
            ================================================================= -->
            <form action="index.php" method="POST">
                <input type="hidden" name="menu-item" value="Grupos">
                <input type="hidden" name="action" value="crear">

                <!-- Campo de nombre con validaciones HTML5 -->
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

                <!-- Botones de acción -->
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

<!-- ============================================================================
     SCRIPTS DE INTERACTIVIDAD
     Manejo de modal y confirmación de eliminación
============================================================================= -->
<script>
    // ========================================================================
    // GESTIÓN DEL MODAL
    // Control de apertura/cierre con múltiples métodos de interacción
    // ========================================================================
    document.addEventListener('DOMContentLoaded', function() {
        // Referencias a elementos del DOM
        const modal = document.getElementById('modalCrearGrupo');
        const btnCrearGrupo = document.getElementById('btnCrearGrupo');
        const btnCerrarModal = document.getElementById('btnCerrarModal');
        const inputNombre = document.getElementById('nombre_grupo');

        /**
         * Abre el modal y establece focus en el input
         */
        btnCrearGrupo.addEventListener('click', function() {
            modal.classList.remove('hidden');
            inputNombre.focus();
        });

        /**
         * Cierra el modal y limpia el formulario
         */
        function cerrarModal() {
            modal.classList.add('hidden');
            inputNombre.value = '';
        }

        // Event listeners para cerrar el modal
        btnCerrarModal.addEventListener('click', cerrarModal);

        // Cierra el modal si se hace clic en el overlay (fuera del contenido)
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                cerrarModal();
            }
        });

        // Cerrar modal con tecla ESC (accesibilidad)
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                cerrarModal();
            }
        });
    });

    // ========================================================================
    // FUNCIÓN DE ELIMINACIÓN CON CONFIRMACIÓN
    // Muestra diálogo nativo y envía formulario POST si se confirma
    // ========================================================================
    /**
     * Solicita confirmación y elimina un grupo.
     * 
     * @param {number} grupoId - ID del grupo a eliminar
     * @param {string} nombreGrupo - Nombre del grupo (para mensaje de confirmación)
     * 
     * @security Usa POST para operación destructiva (no GET)
     * @pattern Confirmación de dos pasos para prevenir eliminaciones accidentales
     */
    function eliminarGrupo(grupoId, nombreGrupo) {
        // Confirmación con diálogo nativo del navegador
        if (confirm(`¿Estás seguro de que deseas eliminar el grupo "${nombreGrupo}"?\n\nEsta acción eliminará el grupo y todos sus miembros.`)) {
            
            // Crear formulario dinámicamente para envío POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php';

            // Agregar campos ocultos con datos necesarios
            const inputMenuItem = document.createElement('input');
            inputMenuItem.type = 'hidden';
            inputMenuItem.name = 'menu-item';
            inputMenuItem.value = 'Grupos';

            const inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action';
            inputAction.value = 'eliminar';

            const inputGrupoId = document.createElement('input');
            inputGrupoId.type = 'hidden';
            inputGrupoId.name = 'grupo_id';
            inputGrupoId.value = grupoId;

            const inputConfirmar = document.createElement('input');
            inputConfirmar.type = 'hidden';
            inputConfirmar.name = 'confirmar_eliminacion';
            inputConfirmar.value = '1';

            // Ensamblar y enviar formulario
            form.appendChild(inputMenuItem);
            form.appendChild(inputAction);
            form.appendChild(inputGrupoId);
            form.appendChild(inputConfirmar);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>