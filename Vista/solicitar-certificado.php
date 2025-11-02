<?php
// Vista/solicitar-certificado.php
include_once __DIR__ . '/componentes/plantillaTop.php';
?>

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Solicitar Certificado</h1>

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

    <div class="bg-white shadow-md rounded-lg p-6">
        <p class="text-gray-600 mb-6">
            Puede solicitar certificados sacramentales propios o de sus familiares registrados.
        </p>

        <form id="formSolicitarCertificado" method="POST" action="?route=certificados/crear" class="space-y-6">
            <!-- Tipo de solicitud -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Solicitud
                </label>
                <div class="space-y-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="tipo_solicitud" value="propio" checked
                               class="form-radio text-blue-600" onchange="cambiarTipoSolicitud()">
                        <span class="ml-2">Certificado Propio</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="tipo_solicitud" value="familiar"
                               class="form-radio text-blue-600" onchange="cambiarTipoSolicitud()">
                        <span class="ml-2">Certificado de Familiar</span>
                    </label>
                </div>
            </div>

            <!-- Selección de familiar (oculto por defecto) -->
            <div id="seccionFamiliar" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Seleccionar Familiar
                </label>
                <select name="familiar_id" id="familiarSelect" class="w-full border border-gray-300 rounded-md p-2"
                        onchange="cargarSacramentosFamiliar()">
                    <option value="">-- Seleccione un familiar --</option>
                    <?php if (!empty($familiares)): ?>
                        <?php foreach ($familiares as $familiar): ?>
                            <option value="<?= htmlspecialchars($familiar['familiar_id'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($familiar['nombre_completo'], ENT_QUOTES, 'UTF-8') ?>
                                (<?= htmlspecialchars($familiar['parentesco'], ENT_QUOTES, 'UTF-8') ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No tiene familiares registrados</option>
                    <?php endif; ?>
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    Si su familiar no aparece en la lista, debe registrar el parentesco en secretaría.
                </p>
            </div>

            <!-- Sacramento a solicitar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Sacramento *
                </label>
                <select name="sacramento_id" id="sacramentoSelect" required
                        class="w-full border border-gray-300 rounded-md p-2">
                    <option value="">-- Seleccione un sacramento --</option>
                    <!-- Se llenan dinámicamente según el tipo de solicitud -->
                    <?php if (!empty($sacramentosPropio)): ?>
                        <?php foreach ($sacramentosPropio as $sacramento): ?>
                            <option value="<?= htmlspecialchars($sacramento['id'], ENT_QUOTES, 'UTF-8') ?>"
                                    data-tipo="propio">
                                <?= htmlspecialchars($sacramento['tipo'], ENT_QUOTES, 'UTF-8') ?>
                                - <?= date('d/m/Y', strtotime($sacramento['fecha_generacion'])) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Motivo de solicitud -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Motivo de Solicitud (Opcional)
                </label>
                <textarea name="motivo" rows="3" class="w-full border border-gray-300 rounded-md p-2"
                          placeholder="Ej: Para trámites escolares, matrimonio, etc."
                          maxlength="500"></textarea>
            </div>

            <!-- Información adicional -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <h3 class="font-semibold text-blue-900 mb-2">Información Importante:</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• El costo del certificado es de <strong>$10.00</strong></li>
                    <li>• Una vez confirmado el pago, el certificado se generará automáticamente</li>
                    <li>• El certificado tendrá <strong>30 días de validez</strong> desde su generación</li>
                    <li>• Podrá descargarlo desde "Mis Certificados"</li>
                </ul>
            </div>

            <!-- Campo oculto para el feligrés del certificado -->
            <input type="hidden" name="feligres_certificado_id" id="feligresCertificadoId" value="<?= $feligresId ?>">

            <!-- Botones -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md">
                    Crear Solicitud y Proceder al Pago
                </button>
                <a href="?route=certificados/mis-solicitudes"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-md text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function cambiarTipoSolicitud() {
    const tipo = document.querySelector('input[name="tipo_solicitud"]:checked').value;
    const seccionFamiliar = document.getElementById('seccionFamiliar');
    const familiarSelect = document.getElementById('familiarSelect');
    const sacramentoSelect = document.getElementById('sacramentoSelect');
    const feligresCertificadoId = document.getElementById('feligresCertificadoId');

    if (tipo === 'familiar') {
        // Mostrar selección de familiar
        seccionFamiliar.classList.remove('hidden');
        familiarSelect.required = true;

        // Limpiar sacramentos (se cargarán al seleccionar familiar)
        sacramentoSelect.innerHTML = '<option value="">-- Primero seleccione un familiar --</option>';
    } else {
        // Ocultar selección de familiar
        seccionFamiliar.classList.add('hidden');
        familiarSelect.required = false;
        familiarSelect.value = '';

        // Restaurar sacramentos propios
        sacramentoSelect.innerHTML = '<option value="">-- Seleccione un sacramento --</option>';
        const opcionesPropio = Array.from(sacramentoSelect.querySelectorAll('[data-tipo="propio"]'));
        sacramentoSelect.innerHTML = '<option value="">-- Seleccione un sacramento --</option>';

        // Restaurar valor del feligrés (propio)
        feligresCertificadoId.value = '<?= $feligresId ?>';

        // Recargar sacramentos propios
        <?php if (!empty($sacramentosPropio)): ?>
            <?php foreach ($sacramentosPropio as $sacramento): ?>
                const option = document.createElement('option');
                option.value = '<?= htmlspecialchars($sacramento['id'], ENT_QUOTES, 'UTF-8') ?>';
                option.setAttribute('data-tipo', 'propio');
                option.textContent = '<?= htmlspecialchars($sacramento['tipo'], ENT_QUOTES, 'UTF-8') ?> - <?= date('d/m/Y', strtotime($sacramento['fecha_generacion'])) ?>';
                sacramentoSelect.appendChild(option);
            <?php endforeach; ?>
        <?php endif; ?>
    }
}

function cargarSacramentosFamiliar() {
    const familiarId = document.getElementById('familiarSelect').value;
    const sacramentoSelect = document.getElementById('sacramentoSelect');
    const feligresCertificadoId = document.getElementById('feligresCertificadoId');

    if (!familiarId) {
        sacramentoSelect.innerHTML = '<option value="">-- Primero seleccione un familiar --</option>';
        return;
    }

    // Actualizar el input oculto con el ID del familiar
    feligresCertificadoId.value = familiarId;

    // Cargar sacramentos del familiar via AJAX
    sacramentoSelect.innerHTML = '<option value="">Cargando...</option>';

    $.ajax({
        url: '?route=certificados/buscar-sacramentos-familiar',
        type: 'POST',
        data: { familiar_id: familiarId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                sacramentoSelect.innerHTML = '<option value="">-- Seleccione un sacramento --</option>';

                if (response.sacramentos && response.sacramentos.length > 0) {
                    response.sacramentos.forEach(function(sacramento) {
                        const option = document.createElement('option');
                        option.value = sacramento.id;
                        option.setAttribute('data-tipo', 'familiar');
                        option.textContent = sacramento.tipo + ' - ' +
                            new Date(sacramento.fecha_generacion).toLocaleDateString('es-ES');
                        sacramentoSelect.appendChild(option);
                    });
                } else {
                    sacramentoSelect.innerHTML = '<option value="">No tiene sacramentos registrados</option>';
                }
            } else {
                alert(response.message || 'Error al cargar sacramentos');
                sacramentoSelect.innerHTML = '<option value="">Error al cargar</option>';
            }
        },
        error: function() {
            alert('Error de conexión al cargar sacramentos');
            sacramentoSelect.innerHTML = '<option value="">Error de conexión</option>';
        }
    });
}
</script>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
