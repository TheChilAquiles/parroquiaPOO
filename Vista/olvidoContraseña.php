<?php
// Vista/olvido-contrasena.php
// (Asegúrate de tener session_start() en tu index.php)
?>

<div class="flex flex-col w-full h-full justify-center items-center">
    <h1 class="text-3xl my-2 font-bold">Recuperar Contraseña</h1>
    <div class="p-10 rounded-md bg-white">

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="?route=olvido/procesar" method="post" onsubmit="return verifyForm(event)" class="w-[20svw] flex flex-col">
            
            <label for="email">Ingresa tu correo electrónico</label>
            <p class="text-sm text-gray-600 mb-2">Te enviaremos un enlace para restablecer tu contraseña.</p>
            
            <input 
                name="email" 
                id="email"
                type="email" 
                class="border border-gray-300 rounded p-2 w-full" 
                placeholder="tu-correo@ejemplo.com" 
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            >
            <label name="email-error" class="text-red-500 hidden">
                Corrije Este Campo
            </label>
            
            <button 
                class="cursor-pointer bg-[#E3FFCD] p-4 rounded w-[15svw] self-center border border-emerald-500 hover:bg-emerald-500 hover:text-white hover:border-emerald-700 mt-4" 
                type="submit"
            >
                Enviar Enlace
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="?route=login" class="text-sm text-blue-600 hover:underline">
                Volver a Iniciar Sesión
            </a>
        </div>

    </div>
</div>

<script>
    function verifyForm(event) {
        event.preventDefault();

        const email = document.querySelector('input[name="email"]');
        const emailError = document.querySelector('label[name="email-error"]');
        
        let isValid = true;

        // Validar correo electrónico
        if (email.value === "") {
            email.classList.add("border-red-500");
            emailError.classList.remove("hidden");
            emailError.textContent = "El correo electrónico es obligatorio.";
            isValid = false;
        } else if (!email.value.includes("@") || !email.value.includes(".")) {
            email.classList.add("border-red-500");
            emailError.classList.remove("hidden");
            emailError.textContent = "El formato del correo no es válido.";
            isValid = false;
        } else {
            email.classList.remove("border-red-500");
            emailError.classList.add("hidden");
        }

        // Si todo es válido, enviar formulario
        if (isValid) {
            event.target.submit();
        }
    }
</script>