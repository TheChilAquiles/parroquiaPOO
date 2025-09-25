<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Noticias Parroquiales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .glass-effect {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .news-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .news-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(208, 184, 168, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .news-card:hover::before {
            left: 100%;
        }

        .news-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .floating-bg {
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, rgba(208, 184, 168, 0.1), rgba(171, 135, 111, 0.1));
            animation: float 20s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes float {

            0%,
            100% {
                transform: rotate(0deg) translate(0px, 0px);
            }

            25% {
                transform: rotate(5deg) translate(20px, -20px);
            }

            50% {
                transform: rotate(10deg) translate(-20px, 20px);
            }

            75% {
                transform: rotate(-5deg) translate(20px, 20px);
            }
        }

        .fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse-ring {
            animation: pulseRing 2s infinite;
        }

        @keyframes pulseRing {
            0% {
                box-shadow: 0 0 0 0 rgba(208, 184, 168, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(208, 184, 168, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(208, 184, 168, 0);
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 relative overflow-x-hidden">
    <!-- Floating Background Animation -->
    <div class="floating-bg"></div>

    <!-- Header Section -->
    <header class="relative z-10 pt-8 pb-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-[#D0B8A8] to-[#ab876f] rounded-full mb-6 shadow-2xl pulse-ring">
                    <span class="material-icons text-white text-3xl">article</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-4">
                    Panel Administrativo
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Gestiona las noticias y comunicados de nuestra comunidad parroquial
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="?action=add" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white font-bold rounded-full shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition duration-300">
                        <span class="material-icons mr-2">add</span>
                        Nueva Noticia
                    </a>
                    <a href="?action=stats" class="inline-flex items-center px-8 py-4 bg-white text-[#ab876f] font-bold rounded-full shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition duration-300 border-2 border-[#D0B8A8]">
                        <span class="material-icons mr-2">analytics</span>
                        Estadísticas
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="relative z-10 container mx-auto px-4 pb-16">
        <?php if ($action === 'list'): ?>
            <!-- Lista de Noticias -->
            <div class="glass-effect rounded-3xl shadow-2xl p-8 mb-8 fade-in">
                <div class="flex flex-col lg:flex-row justify-between items-center mb-8 gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Noticias Publicadas</h2>
                        <p class="text-gray-600">Gestiona y organiza el contenido de tu parroquia</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 px-4 py-2 rounded-full">
                            <span class="text-green-700 font-semibold"><?= count($noticias) ?> Noticias</span>
                        </div>
                        <select class="px-4 py-2 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#D0B8A8]">
                            <option>Todas las categorías</option>
                            <option>Eventos</option>
                            <option>Anuncios</option>
                            <option>Celebraciones</option>
                        </select>
                    </div>
                </div>

                <!-- Tabla Mejorada para Desktop -->
                <div class="hidden lg:block">
                    <div class="bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white rounded-2xl p-6 mb-6">
                        <div class="grid grid-cols-6 gap-6 font-bold">
                            <div class="col-span-2">
                                <span class="material-icons mr-2 align-middle">title</span>
                                Título
                            </div>
                            <div class="col-span-1 text-center">
                                <span class="material-icons mr-2 align-middle">image</span>
                                Imagen
                            </div>
                            <div class="col-span-1 text-center">
                                <span class="material-icons mr-2 align-middle">calendar_today</span>
                                Fecha
                            </div>
                            <div class="col-span-1 text-center">
                                <span class="material-icons mr-2 align-middle">visibility</span>
                                Estado
                            </div>
                            <div class="col-span-1 text-center">
                                <span class="material-icons mr-2 align-middle">settings</span>
                                Acciones
                            </div>
                        </div>
                    </div>

                    <?php foreach ($noticias as $index => $noticia): ?>
                        <div class="news-card bg-white rounded-2xl p-6 mb-4 shadow-lg" style="animation-delay: <?= $index * 0.1 ?>s">
                            <div class="grid grid-cols-6 gap-6 items-center">
                                <div class="col-span-2">
                                    <h3 class="font-bold text-gray-900 text-lg mb-1">
                                        <?= htmlspecialchars($noticia['titulo']) ?>
                                    </h3>
                                    <p class="text-gray-600 text-sm line-clamp-2">
                                        <?= htmlspecialchars(substr($noticia['descripcion'], 0, 80)) ?>...
                                    </p>
                                </div>
                                <div class="col-span-1 flex justify-center">
                                    <div class="relative group">
                                        <img src="<?= htmlspecialchars($noticia['imagen']) ?>"
                                            alt="Miniatura"
                                            class="w-20 h-16 object-cover rounded-xl shadow-md group-hover:scale-110 transition duration-300">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-xl transition duration-300 flex items-center justify-center">
                                            <span class="material-icons text-white opacity-0 group-hover:opacity-100 transition duration-300">zoom_in</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1 text-center">
                                    <div class="bg-blue-50 text-blue-700 px-3 py-2 rounded-full text-sm font-semibold">
                                        <?= htmlspecialchars($noticia['fecha']) ?>
                                    </div>
                                </div>
                                <div class="col-span-1 text-center">
                                    <span class="bg-green-100 text-green-800 px-3 py-2 rounded-full text-sm font-bold">
                                        <span class="material-icons mr-1 text-xs align-middle">check_circle</span>
                                        Publicada
                                    </span>
                                </div>
                                <div class="col-span-1 flex justify-center space-x-2">
                                    <a href="?action=edit&id=<?= htmlspecialchars($noticia['id']) ?>"
                                        class="group bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                                        <span class="material-icons group-hover:scale-110 transition duration-300">edit</span>
                                    </a>
                                    <button onclick="confirmDelete(<?= $noticia['id'] ?>)"
                                        class="group bg-red-500 hover:bg-red-600 text-white p-3 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300">
                                        <span class="material-icons group-hover:scale-110 transition duration-300">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cards para Mobile -->
                <div class="lg:hidden space-y-6">
                    <?php foreach ($noticias as $noticia): ?>
                        <div class="news-card bg-white rounded-3xl shadow-xl overflow-hidden">
                            <div class="relative">
                                <img src="<?= htmlspecialchars($noticia['imagen']) ?>"
                                    alt="<?= htmlspecialchars($noticia['titulo']) ?>"
                                    class="w-full h-48 object-cover">
                                <div class="absolute top-4 right-4">
                                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        Publicada
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    <?= htmlspecialchars($noticia['titulo']) ?>
                                </h3>
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    <?= htmlspecialchars($noticia['descripcion']) ?>
                                </p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm text-gray-500 flex items-center">
                                        <span class="material-icons mr-1 text-sm">calendar_today</span>
                                        <?= htmlspecialchars($noticia['fecha']) ?>
                                    </span>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="?action=edit&id=<?= htmlspecialchars($noticia['id']) ?>"
                                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-3 rounded-full font-semibold transition duration-300 flex items-center justify-center">
                                        <span class="material-icons mr-2">edit</span>
                                        Editar
                                    </a>
                                    <button onclick="confirmDelete(<?= $noticia['id'] ?>)"
                                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 rounded-full font-semibold transition duration-300 flex items-center justify-center">
                                        <span class="material-icons mr-2">delete</span>
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($action === 'add'): ?>
            <!-- Formulario de Nueva Noticia -->
            <div class="max-w-4xl mx-auto">
                <div class="glass-effect rounded-3xl shadow-2xl p-8 fade-in">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full mb-4 shadow-lg">
                            <span class="material-icons text-white text-2xl">add_circle</span>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-2">Crear Nueva Noticia</h2>
                        <p class="text-gray-600">Comparte información importante con tu comunidad</p>
                    </div>

                    <form action="procesar_noticia.php" method="POST" enctype="multipart/form-data" class="space-y-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label for="titulo" class="block text-lg font-semibold text-gray-900 mb-3">
                                        <span class="material-icons mr-2 align-middle">title</span>
                                        Título de la Noticia
                                    </label>
                                    <input type="text" id="titulo" name="titulo" required
                                        class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition duration-300"
                                        placeholder="Ej: Celebración de la Fiesta Patronal">
                                </div>

                                <div>
                                    <label for="categoria" class="block text-lg font-semibold text-gray-900 mb-3">
                                        <span class="material-icons mr-2 align-middle">category</span>
                                        Categoría
                                    </label>
                                    <select id="categoria" name="categoria"
                                        class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition duration-300">
                                        <option value="evento">Evento</option>
                                        <option value="anuncio">Anuncio</option>
                                        <option value="celebracion">Celebración</option>
                                        <option value="formacion">Formación</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="imagen" class="block text-lg font-semibold text-gray-900 mb-3">
                                        <span class="material-icons mr-2 align-middle">image</span>
                                        Imagen Principal
                                    </label>
                                    <div class="relative">
                                        <input type="file" id="imagen" name="imagen" accept="image/*"
                                            class="w-full px-6 py-4 text-lg border-2 border-dashed border-gray-300 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-[#D0B8A8] file:text-white file:font-semibold">
                                        <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none">
                                            <span class="material-icons text-gray-400">cloud_upload</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">Formatos: JPG, PNG, GIF. Tamaño máximo: 5MB</p>
                                </div>

                                <div>
                                    <label for="fecha_evento" class="block text-lg font-semibold text-gray-900 mb-3">
                                        <span class="material-icons mr-2 align-middle">event</span>
                                        Fecha del Evento (Opcional)
                                    </label>
                                    <input type="date" id="fecha_evento" name="fecha_evento"
                                        class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition duration-300">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="descripcion" class="block text-lg font-semibold text-gray-900 mb-3">
                                <span class="material-icons mr-2 align-middle">description</span>
                                Contenido de la Noticia
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="8" required
                                class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition duration-300 resize-none"
                                placeholder="Describe los detalles de tu noticia aquí..."></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white text-xl font-bold py-4 px-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition duration-300 flex items-center justify-center">
                                <span class="material-icons mr-3">publish</span>
                                Publicar Noticia
                            </button>
                            <a href="?action=list"
                                class="flex-1 bg-gray-200 text-gray-800 text-xl font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300 flex items-center justify-center">
                                <span class="material-icons mr-3">cancel</span>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        <?php elseif ($action === 'edit' && $noticia_a_editar): ?>
            <!-- Formulario de Edición -->
            <div class="max-w-4xl mx-auto">
                <div class="glass-effect rounded-3xl shadow-2xl p-8 fade-in">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full mb-4 shadow-lg">
                            <span class="material-icons text-white text-2xl">edit</span>
                        </div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-2">Editar Noticia</h2>
                        <p class="text-gray-600">Actualiza la información de tu publicación</p>
                    </div>

                    <form action="procesar_edicion_noticia.php" method="POST" enctype="multipart/form-data" class="space-y-8">
                        <input type="hidden" name="id_noticia" value="<?= htmlspecialchars($noticia_a_editar['id']) ?>">

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label for="titulo" class="block text-lg font-semibold text-gray-900 mb-3">
                                        <span class="material-icons mr-2 align-middle">title</span>
                                        Título de la Noticia
                                    </label>
                                    <input type="text" id="titulo" name="titulo" required
                                        value="<?= htmlspecialchars($noticia_a_editar['titulo']) ?>"
                                        class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition duration-300">
                                </div>

                                <div>
                                    <label class="block text-lg font-semibold text-gray-900 mb-3">
                                        <span class="material-icons mr-2 align-middle">image</span>
                                        Imagen Actual
                                    </label>
                                    <div class="relative group">
                                        <img src="<?= htmlspecialchars($noticia_a_editar['imagen']) ?>"
                                            alt="Imagen actual"
                                            class="w-full h-48 object-cover rounded-2xl shadow-lg group-hover:scale-105 transition duration-300">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-2xl transition duration-300 flex items-center justify-center">
                                            <span class="material-icons text-white text-4xl opacity-0 group-hover:opacity-100 transition duration-300">zoom_in</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="imagen_nueva" class="block text-lg font-semibold text-gray-900 mb-3">
                                        <span class="material-icons mr-2 align-middle">change_circle</span>
                                        Cambiar Imagen (Opcional)
                                    </label>
                                    <div class="relative">
                                        <input type="file" id="imagen_nueva" name="imagen_nueva" accept="image/*"
                                            class="w-full px-6 py-4 text-lg border-2 border-dashed border-gray-300 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-500 file:text-white file:font-semibold">
                                        <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none">
                                            <span class="material-icons text-gray-400">cloud_upload</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">Deja vacío si no quieres cambiar la imagen</p>
                                </div>

                                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                                    <div class="flex items-center mb-3">
                                        <span class="material-icons text-yellow-600 mr-2">info</span>
                                        <h4 class="font-semibold text-yellow-800">Información de la Noticia</h4>
                                    </div>
                                    <div class="space-y-2 text-sm text-yellow-700">
                                        <p><strong>ID:</strong> <?= htmlspecialchars($noticia_a_editar['id']) ?></p>
                                        <p><strong>Fecha de creación:</strong> <?= htmlspecialchars($noticia_a_editar['fecha']) ?></p>
                                        <p><strong>Estado:</strong> <span class="text-green-600 font-semibold">Publicada</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="descripcion" class="block text-lg font-semibold text-gray-900 mb-3">
                                <span class="material-icons mr-2 align-middle">description</span>
                                Contenido de la Noticia
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="8" required
                                class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#D0B8A8] focus:border-[#D0B8A8] transition duration-300 resize-none"><?= htmlspecialchars($noticia_a_editar['descripcion']) ?></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <button type="submit"
                                class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xl font-bold py-4 px-8 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition duration-300 flex items-center justify-center">
                                <span class="material-icons mr-3">save</span>
                                Guardar Cambios
                            </button>
                            <a href="?action=list"
                                class="flex-1 bg-gray-200 text-gray-800 text-xl font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition duration-300 flex items-center justify-center">
                                <span class="material-icons mr-3">arrow_back</span>
                                Volver a la Lista
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <!-- Error 404 Personalizado -->
            <div class="text-center py-16 fade-in">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-6">
                    <span class="material-icons text-red-500 text-4xl">error_outline</span>
                </div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Noticia No Encontrada</h2>
                <p class="text-xl text-gray-600 mb-8">Lo sentimos, la noticia que buscas no existe o ha sido eliminada.</p>
                <a href="?action=list"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white font-bold rounded-full shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition duration-300">
                    <span class="material-icons mr-2">arrow_back</span>
                    Volver a Noticias
                </a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Modal de Confirmación de Eliminación -->
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md mx-4 transform scale-95 transition-transform duration-300" id="deleteModalContent">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-6">
                    <span class="material-icons text-red-500 text-2xl">warning</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Confirmar Eliminación</h3>
                <p class="text-gray-600 mb-8">¿Estás seguro de que deseas eliminar esta noticia? Esta acción no se puede deshacer.</p>
                <div class="flex space-x-4">
                    <button onclick="closeDeleteModal()"
                        class="flex-1 bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-full hover:bg-gray-300 transition duration-300">
                        Cancelar
                    </button>
                    <button onclick="executeDelete()"
                        class="flex-1 bg-red-500 text-white font-semibold py-3 px-6 rounded-full hover:bg-red-600 transition duration-300">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteItemId = null;

        // Animaciones de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });

        // Modal de eliminación
        function confirmDelete(id) {
            deleteItemId = id;
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');

            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');

            content.classList.remove('scale-100');
            content.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                deleteItemId = null;
            }, 300);
        }

        function executeDelete() {
            if (deleteItemId) {
                window.location.href = `?action=delete&id=${deleteItemId}`;
            }
        }

        // Preview de imagen en formularios
        document.addEventListener('change', function(e) {
            if (e.target.type === 'file' && e.target.accept.includes('image')) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Crear preview si no existe
                        let preview = document.getElementById('imagePreview');
                        if (!preview) {
                            preview = document.createElement('img');
                            preview.id = 'imagePreview';
                            preview.className = 'mt-4 w-full h-48 object-cover rounded-2xl shadow-lg';
                            e.target.parentNode.appendChild(preview);
                        }
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        // Contador de caracteres para textarea
        document.addEventListener('input', function(e) {
            if (e.target.tagName.toLowerCase() === 'textarea') {
                const maxLength = 500;
                const currentLength = e.target.value.length;

                let counter = document.getElementById('charCounter');
                if (!counter) {
                    counter = document.createElement('div');
                    counter.id = 'charCounter';
                    counter.className = 'text-sm text-gray-500 mt-2 text-right';
                    e.target.parentNode.appendChild(counter);
                }

                counter.textContent = `${currentLength}/${maxLength} caracteres`;

                if (currentLength > maxLength * 0.9) {
                    counter.className = 'text-sm text-red-500 mt-2 text-right font-semibold';
                } else {
                    counter.className = 'text-sm text-gray-500 mt-2 text-right';
                }
            }
        });

        // Efectos de hover mejorados
        document.addEventListener('mouseover', function(e) {
            if (e.target.closest('.news-card')) {
                e.target.closest('.news-card').style.transform = 'translateY(-8px)';
            }
        });

        document.addEventListener('mouseout', function(e) {
            if (e.target.closest('.news-card')) {
                e.target.closest('.news-card').style.transform = 'translateY(0)';
            }
        });

        // Cerrar modal con Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
        });

        // Validación de formularios
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const titulo = form.querySelector('[name="titulo"]');
            const descripcion = form.querySelector('[name="descripcion"]');

            if (titulo && titulo.value.trim().length < 10) {
                e.preventDefault();
                alert('El título debe tener al menos 10 caracteres');
                titulo.focus();
                return false;
            }

            if (descripcion && descripcion.value.trim().length < 20) {
                e.preventDefault();
                alert('La descripción debe tener al menos 20 caracteres');
                descripcion.focus();
                return false;
            }

            // Mostrar indicador de carga
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="material-icons mr-2">hourglass_empty</span>Procesando...';
                submitBtn.disabled = true;
            }
        });
    </script>
</body>

</html>