<div class="flex flex-col flex-1 w-full h-full justify-center items-center">
    <h1 class="text-3xl my-2 font-bold">Registrarse</h1>
    <div class="p-10 rounded-md h-80 border border-emerald-500 bg-emerald-50">

        <div class="w-full max-w-md flex flex-col items-center justify-center text-center"> 

            <h2 class="text-2xl font-bold text-green-600 mb-4">¡Registro Exitoso!</h2>
            <p class="mb-6 text-lg text-gray-700">
                <span class="font-bold"><?= htmlspecialchars($_SESSION['temp_email'] ?? 'Tu cuenta', ENT_QUOTES, 'UTF-8') ?></span> ha sido registrada. 
                <br>Ya puedes usar el sistema.
            </p>

            <a href="?route=login" class="px-8 py-3 bg-[#ab876f] text-white font-bold rounded-xl shadow-lg hover:bg-[#99775f] transition transform hover:-translate-y-1 block">
                Iniciar Sesión
            </a>
            
            <?php unset($_SESSION['temp_email']); ?>
         

        </div>


    </div>
</div>