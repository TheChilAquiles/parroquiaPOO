<!-- Vista/grupoDetalle.php - UPDATED FOR MVC -->

<!-- ============================================================================
     NOTIFICATION SYSTEM
     Display session messages with appropriate styling
============================================================================= -->
<?php
// Check if notification message exists in session
if (isset($_SESSION['mensaje'])) {
    // Determine CSS classes based on message type (TERNARY OPERATOR - conditional assignment)
    // Nested ternary: if success -> green, else if error -> red, else -> blue
    $tipo_clase = $_SESSION['tipo_mensaje'] == 'success' 
        ? 'bg-green-100 border-green-400 text-green-700'  // Success: green styling
        : ($_SESSION['tipo_mensaje'] == 'error' 
            ? 'bg-red-100 border-red-400 text-red-700'  // Error: red styling
            : 'bg-blue-100 border-blue-400 text-blue-700');  // Info: blue styling
    
    // Output notification div with dynamic classes
    // htmlspecialchars() prevents XSS attacks by escaping HTML characters
    echo '<div class="' . $tipo_clase . ' border px-4 py-3 rounded mb-4">' 
         . htmlspecialchars($_SESSION['mensaje']) . '</div>';
    // Clean up session variables after displaying (prevents repeated display)
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}
?>

<!-- ============================================================================
     MAIN CONTAINER
     Centered layout with max width and padding
============================================================================= -->
<main class="mx-auto max-w-6xl px-4 py-8">
    
    <!-- ========================================================================
         NAVIGATION BAR
         Back button and action buttons
    ======================================================================== -->
    <!-- Flex container: stacks vertically on mobile, horizontally on desktop -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <!-- ✅ NEW: Back button with MVC route -->
        <!-- url() is a helper function that generates proper MVC routes -->
        <a href="<?= url('grupos') ?>"
           class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
            <!-- Left arrow icon SVG -->
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a Grupos
        </a>

        <!-- ACTION BUTTONS (right side) -->
        <div class="flex gap-2">
            <!-- ✅ NEW: Edit button with MVC route -->
            <!-- url() with second parameter passes query parameters (id in this case) -->
            <a href="<?= url('grupos/editar', ['id' => $grupo['id']]) ?>"
               class="px-4 py-2 bg-[#D0B8A8] text-white rounded-lg font-medium hover:bg-[#ab876f] transition duration-200">
                Editar Grupo
            </a>
            
            <!-- ✅ NEW: Delete button with MVC route -->
            <!-- onclick calls JavaScript function (EVENT HANDLER) -->
            <button onclick="confirmarEliminacion()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200">
                Eliminar Grupo
            </button>
        </div>
    </div>

    <!-- ========================================================================
         GROUP INFORMATION CARD
         Display group name and member count
    ======================================================================== -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <!-- Flex container for group info and add button -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <!-- Left side: Group name and stats -->
            <div>
                <!-- Group name heading with XSS protection -->
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <?php echo htmlspecialchars($grupo['nombre']); ?>
                </h1>
                <!-- Member count with plural handling -->
                <p class="text-gray-600">
                    <span class="font-medium"><?php echo $grupo['total_miembros']; ?></span>
                    <!-- Conditional pluralization: "miembro" or "miembros" -->
                    miembro<?php echo $grupo['total_miembros'] != 1 ? 's' : ''; ?>
                </p>
            </div>
            
            <!-- Right side: Add member button -->
            <!-- Button ID used by JavaScript to open modal -->
            <button id="btnAgregarMiembro"
                    class="px-6 py-3 bg-[#D0B8A8] text-white rounded-lg font-medium hover:bg-[#ab876f] transition duration-200 shadow-sm">
                + Agregar Miembro
            </button>
        </div>
    </div>

    <!-- ========================================================================
         MEMBERS LIST SECTION
         Table or empty state showing group members
    ======================================================================== -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-6 text-gray-900">Miembros del Grupo</h2>

        <!-- Conditional rendering: show empty state or members table -->
        <?php if (empty($miembros)): ?>
            <!-- ================================================================
                 EMPTY STATE
                 Displayed when group has no members
            ================================================================ -->
            <div class="text-center py-12">
                <!-- Users icon (large, gray) -->
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <!-- Empty state message -->
                <p class="text-gray-500 text-lg">Este grupo aún no tiene miembros.</p>
                <p class="text-gray-400 text-sm mt-1">Haz clic en "Agregar Miembro" para comenzar.</p>
            </div>
        <?php else: ?>
            <!-- ================================================================
                 MEMBERS TABLE
                 Display all group members in a table
            ================================================================ -->
            <!-- Scrollable container (responsive on mobile) -->
            <div class="overflow-x-auto">
                <!-- Table with dividers between rows -->
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Table header -->
                    <thead class="bg-gray-50">
                        <tr>
                            <!-- Column headers with uppercase text -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol en Grupo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Loop through each member (ITERATION) -->
                        <?php foreach ($miembros as $miembro): ?>
                            <!-- Table row with hover effect -->
                            <tr class="hover:bg-gray-50">
                                <!-- ====================================================
                                     USER COLUMN
                                     Avatar with initials, name, and email
                                ==================================================== -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <!-- Avatar container -->
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <!-- Circular avatar with initials -->
                                            <div class="h-10 w-10 rounded-full bg-[#D0B8A8] flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    <?php
                                                    // Generate initials from name
                                                    $iniciales = '';
                                                    // Split full name into words
                                                    $nombres = explode(' ', $miembro['nombre_completo']);
                                                    // Take first 2 words (first and last name typically)
                                                    // array_slice() extracts portion of array
                                                    foreach (array_slice($nombres, 0, 2) as $nombre) {
                                                        // Get first character and convert to uppercase
                                                        // substr() extracts substring, strtoupper() converts to uppercase
                                                        $iniciales .= strtoupper(substr($nombre, 0, 1));
                                                    }
                                                    // If no initials, use first letter of email
                                                    // ?: is Elvis operator (shorthand for ternary with same condition)
                                                    echo $iniciales ?: strtoupper(substr($miembro['email'], 0, 1));
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Name and email text -->
                                        <div class="ml-4">
                                            <!-- Full name with XSS protection -->
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($miembro['nombre_completo']); ?>
                                            </div>
                                            <!-- Email with XSS protection -->
                                            <div class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($miembro['email']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- ====================================================
                                     ROLE COLUMN
                                     Badge with dynamic color based on role
                                ==================================================== -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <!-- Badge span with dynamic classes -->
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php
                                        // Convert role to lowercase for comparison
                                        $rol = strtolower($miembro['rol']);
                                        // Determine badge color based on role (CONDITIONAL STYLING)
                                        if ($rol === 'líder' || $rol === 'lider') 
                                            echo 'bg-[#F5F0EB] text-[#ab876f]';  // Leader: beige
                                        elseif ($rol === 'coordinador') 
                                            echo 'bg-[#E8DFD5] text-[#8B6F47]';  // Coordinator: tan
                                        elseif ($rol === 'secretario') 
                                            echo 'bg-[#DFD3C3] text-[#5D4E37]';  // Secretary: brown
                                        else 
                                            echo 'bg-gray-100 text-gray-800';  // Default: gray
                                        ?>">
                                        <!-- Display role text with XSS protection -->
                                        <?php echo htmlspecialchars($miembro['rol']); ?>
                                    </span>
                                </td>
                                
                                <!-- ====================================================
                                     PHONE COLUMN
                                     Show phone or dash if not available
                                ==================================================== -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php 
                                    // Ternary operator: if phone exists, show it; else show dash
                                    echo $miembro['telefono'] ? htmlspecialchars($miembro['telefono']) : '-'; 
                                    ?>
                                </td>
                                
                                <!-- ====================================================
                                     USER TYPE COLUMN
                                     Show user role in system (not group role)
                                ==================================================== -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php 
                                    // Null coalescing operator: use 'N/A' if role doesn't exist
                                    echo htmlspecialchars($miembro['rol_usuario'] ?? 'N/A'); 
                                    ?>
                                </td>
                                
                                <!-- ====================================================
                                     ACTIONS COLUMN
                                     Buttons to change role or remove member
                                ==================================================== -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- Change role button -->
                                        <!-- onclick calls JavaScript function with parameters -->
                                        <!-- ENT_QUOTES escapes both single and double quotes -->
                                        <button onclick="editarRol(<?php echo $miembro['usuario_id']; ?>, '<?php echo htmlspecialchars($miembro['rol'], ENT_QUOTES); ?>')"
                                                class="text-[#ab876f] hover:text-[#8B6F47] transition duration-200">
                                            Cambiar Rol
                                        </button>
                                        <!-- Remove member button -->
                                        <button onclick="eliminarMiembro(<?php echo $miembro['usuario_id']; ?>, '<?php echo htmlspecialchars($miembro['nombre_completo'], ENT_QUOTES); ?>')"
                                                class="text-red-600 hover:text-red-900 transition duration-200">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- ============================================================================
     MODAL: ADD MEMBER
     Popup form to add new member to group
============================================================================= -->
<!-- Modal container (hidden by default) -->
<!-- z-50 ensures modal appears above other content (high z-index) -->
<div id="modalAgregarMiembro" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50">
    <!-- Centered modal card -->
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal title -->
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Agregar Nuevo Miembro</h3>
            
            <!-- ✅ NEW: Form with MVC route -->
            <!-- POST method for data submission -->
            <form action="<?= url('grupos/agregar-miembro') ?>" method="POST">
                <!-- Hidden input: sends group ID to server -->
                <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo['id']); ?>">

                <!-- ============================================================
                     USER SELECTOR
                     Dropdown to select available user
                ============================================================= -->
                <div class="mb-4">
                    <label for="usuario_id" class="block text-gray-700 font-medium mb-2">Seleccionar Usuario</label>
                    <!-- Select dropdown with focus styling -->
                    <select id="usuario_id" name="usuario_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C4A68A]">
                        <!-- Empty option as placeholder -->
                        <option value="">Seleccione un usuario...</option>
                        <!-- Loop through available users (ITERATION) -->
                        <?php foreach ($usuariosDisponibles as $usuario): ?>
                            <!-- Option with user ID as value -->
                            <option value="<?php echo $usuario['id']; ?>">
                                <!-- Display name and email with XSS protection -->
                                <?php echo htmlspecialchars($usuario['nombre_completo'] . ' (' . $usuario['email'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- ============================================================
                     ROLE SELECTOR
                     Dropdown to select role for new member
                ============================================================= -->
                <div class="mb-6">
                    <label for="rol_id" class="block text-gray-700 font-medium mb-2">Rol en el Grupo</label>
                    <select id="rol_id" name="rol_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C4A68A]">
                        <!-- Empty option as placeholder -->
                        <option value="">Seleccione un rol...</option>
                        <!-- Loop through available roles (ITERATION) -->
                        <?php foreach ($rolesGrupo as $rol): ?>
                            <option value="<?php echo $rol['id']; ?>">
                                <?php echo htmlspecialchars($rol['rol']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Form action buttons -->
                <div class="flex gap-3">
                    <!-- Submit button -->
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-[#D0B8A8] text-white rounded-lg hover:bg-[#ab876f] transition duration-200">
                        Agregar Miembro
                    </button>
                    <!-- Cancel button (closes modal without submitting) -->
                    <button type="button" id="btnCerrarModalAgregar"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================================
     MODAL: CHANGE ROLE
     Popup form to change member's role in group
============================================================================= -->
<!-- Modal container with dark overlay background -->
<div id="modalCambiarRol" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <!-- Centered modal card -->
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal title -->
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Cambiar Rol de Miembro</h3>
            
            <!-- ✅ NEW: Form with MVC route -->
            <form action="<?= url('grupos/actualizar-rol') ?>" method="POST">
                <!-- Hidden inputs: group ID and user ID -->
                <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo['id']); ?>">
                <!-- This value will be set by JavaScript when opening modal -->
                <input type="hidden" name="usuario_id" id="usuario_cambiar_rol">

                <!-- ============================================================
                     NEW ROLE SELECTOR
                     Dropdown to select new role for member
                ============================================================= -->
                <div class="mb-6">
                    <label for="nuevo_rol_id" class="block text-gray-700 font-medium mb-2">Nuevo Rol</label>
                    <select id="nuevo_rol_id" name="nuevo_rol_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C4A68A]">
                        <!-- Loop through available roles -->
                        <?php foreach ($rolesGrupo as $rol): ?>
                            <option value="<?php echo $rol['id']; ?>">
                                <?php echo htmlspecialchars($rol['rol']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Form action buttons -->
                <div class="flex gap-3">
                    <!-- Submit button -->
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-[#D0B8A8] text-white rounded-lg hover:bg-[#ab876f] transition duration-200">
                        Actualizar Rol
                    </button>
                    <!-- Cancel button -->
                    <button type="button" id="btnCerrarModalRol"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================================
     JAVASCRIPT SECTION
     Modal controls and member management functions
============================================================================= -->
<script>
    // Wait for DOM to be fully loaded (DOM READY EVENT)
    document.addEventListener('DOMContentLoaded', function() {
        // ====================================================================
        // GET DOM ELEMENTS
        // Store references to frequently used elements (DOM SELECTION)
        // ====================================================================
        const modalAgregar = document.getElementById('modalAgregarMiembro');
        const modalRol = document.getElementById('modalCambiarRol');
        const btnAgregarMiembro = document.getElementById('btnAgregarMiembro');
        const btnCerrarModalAgregar = document.getElementById('btnCerrarModalAgregar');
        const btnCerrarModalRol = document.getElementById('btnCerrarModalRol');

        // ====================================================================
        // MODAL CONTROL EVENTS
        // Open and close modals with click events
        // ====================================================================
        // Open add member modal (EVENT LISTENER)
        btnAgregarMiembro.addEventListener('click', () => modalAgregar.classList.remove('hidden'));
        // Close add member modal
        btnCerrarModalAgregar.addEventListener('click', () => modalAgregar.classList.add('hidden'));
        // Close change role modal
        btnCerrarModalRol.addEventListener('click', () => modalRol.classList.add('hidden'));

        // ====================================================================
        // CLICK OUTSIDE TO CLOSE
        // Close modal when clicking on dark overlay (UX PATTERN)
        // ====================================================================
        // Loop through both modals (ITERATION)
        [modalAgregar, modalRol].forEach(modal => {
            // Listen for clicks anywhere in window (GLOBAL EVENT LISTENER)
            window.addEventListener('click', function(event) {
                // If clicked element is the modal itself (not children), close it
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    });

    /**
     * Opens change role modal with pre-filled user information
     * 
     * @param {number} usuarioId - ID of user to change role
     * @param {string} rolActual - Current role of user (for reference)
     * 
     * FUNCTION DEFINITION: Reusable code block
     */
    function editarRol(usuarioId, rolActual) {
        // Set hidden input value (PRE-FILL FORM)
        document.getElementById('usuario_cambiar_rol').value = usuarioId;
        // Show modal
        document.getElementById('modalCambiarRol').classList.remove('hidden');
    }

    /**
     * ✅ NEW: Remove member with POST to MVC route
     * 
     * Creates and submits a form dynamically to remove member from group
     * 
     * @param {number} usuarioId - ID of user to remove
     * @param {string} nombreUsuario - Name of user (for confirmation message)
     * 
     * PATTERN: Programmatic form submission (avoids GET with sensitive data)
     */
    function eliminarMiembro(usuarioId, nombreUsuario) {
        // Show native confirmation dialog (BROWSER API)
        if (confirm(`¿Estás seguro de que deseas eliminar a "${nombreUsuario}" del grupo?`)) {
            // Create form element dynamically (DOM CREATION)
            const form = document.createElement('form');
            form.method = 'POST';  // Use POST for data modification
            form.action = '<?= url('grupos/eliminar-miembro') ?>';  // MVC route

            // Create hidden input for group ID
            const inputGrupoId = document.createElement('input');
            inputGrupoId.type = 'hidden';
            inputGrupoId.name = 'grupo_id';
            inputGrupoId.value = '<?php echo $grupo['id']; ?>';

            // Create hidden input for user ID
            const inputUsuarioId = document.createElement('input');
            inputUsuarioId.type = 'hidden';
            inputUsuarioId.name = 'usuario_id';
            inputUsuarioId.value = usuarioId;

            // Append inputs to form (DOM MANIPULATION)
            form.appendChild(inputGrupoId);
            form.appendChild(inputUsuarioId);
            // Append form to document body
            document.body.appendChild(form);
            // Submit form programmatically
            form.submit();
        }
        // If user cancels, do nothing (dialog closes automatically)
    }

    /**
     * ✅ NEW: Confirm group deletion with MVC route
     * 
     * Shows confirmation dialog then redirects to delete endpoint
     * 
     * PATTERN: Confirmation before destructive action (DEFENSIVE UX)
     */
    function confirmarEliminacion() {
        // Show confirmation with warning emoji (VISUAL EMPHASIS)
        if (confirm('⚠️ ¿Estás seguro de que deseas eliminar este grupo?\n\nEsta acción eliminará el grupo y todos sus miembros de forma permanente.')) {
            // Redirect to delete endpoint (PAGE NAVIGATION)
            window.location.href = '<?= url('grupos/eliminar', ['id' => $grupo['id']]) ?>';
        }
    }
</script>