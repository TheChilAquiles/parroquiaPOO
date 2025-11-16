<?php
// Vista/pagar-redireccion.php
// Vista para redirección automática a PaymentsWay
include_once __DIR__ . '/componentes/plantillaTop.php';
?>

<div class="container mx-auto p-6 max-w-2xl">
    <div class="bg-white shadow-md rounded-lg p-8">
        <!-- Logo y título -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Procesando su pago...</h1>
            <p class="text-gray-600 mb-6">Será redirigido a la pasarela de pago segura</p>
        </div>

        <!-- Logo de PaymentsWay / Spinner -->
        <div class="flex flex-col justify-center items-center bg-[#3e5569] p-8 rounded-2xl mb-6">
            <div id="paymentsway-logo">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-64 mb-4"
                    style="shape-rendering:geometricPrecision;text-rendering:geometricPrecision;image-rendering:optimizeQuality;fill-rule:evenodd;clip-rule:evenodd"
                    viewBox="0 0 3437 984">
                    <path style="opacity:.999" fill="#fefefe"
                        d="M463.5-.5h58q217.215 17.972 355.5 185 136.983 178.94 98 402Q929.146 799.226 745.5 914q-101.68 59.645-219 69.5h-70Q216.377 959.866 80 762.5 9.008 651.773-.5 520.5v-58q17.033-213.324 179-351.5Q303.32 10.96 463.5-.5" />
                    <path style="opacity:.994" fill="#fefefe"
                        d="M2555.5 115.5a67428 67428 0 0 1 219.5 219c.5-66.332.67-132.666.5-199h66c.33 119.002 0 238.002-1 357a66924 66924 0 0 1-218.5-218c-.5 65.999-.67 131.999-.5 198h-66z" />
                    <!-- SVG path data simplificado para el logo de PaymentsWay -->
                </svg>

                <!-- Spinner de carga -->
                <div class="flex justify-center">
                    <svg class="animate-spin h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Pago Seguro</p>
                    <ul class="space-y-1">
                        <li>✓ Conexión encriptada</li>
                        <li>✓ Múltiples métodos de pago disponibles</li>
                        <li>✓ Proceso validado por PaymentsWay Colombia</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Formulario oculto que se enviará automáticamente -->
        <form class="hidden" id="paymentForm" method="post" action="<?= htmlspecialchars($paymentUrl) ?>">
            <?php foreach ($formData as $key => $value): ?>
                <input name="<?= htmlspecialchars($key) ?>" type="hidden" value="<?= htmlspecialchars($value) ?>">
            <?php endforeach; ?>
        </form>

        <!-- Mensaje de backup -->
        <div class="text-center">
            <p class="text-sm text-gray-500">Si no es redirigido automáticamente en unos segundos,</p>
            <button onclick="document.getElementById('paymentForm').submit();"
                    class="mt-2 text-blue-600 hover:text-blue-800 font-semibold underline">
                haga clic aquí
            </button>
        </div>
    </div>
</div>

<script>
    // Enviar formulario automáticamente después de 1 segundo
    setTimeout(function() {
        document.getElementById('paymentForm').submit();
    }, 1000);
</script>

<?php
include_once __DIR__ . '/componentes/plantillaBottom.php';
?>
