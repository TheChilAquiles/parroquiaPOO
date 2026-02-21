<div class="flex flex-col flex-1 w-full h-full justify-center items-center bg-[#8D7B68] py-10">

    <h1 class="text-3xl my-6 font-bold text-white tracking-wide">
        
    </h1>

    <div class="p-10 rounded-2xl w-full max-w-lg border border-white/30 bg-white shadow-2xl">

        <div class="w-full flex flex-col items-center justify-center text-center">

            <!-- Ícono decorativo -->
            <div class="w-16 h-16 flex items-center justify-center rounded-full bg-[#8D7B68]/20 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#8D7B68]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-[#8D7B68] mb-4">
                ¡Registro Exitoso!
            </h2>

            <p class="mb-6 text-lg text-gray-700 leading-relaxed">
                <span class="font-semibold text-[#8D7B68]">
                    <?= htmlspecialchars($_SESSION['temp_email'] ?? 'Tu cuenta', ENT_QUOTES, 'UTF-8') ?>
                </span>
                ha sido registrada.
                <br>
                Ya puedes usar el sistema.
            </p>

            <a href="?route=login"
               class="px-8 py-3 bg-[#8D7B68] text-white font-bold rounded-xl shadow-lg hover:bg-[#7a6959] transition transform hover:-translate-y-1 hover:shadow-xl">
                Iniciar Sesión
            </a>

            <?php unset($_SESSION['temp_email']); ?>

        </div>

    </div>
</div>