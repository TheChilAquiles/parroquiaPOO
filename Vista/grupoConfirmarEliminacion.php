<!-- /**
 * @file grupoConfirmarEliminacion.php
 * @version 2.3
 * @author Samuel Bedoya
 * @brief Advanced confirmation view for group deletion
 * 
 * Implements a three-level confirmation system to prevent
 * accidental deletions of parish groups and their members.
 * 
 * @dependencies
 * - PHP Variables: $grupo, $miembros (optional)
 * - TailwindCSS for styles
 * - JavaScript for real-time validation
 * 
 * @features
 * - Triple confirmation (view + text input + final dialog)
 * - Detailed information about deletion impact
 * - Preview of members that will be affected
 * - Real-time validation with button disable
 * - Visual design with clear warnings
 * 
 * @security
 * - Requires exact match of group name
 * - Additional confirmation with JavaScript dialog
 * - Submit button disabled by default
 */ -->

<?php
// ============================================================================
// NOTIFICATION SYSTEM
// Renders messages if they exist in the session
// ============================================================================
// Check if there's a message stored in the session
if (isset($_SESSION['mensaje'])) {
    // Determine CSS classes based on message type (ternary operator - conditional assignment)
    // If success: green background, if error: red background, else: blue background
    $tipo_clase = $_SESSION['tipo_mensaje'] == 'success'
        ? 'bg-green-100 border-green-400 text-green-700'
        : ($_SESSION['tipo_mensaje'] == 'error'
            ? 'bg-red-100 border-red-400 text-red-700'
            : 'bg-blue-100 border-blue-400 text-blue-700');

    // Display the notification message with appropriate styling
    // htmlspecialchars() prevents XSS attacks by escaping HTML characters
    echo '<div class="' . $tipo_clase . ' border px-4 py-3 rounded mb-4">'
        . htmlspecialchars($_SESSION['mensaje']) . '</div>';
    // Clean up session variables after displaying (prevents message from showing again)
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}
?>

<!-- ============================================================================
     MAIN CONTAINER
     Centered layout for confirmation form
============================================================================= -->
<main class="mx-auto max-w-2xl px-4 py-8">

    <!-- ========================================================================
         NAVIGATION BAR
         Breadcrumb with exit options
    ========================================================================= -->
    <div class="flex items-center justify-between mb-8">
        <!-- Back to group detail button (left side) -->
        <!-- url() is a helper function that generates proper URLs -->
        <a href="<?= url('grupos/ver', ['id' => $grupo['id']]) ?>"
            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
            <!-- Left arrow SVG icon -->
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al Grupo
        </a>

        <!-- Back to all groups list button (right side) -->
        <a href="<?= url('grupos') ?>"
            class="px-4 py-2 bg-[#D0B8A8] text-white rounded-lg font-medium hover:bg-[#ab876f] transition duration-200">
            Ver Todos los Grupos
        </a>
    </div>

    <!-- ========================================================================
         CONFIRMATION CARD
         Design with visual warnings and clear information structure
    ========================================================================= -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">

        <!-- ====================================================================
             HEADER WITH WARNING
             Red background with alert icon to capture attention
        ==================================================================== -->
        <div class="bg-red-50 px-6 py-4 border-b border-red-200">
            <!-- Flex container to align icon and text -->
            <div class="flex items-center">
                <!-- Warning icon (non-shrinking) -->
                <div class="flex-shrink-0">
                    <!-- Triangle alert icon SVG -->
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <!-- Header text -->
                <div class="ml-3">
                    <h1 class="text-lg font-medium text-red-800">
                        Confirmar Eliminación de Grupo
                    </h1>
                    <!-- Warning subtext emphasizing irreversibility -->
                    <p class="text-sm text-red-600 mt-1">
                        Esta acción no se puede deshacer
                    </p>
                </div>
            </div>
        </div>

        <!-- Main content area with padding -->
        <div class="px-6 py-6">
            <!-- ================================================================
                 INFORMATION ABOUT GROUP TO DELETE
                 Card with visual group data
            ================================================================= -->
            <div class="mb-6">
                <!-- Main question heading -->
                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                    ¿Estás seguro de que deseas eliminar este grupo?
                </h2>

                <!-- Group information card (light gray background) -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center mb-3">
                        <!-- Group icon (circular with people icon) -->
                        <div class="flex-shrink-0 h-12 w-12 bg-[#D0B8A8] rounded-lg flex items-center justify-center">
                            <!-- People/group icon SVG -->
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <!-- Group details text -->
                        <div class="ml-4">
                            <!-- Group name (escaped for security) -->
                            <h3 class="text-lg font-medium text-gray-900">
                                <?php echo htmlspecialchars($grupo['nombre']); ?>
                            </h3>
                            <!-- Member count and creation date -->
                            <p class="text-sm text-gray-600">
                                <?php echo $grupo['total_miembros']; ?>
                                <!-- Plural handling: add 's' if more than 1 member -->
                                miembro<?php echo $grupo['total_miembros'] != 1 ? 's' : ''; ?>
                                <!-- Conditionally show creation date if it exists -->
                                <?php if (isset($grupo['estado_registro']) && $grupo['estado_registro']): ?>
                                    • Creado el <?php echo date('d/m/Y', strtotime($grupo['estado_registro'])); ?>
                                <?php endif; ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================================================
                 IMPACT WARNINGS
                 Detailed list of deletion consequences
            ================================================================= -->
            <!-- Yellow warning box to highlight important information -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <!-- Warning icon -->
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <!-- Warning text content -->
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 mb-2">
                            Ten en cuenta que al eliminar este grupo:
                        </h3>
                        <!-- Bulleted list of consequences -->
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <!-- Consequence 1: Group will be permanently deleted -->
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Se eliminará permanentemente el grupo
                                    "<?php echo htmlspecialchars($grupo['nombre']); ?>"</span>
                            </li>
                            <!-- Consequence 2: All members will be removed -->
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Se removerán todos los <?php echo $grupo['total_miembros']; ?>
                                    miembro<?php echo $grupo['total_miembros'] != 1 ? 's' : ''; ?> del grupo</span>
                            </li>
                            <!-- Consequence 3: Action is irreversible -->
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Esta acción no se puede deshacer</span>
                            </li>
                            <!-- Clarification: Users themselves won't be deleted -->
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Los usuarios no serán eliminados, solo su pertenencia a este grupo</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ================================================================
                 PREVIEW OF AFFECTED MEMBERS
                 Shows up to 5 members who will be removed from the group
            ================================================================= -->
            <!-- Only show this section if there are members in the group -->
            <?php if ($grupo['total_miembros'] > 0): ?>
                <div class="mb-6">
                    <!-- Section heading -->
                    <h3 class="text-sm font-medium text-gray-900 mb-3">
                        Miembros que serán removidos del grupo:
                    </h3>
                    <!-- Members list container -->
                    <div class="bg-gray-50 rounded-lg p-3">
                        <!-- Check if members array is not empty -->
                        <?php if (!empty($miembros)): ?>
                            <div class="space-y-2">
                                <?php
                                // Limit display to maximum 5 members to avoid UI clutter
                                // array_slice extracts a portion of the array (first 5 elements)
                                $miembrosMostrar = array_slice($miembros, 0, 5);
                                // Loop through each member to display (ITERATION)
                                foreach ($miembrosMostrar as $miembro):
<<<<<<< HEAD
                                ?>
=======
                                    ?>
                                    <!-- Individual member row -->
>>>>>>> origin/Santiago
                                    <div class="flex items-center text-sm">
                                        <!-- Avatar with first letter initial -->
                                        <div
                                            class="flex-shrink-0 h-6 w-6 bg-[#D0B8A8] rounded-full flex items-center justify-center mr-2">
                                            <span class="text-white text-xs font-medium">
                                                <?php 
                                                // Get first character of full name and convert to uppercase
                                                // substr() extracts substring, strtoupper() converts to uppercase
                                                echo strtoupper(substr($miembro['nombre_completo'], 0, 1)); 
                                                ?>
                                            </span>
                                        </div>
                                        <!-- Member name and role -->
                                        <span class="text-gray-700">
                                            <?php echo htmlspecialchars($miembro['nombre_completo']); ?>
                                            <!-- Role in parentheses with gray color -->
                                            <span
                                                class="text-gray-500 ml-1">(<?php echo htmlspecialchars($miembro['rol']); ?>)</span>
                                        </span>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Indicator for additional members not shown -->
                                <!-- Only display if there are more than 5 members total -->
                                <?php if (count($miembros) > 5): ?>
                                    <div class="text-sm text-gray-500 ml-8">
                                        <!-- Calculate and show count of remaining members -->
                                        ... y <?php echo count($miembros) - 5; ?>
                                        <!-- Plural handling for "miembro/miembros" -->
                                        miembro<?php echo (count($miembros) - 5) != 1 ? 's' : ''; ?> más
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ================================================================
                 CONFIRMATION WITH TEXT INPUT
                 Second security layer: requires typing exact name
            ================================================================= -->
            <div class="mb-6">
                <!-- Label instructing user to type group name -->
                <label for="confirmacion" class="block text-sm font-medium text-gray-700 mb-2">
                    Para confirmar, escribe el nombre del grupo:
                    <!-- Show exact group name in bold -->
                    <strong><?php echo htmlspecialchars($grupo['nombre']); ?></strong>
                </label>
                <!-- Text input for confirmation (no autocomplete for security) -->
                <input type="text" id="confirmacion"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    placeholder="Escribe el nombre del grupo aquí..." autocomplete="off">
                <!-- Help text explaining purpose of confirmation -->
                <p class="mt-1 text-xs text-gray-500">
                    Esta confirmación adicional ayuda a prevenir eliminaciones accidentales.
                </p>
            </div>

            <!-- ================================================================
                 ACTION BUTTONS
                 Submit disabled until confirmed with text
            ================================================================= -->
            <!-- Flex container for buttons (stacks vertically on mobile, horizontally on desktop) -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Deletion form (POST method for data modification) -->
                <form action="<?= url('grupos/eliminar', ['id' => $grupo['id']]) ?>" method="POST" class="flex-1">
<<<<<<< HEAD
                    <button type="submit" name="confirmar_eliminacion" value="1" id="btnConfirmarEliminacion" disabled
                    class="w-full flex justify-center items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed">
=======
                    <!-- Submit button (disabled by default for safety) -->
                    <button type="submit" id="btnConfirmarEliminacion" disabled
                        class="w-full flex justify-center items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 disabled:bg-gray-300 disabled:cursor-not-allowed">
                        <!-- Trash bin icon SVG -->
>>>>>>> origin/Santiago
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Sí, Eliminar Grupo Permanentemente
                    </button>
                </form>

                <!-- Cancel button (link styled as button) -->
                <a href="<?= url('grupos/ver', ['id' => $grupo['id']]) ?>"
                    class="flex-1 flex justify-center items-center px-6 py-3 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                    <!-- X icon for cancel action -->
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
     VALIDATION AND CONFIRMATION SCRIPTS
     Three-level confirmation system
============================================================================= -->
<script>
<<<<<<< HEAD
    document.addEventListener('DOMContentLoaded', function() {
=======
    // Wait for DOM to be fully loaded before running scripts (DOM READY EVENT)
    document.addEventListener('DOMContentLoaded', function () {
>>>>>>> origin/Santiago
        // ====================================================================
        // LEVEL 1: TEXT INPUT VALIDATION
        // Compares entered text with exact group name
        // ====================================================================
        // Convert PHP variable to JavaScript (JSON encode for safety)
        const nombreGrupo = <?php echo json_encode($grupo['nombre']); ?>;
        // Get references to DOM elements (DOM SELECTION)
        const inputConfirmacion = document.getElementById('confirmacion');
        const btnConfirmar = document.getElementById('btnConfirmarEliminacion');

        /**
         * Real-time validation of confirmation input.
         * Enables button only if text matches exactly.
         * 
         * @security Requires exact match (case-sensitive)
         * 
         * EVENT LISTENER: Responds to user input in real-time
         */
<<<<<<< HEAD
        inputConfirmacion.addEventListener('input', function() {
=======
        inputConfirmacion.addEventListener('input', function () {
            // Compare trimmed input value with group name (exact match required)
            // trim() removes whitespace from both ends
>>>>>>> origin/Santiago
            const esValido = this.value.trim() === nombreGrupo;
            // Enable/disable button based on validation (PROPERTY MANIPULATION)
            btnConfirmar.disabled = !esValido;

            // Update visual styles based on validation state (CONDITIONAL LOGIC)
            if (esValido) {
                // Remove disabled styles
                btnConfirmar.classList.remove('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
                // Add enabled styles (red danger color)
                btnConfirmar.classList.add('bg-red-600', 'hover:bg-red-700');
            } else {
                // Add disabled styles (gray out button)
                btnConfirmar.classList.add('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
                // Remove enabled styles
                btnConfirmar.classList.remove('bg-red-600', 'hover:bg-red-700');
            }
        });

        // ====================================================================
        // LEVEL 2: CONFIRMATION WITH NATIVE DIALOG
        // Last chance to cancel before deletion
        // ====================================================================
        /**
         * Final confirmation dialog before submit.
         * Third and last security layer.
         * 
         * @pattern Cascade confirmation (triple check)
         * 
         * EVENT LISTENER: Intercepts form submission
         */
<<<<<<< HEAD
        btnConfirmar.closest('form').addEventListener('submit', function(e) {
=======
        // Get parent form element and add submit listener
        // closest() finds nearest ancestor matching selector (DOM TRAVERSAL)
        btnConfirmar.closest('form').addEventListener('submit', function (e) {
            // Show native browser confirmation dialog (BROWSER API)
            // confirm() returns true if user clicks OK, false if Cancel
>>>>>>> origin/Santiago
            if (!confirm('⚠️ ÚLTIMA CONFIRMACIÓN\n\n¿Estás absolutamente seguro de que deseas eliminar este grupo?\n\nEsta acción es IRREVERSIBLE.')) {
                // Prevent form submission if user cancels (EVENT PREVENTION)
                e.preventDefault();
            }
            // If user confirms (returns true), form submits normally
        });

        // ====================================================================
        // UX: AUTO-FOCUS ON CONFIRMATION FIELD
        // Improves user flow by directing attention
        // ====================================================================
        // Automatically place cursor in input field when page loads (FOCUS MANAGEMENT)
        inputConfirmacion.focus();
    });
</script>