<?php 
// Include the top template component (header, navigation, etc.)
include_once __DIR__ . '/componentes/plantillaTop.php'; 
?>

<!-- Main container with responsive padding -->
<div class="container mx-auto px-4 py-8">
    <!-- Header section with title and button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <!-- Page main title -->
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Gestión de Certificados</h1>
            <!-- Page subtitle/description -->
            <p class="text-gray-600 mt-2">Administra y genera certificados sacramentales</p>
        </div>

        <!-- Button to open modal for new certificate -->
        <button id="btnAbrirModal"
                class="px-6 py-3 bg-[#D0B8A8] text-white rounded-lg shadow-md hover:bg-[#ab876f] transition duration-200 font-medium">
            <!-- Plus icon SVG -->
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nuevo Certificado
        </button>
    </div>

    <!-- Certificates history section (DataTables) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Section header with gradient background -->
        <div class="px-6 py-4 bg-gradient-to-r from-[#F5F0EB] to-[#E8DFD5] border-b border-[#DFD3C3]">
            <h2 class="text-xl font-semibold text-gray-800">Historial de Certificados</h2>
        </div>

        <!-- Table container with padding -->
        <div class="p-6">
            <!-- Wrapper for horizontal scroll on small screens -->
            <div class="overflow-x-auto">
                <!-- DataTable for certificates list -->
                <table id="tablaCertificados" class="table-auto w-full">
                    <!-- Table header -->
                    <thead class="bg-gray-50">
                        <tr>
                            <!-- Column: Certificate ID -->
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                            <!-- Column: Sacrament type -->
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tipo Sacramento</th>
                            <!-- Column: Parishioner name -->
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Feligrés</th>
                            <!-- Column: Document number -->
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Documento</th>
                            <!-- Column: Person who requested -->
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Solicitante</th>
                            <!-- Column: Certificate status -->
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Estado</th>
                            <!-- Column: Request date -->
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Fecha Solicitud</th>
                            <!-- Column: Action buttons -->
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables will populate this automatically via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for generating certificates -->
<div id="modalCertificado" class="hidden fixed inset-0 bg-black/50  backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <!-- Modal content card with max width and scroll -->
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal header with gradient -->
        <div class="px-6 py-4 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] border-b border-[#8B6F47]">
            <!-- Header with title and close button -->
            <div class="flex justify-between items-center">
                <!-- Modal title -->
                <h2 class="text-2xl font-bold text-white">Generar Certificado Sacramental</h2>
                <!-- Close button (X icon) -->
                <button id="btnCerrarModal" class="text-white hover:text-gray-200 transition">
                    <!-- X icon SVG -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal subtitle/instructions -->
            <p class="text-white text-sm mt-2">Ingrese los datos del feligrés para buscar el sacramento</p>
        </div>

        <!-- Certificate generation form -->
        <form id="formCertificado" class="p-6 space-y-5">
            <!-- Document type field -->
            <div>
                <!-- Label with required asterisk -->
                <label class="block text-gray-700 font-semibold mb-2">
                    Tipo de Documento <span class="text-red-500">*</span>
                </label>
                <!-- Select dropdown for document type -->
                <select name="tipo_documento_id" id="tipoDocumento" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition">
                    <!-- Default empty option -->
                    <option value="">Seleccione...</option>
                    <!-- Document type options (value = database ID) -->
                    <option value="1">Cédula de Ciudadanía</option>
                    <option value="2">Tarjeta de Identidad</option>
                    <option value="3">Cédula de Extranjería</option>
                    <option value="4">Registro Civil</option>
                    <option value="5">Permiso Especial de Permanencia</option>
                    <option value="6">NIT</option>
                </select>
            </div>

            <!-- Document number field -->
            <div>
                <!-- Label with required asterisk -->
                <label class="block text-gray-700 font-semibold mb-2">
                    Número de Documento <span class="text-red-500">*</span>
                </label>
                <!-- Text input for document number -->
                <input type="text" name="numero_documento" id="numeroDocumento" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                       placeholder="Ej: 1234567890">
            </div>

            <!-- Sacrament type field -->
            <div>
                <!-- Label with required asterisk -->
                <label class="block text-gray-700 font-semibold mb-2">
                    Tipo de Sacramento <span class="text-red-500">*</span>
                </label>
                <!-- Select dropdown for sacrament type -->
                <select name="tipo_sacramento_id" id="tipoSacramento" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition">
                    <!-- Default empty option -->
                    <option value="">Seleccione...</option>
                    <!-- Sacrament type options (value = database ID) -->
                    <option value="1">Bautismo</option>
                    <option value="2">Confirmación</option>
                    <option value="3">Defunción</option>
                    <option value="4">Matrimonio</option>
                </select>
            </div>

            <!-- Cash payment checkbox section -->
            <div class="bg-[#F5F0EB] border border-[#DFD3C3] rounded-lg p-4">
                <!-- Flex container for checkbox and label -->
                <div class="flex items-start">
                    <!-- Checkbox for cash payment option -->
                    <input type="checkbox" name="metodo_pago" id="pagoEfectivo" value="efectivo"
                           class="w-5 h-5 text-[#ab876f] rounded focus:ring-2 focus:ring-[#C4A68A] mt-0.5">
                    <!-- Label with title and description -->
                    <label for="pagoEfectivo" class="ml-3">
                        <span class="font-semibold text-gray-900 block">Pago en efectivo</span>
                        <span class="text-sm text-gray-600">Generar PDF inmediatamente sin requerir pago online</span>
                    </label>
                </div>
            </div>

            <!-- Form action buttons -->
            <div class="flex gap-3 pt-4">
                <!-- Submit button to generate certificate -->
                <button type="submit"
                        class="flex-1 bg-[#D0B8A8] hover:bg-[#ab876f] text-white px-6 py-3 rounded-lg shadow-md font-semibold transition duration-200">
                    <!-- Checkmark icon SVG -->
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Generar Certificado
                </button>
                <!-- Cancel button to close modal -->
                <button type="button" id="btnCancelar"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow font-semibold transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include DataTables library for advanced table features -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<!-- Include DataTables CSS styles -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<!-- Include SweetAlert2 for beautiful alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Wait for DOM to be fully loaded before executing JavaScript
$(document).ready(function() {
    // Initialize DataTables on the certificates table (OOP: calling method on jQuery object)
    const table = $('#tablaCertificados').DataTable({
        // AJAX configuration to fetch data from server
        ajax: '<?= url('certificados/listar-todos') ?>',
        // Define table columns and their data sources
        columns: [
            // Column 1: Certificate ID
            { data: 'id' },
            // Column 2: Sacrament type name
            { data: 'tipo_sacramento' },
            // Column 3: Parishioner info with custom rendering
            {
                data: null, // Use multiple data fields
                render: function(data) { // Custom render function
                    // Return HTML with name and document info
                    return data.nombre_feligres + '<br><small class="text-gray-500">' + data.tipo_documento + ': ' + data.numero_documento + '</small>';
                }
            },
            // Column 4: Document number
            { data: 'numero_documento' },
            // Column 5: Requester name with fallback
            {
                data: null,
                render: function(data) {
                    // Show requester name or placeholder if empty
                    return data.solicitante_nombre || '<span class="text-gray-400">Sin solicitante</span>';
                }
            },
            // Column 6: Status with colored badges
            {
                data: 'estado',
                render: function(estado) { // Custom render for status badges
                    // Object with badge HTML for each status (OOP-like: object as data structure)
                    const badges = {
                        'pendiente_pago': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente Pago</span>',
                        'pagado': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Pagado</span>',
                        'generado': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Generado</span>',
                        'entregado': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#E8DFD5] text-[#8B6F47]">Entregado</span>'
                    };
                    // Return badge HTML or plain text if status not found
                    return badges[estado] || estado;
                }
            },
            // Column 7: Request date formatted
            {
                data: 'fecha_solicitud',
                render: function(fecha) {
                    // Format date to Colombian locale with time
                    return new Date(fecha).toLocaleString('es-CO');
                }
            },
            // Column 8: Action buttons
            {
                data: null,
                render: function(data) {
                    // Start building HTML string for buttons
                    let html = '<div class="flex gap-2 justify-center">';

                    // Show "Receive Payment" button only if status is pending payment
                    if (data.estado === 'pendiente_pago') {
                         // Button to register cash payment (calls global function)
                         html += `<button onclick="registrarPago(${data.id}, ${data.precio})" 
                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-medium transition flex items-center"
                                    title="Recibir Pago en Efectivo">
                                    <span class="material-icons text-sm mr-1">payments</span> Recibir Pago
                                </button>`;
                    }

                    // Show download button if PDF file exists
                    if (data.ruta_archivo) {
                        // Build download URL with query parameters
                        let downloadUrl = "<?= url('certificados/descargar') ?>";
                        // Check if URL already has parameters
                        let separator = downloadUrl.includes('?') ? '&' : '?';
                        // Add download link button
                        html += `<a href="${downloadUrl}${separator}id=${data.id}"
                                    class="px-3 py-1.5 bg-[#D0B8A8] hover:bg-[#ab876f] text-white rounded text-sm font-medium transition"
                                    title="Descargar PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>`;
                    } else if (data.estado !== 'pendiente_pago') {
                         // Show regenerate button if PDF missing and not pending payment
                         html += `<div class="flex flex-col items-center gap-1">
                                    <span class="text-xs text-red-400 font-medium">PDF no generado</span>
                                    <button onclick="regenerarPDF(${data.id})" 
                                            class="px-2 py-1 bg-amber-100 hover:bg-amber-200 text-amber-800 rounded text-xs font-semibold transition flex items-center gap-1"
                                            title="Intentar regenerar el archivo PDF">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        Regenerar
                                    </button>
                                </div>`;
                    }

                    // Close the flex container div
                    html += '</div>';
                    return html;
                },
                // Center align this column
                className: 'text-center'
            }
        ],
        // Spanish language configuration for DataTables interface
        language: {
            "decimal": ",", // Decimal separator
            "thousands": ".", // Thousands separator
            "info": "Mostrando _START_ a _END_ de _TOTAL_ certificados", // Info text
            "infoEmpty": "Mostrando 0 a 0 de 0 certificados", // Empty table text
            "infoFiltered": "(filtrado de _MAX_ certificados totales)", // Filtered text
            "infoPostFix": "", // Text after info
            "lengthMenu": "Mostrar _MENU_ certificados", // Length menu text
            "loadingRecords": "Cargando...", // Loading text
            "processing": "Procesando...", // Processing text
            "search": "Buscar:", // Search label
            "zeroRecords": "No se encontraron certificados", // No records text
            // Pagination button texts
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            // Accessibility labels
            "aria": {
                "sortAscending": ": activar para ordenar la columna de manera ascendente",
                "sortDescending": ": activar para ordenar la columna de manera descendente"
            }
        },
        // Default sorting: column 0 (ID) descending
        order: [[0, 'desc']],
        // Show 25 rows per page by default
        pageLength: 25
    });

    // Define global function to register cash payment
    window.registrarPago = function(id, precio) {
        // Format price to Colombian currency
        const precioFormateado = new Intl.NumberFormat('es-CO', { 
            style: 'currency', 
            currency: 'COP',
            minimumFractionDigits: 0
        }).format(precio);

        // Show confirmation dialog with formatted price
        Swal.fire({
            title: 'Registrar Pago en Efectivo', // Dialog title
            html: `
                <p class="mb-4">¿Confirmas recibir el pago por este certificado?</p>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <span class="text-gray-600">Valor a cobrar:</span>
                    <div class="text-3xl font-bold text-green-600 mt-1">${precioFormateado}</div>
                </div>
            `,
            icon: 'question', // Question icon
            showCancelButton: true, // Show cancel button
            confirmButtonText: 'Sí, recibir pago', // Confirm button text
            cancelButtonText: 'Cancelar', // Cancel button text
            confirmButtonColor: '#10B981', // Green confirm button
            cancelButtonColor: '#6B7280' // Gray cancel button
        }).then((result) => { // Handle user's choice
            if (result.isConfirmed) { // If user confirmed
                // Show loading state
                Swal.fire({
                    title: 'Procesando...',
                    didOpen: () => Swal.showLoading() // Show spinner
                });

                // Send AJAX request to register payment
                $.ajax({
                    url: '<?= url('pagos/registrar-efectivo') ?>', // Endpoint URL
                    method: 'POST', // HTTP method
                    data: { // Data to send
                        certificado_id: id,
                        monto: precio
                    },
                    dataType: 'json', // Expected response type
                    success: function(response) { // Success callback (OOP: callback function)
                        if (response.success) { // If server returned success
                            // Show success message
                            Swal.fire('¡Pago registrado!', response.message, 'success');
                            // Reload table data to show updated status
                            table.ajax.reload();
                        } else { // If server returned error
                            // Show error message
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() { // Error callback
                        // Show generic error message
                        Swal.fire('Error', 'No se pudo registrar el pago', 'error');
                    }
                });
            }
        });
    };

    // Define global function to regenerate PDF
    window.regenerarPDF = function(id) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Regenerar Certificado',
            text: "¿Desea intentar generar nuevamente el archivo PDF?",
            icon: 'warning', // Warning icon
            showCancelButton: true,
            confirmButtonColor: '#D0B8A8', // Custom color
            cancelButtonColor: '#d33', // Red cancel
            confirmButtonText: 'Sí, regenerar'
        }).then((result) => { // Handle user's choice
            if (result.isConfirmed) { // If user confirmed
                // Show loading state
                Swal.fire({
                    title: 'Regenerando...',
                    didOpen: () => Swal.showLoading()
                });

                // Send AJAX request to regenerate PDF
                $.ajax({
                    url: '<?= url('certificados/regenerar') ?>', // Endpoint URL
                    method: 'POST',
                    data: { id: id }, // Send certificate ID
                    success: function(response) { // Success callback
                        if (response.success) {
                            // Show success message
                            Swal.fire('¡Éxito!', response.message, 'success');
                            // Reload table to show updated data
                            table.ajax.reload();
                        } else {
                            // Show error message from server
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() { // Error callback
                        // Show connection error message
                        Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                    }
                });
            }
        });
    };

    // Modal controls - Open modal button click handler
    $('#btnAbrirModal').click(function() {
        // Remove hidden class to show modal
        $('#modalCertificado').removeClass('hidden');
        // Prevent body scrolling when modal is open
        $('body').addClass('overflow-hidden');
    });

    // Function to close modal and reset state
    function cerrarModal() {
        // Hide modal by adding hidden class
        $('#modalCertificado').addClass('hidden');
        // Re-enable body scrolling
        $('body').removeClass('overflow-hidden');
        // Reset form to empty all fields
        $('#formCertificado')[0].reset();
    }

    // Attach close function to close and cancel buttons
    $('#btnCerrarModal, #btnCancelar').click(cerrarModal);

    // Close modal when clicking outside (on backdrop)
    $('#modalCertificado').click(function(e) {
        // Check if clicked element is the modal backdrop itself
        if (e.target.id === 'modalCertificado') {
            cerrarModal();
        }
    });

    // Close modal with ESC key
    $(document).keydown(function(e) {
        // Check if ESC key pressed and modal is visible
        if (e.key === 'Escape' && !$('#modalCertificado').hasClass('hidden')) {
            cerrarModal();
        }
    });

    // Handle form submission via AJAX
    $('#formCertificado').on('submit', function(e) {
        // Prevent default form submission (page reload)
        e.preventDefault();

        // Create FormData object from form (handles file uploads too)
        const formData = new FormData(this);

        // Show loading alert while processing
        Swal.fire({
            title: 'Generando certificado...',
            text: 'Por favor espere',
            allowOutsideClick: false, // Prevent closing by clicking outside
            showConfirmButton: false, // Hide OK button
            willOpen: () => {
                Swal.showLoading(); // Show spinner
            }
        });

        // Send AJAX request to generate certificate
        $.ajax({
            url: '<?= url('certificados/generar-simplificado') ?>', // Endpoint URL
            type: 'POST', // HTTP method
            data: formData, // Form data to send
            processData: false, // Don't process FormData (required for file uploads)
            contentType: false, // Don't set content type (FormData sets it automatically)
            dataType: 'json', // Expected response format
            success: function(response) { // Success callback
                // Close loading alert
                Swal.close();

                if (response.success) { // If server returned success
                    // Show success toast notification (non-blocking)
                    Swal.fire({
                        toast: true, // Toast mode
                        position: 'top-end', // Position in top-right corner
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false, // No button
                        timer: 3000 // Auto-close after 3 seconds
                    });

                    // Close modal and reset form
                    cerrarModal();

                    // Reload DataTables to show new certificate
                    table.ajax.reload();

                    // If PDF was generated with cash payment, show additional message
                    if (response.pdf_generado) {
                        // Wait 500ms before showing next alert
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'PDF generado',
                                text: 'El certificado ha sido generado exitosamente.',
                                confirmButtonColor: '#D0B8A8'
                            });
                        }, 500);
                    }
                } else { // If server returned error
                    // Show error alert with server message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#D0B8A8'
                    });
                }
            },
            error: function(xhr, status, error) { // Error callback
                // Close loading alert
                Swal.close();
                // Show connection error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Intente nuevamente.',
                    confirmButtonColor: '#D0B8A8'
                });
            }
        });
    });
});
</script>

<?php 
// Include the bottom template component (footer, closing tags, etc.)
include_once __DIR__ . '/componentes/plantillaBottom.php'; 
?>