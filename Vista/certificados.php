<?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Gestión de Certificados</h1>
            <p class="text-gray-600 mt-2">Administra y genera certificados sacramentales</p>
        </div>

        <button id="btnAbrirModal"
                class="px-6 py-3 bg-[#D0B8A8] text-white rounded-lg shadow-md hover:bg-[#ab876f] transition duration-200 font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nuevo Certificado
        </button>
    </div>

    <!-- Historial de Certificados (DataTables) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#F5F0EB] to-[#E8DFD5] border-b border-[#DFD3C3]">
            <h2 class="text-xl font-semibold text-gray-800">Historial de Certificados</h2>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="tablaCertificados" class="table-auto w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tipo Sacramento</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Feligrés</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Documento</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Solicitante</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Fecha Solicitud</th>
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

<!-- Modal para Generar Certificado -->
<div id="modalCertificado" class="hidden fixed inset-0 bg-black/50  backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header del Modal -->
        <div class="px-6 py-4 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] border-b border-[#8B6F47]">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white">Generar Certificado Sacramental</h2>
                <button id="btnCerrarModal" class="text-white hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-white text-sm mt-2">Ingrese los datos del feligrés para buscar el sacramento</p>
        </div>

        <!-- Formulario -->
        <form id="formCertificado" class="p-6 space-y-5">
            <!-- Tipo de Documento -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    Tipo de Documento <span class="text-red-500">*</span>
                </label>
                <select name="tipo_documento_id" id="tipoDocumento" required
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

            <!-- Número de Documento -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    Número de Documento <span class="text-red-500">*</span>
                </label>
                <input type="text" name="numero_documento" id="numeroDocumento" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition"
                       placeholder="Ej: 1234567890">
            </div>

            <!-- Tipo de Sacramento -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    Tipo de Sacramento <span class="text-red-500">*</span>
                </label>
                <select name="tipo_sacramento_id" id="tipoSacramento" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition">
                    <option value="">Seleccione...</option>
                    <option value="1">Bautismo</option>
                    <option value="2">Confirmación</option>
                    <option value="3">Defunción</option>
                    <option value="4">Matrimonio</option>
                </select>
            </div>

            <!-- Pago en Efectivo -->
            <div class="bg-[#F5F0EB] border border-[#DFD3C3] rounded-lg p-4">
                <div class="flex items-start">
                    <input type="checkbox" name="metodo_pago" id="pagoEfectivo" value="efectivo"
                           class="w-5 h-5 text-[#ab876f] rounded focus:ring-2 focus:ring-[#C4A68A] mt-0.5">
                    <label for="pagoEfectivo" class="ml-3">
                        <span class="font-semibold text-gray-900 block">Pago en efectivo</span>
                        <span class="text-sm text-gray-600">Generar PDF inmediatamente sin requerir pago online</span>
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-3 pt-4">
                <button type="submit"
                        class="flex-1 bg-[#D0B8A8] hover:bg-[#ab876f] text-white px-6 py-3 rounded-lg shadow-md font-semibold transition duration-200">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Generar Certificado
                </button>
                <button type="button" id="btnCancelar"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow font-semibold transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Inicializar DataTables
    const table = $('#tablaCertificados').DataTable({
        ajax: '<?= url('certificados/listar-todos') ?>',
        columns: [
            { data: 'id' },
            { data: 'tipo_sacramento' },
            {
                data: null,
                render: function(data) {
                    return data.nombre_feligres + '<br><small class="text-gray-500">' + data.tipo_documento + ': ' + data.numero_documento + '</small>';
                }
            },
            { data: 'numero_documento' },
            {
                data: null,
                render: function(data) {
                    return data.solicitante_nombre || '<span class="text-gray-400">Sin solicitante</span>';
                }
            },
            {
                data: 'estado',
                render: function(estado) {
                    const badges = {
                        'pendiente_pago': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente Pago</span>',
                        'pagado': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Pagado</span>',
                        'generado': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Generado</span>',
                        'entregado': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#E8DFD5] text-[#8B6F47]">Entregado</span>'
                    };
                    return badges[estado] || estado;
                }
            },
            {
                data: 'fecha_solicitud',
                render: function(fecha) {
                    return new Date(fecha).toLocaleString('es-CO');
                }
            },
            {
                data: null,
                render: function(data) {
                    let html = '<div class="flex gap-2 justify-center">';

                    // Botón de Pagar (Solo si está pendiente)
                    if (data.estado === 'pendiente_pago') {
                         html += `<button onclick="registrarPago(${data.id}, ${data.precio})" 
                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-medium transition flex items-center"
                                    title="Recibir Pago en Efectivo">
                                    <span class="material-icons text-sm mr-1">payments</span> Recibir Pago
                                </button>`;
                    }

                    if (data.ruta_archivo) {
                        html += `<a href="<?= url('certificados/descargar') ?>?id=${data.id}"
                                    class="px-3 py-1.5 bg-[#D0B8A8] hover:bg-[#ab876f] text-white rounded text-sm font-medium transition"
                                    title="Descargar PDF">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>`;
                    } else if (data.estado !== 'pendiente_pago') {
                        html += '<span class="text-xs text-gray-400">PDF no generado</span>';
                    }

                    html += '</div>';
                    return html;
                },
                className: 'text-center'
            }
        ],
        language: {
            "decimal": ",",
            "thousands": ".",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ certificados",
            "infoEmpty": "Mostrando 0 a 0 de 0 certificados",
            "infoFiltered": "(filtrado de _MAX_ certificados totales)",
            "infoPostFix": "",
            "lengthMenu": "Mostrar _MENU_ certificados",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron certificados",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "aria": {
                "sortAscending": ": activar para ordenar la columna de manera ascendente",
                "sortDescending": ": activar para ordenar la columna de manera descendente"
            }
        },
        order: [[0, 'desc']],
        pageLength: 25
    });

    // Función Global para registrar pago
    window.registrarPago = function(id, precio) {
        // Formatear precio para mostrar
        const precioFormateado = new Intl.NumberFormat('es-CO', { 
            style: 'currency', 
            currency: 'COP',
            minimumFractionDigits: 0
        }).format(precio);

        Swal.fire({
            title: 'Registrar Pago en Efectivo',
            html: `
                <p class="mb-4">¿Confirmas recibir el pago por este certificado?</p>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <span class="text-gray-600">Valor a cobrar:</span>
                    <div class="text-3xl font-bold text-green-600 mt-1">${precioFormateado}</div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, recibir pago',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar cargando
                Swal.fire({
                    title: 'Procesando...',
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: '<?= url('pagos/registrar-efectivo') ?>',
                    method: 'POST',
                    data: {
                        certificado_id: id,
                        monto: precio
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('¡Pago registrado!', response.message, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo registrar el pago', 'error');
                    }
                });
            }
        });
    };

    // Controles del Modal
    $('#btnAbrirModal').click(function() {
        $('#modalCertificado').removeClass('hidden');
        $('body').addClass('overflow-hidden');
    });

    function cerrarModal() {
        $('#modalCertificado').addClass('hidden');
        $('body').removeClass('overflow-hidden');
        $('#formCertificado')[0].reset();
    }

    $('#btnCerrarModal, #btnCancelar').click(cerrarModal);

    // Cerrar al hacer clic fuera del modal
    $('#modalCertificado').click(function(e) {
        if (e.target.id === 'modalCertificado') {
            cerrarModal();
        }
    });

    // Cerrar con tecla ESC
    $(document).keydown(function(e) {
        if (e.key === 'Escape' && !$('#modalCertificado').hasClass('hidden')) {
            cerrarModal();
        }
    });

    // Manejar envío del formulario (AJAX)
    $('#formCertificado').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Mostrar loading
        Swal.fire({
            title: 'Generando certificado...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?= url('certificados/generar-simplificado') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                Swal.close();

                if (response.success) {
                    // Mostrar éxito
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Cerrar modal y limpiar formulario
                    cerrarModal();

                    // Recargar DataTables
                    table.ajax.reload();

                    // Si se generó PDF con pago efectivo, mostrar mensaje adicional
                    if (response.pdf_generado) {
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'PDF generado',
                                text: 'El certificado ha sido generado exitosamente.',
                                confirmButtonColor: '#D0B8A8'
                            });
                        }, 500);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#D0B8A8'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
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

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
