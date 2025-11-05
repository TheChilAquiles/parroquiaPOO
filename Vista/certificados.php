<?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Gestión de Certificados</h1>

    <!-- Formulario Simplificado (3 campos) -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Generar Certificado Sacramental</h2>
        <p class="text-gray-600 mb-6 text-sm">Ingrese los datos del feligrés y el sistema buscará automáticamente el sacramento correspondiente.</p>

        <form id="formCertificado" class="space-y-4">
            <!-- Tipo de Documento -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tipo de Documento:</label>
                <select name="tipo_documento_id" id="tipoDocumento" required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
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
                <label class="block text-gray-700 font-semibold mb-2">Número de Documento:</label>
                <input type="text" name="numero_documento" id="numeroDocumento" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                       placeholder="Ej: 1234567890">
            </div>

            <!-- Tipo de Sacramento -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Tipo de Sacramento:</label>
                <select name="tipo_sacramento_id" id="tipoSacramento" required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">Seleccione...</option>
                    <option value="1">Bautismo</option>
                    <option value="2">Confirmación</option>
                    <option value="3">Defunción</option>
                    <option value="4">Matrimonio</option>
                </select>
            </div>

            <!-- Pago en Efectivo (opcional) -->
            <div class="flex items-center">
                <input type="checkbox" name="metodo_pago" id="pagoEfectivo" value="efectivo"
                       class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-400">
                <label for="pagoEfectivo" class="ml-2 text-gray-700">
                    <span class="font-semibold">Pago en efectivo</span>
                    <span class="text-sm text-gray-500">(Generar PDF inmediatamente)</span>
                </label>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 pt-4">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow font-semibold transition">
                    Generar Certificado
                </button>
                <button type="reset"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow transition">
                    Limpiar
                </button>
            </div>
        </form>
    </div>

    <!-- Historial de Certificados (DataTables) -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Historial de Certificados</h2>

        <div class="overflow-x-auto">
            <table id="tablaCertificados" class="table-auto w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Tipo Sacramento</th>
                        <th class="px-4 py-2 text-left">Feligrés</th>
                        <th class="px-4 py-2 text-left">Documento</th>
                        <th class="px-4 py-2 text-left">Solicitante</th>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-left">Fecha Solicitud</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTables populate this -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializar DataTables
    const table = $('#tablaCertificados').DataTable({
        ajax: {
            url: '?route=certificados/listar-todos',
            dataSrc: function(json) {
                if (json.success) {
                    return json.data;
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error al cargar certificados',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return [];
                }
            }
        },
        columns: [
            { data: 'id' },
            { data: 'tipo_sacramento' },
            { data: 'feligres_nombre' },
            { data: 'numero_documento' },
            { data: 'solicitante_nombre' },
            {
                data: 'estado',
                render: function(data) {
                    const badges = {
                        'Pendiente pago': 'bg-yellow-100 text-yellow-800',
                        'Generado': 'bg-green-100 text-green-800',
                        'Descargado': 'bg-blue-100 text-blue-800',
                        'Expirado': 'bg-red-100 text-red-800'
                    };
                    const badge = badges[data] || 'bg-gray-100 text-gray-800';
                    return `<span class="px-2 py-1 rounded text-xs font-semibold ${badge}">${data}</span>`;
                }
            },
            { data: 'fecha_solicitud' },
            {
                data: null,
                render: function(data, type, row) {
                    let html = '';

                    // Botón descargar (solo si PDF generado)
                    if (row.ruta_archivo) {
                        html += `<a href="${row.ruta_archivo}" target="_blank"
                                   class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm mr-2">
                                   Descargar PDF
                                </a>`;
                    } else {
                        html += `<span class="text-gray-400 text-sm">PDF no disponible</span>`;
                    }

                    return html;
                },
                className: 'text-center'
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'desc']],
        pageLength: 25
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
            url: '?route=certificados/generar-simplificado',
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

                    // Limpiar formulario
                    $('#formCertificado')[0].reset();

                    // Recargar DataTables
                    table.ajax.reload();

                    // Si se generó PDF con pago efectivo, mostrar mensaje adicional
                    if (response.pdf_generado) {
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'info',
                                title: 'PDF Generado',
                                text: 'El certificado ha sido generado y está disponible en el historial',
                                confirmButtonText: 'Entendido'
                            });
                        }, 3000);
                    }
                } else {
                    // Mostrar error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'Entendido'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Intente nuevamente.',
                    confirmButtonText: 'Entendido'
                });
                console.error('Error AJAX:', error);
            }
        });
    });
});
</script>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
