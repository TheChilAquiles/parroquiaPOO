<main class="max-w-7xl mx-auto p-4 md:p-8 w-full">

    <!-- Header Section -->
    <section class="mb-8 rounded-3xl bg-white p-6 md:p-8 shadow-lg border border-stone-200">
        <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
            <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-[#F4EBE7] border border-[#E6D5CC] flex-shrink-0">
                <svg class="h-10 w-10 text-[#8D7B68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>

            <div class="flex-1 space-y-2">
                <h1 class="text-3xl font-bold text-[#5A4D41] tracking-tight">
                    Libros de <?= htmlspecialchars($libroTipo ?? 'Sacramentos') ?>
                </h1>
                <p class="text-lg text-gray-600 font-medium leading-relaxed">
                    Gestión de libros parroquiales.
                    <span class="block text-gray-500 text-base">Seleccione un tomo para ver sus registros o añada uno nuevo.</span>
                </p>
            </div>

            <div class="hidden md:block">
                <a href="<?= url('sacramentos') ?>" class="inline-flex items-center space-x-2 rounded-full bg-[#F4EBE7] px-5 py-3 border border-[#E6D5CC] transition-all hover:bg-[#E6D5CC]">
                    <svg class="h-5 w-5 text-[#8D7B68]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="text-sm font-bold text-[#8D7B68]">Volver a Tipos</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Libros Grid -->
    <section class="rounded-3xl bg-white shadow-lg border border-stone-200 p-6 md:p-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

            <?php if (isset($cantidad) && $cantidad > 0): ?>
                <?php for ($i = 1; $i <= $cantidad; $i++): ?>
                    <a href="<?= url('sacramentos/libro') ?>?tipo=<?= $tipoId ?>&numero=<?= $i ?>" 
                       class="group relative flex flex-col justify-center items-center p-4 rounded-2xl border-2 border-[#E6D5CC] bg-[#faf6f4] transition-all duration-300 hover:scale-[1.03] hover:shadow-lg hover:border-[#8D7B68] hover:bg-white">
                        
                        <div class="relative w-3/4 aspect-[3/4] mb-3 transition-transform group-hover:-translate-y-1">
                            <!-- Book Icon styled -->
                            <svg class="w-full h-full drop-shadow-md" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 8v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z" fill="#8D7B68" /> <!-- Cover -->
                                <path d="M3 7v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V7z" fill="#F4EBE7" /> <!-- Pages side -->
                                <path d="M3 6v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V6z" fill="#E6D5CC" /> <!-- Decor -->
                                <path d="M3 5v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V5z" fill="#F4EBE7" /> <!-- Top page edge -->
                                <path d="M5 1a2 2 0 0 0-2 2v18a2 2 0 0 0 2 2h2v-1H5.5a1.5 1.5 0 0 1 0-3H19a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#6b5d4f" /> <!-- Spine -->
                                <path d="M8 1v18h11a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#8D7B68" /> <!-- Front -->
                            </svg>
                            
                            <!-- Number Overlay -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center pt-4">
                                <span class="text-[#F4EBE7] font-bold text-lg md:text-xl drop-shadow-sm opacity-90 leading-none mb-1">
                                    <?= htmlspecialchars($libroTipo, ENT_QUOTES, 'UTF-8') ?>
                                </span>
                                <span class="text-white font-black text-5xl md:text-6xl drop-shadow-md tracking-tighter">
                                    <?= $i ?>
                                </span>
                            </div>
                        </div>

           
                    </a>
                <?php endfor; ?>
            <?php endif; ?>

            <!-- Add New Book Button -->
            <a href="<?= url('libros/crear') ?>?tipo=<?= $tipoId ?>" 
               class="group flex flex-col justify-center items-center p-4 rounded-2xl border-2 border-dashed border-[#d6c4b8] bg-white transition-all duration-300 hover:scale-[1.03] hover:shadow-lg hover:border-[#8D7B68] hover:bg-[#faf6f4]">
                
                <div class="flex items-center justify-center w-20 h-20 rounded-full bg-[#F4EBE7] mb-4 group-hover:bg-[#E6D5CC] transition-colors">
                    <svg class="w-10 h-10 text-[#8D7B68]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                
                <span class="text-[#8D7B68] font-bold text-lg group-hover:text-[#6b5d4f]">Añadir Nuevo</span>
                
            </a>

        </div>
    </section>

</main>