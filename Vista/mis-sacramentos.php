<?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Mis Sacramentos</h1>
        <p class="text-gray-600">Aquí puedes ver los sacramentos en los que has participado y solicitar certificados.</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['info'])): ?>
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_SESSION['info'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['info']); ?>
    <?php endif; ?>

    <?php if (!empty($misSacramentos)): ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <h3 class="text-sm font-medium text-blue-800">Información sobre certificados</h3>
            <ul class="mt-2 text-sm text-blue-700 list-disc list-inside space-y-1">
                <li>Puedes solicitar un certificado de cualquiera de tus sacramentos</li>
                <li>Los certificados tienen validez de 30 días y requieren pago</li>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($misSacramentos)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($misSacramentos as $sacramento): ?>
                <?php
                $tipo = $sacramento['tipo_sacramento'] ?? 'Desconocido';
                $colores = [
                    'Bautizo' => 'border-blue-400 bg-blue-50',
                    'Confirmación' => 'border-purple-400 bg-purple-50',
                    'Matrimonio' => 'border-pink-400 bg-pink-50',
                    'Defunción' => 'border-gray-400 bg-gray-50'
                ];
                $colorClass = $colores[$tipo] ?? 'border-gray-400 bg-gray-50';
                ?>

                <div class="border-2 <?= $colorClass ?> rounded-lg shadow-md">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <?= htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8') ?>
                        </h3>
                        <p class="text-sm text-gray-600">
                            Libro: <?= htmlspecialchars($sacramento['libro_tipo'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                            #<?= htmlspecialchars($sacramento['libro_numero'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <p class="text-xs text-gray-500">
                            Tu rol: <?= htmlspecialchars($sacramento['mi_rol'] ?? 'Participante', ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    </div>

                    <div class="p-4 space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Fecha:</p>
                            <p class="font-medium text-gray-800">
                                <?= date('d/m/Y', strtotime($sacramento['fecha_generacion'] ?? 'now')) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">Participantes:</p>
                            <div class="text-xs text-gray-700 max-h-20 overflow-y-auto bg-white p-2 rounded border">
                                <?= htmlspecialchars($sacramento['participantes'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t border-gray-200">
                        <button
                            class="btn-solicitar w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded"
                            data-sacramento-id="<?= $sacramento['id'] ?>"
                            data-tipo-id="<?= $sacramento['tipo_sacramento_id'] ?>">
                            Solicitar Certificado
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900">No tienes sacramentos registrados</h3>
            <p class="mt-2 text-gray-500">Contacta con la secretaría parroquial para registrar tus sacramentos</p>
        </div>
    <?php endif; ?>
</div>

<div id="modalSolicitar" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h4 class="text-xl font-semibold mb-4">Solicitar Certificado</h4>
        <form id="formSolicitar" method="POST">
            <input type="hidden" name="sacramento_id" id="sacramento_id">
            <input type="hidden" name="tipo_sacramento_id" id="tipo_sacramento_id">

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">¿Para quién?</label>
                <label class="flex items-center p-3 border rounded mb-2 cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="para_quien" value="yo" class="mr-3" checked>
                    <span>Para mí</span>
                </label>
                <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="para_quien" value="familiar" class="mr-3">
                    <span>Para un familiar</span>
                </label>
            </div>

            <div id="selector-familiar" class="mb-4 hidden">
                <label for="familiar_id" class="block text-gray-700 font-medium mb-2">Selecciona el familiar:</label>
                <select name="familiar_id" id="familiar_id" class="w-full border border-gray-300 rounded p-2">
                    <option value="">-- Selecciona un familiar --</option>
                </select>
                <p class="text-xs text-gray-600 mt-1">
                    Si no ves a tu familiar, contacta con la secretaría para registrarlo
                </p>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="cerrarModal" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">
                    Cancelar
                </button>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Solicitar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).on('click', '.btn-solicitar', function() {
    const sacramentoId = $(this).data('sacramento-id');
    const tipoId = $(this).data('tipo-id');
    $('#sacramento_id').val(sacramentoId);
    $('#tipo_sacramento_id').val(tipoId);
    $('#modalSolicitar').removeClass('hidden');

    // Cargar familiares
    cargarFamiliares();
});

$(document).on('click', '#cerrarModal', function() {
    $('#modalSolicitar').addClass('hidden');
    $('#formSolicitar')[0].reset();
    $('#selector-familiar').addClass('hidden');
});

// Mostrar/ocultar selector de familiar
$('input[name="para_quien"]').on('change', function() {
    if ($(this).val() === 'familiar') {
        $('#selector-familiar').removeClass('hidden');
    } else {
        $('#selector-familiar').addClass('hidden');
    }
});

// Cargar familiares registrados (AJAX)
function cargarFamiliares() {
    $.ajax({
        url: '<?= url('certificados/obtener-familiares') ?>',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data) {
                const select = $('#familiar_id');
                select.html('<option value="">-- Selecciona un familiar --</option>');

                if (response.data.length === 0) {
                    select.html('<option value="">No tienes familiares registrados</option>');
                } else {
                    response.data.forEach(function(familiar) {
                        select.append(
                            `<option value="${familiar.familiar_id}">
                                ${familiar.nombre_completo} (${familiar.parentesco})
                            </option>`
                        );
                    });
                }
            }
        },
        error: function(xhr) {
            console.error('Error al cargar familiares:', xhr);
            $('#familiar_id').html('<option value="">Error al cargar familiares</option>');
        }
    });
}

$(document).on('submit', '#formSolicitar', function(e) {
    e.preventDefault();

    const paraQuien = $('input[name="para_quien"]:checked').val();

    // Validar selección de familiar si es necesario
    if (paraQuien === 'familiar') {
        const familiarId = $('#familiar_id').val();
        if (!familiarId) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona un familiar',
                text: 'Debes seleccionar a qué familiar le solicitarás el certificado'
            });
            return;
        }
    }

    const formData = $(this).serialize();

    $.ajax({
        url: '<?= url('certificados/solicitar-desde-sacramento') ?>',
        method: 'POST',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
            Swal.fire({
                title: 'Procesando...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        },
        success: function(response) {
            Swal.close();
            if (response.success) {
                $('#modalSolicitar').addClass('hidden');
                Swal.fire({
                    icon: 'success',
                    title: 'Solicitud exitosa',
                    text: response.message,
                    confirmButtonText: 'Ver mis certificados'
                }).then(() => {
                    window.location.href = '<?= url('certificados') ?>';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo procesar la solicitud'
            });
        }
    });
});
</script>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
