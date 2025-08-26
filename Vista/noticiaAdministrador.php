<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 text-center mb-10">
        Gestión de Noticias
    </h1>

    <?php if (isset($mensaje)): ?>
        <div class="p-4 mb-4 text-sm rounded-lg
            <?php echo ($mensaje['tipo'] == 'exito') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
            <?php echo htmlspecialchars($mensaje['texto']); ?>
        </div>
    <?php endif; ?>

    <?php if ($accion === 'ver_form' || $accion === 'guardar'): ?>
        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <?php echo ($noticia) ? 'Editar Noticia' : 'Crear Nueva Noticia'; ?>
            </h2>
            <form action="?menu-item=Noticias&accion=guardar" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo ($noticia) ? htmlspecialchars($noticia['id']) : ''; ?>">
                
                <div class="mb-4">
                    <label for="titulo" class="block text-gray-700 font-bold mb-2">Título</label>
                    <input type="text" id="titulo" name="titulo" value="<?php echo ($noticia) ? htmlspecialchars($noticia['titulo']) : ''; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div class="mb-4">
                    <label for="descripcion" class="block text-gray-700 font-bold mb-2">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="5" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo ($noticia) ? htmlspecialchars($noticia['descripcion']) : ''; ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="imagen" class="block text-gray-700 font-bold mb-2">Imagen</label>
                    <input type="file" id="imagen" name="imagen" <?php echo ($noticia) ? '' : 'required'; ?> class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    <?php if ($noticia && $noticia['imagen']): ?>
                        <p class="text-xs text-gray-500 mt-1">Imagen actual: <?php echo htmlspecialchars($noticia['imagen']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Guardar
                    </button>
                    <a href="?menu-item=Noticias" class="inline-block align-baseline font-bold text-sm text-purple-500 hover:text-purple-800">
                        Volver al Listado
                    </a>
                </div>
            </form>
        </div>

    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-end mb-4">
                <a href="?menu-item=Noticias&accion=ver_form" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Crear Nueva Noticia
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-left">Título</th>
                            <th class="py-3 px-6 text-left">Descripción</th>
                            <th class="py-3 px-6 text-center">Imagen</th>
                            <th class="py-3 px-6 text-center">Fecha</th>
                            <th class="py-3 px-6 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php if ($noticias && !empty($noticias)): ?>
                            <?php foreach ($noticias as $noticia): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($noticia['titulo']); ?></td>
                                    <td class="py-3 px-6 text-left"><?php echo htmlspecialchars(substr($noticia['descripcion'], 0, 50)) . '...'; ?></td>
                                    <td class="py-3 px-6 text-center">
                                        <img src="<?php echo htmlspecialchars($noticia['imagen']); ?>" alt="Imagen de noticia" class="h-10 w-10 rounded-full mx-auto object-cover">
                                    </td>
                                    <td class="py-3 px-6 text-center"><?php echo htmlspecialchars($noticia['fecha']); ?></td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">
                                            <a href="?menu-item=Noticias&accion=ver_form&id=<?php echo $noticia['id']; ?>" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                            <a href="?menu-item=Noticias&accion=eliminar&id=<?php echo $noticia['id']; ?>" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" onclick="return confirm('¿Estás seguro de que quieres eliminar esta noticia?');">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1H4a1 1 0 00-1 1v2m-1 1h16" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="py-3 px-6 text-center text-gray-500">No hay noticias para mostrar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>