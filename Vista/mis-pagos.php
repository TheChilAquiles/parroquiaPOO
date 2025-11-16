<?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Mis Pagos Pendientes</h1>
    <p class="text-gray-600 mb-8">Gestiona los pagos de tus certificados sacramentales</p>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Tarjetas de Pagos Pendientes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (!empty($pagosPendientes)): ?>
            <?php foreach ($pagosPendientes as $pago): ?>
                <div class="bg-white shadow-md rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                <?= htmlspecialchars($pago['tipo_certificado']) ?>
                            </h3>
                            <p class="text-sm text-gray-600">
                                ID: #<?= htmlspecialchars($pago['id']) ?>
                            </p>
                        </div>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">
                            Pendiente
                        </span>
                    </div>

                    <div class="space-y-2 mb-4 text-sm">
                        <p class="text-gray-700">
                            <span class="font-semibold">Feligrés:</span>
                            <?= htmlspecialchars($pago['feligres_nombre']) ?>
                        </p>
                        <p class="text-gray-700">
                            <span class="font-semibold">Documento:</span>
                            <?= htmlspecialchars($pago['numero_documento']) ?>
                        </p>
                        <?php if (!empty($pago['relacion'])): ?>
                            <p class="text-gray-700">
                                <span class="font-semibold">Relación:</span>
                                <?= htmlspecialchars($pago['relacion']) ?>
                            </p>
                        <?php endif; ?>
                        <p class="text-gray-700">
                            <span class="font-semibold">Fecha solicitud:</span>
                            <?= date('d/m/Y', strtotime($pago['fecha_solicitud'])) ?>
                        </p>
                    </div>

                    <div class="border-t pt-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Monto a pagar</p>
                            <p class="text-2xl font-bold text-blue-600">
                                $<?= number_format($pago['monto'], 0, ',', '.') ?>
                            </p>
                        </div>
                        <button onclick="pagarAhora(<?= $pago['id'] ?>, <?= $pago['monto'] ?>)"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition font-semibold">
                            Pagar Ahora
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Estado vacío -->
            <div class="col-span-full">
                <div class="bg-white shadow-md rounded-lg p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">
                        ¡Todo al día!
                    </h3>
                    <p class="text-gray-500">
                        No tienes pagos pendientes en este momento
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Información adicional -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mt-8">
        <h4 class="text-lg font-semibold text-blue-900 mb-2">Información sobre Pagos</h4>
        <ul class="text-sm text-blue-800 space-y-2">
            <li>• Los certificados tienen un costo de $15.000 COP</li>
            <li>• El pago se procesa de forma simulada (entorno de desarrollo)</li>
            <li>• Una vez pagado, recibirás el certificado en formato PDF</li>
            <li>• Los certificados están disponibles por 30 días desde su generación</li>
            <li>• También puedes pagar en efectivo en la secretaría parroquial</li>
        </ul>
    </div>
</div>

<script>
function pagarAhora(certificadoId, monto) {
    Swal.fire({
        title: 'Confirmar Pago',
        html: `
            <p class="text-gray-700 mb-4">
                Está a punto de pagar <strong class="text-blue-600">$${monto.toLocaleString('es-CO')}</strong>
                por el certificado <strong>#${certificadoId}</strong>
            </p>
            <p class="text-sm text-gray-500 mb-4">
                <em>Este es un pago simulado para entorno de desarrollo.</em><br>
                <em>En producción, se redirigirá a PaymentsWay.</em>
            </p>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Procesar Pago',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280'
    }).then((result) => {
        if (result.isConfirmed) {
            procesarPagoSimulado(certificadoId);
        }
    });
}

function procesarPagoSimulado(certificadoId) {
    // Mostrar loading
    Swal.fire({
        title: 'Procesando pago...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Simular delay de procesamiento
    setTimeout(() => {
        // Enviar POST al servidor
        const formData = new FormData();
        formData.append('certificado_id', certificadoId);
        formData.append('metodo_pago', 'online');

        fetch('<?= url('pagos/procesar-pago-online') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            Swal.close();

            // Mostrar éxito
            Swal.fire({
                icon: 'success',
                title: '¡Pago Exitoso!',
                text: 'Tu certificado se está generando y estará disponible en unos momentos',
                confirmButtonText: 'Ver Mis Certificados',
                confirmButtonColor: '#10b981'
            }).then(() => {
                // Recargar página para actualizar lista
                window.location.reload();
            });
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo procesar el pago. Intente nuevamente.',
                confirmButtonText: 'Entendido'
            });
            console.error('Error:', error);
        });
    }, 1500); // Simular delay de pasarela de pago
}
</script>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
