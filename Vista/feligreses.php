<?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Gestión de Feligreses</h1>
            <p class="text-gray-600 mt-2">Administra el registro de feligreses de la parroquia</p>
        </div>

        <button id="addFeligres"
                class="px-6 py-3 bg-[#D0B8A8] text-white rounded-lg shadow-md hover:bg-[#ab876f] transition duration-200 font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Agregar Feligrés
        </button>
    </div>

    <!-- Mensajes de sesión -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-6 px-6 py-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 px-6 py-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Tabla de Feligreses (DataTables) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#F5F0EB] to-[#E8DFD5] border-b border-[#DFD3C3]">
            <h2 class="text-xl font-semibold text-gray-800">Registro de Feligreses</h2>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="tableFeligreses" class="table-auto w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nombre Completo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tipo Documento</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Número Documento</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Teléfono</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Dirección</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar/editar feligrés -->
<div id="feligresModal" class="modal fixed inset-0 bg-black/50 bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden">


    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
        <!-- Header del Modal -->
        <div class="px-6 py-4 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] border-b border-[#8B6F47]">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white" id="modalTitle">Agregar Feligrés</h2>
                <button type="button" id="btnCancelar" class="text-white hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-white text-sm mt-2">Complete la información del feligrés</p>
        </div>

        <!-- Formulario -->
        <div class="overflow-y-auto max-h-[calc(90vh-100px)]">
            <form id="feligresForm" class="p-6 space-y-5" method="POST">
                <input type="hidden" name="feligres-id" id="feligresId" value="">

                <!-- Tipo y Número de Documento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Tipo Documento <span class="text-red-500">*</span>
                        </label>
                        <select name="tipo-doc" id="tipoDoc" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition">
                            <option value="">Seleccione...</option>
                            <option value="1">Cédula de Ciudadanía</option>
                            <option value="2">Tarjeta de Identidad</option>
                            <option value="3">Cédula de Extranjería</option>
                            <option value="4">Registro Civil</option>
                            <option value="5">Permiso Especial de Permanencia</option>
                            <option value="6">NIT</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Número Documento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="documento" id="documento" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: 1234567890">
                    </div>
                </div>

                <!-- Nombres -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Primer Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="primer-nombre" id="primerNombre" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Juan">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Segundo Nombre
                        </label>
                        <input type="text" name="segundo-nombre" id="segundoNombre"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Carlos">
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Primer Apellido <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="primer-apellido" id="primerApellido" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Pérez">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Segundo Apellido
                        </label>
                        <input type="text" name="segundo-apellido" id="segundoApellido"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Gómez">
                    </div>
                </div>

                <!-- Teléfono y Dirección -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Teléfono
                        </label>
                        <input type="text" name="telefono" id="telefono"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: 3001234567">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            Dirección <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="direccion" id="direccion" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                               placeholder="Ej: Calle 123 #45-67">
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex gap-3 pt-4">
                    <button type="submit" id="btnGuardar"
                            class="flex-1 bg-[#D0B8A8] hover:bg-[#ab876f] text-white px-6 py-3 rounded-lg shadow-md font-semibold transition duration-200">
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

<script>
$(document).ready(function() {

    // Inicializar DataTables
    const table = new DataTable('#tableFeligreses', {
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        order: [[0, 'desc']],
        language: {
            lengthMenu: "Mostrar _MENU_ registros",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            sSearch: "Buscar: ",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            },
            sProcessing: "Procesando...",
        },
        ajax: {
            url: "?route=feligreses/listar",
            type: "POST",
            dataType: "json"
        },
        columns: [
            { data: 'id', title: 'ID', width: "50px" },
            { data: 'nombre_completo', title: 'Nombre Completo' },
            { data: 'tipo_documento', title: 'Tipo Documento' },
            { data: 'numero_documento', title: 'Número Documento' },
            { data: 'telefono', title: 'Teléfono' },
            { data: 'direccion', title: 'Dirección' },
            {
                data: null,
                title: 'Acciones',
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
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
        pageLength: 10
    });

    // Abrir modal para agregar
    $('#addFeligres').on('click', function() {
        $('#modalTitle').text('Agregar Feligrés');
        $('#feligresForm')[0].reset();
        $('#feligresId').val('');
        $('#feligresModal').removeClass('hidden');
        $('#primerNombre').focus();
    });

    // Cerrar modal
    $('#btnCancelar').on('click', function() {
        $('#feligresModal').addClass('hidden');
    });

    // Cerrar modal con ESC
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && !$('#feligresModal').hasClass('hidden')) {
            $('#feligresModal').addClass('hidden');
        }
    });

    // Botón editar
    $(document).on('click', '.btn-editar', function() {
        const id = $(this).data('id');
        const row = table.row($(this).closest('tr')).data();

        // Llenar formulario con datos de la fila
        $('#modalTitle').text('Editar Feligrés');
        $('#feligresId').val(row.id);
        $('#tipoDoc').val(row.tipo_documento_id || '');
        $('#documento').val(row.numero_documento || '');
        $('#primerNombre').val(row.primer_nombre || '');
        $('#segundoNombre').val(row.segundo_nombre || '');
        $('#primerApellido').val(row.primer_apellido || '');
        $('#segundoApellido').val(row.segundo_apellido || '');
        $('#telefono').val(row.telefono || '');
        $('#direccion').val(row.direccion || '');

        $('#feligresModal').removeClass('hidden');
        $('#primerNombre').focus();
    });

    // Submit del formulario
    $(document).on('submit', '#feligresForm', function(event) {
        event.preventDefault();

        const feligresId = $('#feligresId').val();
        const isEdit = feligresId !== '';
        const url = isEdit ? `?route=feligreses/editar&id=${feligresId}` : "?route=feligreses/crear";
        const formData = $(this).serialize();

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function() {
                $('#btnGuardar').prop('disabled', true).html('<svg class="w-5 h-5 mr-2 inline animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Guardando...');
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#feligresForm')[0].reset();
                    $('#feligresModal').addClass('hidden');

                    table.ajax.reload(null, false);

                    Swal.fire({
                        icon: 'success',
                        title: 'Completado',
                        text: response.message || 'Feligrés guardado correctamente',
                        timer: 2000,
                        showConfirmButton: false,
                        confirmButtonColor: '#D0B8A8'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error al guardar el feligrés',
                        confirmButtonColor: '#D0B8A8'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', {xhr, status, error});

                let mensaje = 'Error al guardar el feligrés';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje,
                    confirmButtonColor: '#D0B8A8'
                });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).html('<svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Guardar');
            }
        });
    });

    // Botón eliminar
    $(document).on('click', '.btn-eliminar', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `?route=feligreses/eliminar&id=${id}`;
            }
        });
    });

});
</script>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
