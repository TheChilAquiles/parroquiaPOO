<!-- Vista/grupoFormulario.php - ACTUALIZADA PARA MVC -->

<?php
// ==========================================
// DETERMINACIÓN DEL MODO DE OPERACIÓN
// ==========================================

// Verifica si existe la variable $grupo y si tiene contenido para determinar si estamos editando
$esEdicion = isset($grupo) && $grupo;

// Define el título dinámico según el modo: "Editar" si existe grupo, "Crear" si es nuevo
$titulo = $esEdicion ? 'Editar Grupo' : 'Crear Nuevo Grupo';

// Define el texto del botón de envío según el contexto
$textoBoton = $esEdicion ? 'Actualizar Grupo' : 'Crear Grupo';

// Si estamos editando, obtiene el nombre del grupo y lo sanitiza con htmlspecialchars para evitar XSS
// Si estamos creando, deja el campo vacío
$valorNombre = $esEdicion ? htmlspecialchars($grupo['nombre']) : '';

// ==========================================
// SISTEMA DE NOTIFICACIONES
// ==========================================

// Verifica si existe un mensaje en la sesión (generado por el controlador después de una acción)
if (isset($_SESSION['mensaje'])) {
    // Determina las clases CSS de Tailwind según el tipo de mensaje
    // Usa operador ternario anidado para evaluar: success -> verde, error -> rojo, otros -> azul
    $tipo_clase = $_SESSION['tipo_mensaje'] == 'success' 
        ? 'bg-green-100 border-green-400 text-green-700'  // Clases para mensaje de éxito
        : ($_SESSION['tipo_mensaje'] == 'error' 
            ? 'bg-red-100 border-red-400 text-red-700'    // Clases para mensaje de error
            : 'bg-blue-100 border-blue-400 text-blue-700'); // Clases para mensaje informativo
    
    // Muestra el mensaje con las clases correspondientes y lo sanitiza con htmlspecialchars
    echo '<div class="' . $tipo_clase . ' border px-4 py-3 rounded mb-4">' 
         . htmlspecialchars($_SESSION['mensaje']) . '</div>';
    
    // Elimina el mensaje de la sesión para que no se muestre en la próxima carga de página
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}
?>

<!-- ========================================== -->
<!-- CONTENEDOR PRINCIPAL -->
<!-- ========================================== -->

<!-- Contenedor principal con máximo ancho de 2xl, padding horizontal y vertical -->
<main class="mx-auto max-w-2xl px-4 py-8">
    
    <!-- ========================================== -->
    <!-- BARRA DE NAVEGACIÓN -->
    <!-- ========================================== -->
    
    <!-- Contenedor flexible que distribuye elementos en los extremos -->
    <div class="flex items-center justify-between mb-8">
        
        <!-- Botón para volver al listado de grupos -->
        <!-- url() es una función helper del framework MVC que genera URLs amigables -->
        <a href="<?= url('grupos') ?>"
           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
            <!-- Icono SVG de flecha hacia la izquierda -->
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a Grupos
        </a>

        <!-- Botón "Ver Detalles" - Solo se muestra en modo edición -->
        <?php if ($esEdicion): ?>
            <!-- url() con segundo parámetro array pasa el ID como parámetro en la URL -->
            <a href="<?= url('grupos/ver', ['id' => $grupo['id']]) ?>"
               class="px-4 py-2 bg-[#D0B8A8] text-white rounded-lg font-medium hover:bg-[#ab876f] transition duration-200">
                Ver Detalles
            </a>
        <?php endif; ?>
    </div>

    <!-- ========================================== -->
    <!-- FORMULARIO PRINCIPAL -->
    <!-- ========================================== -->
    
    <!-- Tarjeta blanca con sombra y bordes redondeados que contiene el formulario -->
    <div class="bg-white rounded-lg shadow-md p-8">
        
        <!-- ========================================== -->
        <!-- ENCABEZADO DEL FORMULARIO -->
        <!-- ========================================== -->
        
        <!-- Sección centrada con el título y descripción -->
        <div class="text-center mb-8">
            <!-- Círculo decorativo con icono SVG en el centro -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#F5F0EB] rounded-full mb-4">
                <!-- Icono de grupo de personas -->
                <svg class="w-8 h-8 text-[#ab876f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            
            <!-- Título principal - muestra contenido dinámico según $titulo -->
            <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo $titulo; ?></h1>
            
            <!-- Descripción contextual - cambia según el modo de operación -->
            <p class="text-gray-600">
                <?php echo $esEdicion 
                    ? 'Modifica la información del grupo parroquial'  // Texto para edición
                    : 'Completa la información para crear un nuevo grupo parroquial'; ?> <!-- Texto para creación -->
            </p>
        </div>

        <!-- ========================================== -->
        <!-- FORMULARIO HTML -->
        <!-- ========================================== -->
        
        <!-- 
            Formulario con action dinámico:
            - Si es edición: envía a grupos/editar con el ID
            - Si es creación: envía a grupos/crear
            Usa método POST para enviar datos de forma segura
        -->
        <form action="<?= $esEdicion ? url('grupos/editar', ['id' => $grupo['id']]) : url('grupos/crear') ?>"
              method="POST"
              class="space-y-6">

            <!-- ========================================== -->
            <!-- CAMPO: NOMBRE DEL GRUPO -->
            <!-- ========================================== -->
            
            <div>
                <!-- Etiqueta del campo con asterisco para indicar que es obligatorio -->
                <label for="nombre_grupo" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Grupo *
                </label>
                
                <!-- Contenedor relativo para posicionar el icono interno -->
                <div class="relative">
                    <!-- 
                        Input de texto con:
                        - value dinámico que mantiene el nombre en edición o vacío en creación
                        - required: validación HTML5 de campo obligatorio
                        - maxlength: límite de 255 caracteres
                        - autocomplete="off": desactiva autocompletado del navegador
                    -->
                    <input type="text"
                           id="nombre_grupo"
                           name="nombre_grupo"
                           value="<?php echo $valorNombre; ?>"
                           required
                           maxlength="255"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent transition duration-200"
                           placeholder="Ej: Coro Parroquial, Grupo de Jóvenes, Catequesis..."
                           autocomplete="off">
                    
                    <!-- Icono decorativo posicionado a la derecha del input -->
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <!-- Icono SVG de grupo de personas -->
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Texto de ayuda debajo del input -->
                <p class="mt-1 text-sm text-gray-500">
                    El nombre debe ser único y descriptivo del grupo parroquial.
                </p>
            </div>

            <!-- ========================================== -->
            <!-- INFORMACIÓN ADICIONAL (SOLO EN MODO EDICIÓN) -->
            <!-- ========================================== -->
            
            <!-- Solo se muestra si estamos editando un grupo existente -->
            <?php if ($esEdicion): ?>
                <!-- Tarjeta con fondo gris claro para mostrar información del grupo -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Información del Grupo</h3>
                    
                    <!-- Grid de una columna para mostrar datos -->
                    <div class="grid grid-cols-1 gap-4 text-sm">
                        <div>
                            <!-- Muestra el total de miembros del grupo desde el array $grupo -->
                            <span class="text-gray-500">Miembros:</span>
                            <span class="font-medium text-gray-900"><?php echo $grupo['total_miembros']; ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ========================================== -->
            <!-- BOTONES DE ACCIÓN -->
            <!-- ========================================== -->
            
            <!-- Contenedor flexible para los botones, responsive (columna en móvil, fila en desktop) -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                
                <!-- BOTÓN PRINCIPAL: CREAR/ACTUALIZAR -->
                <!-- type="submit" envía el formulario cuando se hace clic -->
                <button type="submit"
                        class="flex-1 flex justify-center items-center px-6 py-3 bg-[#D0B8A8] text-white font-medium rounded-lg hover:bg-[#ab876f] focus:outline-none focus:ring-2 focus:ring-[#C4A68A] focus:ring-offset-2 transition duration-200">
                    <!-- Icono de check/confirmación -->
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <!-- Texto dinámico del botón según el contexto -->
                    <?php echo $textoBoton; ?>
                </button>

                <!-- BOTÓN CANCELAR - comportamiento diferente según el modo -->
                <?php if ($esEdicion): ?>
                    <!-- En modo edición: volver a la página de detalles del grupo -->
                    <a href="<?= url('grupos/ver', ['id' => $grupo['id']]) ?>"
                       class="flex-1 flex justify-center items-center px-6 py-3 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                        <!-- Icono de X para cancelar -->
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </a>
                <?php else: ?>
                    <!-- En modo creación: volver al listado de grupos -->
                    <a href="<?= url('grupos') ?>"
                       class="flex-1 flex justify-center items-center px-6 py-3 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                        <!-- Icono de X para cancelar -->
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelar
                    </a>
                <?php endif; ?>
            </div>

            <!-- ========================================== -->
            <!-- NOTA LEGAL -->
            <!-- ========================================== -->
            
            <!-- Sección con borde superior para separar visualmente -->
            <div class="text-center pt-4 border-t border-gray-200">
                <!-- Texto legal en tamaño pequeño -->
                <p class="text-xs text-gray-500">
                    Los campos marcados con * son obligatorios.
                    <?php if (!$esEdicion): ?>
                        <!-- Mensaje adicional solo en modo creación -->
                        Una vez creado el grupo, podrás agregar miembros y asignar roles.
                    <?php endif; ?>
                </p>
            </div>
        </form>
    </div>

    <!-- ========================================== -->
    <!-- ZONA PELIGROSA (SOLO EN MODO EDICIÓN) -->
    <!-- ========================================== -->
    
    <!-- Solo se muestra al editar un grupo existente -->
    <?php if ($esEdicion): ?>
        <!-- Tarjeta con borde rojo a la izquierda para indicar peligro -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8 border-l-4 border-red-500">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Zona Peligrosa</h3>
            
            <!-- Advertencia sobre acciones irreversibles -->
            <p class="text-gray-600 mb-4">
                Las siguientes acciones son irreversibles. Procede con precaución.
            </p>
            
            <!-- Botón que llama a la función JavaScript de confirmación -->
            <button onclick="confirmarEliminacion()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200">
                Eliminar Grupo Permanentemente
            </button>
        </div>

        <script>
            /**
             * Función JavaScript que confirma la eliminación del grupo
             * Muestra un diálogo de confirmación nativo del navegador
             */
            function confirmarEliminacion() {
                // confirm() devuelve true si el usuario acepta, false si cancela
                if (confirm('⚠️ ¿Estás seguro de que deseas eliminar este grupo?\n\nEsta acción eliminará el grupo y todos sus miembros de forma permanente y no se puede deshacer.')) {
                    // Si acepta, redirige a la URL de eliminación con el ID del grupo
                    window.location.href = '<?= url('grupos/eliminar', ['id' => $grupo['id']]) ?>';
                }
            }
        </script>
    <?php endif; ?>
</main>

<!-- ========================================== -->
<!-- SCRIPTS DE VALIDACIÓN -->
<!-- ========================================== -->

<script>
    // DOMContentLoaded se ejecuta cuando el DOM está completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Obtiene la referencia al input del nombre del grupo
        const nombreInput = document.getElementById('nombre_grupo');

        // ========================================
        // VALIDACIÓN EN TIEMPO REAL
        // ========================================
        
        // Escucha el evento 'input' que se dispara cada vez que cambia el valor
        nombreInput.addEventListener('input', function() {
            // Obtiene el valor del input sin espacios al inicio/final
            const valor = this.value.trim();
            
            // Valida que tenga entre 2 y 255 caracteres
            const esValido = valor.length >= 2 && valor.length <= 255;

            // Si no es válido Y tiene contenido, muestra el borde rojo
            if (!esValido && valor.length > 0) {
                this.classList.add('border-red-500');      // Agrega clase de error
                this.classList.remove('border-gray-300'); // Quita clase normal
            } else {
                // Si es válido, restaura el borde normal
                this.classList.remove('border-red-500');  // Quita clase de error
                this.classList.add('border-gray-300');    // Agrega clase normal
            }
        });

        // ========================================
        // AUTO-FOCUS
        // ========================================
        
        // Coloca el cursor automáticamente en el campo de nombre al cargar la página
        nombreInput.focus();
    });
</script>