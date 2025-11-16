<div class="flex flex-1 justify-center items-center p-4">

    <div class="grid grid-cols-5 bg-white border  border-gray-200  w-full h-full p-4 rounded-md gap-2">


        <?php if (isset($cantidad) && $cantidad > 0): ?>

            <?php for ($i = 1; $i <= $cantidad; $i++): ?>

                <form action="<?= url('sacramentos/libro') ?>" method="GET">
                    <input type="hidden" name="route" value="sacramentos/libro">
                    <input type="hidden" name="tipo" value="<?= $tipoId ?>">
                    <input type="hidden" name="numero" value="<?= $i ?>">


                    <button type="submit" class="w-full cursor-pointer relative flex justify-center items-center p-2 rounded-md border border-gray-500/50 hover:bg-emerald-100">
                        <svg class="w-60" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 8v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z" fill="#16a085" />
                            <path d="M3 7v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V7z" fill="#ecf0f1" />
                            <path d="M3 6v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V6z" fill="#bdc3c7" />
                            <path d="M3 5v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V5z" fill="#ecf0f1" />
                            <path d="M5 1a2 2 0 0 0-2 2v18a2 2 0 0 0 2 2h2v-1H5.5a1.5 1.5 0 0 1 0-3H19a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#16a085" />
                            <path d="M8 1v18h11a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#1abc9c" />
                        </svg>
                       <span class="absolute"><?= htmlspecialchars($libroTipo, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class=" absolute inset-x-0 top-17 text-white font-bold text-8xl text-center "><?= $i ?></span>
                    </button>
                </form>

            <?php endfor; ?>

        <?php endif; ?>


        <form action="<?= url('libros/crear') ?>" method="POST">
            <input type="hidden" name="tipo" value="<?= $tipoId ?>">


            <button type="submit" class="w-full cursor-pointer relative flex justify-center items-center p-2 rounded-md border border-gray-500/50 hover:bg-emerald-100 group">
                <svg class="w-60" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 8v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z" fill="#c1c1c1" />
                    <path d="M3 7v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V7z" fill="#ecf0f1" />
                    <path d="M3 6v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V6z" fill="#bdc3c7" />
                    <path d="M3 5v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V5z" fill="#ecf0f1" />
                    <path d="M5 1a2 2 0 0 0-2 2v18a2 2 0 0 0 2 2h2v-1H5.5a1.5 1.5 0 0 1 0-3H19a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#606060" />
                    <path d="M8 1v18h11a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#c1c1c1" />
                </svg>
                <span class=" absolute inset-x-0 top-6 text-white font-bold text-2xl text-center group-hover:text-emerald-100">Añadir </span>
                <span class=" absolute inset-x-0 top-17 text-white font-bold text-8xl text-center group-hover:text-emerald-100 ">+</span>
            </button>
        </form>



        <!-- <div class="relative flex justify-between items-center bg-red-500 p-2 rounded-md">
            <svg class="w-60" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 8v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z" fill="#c1c1c1" />
                <path d="M3 7v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V7z" fill="#ecf0f1" />
                <path d="M3 6v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V6z" fill="#bdc3c7" />
                <path d="M3 5v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V5z" fill="#ecf0f1" />
                <path d="M5 1a2 2 0 0 0-2 2v18a2 2 0 0 0 2 2h2v-1H5.5a1.5 1.5 0 0 1 0-3H19a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#606060" />
                <path d="M8 1v18h11a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#c1c1c1" />
            </svg>

            <span class=" absolute inset-x-0 top-6 text-white font-bold text-2xl text-center ">Añadir</span>
            <span class=" absolute inset-x-0 top-17 text-white font-bold text-8xl text-center ">+</span>
        </div> -->




    </div>






</div>