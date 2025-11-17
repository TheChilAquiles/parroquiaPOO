<?php
/**
 * Vista/reportes_editar.php - Formulario de Edición de Reportes
 * Variables requeridas desde el controlador:
 * - $reporte (array): Datos del reporte a editar
 * - $categorias (array): Lista de categorías disponibles
 */

// Guardas de seguridad
$reporte = $reporte ?? [];
$categorias = $categorias ?? [];

// Helper para escapar HTML
require_once __DIR__ . '/../helpers.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reporte - Sistema Parroquial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="gradient-bg py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-white">Editar Reporte</h1>
                <a href="index.php?route=reportes" class="inline-flex items-center px-4 py-2 bg-white text-purple-600 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Mensajes de error/éxito -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?= e($_SESSION['error']) ?></p>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700"><?= e($_SESSION['success']) ?></p>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-8">
                <form action="index.php?route=reportes/actualizar" method="POST" id="formEditarReporte">
                    <input type="hidden" name="id" value="<?= e($reporte['id_reporte'] ?? '') ?>">

                    <!-- Información del Pago (solo lectura) -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Pago</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ID de Pago</label>
                                <input type="text" value="<?= e($reporte['id_pago'] ?? 'N/A') ?>" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valor del Pago</label>
                                <input type="text" value="$<?= number_format(floatval($reporte['valor'] ?? 0), 0, ',', '.') ?>" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado del Pago</label>
                                <input type="text" value="<?= e(ucfirst($reporte['estado_pago'] ?? 'N/A')) ?>" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha del Reporte</label>
                                <input type="text" value="<?= date('d/m/Y H:i', strtotime($reporte['fecha_reporte'] ?? 'now')) ?>" readonly class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <!-- Campos Editables -->
                    <div class="space-y-6">
                        <!-- Título -->
                        <div>
                            <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                                Título del Reporte <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="titulo" name="titulo" value="<?= e($reporte['titulo'] ?? '') ?>" required minlength="3" maxlength="255" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="Ingrese el título del reporte">
                            <p class="mt-1 text-sm text-gray-500">Mínimo 3 caracteres, máximo 255</p>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción <span class="text-red-500">*</span>
                            </label>
                            <textarea id="descripcion" name="descripcion" required minlength="10" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none" placeholder="Ingrese una descripción detallada del reporte"><?= e($reporte['descripcion'] ?? '') ?></textarea>
                            <p class="mt-1 text-sm text-gray-500">Mínimo 10 caracteres</p>
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                                Categoría <span class="text-red-500">*</span>
                            </label>
                            <?php if (!empty($categorias)): ?>
                                <select id="categoria" name="categoria" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <option value="">Seleccione una categoría</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= e($cat) ?>" <?= ($reporte['categoria'] ?? '') === $cat ? 'selected' : '' ?>>
                                            <?= e($cat) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="otra">Otra (escribir nueva)</option>
                                </select>
                            <?php else: ?>
                                <input type="text" id="categoria" name="categoria" value="<?= e($reporte['categoria'] ?? '') ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="Ingrese la categoría">
                            <?php endif; ?>
                        </div>

                        <!-- Categoría personalizada (mostrar si se selecciona "Otra") -->
                        <div id="categoria_personalizada_container" style="display: none;">
                            <label for="categoria_personalizada" class="block text-sm font-medium text-gray-700 mb-2">
                                Nueva Categoría
                            </label>
                            <input type="text" id="categoria_personalizada" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="Ingrese la nueva categoría">
                        </div>

                        <!-- Estado del Registro -->
                        <div>
                            <label for="estado_registro" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado del Registro <span class="text-red-500">*</span>
                            </label>
                            <select id="estado_registro" name="estado_registro" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="activo" <?= ($reporte['estado_registro'] ?? 'activo') === 'activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="inactivo" <?= ($reporte['estado_registro'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-end">
                        <a href="index.php?route=reportes" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Manejo de categoría personalizada
        document.getElementById('categoria').addEventListener('change', function() {
            const container = document.getElementById('categoria_personalizada_container');
            const input = document.getElementById('categoria_personalizada');

            if (this.value === 'otra') {
                container.style.display = 'block';
                input.required = true;
            } else {
                container.style.display = 'none';
                input.required = false;
                input.value = '';
            }
        });

        // Validación antes de enviar
        document.getElementById('formEditarReporte').addEventListener('submit', function(e) {
            const categoria = document.getElementById('categoria');
            const categoriaPersonalizada = document.getElementById('categoria_personalizada');

            if (categoria.value === 'otra' && categoriaPersonalizada.value.trim() === '') {
                e.preventDefault();
                alert('Por favor, ingrese una categoría personalizada.');
                categoriaPersonalizada.focus();
                return false;
            }

            // Si se seleccionó "otra", usar el valor personalizado
            if (categoria.value === 'otra') {
                categoria.value = categoriaPersonalizada.value.trim();
            }
        });
    </script>
</body>
</html>
