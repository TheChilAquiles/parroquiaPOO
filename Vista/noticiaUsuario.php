<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 text-center mb-10">
        Últimas Noticias
    </h1>

    <?php if (isset($noticias) && !empty($noticias)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($noticias as $noticia): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img class="w-full h-48 object-cover" src="<?php echo htmlspecialchars($noticia['imagen']); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                    <div class="p-4">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">
                            <?php echo htmlspecialchars($noticia['titulo']); ?>
                        </h2>
                        <p class="text-gray-600 text-sm mb-4">
                            <?php echo htmlspecialchars($noticia['descripcion']); ?>
                        </p>
                        <p class="text-gray-500 text-xs text-right">
                            Publicado el: <?php echo htmlspecialchars($noticia['fecha']); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-10 bg-white rounded-lg shadow-md">
            <p class="text-gray-500 text-lg">No hay noticias para mostrar en este momento.</p>
        </div>
    <?php endif; ?>
</div>