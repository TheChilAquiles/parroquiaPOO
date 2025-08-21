<?php
// admin_noticias.php

// Datos de ejemplo. En un proyecto real, esto vendría de tu modelo o base de datos.
$noticias = [
    [
        'id' => 1,
        'titulo' => 'Bingo Bazar',
        'descripcion' => 'El día de la fiesta patronal esta cerca, te esperamos a participar de esta gran fiesta...',
        'imagen' => 'bingo_bazar.png',
        'fecha' => '25-10-2023'
    ],
    [
        'id' => 2,
        'titulo' => 'Peregrinación',
        'descripcion' => 'Últimos días para apartar tu cupo a la peregrinación...',
        'imagen' => 'peregrinacion.png',
        'fecha' => '20-10-2023'
    ]
];

// Lógica para mostrar la vista correcta
$action = $_GET['action'] ?? 'list'; // Obtiene la acción de la URL, por defecto es 'list'
$noticia_a_editar = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $noticia_id = (int)$_GET['id'];
    foreach ($noticias as $noticia) {
        if ($noticia['id'] === $noticia_id) {
            $noticia_a_editar = $noticia;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Noticias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans text-gray-800">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Panel de Administración de Noticias</h1>
            <a href="?action=add" class="py-2 px-4 bg-purple-600 text-white rounded-md shadow-sm hover:bg-purple-700">
                + Agregar Nueva Noticia
            </a>
        </div>

        <?php if ($action === 'list'): ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="hidden md:grid grid-cols-5 gap-4 py-3 px-4 text-sm font-semibold text-gray-600 border-b border-gray-200">
                    <div class="col-span-2">Título</div>
                    <div class="col-span-1">Imagen</div>
                    <div class="col-span-1">Fecha</div>
                    <div class="col-span-1 text-right">Acciones</div>
                </div>

                <?php foreach ($noticias as $noticia): ?>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 py-4 px-4 border-b border-gray-100 items-center">
                        <div class="col-span-2 font-medium text-gray-900">
                            <?= htmlspecialchars($noticia['titulo']) ?>
                        </div>
                        <div class="col-span-1">
                            <img src="<?= htmlspecialchars($noticia['imagen']) ?>" alt="Miniatura" class="w-16 h-12 object-cover rounded-md">
                        </div>
                        <div class="col-span-1 text-sm text-gray-500">
                            <?= htmlspecialchars($noticia['fecha']) ?>
                        </div>
                        <div class="col-span-1 flex justify-end space-x-2">
                            <a href="?action=edit&id=<?= htmlspecialchars($noticia['id']) ?>" class="py-1 px-3 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600">
                                Editar
                            </a>
                            <a href="?action=delete&id=<?= htmlspecialchars($noticia['id']) ?>" class="py-1 px-3 bg-red-500 text-white rounded-md text-sm hover:bg-red-600">
                                Eliminar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($action === 'add'): ?>
            <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-4">Agregar Nueva Noticia</h2>
                <form action="procesar_noticia.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" id="titulo" name="titulo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>
                    <div class="mb-6">
                        <label for="imagen" class="block text-sm font-medium text-gray-700">Imagen</label>
                        <input type="file" id="imagen" name="imagen" class="mt-1 block w-full text-sm text-gray-500">
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-purple-600 text-white rounded-md hover:bg-purple-700">Publicar</button>
                </form>
            </div>
        
        <?php elseif ($action === 'edit' && $noticia_a_editar): ?>
            <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-4">Editar Noticia</h2>
                <form action="procesar_edicion_noticia.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_noticia" value="<?= htmlspecialchars($noticia_a_editar['id']) ?>">
                    <div class="mb-4">
                        <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                        <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($noticia_a_editar['titulo']) ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"><?= htmlspecialchars($noticia_a_editar['descripcion']) ?></textarea>
                    </div>
                    <div class="mb-6">
                        <p class="block text-sm font-medium text-gray-700 mb-2">Imagen Actual:</p>
                        <img src="<?= htmlspecialchars($noticia_a_editar['imagen']) ?>" alt="Imagen actual" class="w-32 h-20 object-cover rounded-md mb-2">
                        <label for="imagen_nueva" class="block text-sm font-medium text-gray-700">Cambiar Imagen</label>
                        <input type="file" id="imagen_nueva" name="imagen_nueva" class="mt-1 block w-full text-sm text-gray-500">
                    </div>
                    <button type="submit" class="w-full py-2 px-4 bg-green-600 text-white rounded-md hover:bg-green-700">Guardar Cambios</button>
                </form>
            </div>
        
        <?php else: ?>
            <div class="bg-white p-6 rounded-lg shadow-md text-center text-red-500">
                <p>Noticia no encontrada.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>