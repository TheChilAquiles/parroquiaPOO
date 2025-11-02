<?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Certificados Sacramentales</h1>

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

    <!-- Formulario para generar certificado -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Generar Nuevo Certificado</h2>

        <form action="?route=certificados/generar" method="POST" class="space-y-4">
            <input type="hidden" name="usuario_id" value="<?= $_SESSION['user-id'] ?? 1 ?>">

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Feligrés ID:</label>
                <input type="number" name="feligres_id" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nombre del Feligrés:</label>
                <input type="text" name="nombre_feligres" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Sacramento:</label>
                <select name="sacramento" required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">Seleccione...</option>
                    <option value="Bautizo">Bautizo</option>
                    <option value="Primera Comunión">Primera Comunión</option>
                    <option value="Confirmación">Confirmación</option>
                    <option value="Matrimonio">Matrimonio</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Fecha de Realización:</label>
                <input type="date" name="fecha_realizacion" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Lugar:</label>
                <input type="text" name="lugar" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                       placeholder="Ej: Parroquia San Francisco de Asís">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Observaciones (opcional):</label>
                <textarea name="observaciones" rows="3"
                          class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow font-semibold">
                    Generar Certificado PDF
                </button>
                <a href="?route=inicio"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>
