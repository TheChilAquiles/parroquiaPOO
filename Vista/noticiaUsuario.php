<div class="max-w-4xl mx-auto p-4">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Últimas Noticias</h2>
    
    <?php if (!empty($noticias)): ?>
        <div class="space-y-6">
            <?php foreach ($noticias as $noticia): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img class="w-full h-56 object-cover" src="<?= htmlspecialchars($noticia['imagen']) ?>" alt="Imagen de la noticia">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($noticia['titulo']) ?></h3>
                        <p class="text-gray-600 mb-4"><?= nl2br(htmlspecialchars($noticia['descripcion'])) ?></p>
                        <p class="text-sm text-gray-400">Publicado el: <?= date('d/m/Y', strtotime($noticia['estado_registro'])) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500">En este momento no hay noticias disponibles.</p>
    <?php endif; ?>
</div>