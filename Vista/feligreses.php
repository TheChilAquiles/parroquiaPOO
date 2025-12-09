<?php 
// Include the top template/header file (reusable component - MODULAR DESIGN)
// __DIR__ is a PHP magic constant that gets the current directory path
include_once __DIR__ . '/componentes/plantillaTop.php'; 
?>

<!-- Main container with responsive padding -->
<div class="container mx-auto px-4 py-8">
    <!-- ====================================================================
         HEADER SECTION
         Title and add button with responsive layout
    ==================================================================== -->
    <!-- Flex container that stacks vertically on mobile, horizontally on desktop -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <!-- Left side: Title and description -->
        <div>
            <!-- Main title with responsive font sizes -->
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Gestión de Feligreses</h1>
            <!-- Subtitle/description text -->
            <p class="text-gray-600 mt-2">Administra el registro de feligreses de la parroquia</p>
        </div>

        <!-- Right side: Add button -->
        <!-- Button to open add modal (event handler in JavaScript below) -->
        <button id="addFeligres"
                class="px-6 py-3 bg-[#D0B8A8] text-white rounded-lg shadow-md hover:bg-[#ab876f] transition duration-200 font-medium">
            <!-- Plus icon SVG -->
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Agregar Feligrés
        </button>
    </div>

    <!-- ====================================================================
         SESSION MESSAGES
         Display success/error messages from PHP session
    ==================================================================== -->
    <!-- Success message (only displays if session variable exists) -->
    <?php if (isset($_SESSION['success'])): ?>
        <!-- Green notification box for success messages -->
        <div class="mb-6 px-6 py-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm">
            <div class="flex items-center">
                <!-- Checkmark icon SVG -->
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <!-- Display success message with XSS protection -->
                <!-- ENT_QUOTES converts both single and double quotes -->
                <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
        <?php 
        // Remove message from session after displaying (prevents repeated display)
        unset($_SESSION['success']); 
        ?>
    <?php endif; ?>

    <!-- Error message (only displays if session variable exists) -->
    <?php if (isset($_SESSION['error'])): ?>
        <!-- Red notification box for error messages -->
        <div class="mb-6 px-6 py-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
            <div class="flex items-center">
                <!-- X icon SVG for errors -->
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <!-- Display error message with XSS protection -->
                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
        <?php 
        // Remove error message from session after displaying
        unset($_SESSION['error']); 
        ?>
    <?php endif; ?>

    <!-- ====================================================================
         DATA TABLE SECTION
         Main table for displaying feligreses (parishioners) list
    ==================================================================== -->
    <!-- White card container with shadow -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Table header with gradient background -->
        <div class="px-6 py-4 bg-gradient-to-r from-[#F5F0EB] to-[#E8DFD5] border-b border-[#DFD3C3]">
            <h2 class="text-xl font-semibold text-gray-800">Registro de Feligreses</h2>
        </div>

        <!-- Table content area -->
        <div class="p-6">
            <!-- Scrollable container for table (responsive on mobile) -->
            <div class="overflow-x-auto">
                <!-- DataTables will populate this table via AJAX -->
                <!-- ID is used by jQuery DataTables plugin for initialization -->
                <table id="tableFeligreses" class="table-auto w-full">
                    <!-- Table header row -->
                    <thead class="bg-gray-50">
                        <tr>
                            <!-- Column headers with uppercase text -->
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nombre Completo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tipo Documento</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Número Documento</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Teléfono</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Dirección</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <!-- Table body (empty - filled by DataTables via AJAX) -->
                    <tbody>
                        <!-- DataTables populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================================
     MODAL FOR ADD/EDIT FELIGRES
     Popup form for creating or editing parishioner records
============================================================================= -->
<!-- Modal container (hidden by default with 'hidden' class) -->
<!-- Covers entire screen with dark overlay (backdrop) -->
<div id="feligresModal" class="modal fixed inset-0 bg-black/50 bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden">

    <!-- Modal card (centered) with max width and height -->
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <!-- ====================================================================
             MODAL HEADER
             Title bar with close button
        ==================================================================== -->
        <div class="px-6 py-4 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] border-b border-[#8B6F47]">
            <div class="flex justify-between items-center">
                <!-- Modal title (changes dynamically: "Add" or "Edit") -->
                <h2 class="text-2xl font-bold text-white" id="modalTitle">Agregar Feligrés</h2>
                <!-- Close button (X icon) -->
                <button type="button" id="btnCancelar" class="text-white hover:text-gray-200 transition">
                    <!-- X icon SVG -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Subtitle/instruction text -->
            <p class="text-white text-sm mt-2">Complete la información del feligrés</p>
        </div>

        <!-- ====================================================================
             MODAL BODY - FORM
             Scrollable form container
        ==================================================================== -->
        <div class="overflow-y-auto max-h-[calc(90vh-100px)]">
            <!-- Form element (POST method for data submission) -->
            <form id="feligresForm" class="p-6 space-y-5" method="POST">
                <!-- Hidden input to store feligres ID (for edit mode) -->
                <input type="hidden" name="feligres-id" id="feligresId" value="">

                <!-- ============================================================
                     DOCUMENT SECTION
                     Document type and number fields in 2-column grid
                ============================================================= -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Document type dropdown -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Tipo Documento <span class="text-red-500">*</span>
                        </label>
                        <!-- Select dropdown with document types -->
                        <select name="tipo-doc" id="tipoDoc" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition">
                            <!-- Empty option as placeholder -->
                            <option value="">Seleccione...</option>
                            <!-- Document type options (values are database IDs) -->
                            <option value="1">Cédula de Ciudadanía</option>
                            <option value="2">Tarjeta de Identidad</option>
                            <option value="3">Cédula de Extranjería</option>
                            <option value="4">Registro Civil</option>
                            <option value="5">Permiso Especial de Permanencia</option>
                            <option value="6">NIT</option>
                        </select>
                    </div>

                    <!-- Document number input -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Número Documento <span class="text-red-500">*</span>
                        </label>
                        <!-- Text input for document number (required field) -->
                        <input type="text" name="documento" id="documento" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: 1234567890">
                    </div>
                </div>

                <!-- ============================================================
                     NAMES SECTION
                     First and second name fields
                ============================================================= -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First name (required) -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Primer Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="primer-nombre" id="primerNombre" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Juan">
                    </div>

                    <!-- Second name (optional) -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Segundo Nombre
                        </label>
                        <input type="text" name="segundo-nombre" id="segundoNombre"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Carlos">
                    </div>
                </div>

                <!-- ============================================================
                     LAST NAMES SECTION
                     First and second last name fields
                ============================================================= -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First last name (required) -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Primer Apellido <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="primer-apellido" id="primerApellido" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Pérez">
                    </div>

                    <!-- Second last name (optional) -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Segundo Apellido
                        </label>
                        <input type="text" name="segundo-apellido" id="segundoApellido"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Gómez">
                    </div>
                </div>

                <!-- ============================================================
                     CONTACT SECTION
                     Phone and address fields
                ============================================================= -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Phone number (optional) -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Teléfono
                        </label>
                        <input type="text" name="telefono" id="telefono"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: 3001234567">
                    </div>

                    <!-- Address (required) -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Dirección <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="direccion" id="direccion" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Calle 123 #45-67">
                    </div>
                </div>

                <!-- ============================================================
                     FORM BUTTONS
                     Submit button
                ============================================================= -->
                <div class="flex gap-3 pt-4">
                    <!-- Submit button with checkmark icon -->
                    <button type="submit" id="btnGuardar"
                            class="flex-1 bg-[#D0B8A8] hover:bg-[#ab876f] text-white px-6 py-3 rounded-lg shadow-md font-semibold transition duration-200">
                        <!-- Checkmark icon SVG -->
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- ============================================================================
     JAVASCRIPT SECTION
     jQuery code for table initialization and CRUD operations
============================================================================= -->
<script>
// Wait for DOM to be fully loaded (jQuery ready event)
$(document).ready(function() {

    // ========================================================================
    // DATATABLES INITIALIZATION
    // Configure and initialize the DataTables plugin for the table
    // ========================================================================
    // Create DataTable instance and store reference in variable
    const table = new DataTable('#tableFeligreses', {
        processing: true,  // Show processing indicator during load
        serverSide: true,  // Enable server-side processing (data loaded via AJAX)
        serverMethod: 'post',  // Use POST method for AJAX requests
        order: [[0, 'desc']],  // Default sort: first column (ID) descending
        // Spanish language configuration for DataTables UI
        language: {
            lengthMenu: "Mostrar _MENU_ registros",  // "Show X records" dropdown
            zeroRecords: "No se encontraron resultados",  // No data message
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",  // Pagination info
            infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",  // Empty state message
            infoFiltered: "(filtrado de un total de _MAX_ registros)",  // Filtered results info
            sSearch: "Buscar: ",  // Search box label
            // Pagination button labels
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            },
            sProcessing: "Procesando...",  // Loading message
        },
        // AJAX configuration for loading data
        ajax: {
            url: "?route=feligreses/listar",  // Server endpoint for data
            type: "POST",  // HTTP method
            dataType: "json"  // Expected response format
        },
        // Column definitions (maps data fields to table columns)
        columns: [
            { data: 'id', title: 'ID', width: "50px" },  // ID column with fixed width
            { data: 'nombre_completo', title: 'Nombre Completo' },  // Full name
            { data: 'tipo_documento', title: 'Tipo Documento' },  // Document type
            { data: 'numero_documento', title: 'Número Documento' },  // Document number
            { data: 'telefono', title: 'Teléfono' },  // Phone
            { data: 'direccion', title: 'Dirección' },  // Address
            {
                // Actions column (custom rendering)
                data: null,  // No specific data field
                title: 'Acciones',
                orderable: false,  // Cannot be sorted
                className: 'text-center',  // Center-align content
                // Custom render function for action buttons
                // Parameters: data (full row data), type, row (row object)
                render: function(data, type, row) {
                    // Return HTML string with edit and delete buttons
                    // Template literal (`) allows multi-line strings and ${} interpolation
                    return `
                        <button class="btn-editar bg-[#E8DFD5] hover:bg-[#DFD3C3] text-[#ab876f] px-3 py-1.5 rounded-lg text-sm mr-1 font-medium transition duration-200"
                                data-id="${row.id}"
                                title="Editar feligrés">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </button>
                        <?php if ($_SESSION['user-rol'] === 'Administrador'): ?>
                        <button class="btn-eliminar bg-red-100 hover:bg-red-200 text-red-600 px-3 py-1.5 rounded-lg text-sm font-medium transition duration-200"
                                data-id="${row.id}"
                                title="Eliminar feligrés">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                        <?php endif; ?>
                    `;
                }
            }
        ],
        pageLength: 10  // Show 10 records per page by default
    });

    // ========================================================================
    // OPEN MODAL TO ADD NEW FELIGRES
    // Click handler for "Add" button
    // ========================================================================
    // jQuery event binding: .on('click', selector, handler)
    $('#addFeligres').on('click', function() {
        // Change modal title to "Add"
        $('#modalTitle').text('Agregar Feligrés');
        // Reset form fields (clear all inputs)
        $('#feligresForm')[0].reset();
        // Clear hidden ID field (indicates new record, not edit)
        $('#feligresId').val('');
        // Show modal by removing 'hidden' class
        $('#feligresModal').removeClass('hidden');
        // Focus cursor on first name field for better UX
        $('#primerNombre').focus();
    });

    // ========================================================================
    // CLOSE MODAL
    // Click handler for cancel/close button
    // ========================================================================
    $('#btnCancelar').on('click', function() {
        // Hide modal by adding 'hidden' class
        $('#feligresModal').addClass('hidden');
    });

    // ========================================================================
    // CLOSE MODAL WITH ESC KEY
    // Keyboard shortcut for closing modal
    // ========================================================================
    // Document-level keydown event (GLOBAL EVENT LISTENER)
    $(document).on('keydown', function(e) {
        // Check if Escape key pressed AND modal is visible
        if (e.key === 'Escape' && !$('#feligresModal').hasClass('hidden')) {
            // Hide modal
            $('#feligresModal').addClass('hidden');
        }
    });

    // ========================================================================
    // EDIT BUTTON HANDLER
    // Opens modal with existing feligres data for editing
    // ========================================================================
    // Event delegation: listen on document for dynamically added buttons
    $(document).on('click', '.btn-editar', function() {
        // Get feligres ID from button's data attribute
        const id = $(this).data('id');
        // Get full row data from DataTable
        // .row() gets row element, .data() returns row data object
        const row = table.row($(this).closest('tr')).data();

        // Populate form fields with existing data (PRE-FILLING FORM)
        $('#modalTitle').text('Editar Feligrés');  // Change title to "Edit"
        $('#feligresId').val(row.id);  // Set hidden ID field
        // Use || '' to provide empty string if value is null/undefined
        $('#tipoDoc').val(row.tipo_documento_id || '');
        $('#documento').val(row.numero_documento || '');
        $('#primerNombre').val(row.primer_nombre || '');
        $('#segundoNombre').val(row.segundo_nombre || '');
        $('#primerApellido').val(row.primer_apellido || '');
        $('#segundoApellido').val(row.segundo_apellido || '');
        $('#telefono').val(row.telefono || '');
        $('#direccion').val(row.direccion || '');

        // Show modal
        $('#feligresModal').removeClass('hidden');
        // Focus on first name field
        $('#primerNombre').focus();
    });

    // ========================================================================
    // FORM SUBMIT HANDLER
    // Handles both create and update operations via AJAX
    // ========================================================================
    $(document).on('submit', '#feligresForm', function(event) {
        // Prevent default form submission (would reload page)
        event.preventDefault();

        // Determine if this is edit or create operation
        const feligresId = $('#feligresId').val();
        const isEdit = feligresId !== '';  // If ID exists, it's an edit
        // Build URL based on operation type (CONDITIONAL URL BUILDING)
        const url = isEdit ? `?route=feligreses/editar&id=${feligresId}` : "?route=feligreses/crear";
        // Serialize form data into URL-encoded string (key=value&key=value)
        const formData = $(this).serialize();

        // AJAX request to save data (ASYNCHRONOUS COMMUNICATION)
        $.ajax({
            url: url,  // Server endpoint
            method: "POST",  // HTTP method
            data: formData,  // Form data to send
            dataType: "json",  // Expected response format
            // Before sending request: show loading state
            beforeSend: function() {
                // Disable submit button to prevent double-submission
                $('#btnGuardar').prop('disabled', true)
                    // Change button text to show loading spinner
                    .html('<svg class="w-5 h-5 mr-2 inline animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Guardando...');
            },
            // On successful response
            success: function(response) {
                // Check if server returned success status
                if (response.status === 'success') {
                    // Reset form fields
                    $('#feligresForm')[0].reset();
                    // Close modal
                    $('#feligresModal').addClass('hidden');

                    // Reload DataTable to show updated data
                    // null = no callback, false = stay on current page
                    table.ajax.reload(null, false);

                    // Show success notification using SweetAlert2 library
                    Swal.fire({
                        icon: 'success',
                        title: 'Completado',
                        text: response.message || 'Feligrés guardado correctamente',
                        timer: 2000,  // Auto-close after 2 seconds
                        showConfirmButton: false,  // No confirm button
                        confirmButtonColor: '#D0B8A8'
                    });
                } else {
                    // Server returned error status
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error al guardar el feligrés',
                        confirmButtonColor: '#D0B8A8'
                    });
                }
            },
            // On AJAX error (network error, server error, etc.)
            error: function(xhr, status, error) {
                // Log error details to console for debugging
                console.error('Error AJAX:', {xhr, status, error});

                // Try to extract error message from response
                let mensaje = 'Error al guardar el feligrés';
                // Check if server returned JSON with error message
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }

                // Show error alert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje,
                    confirmButtonColor: '#D0B8A8'
                });
            },
            // Always runs after success or error (CLEANUP CODE)
            complete: function() {
                // Re-enable submit button
                $('#btnGuardar').prop('disabled', false)
                    // Restore original button text
                    .html('<svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Guardar');
            }
        });
    });

    // ========================================================================
    // DELETE BUTTON HANDLER
    // Shows confirmation dialog then deletes record
    // ========================================================================
    $(document).on('click', '.btn-eliminar', function() {
        // Get feligres ID from button
        const id = $(this).data('id');

        // Show confirmation dialog using SweetAlert2
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,  // Show cancel button
            confirmButtonColor: '#dc2626',  // Red color for danger action
            cancelButtonColor: '#6b7280',  // Gray color for cancel
            confirmButtonText: 'Sí, eliminar',  // Confirm button text
            cancelButtonText: 'Cancelar'  // Cancel button text
        // .then() handles the promise returned by Swal.fire (PROMISE HANDLING)
        }).then((result) => {
            // Check if user clicked confirm button
            if (result.isConfirmed) {
                // Redirect to delete endpoint (page refresh with GET request)
                // This triggers server-side deletion
                window.location.href = `?route=feligreses/eliminar&id=${id}`;
            }
            // If cancelled, do nothing (dialog closes automatically)
        });
    });

});
</script>

<?php 
// Include the bottom template/footer file (closes HTML tags)
include_once __DIR__ . '/componentes/plantillaBottom.php'; 
?>