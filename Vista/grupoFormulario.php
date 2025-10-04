<!-- /**
 * @file grupoFormulario.php
 * @version 2.6
 * @author Samuel Bedoya
 * @brief Vista unificada para crear y editar grupos parroquiales
 * 
 * Formulario inteligente que se adapta según el contexto:
 * - Modo creación: formulario vacío para nuevo grupo
 * - Modo edición: formulario precargado con datos existentes
 * 
 * @dependencies
 * - Variable PHP: $grupo (array|null) - Define el modo de operación
 * - TailwindCSS para estilos
 * - JavaScript para validación en tiempo real
 * 
 * @features
 * - Formulario adaptativo (crear/editar)
 * - Validación HTML5 y JavaScript en tiempo real
 * - Zona de acciones peligrosas (solo en edición)
 * - Navegación con breadcrumb
 * - Auto-focus en campo principal
 */ -->

<?php
// ============================================================================
// DETERMINACIÓN DEL MODO DE OPERACIÓN
// Detecta si estamos en modo creación o edición basándose en la variable $grupo
// ============================================================================
$esEdicion = isset($grupo) && $grupo;
$titulo = $esEdicion ? 'Editar Grupo' : 'Crear Nuevo Grupo';
$textoBoton = $esEdicion ? 'Actualizar Grupo' : 'Crear Grupo';
$valorNombre = $esEdicion ? htmlspecialchars($grupo['nombre']) : '';

// ============================================================================
// SISTEMA DE NOTIFICACIONES
// Renderiza mensajes de feedback de operaciones previas
// ============================================================================
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

<!-- ============================================================================
     CONTENEDOR PRINCIPAL
     Layout centrado con ancho máximo para formularios
============================================================================= -->
<main class="mx-auto max-w-2xl px-4 py-8">
    
    <!-- ========================================================================
         BARRA DE NAVEGACIÓN
         Breadcrumb con botones de acción según contexto
    ========================================================================= -->
    <div class="flex items-center justify-between mb-8">
        <!-- Botón volver al listado -->
        <form method="POST" action="index.php" style="display: inline;">
            <input type="hidden" name="menu-item" value="Grupos">
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a Grupos
            </button>
        </form>

        <!-- Botón ver detalles (solo en modo edición) -->
        <?php if ($esEdicion): ?>
            <form method="POST" action="index.php" style="display: inline;">
                <input type="hidden" name="menu-item" value="Grupos">
                <input type="hidden" name="action" value="ver">
                <input type="hidden" name="grupo_id" value="<?php echo $grupo['id']; ?>">
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition duration-200">
                    Ver Detalles
                </button>
            </form>
        <?php endif; ?>
    </div>

    <!-- ========================================================================
         FORMULARIO PRINCIPAL
         Card con diseño centrado y campos de entrada
    ========================================================================= -->
    <div class="bg-white rounded-lg shadow-md p-8">
        
        <!-- ====================================================================
             ENCABEZADO DEL FORMULARIO
             Icono, título y descripción contextual
        ==================================================================== -->
        <div class="text-center mb-8">
            <!-- Icono decorativo -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo $titulo; ?></h1>
            <p class="text-gray-600">
                <?php echo $esEdicion 
                    ? 'Modifica la información del grupo parroquial' 
                    : 'Completa la información para crear un nuevo grupo parroquial'; ?>
            </p>
        </div>

        <!-- ====================================================================
             FORMULARIO DE DATOS
             Se adapta según modo (crear/editar) con campos ocultos apropiados
        ==================================================================== -->
        <form action="index.php" method="POST" class="space-y-6">
            <!-- Campos ocultos para routing -->
            <input type="hidden" name="menu-item" value="Grupos">
            <input type="hidden" name="action" value="<?php echo $esEdicion ? 'editar' : 'crear'; ?>">
            
            <!-- ID del grupo (solo en modo edición) -->
            <?php if ($esEdicion): ?>
                <input type="hidden" name="grupo_id" value="<?php echo $grupo['id']; ?>">
            <?php endif; ?>

            <!-- ================================================================
                 CAMPO: NOMBRE DEL GRUPO
                 Campo principal con validación HTML5 e icono decorativo
            ================================================================= -->
            <div>
                <label for="nombre_grupo" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Grupo *
                </label>
                <div class="relative">
                    <input type="text"
                           id="nombre_grupo"
                           name="nombre_grupo"
                           value="<?php echo $valorNombre; ?>"
                           required
                           maxlength="255"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                           placeholder="Ej: Coro Parroquial, Grupo de Jóvenes, Catequesis..."
                           autocomplete="off">
                    
                    <!-- Icono decorativo posicionado absolutamente -->
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    El nombre debe ser único y descriptivo del grupo parroquial.
                </p>
            </div>

            <!-- ================================================================
                 INFORMACIÓN ADICIONAL (SOLO EN MODO EDICIÓN)
                 Muestra estadísticas del grupo actual
            ================================================================= -->
            <?php if ($esEdicion): ?>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Información del Grupo</h3>
                    <div class="grid grid-cols-1 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Miembros:</span>
                            <span class="font-medium text-gray-900"><?php echo $grupo['total_miembros']; ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ================================================================
                 BOTONES DE ACCIÓN
                 Layout adaptativo con botón principal y cancelar
            ================================================================= -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <!-- Botón principal: Crear/Actualizar -->
                <button type="submit"
                        class="flex-1 flex justify-center items-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <?php echo $textoBoton; ?>
                </button>

                <!-- Botón cancelar: Redirige según contexto -->
                <?php if ($esEdicion): ?>
                    <!-- En edición: volver a detalles del grupo -->
                    <form method="POST" action="index.php" class="flex-1">
                        <input type="hidden" name="menu-item" value="Grupos">
                        <input type="hidden" name="action" value="ver">
                        <input type="hidden" name="grupo_id" value="<?php echo $grupo['id']; ?>">
                        <button type="submit" 
                                class="w-full flex justify-center items-center px-6 py-3 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </button>
                    </form>
                <?php else: ?>
                    <!-- En creación: volver al listado -->
                    <form method="POST" action="index.php" class="flex-1">
                        <input type="hidden" name="menu-item" value="Grupos">
                        <button type="submit" 
                                class="w-full flex justify-center items-center px-6 py-3 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancelar
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- ================================================================
                 NOTA LEGAL E INFORMATIVA
            ================================================================= -->
            <div class="text-center pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Los campos marcados con * son obligatorios.
                    <?php if (!$esEdicion): ?>
                        Una vez creado el grupo, podrás agregar miembros y asignar roles.
                    <?php endif; ?>
                </p>
            </div>
        </form>
    </div>

    <!-- ========================================================================
         ZONA PELIGROSA (SOLO EN MODO EDICIÓN)
         Sección separada para acciones destructivas con advertencia visual
    ========================================================================= -->
    <?php if ($esEdicion): ?>
        <div class="bg-white rounded-lg shadow-md p-6 mt-8 border-l-4 border-red-500">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Zona Peligrosa</h3>
            <p class="text-gray-600 mb-4">
                Las siguientes acciones son irreversibles. Procede con precaución.
            </p>
            <button onclick="confirmarEliminacion()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200">
                Eliminar Grupo Permanentemente
            </button>
        </div>

        <script>
            /**
             * Función de eliminación con doble confirmación.
             * 
             * Muestra diálogo de advertencia y, si se confirma, envía
             * formulario POST para eliminar el grupo y todos sus miembros.
             * 
             * @security Requiere confirmación explícita del usuario
             * @note Usa POST para operación destructiva (no GET)
             */
            function confirmarEliminacion() {
                if (confirm('⚠️ ¿Estás seguro de que deseas eliminar este grupo?\n\nEsta acción eliminará el grupo y todos sus miembros de forma permanente y no se puede deshacer.')) {
                    
                    // Crear formulario dinámicamente para envío POST
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'index.php';

                    // Agregar campos ocultos necesarios
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
                    inputGrupoId.value = '<?php echo $grupo['id']; ?>';

                    const inputConfirmar = document.createElement('input');
                    inputConfirmar.type = 'hidden';
                    inputConfirmar.name = 'confirmar_eliminacion';
                    inputConfirmar.value = '1';

                    // Ensamblar y enviar
                    form.appendChild(inputMenuItem);
                    form.appendChild(inputAction);
                    form.appendChild(inputGrupoId);
                    form.appendChild(inputConfirmar);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    <?php endif; ?>
</main>

<!-- ============================================================================
     SCRIPTS DE VALIDACIÓN Y UX
     JavaScript para mejorar la experiencia del usuario
============================================================================= -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nombreInput = document.getElementById('nombre_grupo');

        // ====================================================================
        // VALIDACIÓN EN TIEMPO REAL
        // Proporciona feedback visual inmediato sobre la validez del input
        // ====================================================================
        /**
         * Valida el campo nombre mientras el usuario escribe.
         * Cambia el borde a rojo si el valor no cumple requisitos.
         * 
         * @validation Longitud entre 2 y 255 caracteres
         */
        nombreInput.addEventListener('input', function() {
            const valor = this.value.trim();
            const esValido = valor.length >= 2 && valor.length <= 255;

            if (!esValido && valor.length > 0) {
                // Feedback visual de error
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                // Restaurar estilo normal
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });

        // ====================================================================
        // AUTO-FOCUS EN CAMPO PRINCIPAL
        // Mejora la accesibilidad y flujo del formulario
        // ====================================================================
        nombreInput.focus();
    });
</script>