<?php
// Vista/pagar-certificado.php
include_once __DIR__ . '/componentes/plantillaTop.php';
?>

<div class="container mx-auto p-6 max-w-2xl">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Pagar Certificado</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Resumen de la solicitud -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Resumen de la Solicitud</h2>

        <div class="space-y-3">
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">Tipo de Certificado:</span>
                <span class="font-semibold"><?= htmlspecialchars($certificado['tipo_certificado'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">Fecha de Solicitud:</span>
                <span class="font-semibold"><?= date('d/m/Y H:i', strtotime($certificado['fecha_solicitud'])) ?></span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-600">Estado:</span>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    Pendiente de Pago
                </span>
            </div>

            <?php if (!empty($certificado['motivo_solicitud'])): ?>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">Motivo:</span>
                    <span class="font-semibold"><?= htmlspecialchars($certificado['motivo_solicitud'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endif; ?>

            <div class="flex justify-between items-center pt-3 border-t-2 border-gray-300">
                <span class="text-lg font-semibold text-gray-700">Total a Pagar:</span>
                <span class="text-2xl font-bold text-blue-600">$10.00</span>
            </div>
        </div>
    </div>

    <!-- Métodos de pago -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Método de Pago</h2>

        <form method="POST" action="?route=pagos/procesar-pago-online" id="formPago">
            <input type="hidden" name="certificado_id" value="<?= htmlspecialchars($certificado['id'], ENT_QUOTES, 'UTF-8') ?>">

            <div class="space-y-4">
                <!-- Pago Online -->
                <label class="block p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                    <div class="flex items-center">
                        <input type="radio" name="metodo_pago" value="online" checked class="form-radio text-blue-600">
                        <div class="ml-3">
                            <span class="block font-semibold text-gray-900">Pago en Línea</span>
                            <span class="block text-sm text-gray-500">Pago seguro con tarjeta de crédito/débito</span>
                        </div>
                    </div>
                </label>

                <!-- Información para pago en efectivo -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-blue-900 mb-1">¿Prefiere pagar en efectivo?</h3>
                            <p class="text-sm text-blue-800">
                                Puede acercarse a la secretaría de la parroquia y realizar el pago en efectivo.
                                El personal procesará su pago y su certificado estará disponible inmediatamente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del proceso -->
            <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-green-900 mb-2">Después del Pago:</h3>
                <ul class="text-sm text-green-800 space-y-1">
                    <li>✓ Su certificado se generará automáticamente</li>
                    <li>✓ Recibirá una confirmación de pago</li>
                    <li>✓ Podrá descargar el PDF desde "Mis Certificados"</li>
                    <li>✓ El certificado tendrá validez por 30 días</li>
                </ul>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 mt-6">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-md transition-colors">
                    Proceder al Pago
                </button>
                <a href="?route=certificados/mis-solicitudes"
                   class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-6 rounded-md text-center transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <!-- Información adicional -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
        <h3 class="font-semibold text-gray-900 mb-2">Política de Reembolso</h3>
        <p class="text-sm text-gray-700">
            Una vez procesado el pago y generado el certificado, no se realizarán reembolsos.
            Asegúrese de que todos los datos sean correctos antes de proceder al pago.
        </p>
    </div>
</div>

<script>
// Prevenir doble envío del formulario
document.getElementById('formPago').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'Procesando...';

    // Si hay algún error, reactivar el botón después de 5 segundos
    setTimeout(function() {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Proceder al Pago';
    }, 5000);
});
</script>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
