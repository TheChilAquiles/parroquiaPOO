<?php
// Mostrar mensajes de la sesión
if (isset($_SESSION['mensaje'])) {
    $tipo_clase = $_SESSION['tipo_mensaje'] == 'success' ? 'bg-green-100 border-green-400 text-green-700' : ($_SESSION['tipo_mensaje'] == 'error' ? 'bg-red-100 border-red-400 text-red-700' :
            'bg-blue-100 border-blue-400 text-blue-700');
    echo '<div class="' . $tipo_clase . ' border px-4 py-3 rounded mb-4">' . htmlspecialchars($_SESSION['mensaje']) . '</div>';
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}
?>

<main class="mx-auto max-w-6xl px-4 py-8">
    <!-- Header con navegación -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <form method="POST" action="index.php" style="display: inline;">
            <input type="hidden" name="menu-item" value="Grupos">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a Grupos
            </button>
        </form>

        <div class="flex gap-2">
            <form method="POST" action="index.php" style="display: inline;">
                <input type="hidden" name="menu-item" value="Grupos">
                <input type="hidden" name="action" value="editar">
                <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo['id']); ?>">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                    Editar Grupo
                </button>
            </form>
            <button onclick="confirmarEliminacion()"
                class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200">
                Eliminar Grupo
            </button>
        </div>
    </div>

    <!-- Información del grupo -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($grupo['nombre']); ?></h1>
                <p class="text-gray-600">
                    <span class="font-medium"><?php echo $grupo['total_miembros']; ?></span>
                    miembro<?php echo $grupo['total_miembros'] != 1 ? 's' : ''; ?>
                </p>
            </div>
            <button id="btnAgregarMiembro"
                class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-200 shadow-sm">
                + Agregar Miembro
            </button>
        </div>
    </div>

    <!-- Lista de miembros -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-6 text-gray-900">Miembros del Grupo</h2>

        <?php if (empty($miembros)): ?>
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <p class="text-gray-500 text-lg">Este grupo aún no tiene miembros.</p>
                <p class="text-gray-400 text-sm mt-1">Haz clic en "Agregar Miembro" para comenzar.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rol en Grupo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Teléfono
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo Usuario
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($miembros as $miembro): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-purple-500 flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    <?php
                                                    $iniciales = '';
                                                    $nombres = explode(' ', $miembro['nombre_completo']);
                                                    foreach (array_slice($nombres, 0, 2) as $nombre) {
                                                        $iniciales .= strtoupper(substr($nombre, 0, 1));
                                                    }
                                                    echo $iniciales ?: strtoupper(substr($miembro['email'], 0, 1));
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($miembro['nombre_completo']); ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($miembro['email']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        <?php
                                        $rol = strtolower($miembro['rol']);
                                        if ($rol === 'líder' || $rol === 'lider') echo 'bg-purple-100 text-purple-800';
                                        elseif ($rol === 'coordinador') echo 'bg-blue-100 text-blue-800';
                                        elseif ($rol === 'secretario') echo 'bg-green-100 text-green-800';
                                        else echo 'bg-gray-100 text-gray-800';
                                        ?>">
                                        <?php echo htmlspecialchars($miembro['rol']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo $miembro['telefono'] ? htmlspecialchars($miembro['telefono']) : '-'; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($miembro['rol_usuario'] ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="editarRol(<?php echo $miembro['usuario_id']; ?>, '<?php echo htmlspecialchars($miembro['rol']); ?>')"
                                            class="text-blue-600 hover:text-blue-900 transition duration-200">
                                            Cambiar Rol
                                        </button>
                                        <button onclick="eliminarMiembro(<?php echo $miembro['usuario_id']; ?>, '<?php echo htmlspecialchars($miembro['nombre_completo']); ?>')"
                                            class="text-red-600 hover:text-red-900 transition duration-200">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal para agregar miembro -->
<div id="modalAgregarMiembro" class="fixed inset-0 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Agregar Nuevo Miembro</h3>
            <form action="index.php" method="POST">
                <input type="hidden" name="menu-item" value="Grupos">
                <input type="hidden" name="action" value="agregar_miembro">
                <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo['id']); ?>">

                <div class="mb-4">
                    <label for="usuario_id" class="block text-gray-700 font-medium mb-2">Seleccionar Usuario</label>
                    <select id="usuario_id" name="usuario_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Seleccione un usuario...</option>
                        <?php foreach ($usuariosDisponibles as $usuario): ?>
                            <option value="<?php echo $usuario['id']; ?>">
                                <?php echo htmlspecialchars($usuario['nombre_completo'] . ' (' . $usuario['email'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="rol_id" class="block text-gray-700 font-medium mb-2">Rol en el Grupo</label>
                    <select id="rol_id" name="rol_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Seleccione un rol...</option>
                        <?php foreach ($rolesGrupo as $rol): ?>
                            <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars($rol['rol']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                        Agregar Miembro
                    </button>
                    <button type="button" id="btnCerrarModalAgregar"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para cambiar rol -->
<div id="modalCambiarRol" class="fixed inset-0 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Cambiar Rol de Miembro</h3>
            <form action="index.php" method="POST">
                <input type="hidden" name="menu-item" value="Grupos">
                <input type="hidden" name="action" value="actualizar_rol">
                <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo['id']); ?>">
                <input type="hidden" name="usuario_id" id="usuario_cambiar_rol">

                <div class="mb-6">
                    <label for="nuevo_rol_id" class="block text-gray-700 font-medium mb-2">Nuevo Rol</label>
                    <select id="nuevo_rol_id" name="nuevo_rol_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <?php foreach ($rolesGrupo as $rol): ?>
                            <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars($rol['rol']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        Actualizar Rol
                    </button>
                    <button type="button" id="btnCerrarModalRol"
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modales
        const modalAgregar = document.getElementById('modalAgregarMiembro');
        const modalRol = document.getElementById('modalCambiarRol');
        const btnAgregarMiembro = document.getElementById('btnAgregarMiembro');
        const btnCerrarModalAgregar = document.getElementById('btnCerrarModalAgregar');
        const btnCerrarModalRol = document.getElementById('btnCerrarModalRol');

        // Event listeners para modales
        btnAgregarMiembro.addEventListener('click', () => modalAgregar.classList.remove('hidden'));
        btnCerrarModalAgregar.addEventListener('click', () => modalAgregar.classList.add('hidden'));
        btnCerrarModalRol.addEventListener('click', () => modalRol.classList.add('hidden'));

        // Cerrar modales al hacer clic fuera
        [modalAgregar, modalRol].forEach(modal => {
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    });

    function editarRol(usuarioId, rolActual) {
        document.getElementById('usuario_cambiar_rol').value = usuarioId;
        document.getElementById('modalCambiarRol').classList.remove('hidden');
    }

    function eliminarMiembro(usuarioId, nombreUsuario) {
        if (confirm(`¿Estás seguro de que deseas eliminar a "${nombreUsuario}" del grupo?`)) {
            // Crear formulario para envío POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php';

            const inputMenuItem = document.createElement('input');
            inputMenuItem.type = 'hidden';
            inputMenuItem.name = 'menu-item';
            inputMenuItem.value = 'Grupos';

            const inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action';
            inputAction.value = 'eliminar_miembro';

            const inputGrupoId = document.createElement('input');
            inputGrupoId.type = 'hidden';
            inputGrupoId.name = 'grupo_id';
            inputGrupoId.value = '<?php echo $grupo['id']; ?>';

            const inputUsuarioId = document.createElement('input');
            inputUsuarioId.type = 'hidden';
            inputUsuarioId.name = 'usuario_id';
            inputUsuarioId.value = usuarioId;

            form.appendChild(inputMenuItem);
            form.appendChild(inputAction);
            form.appendChild(inputGrupoId);
            form.appendChild(inputUsuarioId);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function confirmarEliminacion() {
        if (confirm('⚠️ ¿Estás seguro de que deseas eliminar este grupo?\n\nEsta acción eliminará el grupo y todos sus miembros de forma permanente.')) {
            // Crear formulario para envío POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php';

            const inputMenuItem = document.createElement('input');
            inputMenuItem.type = 'hidden';
            inputMenuItem.name = 'menu-item';
            inputMenuItem.value = 'Grupos';

            const inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action';
            inputAction.value = 'eliminar';

            const inputGrupoId = document.createElement('input');
            inputGrupoId.type = 'hidden';
            inputGrupoId.name = 'grupo_id';
            inputGrupoId.value = '<?php echo $grupo['id']; ?>';

            form.appendChild(inputMenuItem);
            form.appendChild(inputAction);
            form.appendChild(inputGrupoId);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>