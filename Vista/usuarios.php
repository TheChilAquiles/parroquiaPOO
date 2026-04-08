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
    <title>Usuarios - Parroquia San Francisco de Asís</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="bg-[#faf6f0] flex flex-col min-h-screen font-sans text-gray-800">

    <section class="relative bg-gradient-to-r from-[#938070] via-[#8a7666] to-[#786455] text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative container mx-auto px-4 text-center z-10">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 rounded-full mb-8 backdrop-blur-sm shadow-inner">
                <i class='bx bx-group text-5xl text-[#fef3c7]'></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-lg">Gestión de Usuarios</h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">Administra los accesos y roles del sistema parroquial</p>
        </div>
    </section>

    <section class="container mx-auto px-4 -mt-16 relative z-20 mb-20">
        
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-12 backdrop-blur-sm border border-[#f5ebdf]">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                <div class="flex-1 w-full lg:w-auto">
                    <form action="<?= url('usuarios') ?>" method="POST" class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class='bx bx-search text-2xl text-gray-400 group-focus-within:text-[#8a7666] transition-colors'></i>
                        </div>
                        <input type="text" name="buscar" placeholder="Buscar por email o rol..."
                            value="<?= htmlspecialchars($_POST['buscar'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full pl-16 pr-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#8a7666]/30 focus:border-[#8a7666] transition-all duration-300 bg-gray-50 focus:bg-white">
                        <button type="submit" class="absolute inset-y-0 right-0 pr-6 flex items-center">
                            <div class="bg-gradient-to-r from-[#8a7666] to-[#786455] text-white px-6 py-2 rounded-xl font-semibold hover:shadow-lg hover:from-[#786455] hover:to-[#6b594b] transition-all">
                                Buscar
                            </div>
                        </button>
                    </form>
                </div>
                <div class="flex items-center gap-4">
                    <?php if (!empty($_POST['buscar'])): ?>
                        <a href="<?= url('usuarios') ?>" class="flex items-center px-4 py-2 text-[#8a7666] hover:text-[#786455] font-medium transition-colors">
                            <i class='bx bx-x mr-2 text-xl'></i> Limpiar
                        </a>
                    <?php endif; ?>
                    <?php if ($esAdmin): ?>
                        <button id="openModalBtn" class="flex items-center px-6 py-3 bg-[#166534] hover:bg-[#14532d] text-white font-bold rounded-2xl shadow-lg transform hover:-translate-y-1 transition-all">
                            <i class='bx bx-user-plus mr-2 text-xl'></i> Nuevo Usuario
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 items-stretch">
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $index => $user): ?>
                    
                    <?php
                    // ==========================================
                    // LÓGICA PARA ICONOS DINÁMICOS POR ROL
                    // ==========================================
                    $rolStr = strtolower($user['nombre_rol'] ?? $user['rol'] ?? '');
                    $icono = 'bx-user'; // Icono por defecto (Feligrés)
                    $bgIcono = 'bg-[#fef3c7]'; // Fondo crema
                    $colorIcono = 'text-[#8a7666]'; // Color café adaptado

                    if (strpos($rolStr, 'admin') !== false) {
                        $icono = 'bx-shield-quarter'; // Escudo
                        $bgIcono = 'bg-[#ffedd5]'; 
                        $colorIcono = 'text-[#c2410c]';
                    } elseif (strpos($rolStr, 'secretari') !== false) {
                        $icono = 'bx-book-bookmark'; // Libro
                        $bgIcono = 'bg-[#ecfccb]';
                        $colorIcono = 'text-[#4d7c0f]';
                    } elseif (strpos($rolStr, 'sacerdote') !== false || strpos($rolStr, 'padre') !== false) {
                        $icono = 'bx-church'; // Iglesia
                        $bgIcono = 'bg-[#f3e8ff]';
                        $colorIcono = 'text-[#7e22ce]';
                    }
                    ?>

                    <article class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden group flex flex-col h-full transform hover:scale-105 hover:shadow-2xl transition-all duration-300">
                        <div class="p-8 flex flex-col flex-1 items-center text-center">
                            
                            <div class="relative w-24 h-24 <?= $bgIcono ?> rounded-full flex items-center justify-center mb-4 shadow-sm border border-white">
                                <i class='bx <?= $icono ?> text-5xl <?= $colorIcono ?>'></i>
                            </div>
                            
                            <span class="bg-[#fef3c7] text-[#8a7666] text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4 border border-[#fde68a]">
                                <?= htmlspecialchars($user['nombre_rol'] ?? $user['rol'] ?? 'Sin rol', ENT_QUOTES, 'UTF-8') ?>
                            </span>

                            <h3 class="text-xl font-bold text-gray-900 mb-1 truncate w-full" 
                                title="<?= !empty(trim($user['nombre_completo'] ?? '')) ? htmlspecialchars(trim($user['nombre_completo'])) : 'Sin perfil de feligrés' ?>">
                                <?= !empty(trim($user['nombre_completo'] ?? '')) ? htmlspecialchars(trim($user['nombre_completo']), ENT_QUOTES, 'UTF-8') : '<span class="text-gray-400 italic font-normal">Sin perfil de feligrés</span>' ?>
                            </h3>
                            
                            <p class="text-sm text-gray-500 mb-2 truncate w-full" title="<?= htmlspecialchars($user['email']) ?>">
                                <i class='bx bx-envelope mr-1'></i><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>
                            </p>    
                            
                            <?php if ($esAdmin): ?>
                                <div class="mt-auto w-full pt-6 border-t border-gray-100">
                                    <div class="flex items-center justify-between gap-3">
                                        <button class="flex-1 open-edit-modal bg-[#f5f0ec] text-[#8a7666] hover:bg-[#ebe1d8] font-semibold py-2.5 px-4 rounded-xl transition-all flex justify-center items-center"
                                            data-id="<?= $user['id'] ?>"
                                            data-email="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-rol="<?= $user['usuario_rol_id'] ?>">
                                            <i class='bx bx-edit mr-2 text-lg'></i> Editar
                                        </button>
                                        <button class="flex-1 delete-btn bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-2.5 px-4 rounded-xl transition-all flex justify-center items-center"
                                            data-id="<?= $user['id'] ?>">
                                            <i class='bx bx-trash mr-2 text-lg'></i> Inactivar
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-20 bg-white rounded-3xl shadow-sm border border-gray-100">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                        <i class='bx bx-ghost text-4xl text-gray-400'></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">No hay usuarios</h3>
                    <p class="text-gray-500">Aún no se han registrado usuarios en el sistema.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($esAdmin): ?>
    <div id="usuarioModal" class="fixed inset-0 z-50 hidden items-center justify-center  backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-[#8a7666] to-[#786455] text-white p-6 rounded-t-3xl z-10 flex justify-between items-center shadow-md">
                <h3 id="modalTitle" class="text-2xl font-bold flex items-center">
                    <i class='bx bx-user-circle mr-3 text-3xl opacity-80'></i> <span id="modalTitleText">Crear Usuario</span>
                </h3>
                <button id="closeModalBtn" class="text-white hover:text-[#e0d6cd] transition-colors"><i class='bx bx-x text-3xl'></i></button>
            </div>
            
            <form id="usuarioForm" method="POST" class="p-8 space-y-6">
                <input type="hidden" name="id" id="usuarioId">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-envelope text-gray-400 text-xl'></i>
                        </div>
                        <input type="email" id="email" name="email" required placeholder="ejemplo@parroquia.com"
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-[#8a7666]/30 focus:border-[#8a7666] outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">
                        Contraseña <span id="pwdHint" class="text-xs font-normal text-[#8a7666] hidden ml-2">(Dejar en blanco para conservar)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-lock-alt text-gray-400 text-xl'></i>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-[#8a7666]/30 focus:border-[#8a7666] outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Rol Asignado</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='bx bx-id-card text-gray-400 text-xl'></i>
                        </div>
                        <select id="usuario_rol_id" name="usuario_rol_id" required class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl bg-white focus:ring-[#8a7666]/30 focus:border-[#8a7666] outline-none transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>Seleccione el rol...</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['rol']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class='bx bx-chevron-down text-gray-400 text-xl'></i>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 mt-2 border-t border-gray-100">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-[#8a7666] to-[#786455] text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex justify-center items-center">
                        <i class='bx bx-save mr-2 text-xl'></i> Guardar
                    </button>
                    <button type="button" id="cancelBtn" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3.5 rounded-xl hover:bg-gray-200 transition-all">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md mx-4 transform scale-95 transition-all duration-300 modal-content border-t-8 border-red-600">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 mb-6 border-4 border-red-100">
                    <i class='bx bx-error text-red-600 text-5xl'></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">¿Inactivar usuario?</h3>
                <p class="text-gray-500 mb-8 leading-relaxed">Esta acción es irreversible. El usuario perderá inmediatamente su acceso al sistema parroquial.</p>

                <div class="flex gap-4">
                    <button id="cancelDeleteBtn" class="flex-1 px-6 py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-all">
                        Cancelar
                    </button>
                    <button id="confirmDeleteBtn" data-id="" class="flex-1 px-6 py-3.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg hover:shadow-xl transition-all">
                        Sí, Inactivar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#451a03]/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-8 shadow-2xl flex items-center space-x-4">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-[#8a7666] border-t-transparent"></div>
            <span class="text-xl font-bold text-[#786455]">Procesando...</span>
        </div>
    </div>

    <div id="feedback-container" class="fixed top-6 right-6 z-50 space-y-4"></div>

    <script src="assets/js/usuarios.js"></script>
</body>
</html>