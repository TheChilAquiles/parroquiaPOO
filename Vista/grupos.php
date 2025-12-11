<!-- Vista/grupos.php - ACTUALIZADA PARA MVC -->

<!-- ========================================== -->
<!-- SISTEMA DE NOTIFICACIONES -->
<!-- ========================================== -->

<?php
// Verifica si existe un mensaje almacenado en la sesión para mostrar al usuario
if (isset($_SESSION['mensaje'])) {
    // Determina las clases CSS de Tailwind según el tipo de mensaje almacenado
    // Operador ternario anidado: evalúa success -> verde, error -> rojo, default -> azul
    $tipo_clase = $_SESSION['tipo_mensaje'] == 'success'
        ? 'bg-green-100 border-green-400 text-green-700'  // Clases para mensaje exitoso
        : ($_SESSION['tipo_mensaje'] == 'error'
            ? 'bg-red-100 border-red-400 text-red-700'    // Clases para mensaje de error
            : 'bg-blue-100 border-blue-400 text-blue-700'); // Clases para mensaje informativo

    // Muestra el mensaje en un div con las clases correspondientes
    // htmlspecialchars() previene ataques XSS al sanitizar el contenido del mensaje
    echo '<div class="' . $tipo_clase . ' border px-4 py-3 rounded mb-4">'
        . htmlspecialchars($_SESSION['mensaje']) . '</div>';

    // Elimina el mensaje de la sesión para evitar que se muestre nuevamente
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}
?>

<!-- ========================================== -->
<!-- SECCIÓN PRINCIPAL -->
<!-- ========================================== -->

<!-- Contenedor principal con ancho máximo de 7xl, centrado y con padding -->
<main class="mx-auto max-w-7xl px-4 py-8">

    <!-- ========================================== -->
    <!-- ENCABEZADO DE LA PÁGINA -->
    <!-- ========================================== -->
    
    <!-- Contenedor flex que se adapta: columna en móvil, fila en desktop -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
        <!-- Sección de título y descripción -->
        <div>
            <!-- Título principal responsive: texto más pequeño en móvil, más grande en desktop -->
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Grupos Parroquiales</h1>
            <!-- Subtítulo descriptivo -->
            <p class="text-gray-600 mt-2">Gestiona los grupos y comunidades de la parroquia</p>
        </div>

        <!-- Contenedor de botones de acción -->
        <div class="flex gap-2">
            <!-- BOTÓN: CREAR GRUPO -->
            <!-- ID usado para abrir el modal mediante JavaScript -->
            <button id="btnCrearGrupo"
                class="px-6 py-3 bg-[#D0B8A8] text-white rounded-lg shadow-md hover:bg-[#ab876f] transition duration-200 font-medium">
                <!-- Icono SVG de símbolo "+" -->
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Crear Grupo
            </button>

            <!-- BOTÓN: AÑADIR ROL -->
            <!-- ID usado para abrir el modal de roles mediante JavaScript -->
            <button id="btnCrearRol"
                class="px-6 py-3 bg-[#8B6F47] text-white rounded-lg shadow-md hover:bg-[#6B5437] transition duration-200 font-medium">
                <!-- Icono SVG de etiqueta/tag -->
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Añadir Rol
            </button>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- GRID DE GRUPOS -->
    <!-- ========================================== -->
    
    <!-- Grid responsive: 1 columna en móvil, 2 en tablet, 3 en desktop -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Verifica si el array $grupos tiene contenido -->
        <?php if (!empty($grupos)): ?>
            
            <!-- Itera sobre cada grupo del array $grupos -->
            <?php foreach ($grupos as $grupo): ?>
                
                <!-- TARJETA DE GRUPO -->
                <!-- Tarjeta con sombra que aumenta al hacer hover -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Contenedor con padding interno -->
                    <div class="p-6">
                        
                        <!-- ========================================== -->
                        <!-- ENCABEZADO DE LA TARJETA -->
                        <!-- ========================================== -->
                        
                        <!-- Flex container que distribuye contenido entre inicio y final -->
                        <div class="flex items-start justify-between mb-4">
                            <!-- Círculo decorativo con icono de grupo -->
                            <div class="flex-shrink-0 h-12 w-12 bg-[#F5F0EB] rounded-lg flex items-center justify-center">
                                <!-- Icono SVG de múltiples personas -->
                                <svg class="h-6 w-6 text-[#ab876f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>

                            <!-- Badge que muestra el total de miembros -->
                            <div class="ml-4 flex-1">
                                <!-- Insignia con estilo de píldora que muestra cantidad de miembros -->
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <!-- Muestra el número de miembros del grupo -->
                                    <?php echo $grupo['total_miembros']; ?>
                                    <!-- Agrega "s" plural si hay más de 1 miembro, singular si es 1 -->
                                    miembro<?php echo $grupo['total_miembros'] != 1 ? 's' : ''; ?>
                                </span>
                            </div>
                        </div>

                        <!-- NOMBRE DEL GRUPO -->
                        <!-- line-clamp-2: limita el texto a 2 líneas y agrega "..." si es muy largo -->
                        <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            <!-- htmlspecialchars() sanitiza el nombre del grupo para prevenir XSS -->
                            <?php echo htmlspecialchars($grupo['nombre']); ?>
                        </h2>

                        <!-- ========================================== -->
                        <!-- CONTROLES DE ACCIÓN -->
                        <!-- ========================================== -->
                        
                        <!-- Contenedor flex para los botones de acción -->
                        <div class="flex gap-2">
                            
                            <!-- BOTÓN: VER DETALLES -->
                            <!-- url() genera la URL limpia usando el patrón MVC -->
                            <!-- flex-1 hace que el botón ocupe todo el espacio disponible -->
                            <a href="<?= url('grupos/ver', ['id' => $grupo['id']]) ?>"
                                class="flex-1 px-4 py-2 bg-[#D0B8A8] text-white rounded-lg shadow-sm hover:bg-[#ab876f] transition duration-200 font-medium text-center">
                                Ver Detalles
                            </a>

                            <!-- Contenedor para botones pequeños de editar y eliminar -->
                            <div class="flex gap-1">
                                
                                <!-- BOTÓN: EDITAR GRUPO -->
                                <!-- url() genera la ruta de edición con el ID del grupo -->
                                <a href="<?= url('grupos/editar', ['id' => $grupo['id']]) ?>"
                                    class="px-3 py-2 bg-[#E8DFD5] text-[#ab876f] rounded-lg hover:bg-[#DFD3C3] transition duration-200"
                                    title="Editar grupo">
                                    <!-- Icono SVG de lápiz/editar -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- BOTÓN: ELIMINAR GRUPO -->
                                <!-- onclick llama a función JavaScript con ID y nombre del grupo -->
                                <!-- ENT_QUOTES en htmlspecialchars escapa tanto comillas simples como dobles -->
                                <button
                                    onclick="eliminarGrupo(<?php echo $grupo['id']; ?>, '<?php echo htmlspecialchars($grupo['nombre'], ENT_QUOTES); ?>')"
                                    class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition duration-200"
                                    title="Eliminar grupo">
                                    <!-- Icono SVG de papelera/eliminar -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            
            <!-- ========================================== -->
            <!-- ESTADO VACÍO (cuando no hay grupos) -->
            <!-- ========================================== -->
            
            <!-- col-span-full hace que ocupe todas las columnas del grid -->
            <div class="col-span-full">
                <!-- Contenedor centrado con padding vertical -->
                <div class="text-center py-12">
                    <!-- Icono SVG grande de grupo de personas en color gris claro -->
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <!-- Mensaje principal del estado vacío -->
                    <p class="text-gray-500 text-lg mb-2">No hay grupos parroquiales registrados</p>
                    <!-- Mensaje secundario de ayuda -->
                    <p class="text-gray-400 text-sm">Crea el primer grupo para comenzar</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- ========================================== -->
<!-- MODAL DE CREACIÓN DE GRUPO -->
<!-- ========================================== -->

<!-- 
    Modal con posición fija que cubre toda la pantalla
    z-50: índice z alto para aparecer sobre otros elementos
    hidden: oculto por defecto, se muestra con JavaScript
-->
<div id="modalCrearGrupo" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50">
    <!-- Contenedor del modal centrado en la pantalla -->
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Título del modal -->
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center">Crear Nuevo Grupo</h3>

            <!-- FORMULARIO DE CREACIÓN -->
            <!-- url() genera la ruta MVC para crear el grupo -->
            <!-- method="POST" envía los datos de forma segura -->
            <form action="<?= url('grupos/crear') ?>" method="POST">
                
                <!-- Campo de nombre del grupo -->
                <div class="mb-4">
                    <!-- Etiqueta del campo con asterisco indicando campo obligatorio -->
                    <label for="nombre_grupo" class="block text-gray-700 font-medium mb-2">
                        Nombre del Grupo *
                    </label>
                    
                    <!-- 
                        Input de texto con:
                        - required: validación HTML5 obligatoria
                        - maxlength: límite de 255 caracteres
                        - focus:ring-2: anillo visible al enfocar el campo
                    -->
                    <input type="text" id="nombre_grupo" name="nombre_grupo" required maxlength="255"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent"
                        placeholder="Ej: Coro Parroquial, Grupo de Jóvenes...">
                </div>

                <!-- Botones de acción del modal -->
                <div class="flex gap-3">
                    <!-- BOTÓN: CREAR GRUPO -->
                    <!-- type="submit" envía el formulario -->
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                        Crear Grupo
                    </button>
                    
                    <!-- BOTÓN: CANCELAR -->
                    <!-- type="button" evita que envíe el formulario -->
                    <!-- ID usado por JavaScript para cerrar el modal -->
                    <button type="button" id="btnCerrarModal"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200 font-medium">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE CREACIÓN DE ROL -->
<!-- ========================================== -->

<!-- 
    Modal similar al anterior pero para crear roles
    bg-black bg-opacity-50: fondo oscuro semi-transparente
-->
<div id="modalCrearRol" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <!-- Contenedor del modal centrado -->
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Título del modal -->
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 text-center">Añadir Rol de Grupo</h3>

            <!-- FORMULARIO DE CREACIÓN DE ROL -->
            <!-- url() genera la ruta MVC para crear el rol -->
            <form action="<?= url('grupos/crear-rol') ?>" method="POST">
                
                <!-- Campo de nombre del rol -->
                <div class="mb-4">
                    <!-- Etiqueta del campo -->
                    <label for="nombre_rol" class="block text-gray-700 font-medium mb-2">
                        Nombre del Rol *
                    </label>
                    
                    <!-- Input con límite de 100 caracteres (menor que nombre de grupo) -->
                    <input type="text" id="nombre_rol" name="nombre_rol" required maxlength="100"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent"
                        placeholder="Ej: Líder, Coordinador, Secretario...">
                    
                    <!-- Texto de ayuda explicativo -->
                    <p class="mt-1 text-sm text-gray-500">
                        Este rol estará disponible para asignar a miembros de grupos.
                    </p>
                </div>

                <!-- Botones de acción del modal -->
                <div class="flex gap-3">
                    <!-- BOTÓN: CREAR ROL -->
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-[#8B6F47] text-white rounded-lg hover:bg-[#6B5437] transition duration-200 font-medium">
                        Crear Rol
                    </button>
                    
                    <!-- BOTÓN: CANCELAR -->
                    <!-- ID usado por JavaScript para cerrar el modal -->
                    <button type="button" id="btnCerrarModalRol"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200 font-medium">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SCRIPTS DE JAVASCRIPT -->
<!-- ========================================== -->

<script>
    // Espera a que el DOM esté completamente cargado antes de ejecutar el código
    document.addEventListener('DOMContentLoaded', function () {
        
        // ==========================================
        // REFERENCIAS A ELEMENTOS DEL DOM
        // ==========================================
        
        // MODAL DE GRUPO: Obtiene referencias a elementos del modal de crear grupo
        const modalGrupo = document.getElementById('modalCrearGrupo');        // El modal completo
        const btnCrearGrupo = document.getElementById('btnCrearGrupo');       // Botón que abre el modal
        const btnCerrarModal = document.getElementById('btnCerrarModal');     // Botón para cerrar el modal
        const inputNombreGrupo = document.getElementById('nombre_grupo');     // Campo de texto del nombre

        // MODAL DE ROL: Obtiene referencias a elementos del modal de crear rol
        const modalRol = document.getElementById('modalCrearRol');            // El modal completo
        const btnCrearRol = document.getElementById('btnCrearRol');           // Botón que abre el modal
        const btnCerrarModalRol = document.getElementById('btnCerrarModalRol'); // Botón para cerrar el modal
        const inputNombreRol = document.getElementById('nombre_rol');         // Campo de texto del nombre

        // ==========================================
        // EVENT LISTENERS - ABRIR MODALES
        // ==========================================
        
        // Escucha el clic en el botón "Crear Grupo"
        btnCrearGrupo.addEventListener('click', function () {
            modalGrupo.classList.remove('hidden');  // Remueve la clase 'hidden' para mostrar el modal
            inputNombreGrupo.focus();               // Coloca el cursor automáticamente en el input
        });

        // Escucha el clic en el botón "Añadir Rol"
        btnCrearRol.addEventListener('click', function () {
            modalRol.classList.remove('hidden');    // Remueve la clase 'hidden' para mostrar el modal
            inputNombreRol.focus();                 // Coloca el cursor automáticamente en el input
        });

        // ==========================================
        // FUNCIONES DE CIERRE DE MODALES
        // ==========================================
        
        /**
         * Función que cierra el modal de grupo y limpia el input
         */
        function cerrarModalGrupo() {
            modalGrupo.classList.add('hidden');     // Agrega la clase 'hidden' para ocultar el modal
            inputNombreGrupo.value = '';            // Limpia el contenido del input
        }

        /**
         * Función que cierra el modal de rol y limpia el input
         */
        function cerrarModalRol() {
            modalRol.classList.add('hidden');       // Agrega la clase 'hidden' para ocultar el modal
            inputNombreRol.value = '';              // Limpia el contenido del input
        }

        // ==========================================
        // EVENT LISTENERS - CERRAR MODALES
        // ==========================================
        
        // Escucha el clic en el botón "Cancelar" del modal de grupo
        btnCerrarModal.addEventListener('click', cerrarModalGrupo);
        
        // Escucha el clic en el botón "Cancelar" del modal de rol
        btnCerrarModalRol.addEventListener('click', cerrarModalRol);

        // ==========================================
        // CERRAR AL HACER CLIC FUERA DEL MODAL
        // ==========================================
        
        /**
         * Detecta clics en cualquier parte de la ventana
         * Si el clic es en el fondo del modal (no en el contenido), cierra el modal
         */
        window.addEventListener('click', function (event) {
            // Si se hace clic exactamente en el modal de grupo (el fondo oscuro)
            if (event.target === modalGrupo) {
                cerrarModalGrupo();
            }
            // Si se hace clic exactamente en el modal de rol (el fondo oscuro)
            if (event.target === modalRol) {
                cerrarModalRol();
            }
        });

        // ==========================================
        // CERRAR CON TECLA ESC
        // ==========================================
        
        /**
         * Detecta cuando se presiona la tecla Escape (ESC)
         * Cierra cualquier modal que esté abierto
         */
        document.addEventListener('keydown', function (event) {
            // Si la tecla presionada es 'Escape'
            if (event.key === 'Escape') {
                // Si el modal de grupo está visible (no tiene la clase 'hidden')
                if (!modalGrupo.classList.contains('hidden')) {
                    cerrarModalGrupo();
                }
                // Si el modal de rol está visible (no tiene la clase 'hidden')
                if (!modalRol.classList.contains('hidden')) {
                    cerrarModalRol();
                }
            }
        });
    });

    // ==========================================
    // FUNCIÓN GLOBAL: ELIMINAR GRUPO
    // ==========================================
    
    /**
     * Función que maneja la eliminación de un grupo
     * Muestra un diálogo de confirmación antes de proceder
     * 
     * @param {number} grupoId - El ID del grupo a eliminar
     * @param {string} nombreGrupo - El nombre del grupo para mostrarlo en la confirmación
     */
    function eliminarGrupo(grupoId, nombreGrupo) {
        // Muestra un cuadro de diálogo de confirmación nativo del navegador
        // confirm() devuelve true si el usuario acepta, false si cancela
        if (confirm(`¿Estás seguro de que deseas eliminar el grupo "${nombreGrupo}"?\n\nEsta acción eliminará el grupo y todos sus miembros.`)) {
            // Si el usuario confirma, redirige a la URL de eliminación
            // Construye la URL concatenando la ruta base con el ID del grupo
            window.location.href = "grupos/eliminar/" + grupoId;
        }
    }

</script>