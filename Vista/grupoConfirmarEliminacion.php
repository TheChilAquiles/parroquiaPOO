<!-- /**
 * @file grupoConfirmarEliminacion.php
 * @version 2.3
 * @author Samuel Bedoya
 * @brief Vista de confirmación avanzada para eliminación de grupos
 * 
 * Implementa un sistema de confirmación de tres niveles para prevenir
 * eliminaciones accidentales de grupos parroquiales y sus miembros.
 * 
 * @dependencies
 * - Variables PHP: $grupo, $miembros (opcional)
 * - TailwindCSS para estilos
 * - JavaScript para validación en tiempo real
 * 
 * @features
 * - Confirmación triple (vista + input de texto + diálogo final)
 * - Información detallada de impacto de la eliminación
 * - Preview de miembros que serán afectados
 * - Validación en tiempo real con deshabilitación del botón
 * - Diseño visual con advertencias claras
 * 
 * @security
 * - Requiere coincidencia exacta del nombre del grupo
 * - Confirmación adicional con diálogo JavaScript
 * - Botón submit deshabilitado por defecto
 */ -->

<?php
// ============================================================================
// SISTEMA DE NOTIFICACIONES
// Renderiza mensajes si existen en la sesión
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
     Layout centrado para formulario de confirmación
============================================================================= -->
<main class="mx-auto max-w-2xl px-4 py-8">

    <!-- ========================================================================
         BARRA DE NAVEGACIÓN
         Breadcrumb con opciones de salida
    ========================================================================= -->
    <div class="flex items-center justify-between mb-8">
        <!-- Volver al detalle del grupo -->
        <a href="<?= url('grupos/ver', ['id' => $grupo['id']]) ?>"
            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al Grupo
        </a>

        <!-- Volver al listado general -->
        <a href="<?= url('grupos') ?>"
            class="px-4 py-2 bg-[#D0B8A8] text-white rounded-lg font-medium hover:bg-[#ab876f] transition duration-200">
            Ver Todos los Grupos
        </a>
    </div>

    <!-- ========================================================================
         CARD DE CONFIRMACIÓN
         Diseño con advertencias visuales y estructura de información clara
    ========================================================================= -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">

        <!-- ====================================================================
             ENCABEZADO CON ADVERTENCIA
             Fondo rojo con icono de alerta para captar atención
        ==================================================================== -->
        <div class="bg-red-50 px-6 py-4 border-b border-red-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h1 class="text-lg font-medium text-red-800">
                        Confirmar Eliminación de Grupo
                    </h1>
                    <p class="text-sm text-red-600 mt-1">
                        Esta acción no se puede deshacer
                    </p>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <!-- ================================================================
                 INFORMACIÓN DEL GRUPO A ELIMINAR
                 Card con datos visuales del grupo
            ================================================================= -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                    ¿Estás seguro de que deseas eliminar este grupo?
                </h2>

                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center mb-3">
                        <!-- Icono del grupo -->
                        <div class="flex-shrink-0 h-12 w-12 bg-[#D0B8A8] rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <!-- Información del grupo -->
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                <?php echo htmlspecialchars($grupo['nombre']); ?>
                            </h3>
                            <p class="text-sm text-gray-600">
                                <?php echo $grupo['total_miembros']; ?>
                                miembro<?php echo $grupo['total_miembros'] != 1 ? 's' : ''; ?>
                                <?php if (isset($grupo['estado_registro']) && $grupo['estado_registro']): ?>
                                    • Creado el <?php echo date('d/m/Y', strtotime($grupo['estado_registro'])); ?>
                                <?php endif; ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================================================
                 ADVERTENCIAS DE IMPACTO
                 Lista detallada de consecuencias de la eliminación
            ================================================================= -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 mb-2">
                            Ten en cuenta que al eliminar este grupo:
                        </h3>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Se eliminará permanentemente el grupo
                                    "<?php echo htmlspecialchars($grupo['nombre']); ?>"</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Se removerán todos los <?php echo $grupo['total_miembros']; ?>
                                    miembro<?php echo $grupo['total_miembros'] != 1 ? 's' : ''; ?> del grupo</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Esta acción no se puede deshacer</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Los usuarios no serán eliminados, solo su pertenencia a este grupo</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ================================================================
                 PREVIEW DE MIEMBROS AFECTADOS
                 Muestra hasta 5 miembros que serán removidos del grupo
            ================================================================= -->
            <?php if ($grupo['total_miembros'] > 0): ?>
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">
                        Miembros que serán removidos del grupo:
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <?php if (!empty($miembros)): ?>
                            <div class="space-y-2">
                                <?php
                                // Mostrar máximo 5 miembros para no saturar la UI
                                $miembrosMostrar = array_slice($miembros, 0, 5);
                                foreach ($miembrosMostrar as $miembro):
                                    ?>
                                    <div class="flex items-center text-sm">
                                        <!-- Avatar con inicial -->
                                        <div
                                            class="flex-shrink-0 h-6 w-6 bg-[#D0B8A8] rounded-full flex items-center justify-center mr-2">
                                            <span class="text-white text-xs font-medium">
                                                <?php echo strtoupper(substr($miembro['nombre_completo'], 0, 1)); ?>
                                            </span>
                                        </div>
                                        <span class="text-gray-700">
                                            <?php echo htmlspecialchars($miembro['nombre_completo']); ?>
                                            <span
                                                class="text-gray-500 ml-1">(<?php echo htmlspecialchars($miembro['rol']); ?>)</span>
                                        </span>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Indicador de miembros adicionales -->
                                <?php if (count($miembros) > 5): ?>
                                    <div class="text-sm text-gray-500 ml-8">
                                        ... y <?php echo count($miembros) - 5; ?>
                                        miembro<?php echo (count($miembros) - 5) != 1 ? 's' : ''; ?> más
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ================================================================
                 CONFIRMACIÓN CON INPUT DE TEXTO
                 Segunda capa de seguridad: requiere escribir el nombre exacto
            ================================================================= -->
            <div class="mb-6">
                <label for="confirmacion" class="block text-sm font-medium text-gray-700 mb-2">
                    Para confirmar, escribe el nombre del grupo:
                    <strong><?php echo htmlspecialchars($grupo['nombre']); ?></strong>
                </label>
                <input type="text" id="confirmacion"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Escribe el nombre del grupo aquí..." autocomplete="off">
                <p class="mt-1 text-xs text-gray-500">
                    Esta confirmación adicional ayuda a prevenir eliminaciones accidentales.
                </p>
            </div>

            <!-- ================================================================
                 BOTONES DE ACCIÓN
                 Submit deshabilitado hasta que se confirme con texto
            ================================================================= -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Formulario de eliminación -->
                <form action="<?= url('grupos/eliminar', ['id' => $grupo['id']]) ?>" method="POST" class="flex-1">
                    <button type="submit" id="btnConfirmarEliminacion" disabled
                        class="w-full flex justify-center items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Sí, Eliminar Grupo Permanentemente
                    </button>
                </form>

                <!-- Botón cancelar -->
                <a href="<?= url('grupos/ver', ['id' => $grupo['id']]) ?>"
                    class="flex-1 flex justify-center items-center px-6 py-3 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancelar
                </a>
            </div>
        </div>
    </div>
</main>

<!-- ============================================================================
     SCRIPTS DE VALIDACIÓN Y CONFIRMACIÓN
     Sistema de tres niveles de confirmación
============================================================================= -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ====================================================================
        // NIVEL 1: VALIDACIÓN DE INPUT DE TEXTO
        // Compara el texto ingresado con el nombre exacto del grupo
        // ====================================================================
        const nombreGrupo = <?php echo json_encode($grupo['nombre']); ?>;
        const inputConfirmacion = document.getElementById('confirmacion');
        const btnConfirmar = document.getElementById('btnConfirmarEliminacion');

        /**
         * Validación en tiempo real del input de confirmación.
         * Habilita el botón solo si el texto coincide exactamente.
         * 
         * @security Requiere coincidencia exacta (case-sensitive)
         */
        inputConfirmacion.addEventListener('input', function () {
            const esValido = this.value.trim() === nombreGrupo;
            btnConfirmar.disabled = !esValido;

            // Actualiza estilos visuales según validación
            if (esValido) {
                btnConfirmar.classList.remove('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
                btnConfirmar.classList.add('bg-red-600', 'hover:bg-red-700');
            } else {
                btnConfirmar.classList.add('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
                btnConfirmar.classList.remove('bg-red-600', 'hover:bg-red-700');
            }
        });

        // ====================================================================
        // NIVEL 2: CONFIRMACIÓN CON DIÁLOGO NATIVO
        // Última oportunidad para cancelar antes de eliminar
        // ====================================================================
        /**
         * Diálogo de confirmación final antes de submit.
         * Tercera y última capa de seguridad.
         * 
         * @pattern Confirmación en cascada (triple check)
         */
        btnConfirmar.closest('form').addEventListener('submit', function (e) {
            if (!confirm('⚠️ ÚLTIMA CONFIRMACIÓN\n\n¿Estás absolutamente seguro de que deseas eliminar este grupo?\n\nEsta acción es IRREVERSIBLE.')) {
                e.preventDefault();
            }
        });

        // ====================================================================
        // UX: AUTO-FOCUS EN CAMPO DE CONFIRMACIÓN
        // Mejora el flujo del usuario dirigiendo la atención
        // ====================================================================
        inputConfirmacion.focus();
    });
</script>