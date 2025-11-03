<?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

<!-- Contenedor principal -->
<div class="min-h-[500px] px-4 py-8 flex-1">
    <div class="max-w-7xl mx-auto bg-white/60 rounded p-5">

        <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">Gestión de Feligreses</h2>

        <!-- Mensajes de sesión -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium"></h3>

            <button id="addFeligres" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                + Agregar Feligrés
            </button>
        </div>

        <!-- Tabla -->
        <div class="overflow-auto">
            <table id="tableFeligreses" class="min-w-full text-sm text-left text-gray-700 border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Tipo Documento</th>
                        <th>Número Documento</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

<!-- Modal para agregar/editar feligrés -->
<div id="feligresModal" class="modal fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 max-h-[90vh] flex flex-col">
        <h4 class="text-xl font-semibold mb-4 text-center" id="modalTitle">Agregar Feligrés</h4>

        <!-- Contenedor con scroll -->
        <div class="overflow-y-auto flex-1">
            <form id="feligresForm" class="space-y-4" method="POST">
                <input type="hidden" name="feligres-id" id="feligresId" value="">

                <!-- Tipo y Número de Documento -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo Documento <span class="text-red-500">*</span>
                        </label>
                        <select name="tipo-doc" id="tipoDoc" required
                                class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Seleccione...</option>
                            <option value="1">Cédula Ciudadanía</option>
                            <option value="2">Tarjeta Identidad</option>
                            <option value="3">Cédula Extranjera</option>
                            <option value="4">Registro Civil</option>
                            <option value="5">Permiso Especial</option>
                            <option value="6">NIT</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Número Documento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="documento" id="documento" required
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               placeholder="Ej: 1234567890">
                    </div>
                </div>

                <!-- Nombres -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Primer Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="primer-nombre" id="primerNombre" required
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               placeholder="Ej: Juan">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Segundo Nombre
                        </label>
                        <input type="text" name="segundo-nombre" id="segundoNombre"
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               placeholder="Ej: Carlos">
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Primer Apellido <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="primer-apellido" id="primerApellido" required
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               placeholder="Ej: Pérez">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Segundo Apellido
                        </label>
                        <input type="text" name="segundo-apellido" id="segundoApellido"
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               placeholder="Ej: Gómez">
                    </div>
                </div>

                <!-- Teléfono y Dirección -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Teléfono
                        </label>
                        <input type="text" name="telefono" id="telefono"
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               placeholder="Ej: 3001234567">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Dirección <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="direccion" id="direccion" required
                               class="w-full border border-gray-300 rounded px-3 py-2"
                               placeholder="Ej: Calle 123 #45-67">
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-2 pt-4 border-t">
                    <button type="button" id="btnCancelar"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Cancelar
                    </button>
                    <button type="submit" id="btnGuardar"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
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
                        <button class="btn-editar bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs mr-1"
                                data-id="${row.id}"
                                title="Editar feligrés">
                            ✏️ Editar
                        </button>
                        <?php if ($_SESSION['user-rol'] === 'Administrador'): ?>
                        <button class="btn-eliminar bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs"
                                data-id="${row.id}"
                                title="Eliminar feligrés">
                            🗑️ Eliminar
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
    });

    // Cerrar modal
    $('#btnCancelar').on('click', function() {
        $('#feligresModal').addClass('hidden');
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
                $('#btnGuardar').prop('disabled', true).text('Guardando...');
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
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error al guardar el feligrés'
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
                    text: mensaje
                });
            },
            complete: function() {
                $('#btnGuardar').prop('disabled', false).text('Guardar');
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
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
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
