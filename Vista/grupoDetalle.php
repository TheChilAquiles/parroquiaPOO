<main class="mx-auto max-w-4xl px-4 py-8">
    <a href="?menu-item=Grupos" class="inline-block mb-6 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg font-bold hover:bg-gray-300 transition duration-200">
        ← Volver a Grupos
    </a>

    <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($grupo['nombre']); ?></h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold mb-4">Agregar Miembro</h2>
        <form action="?menu-item=Grupos&action=agregar_miembro" method="POST">
            <input type="hidden" name="grupo_id" value="<?php echo htmlspecialchars($grupo['id']); ?>">
            <div class="mb-4">
                <label for="email_miembro" class="block text-gray-700 font-medium mb-2">Correo del Usuario</label>
                <input type="email" id="email_miembro" name="email_miembro" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="mb-4">
                <label for="rol_id" class="block text-gray-700 font-medium mb-2">Rol en el Grupo</label>
                <select id="rol_id" name="rol_id" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="1">Miembro</option>
                    <option value="2">Líder</option>
                </select>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                Agregar
            </button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Miembros del Grupo</h2>
        
        <?php if (empty($miembros)) : ?>
            <p class="text-gray-500">Este grupo aún no tiene miembros.</p>
        <?php else : ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Correo
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rol
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($miembros as $miembro) : ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($miembro['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo htmlspecialchars($miembro['rol']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?menu-item=Grupos&action=eliminar_miembro&grupo_id=<?php echo htmlspecialchars($grupo['id']); ?>&usuario_id=<?php echo htmlspecialchars($miembro['id']); ?>" class="text-red-600 hover:text-red-900 transition duration-200" onclick="return confirm('¿Estás seguro de que deseas eliminar este miembro?');">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>