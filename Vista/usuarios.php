<?php
// Vista/usuarios.php
$esAdmin = isset($_SESSION['user-rol']) &&
    ($_SESSION['user-rol'] === 'Administrador' || $_SESSION['user-rol'] === 'Secretario');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Parroquia</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>
<body class="bg-gray-100 flex flex-col min-h-screen font-sans text-gray-800">

    <section class="relative bg-gradient-to-r from-[#5a67d8] via-[#7f9cf5] to-[#4c51bf] text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative container mx-auto px-4 text-center z-10">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 rounded-full mb-8 backdrop-blur-sm">
                <i class='bx bx-group text-5xl'></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-2xl">Gestión de Usuarios</h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">Administra los accesos y roles del sistema parroquial</p>
        </div>
    </section>

    <section class="container mx-auto px-4 -mt-16 relative z-20 mb-20">
        
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-12 backdrop-blur-sm">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                <div class="flex-1 w-full lg:w-auto">
                    <form action="<?= url('usuarios') ?>" method="POST" class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class='bx bx-search text-2xl text-gray-400 group-focus-within:text-[#5a67d8] transition-colors'></i>
                        </div>
                        <input type="text" name="buscar" placeholder="Buscar por email o rol..."
                            value="<?= htmlspecialchars($_POST['buscar'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full pl-16 pr-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#5a67d8] focus:border-[#5a67d8] transition-all duration-300 bg-gray-50 focus:bg-white">
                        <button type="submit" class="absolute inset-y-0 right-0 pr-6 flex items-center">
                            <div class="bg-gradient-to-r from-[#5a67d8] to-[#4c51bf] text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg transition-all">
                                Buscar
                            </div>
                        </button>
                    </form>
                </div>
                <div class="flex items-center gap-4">
                    <?php if (!empty($_POST['buscar'])): ?>
                        <a href="<?= url('usuarios') ?>" class="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            <i class='bx bx-x mr-2 text-xl'></i> Limpiar
                        </a>
                    <?php endif; ?>
                    <?php if ($esAdmin): ?>
                        <button id="openModalBtn" class="flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-2xl shadow-lg transform hover:-translate-y-1 transition-all">
                            <i class='bx bx-user-plus mr-2 text-xl'></i> Nuevo Usuario
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 items-stretch">
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $index => $user): ?>
                    <article class="bg-white rounded-3xl shadow-xl overflow-hidden group flex flex-col h-full transform hover:scale-105 transition-all duration-300">
                        <div class="p-8 flex flex-col flex-1 items-center text-center">
                            
                            <div class="relative w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class='bx bx-user text-5xl text-gray-400'></i>
                            </div>
                            <span class="bg-[#e0e7ff] text-[#5a67d8] text-sm font-bold px-4 py-1 rounded-full uppercase tracking-wide mb-4">
                                <?= htmlspecialchars($user['nombre_rol'], ENT_QUOTES, 'UTF-8') ?>
                            </span>

                            <h3 class="text-xl font-bold text-gray-900 mb-1 truncate w-full" 
                                title="<?= !empty(trim($user['nombre_completo'])) ? htmlspecialchars(trim($user['nombre_completo'])) : 'Sin perfil de feligrés' ?>">
                                <?= !empty(trim($user['nombre_completo'])) ? htmlspecialchars(trim($user['nombre_completo']), ENT_QUOTES, 'UTF-8') : '<span class="text-gray-400 italic">Sin perfil de feligrés</span>' ?>
                            </h3>
                            
                            <p class="text-sm text-gray-500 mb-2 truncate w-full" title="<?= htmlspecialchars($user['email']) ?>">
                                <i class='bx bx-envelope mr-1'></i><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>
                            </p>    
                            
                            <?php if ($esAdmin): ?>
                                <div class="mt-auto w-full pt-6 border-t border-gray-100">
                                    <div class="flex items-center justify-between gap-3">
                                        <button class="flex-1 open-edit-modal bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold py-2 px-4 rounded-xl transition-all flex justify-center"
                                            data-id="<?= $user['id'] ?>"
                                            data-email="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-rol="<?= $user['usuario_rol_id'] ?>">
                                            <i class='bx bx-edit mr-2 text-lg'></i> Editar
                                        </button>
                                        <button class="flex-1 delete-btn bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-2 px-4 rounded-xl transition-all flex justify-center"
                                            data-id="<?= $user['id'] ?>">
                                            <i class='bx bx-trash mr-2 text-lg'></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-20">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">No hay usuarios disponibles</h3>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($esAdmin): ?>
    <div id="usuarioModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-[#5a67d8] to-[#4c51bf] text-white p-6 rounded-t-3xl z-10 flex justify-between items-center">
                <h3 id="modalTitle" class="text-2xl font-bold">Crear Usuario</h3>
                <button id="closeModalBtn" class="text-white hover:text-gray-200"><i class='bx bx-x text-3xl'></i></button>
            </div>
            
            <form id="usuarioForm" method="POST" class="p-8 space-y-6">
                <input type="hidden" name="id" id="usuarioId">
                
                <div>
                    <label class="block text-lg font-bold text-gray-900 mb-2">Correo Electrónico (Email)</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-[#5a67d8]">
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-900 mb-2">
                        Contraseña <span id="pwdHint" class="text-sm font-normal text-gray-500 hidden">(Déjalo en blanco para no cambiarla)</span>
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-[#5a67d8]">
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-900 mb-2">Rol de Usuario</label>
                    <select id="usuario_rol_id" name="usuario_rol_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-white focus:ring-[#5a67d8]">
                        <option value="">Seleccione un rol...</option>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['rol']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-[#5a67d8] to-[#4c51bf] text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all">
                        Guardar
                    </button>
                    <button type="button" id="cancelBtn" class="flex-1 bg-gray-200 text-gray-800 font-bold py-3 rounded-xl shadow-lg hover:shadow-xl transition-all">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <?php endif; ?>

    <div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md mx-4 transform scale-95 transition-all duration-300 modal-content">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                    <i class='bx bx-error text-red-600 text-4xl'></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">¿Eliminar usuario?</h3>
                <p class="text-gray-600 mb-8">Esta acción no se puede deshacer y el usuario perderá su acceso al sistema.</p>

                <div class="flex gap-4">
                    <button id="cancelDeleteBtn" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-xl hover:bg-gray-300 transition">
                        Cancelar
                    </button>
                    <button id="confirmDeleteBtn" data-id="" class="flex-1 px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-8 shadow-2xl">
            <div class="flex items-center space-x-4">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-[#5a67d8] border-t-transparent"></div>
                <span class="text-xl font-semibold text-gray-700">Procesando...</span>
            </div>
        </div>
    </div>

    <div id="feedback-container" class="fixed top-4 right-4 z-50 space-y-4"></div>

    <script src="assets/js/usuarios.js"></script>
</body>
</html>