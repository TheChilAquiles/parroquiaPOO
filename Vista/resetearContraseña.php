<?php
// Vista/resetear-contrasena.php
// (Asegúrate de tener session_start() en tu index.php)

// El controlador (LoginController->mostrarFormularioReseteo) 
// debe pasar la variable $token a esta vista.
if (!isset($token)) {
    // Si $token no fue definido por el controlador, morimos.
    die('Error: Token no proporcionado.'); 
}
?>

<div class="flex flex-1 flex-col w-full h-full justify-center items-center">
    <h1 class="text-3xl my-2 font-bold">Crear Nueva Contraseña</h1>
    <div class="p-10 rounded-md bg-white">

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="?route=resetear/procesar" method="post" onsubmit="return verifyForm(event)" class="w-[20svw] flex flex-col">
            
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
            
            <label class="mt-2" for="password">Nueva Contraseña</label>
            <input 
                name="password" 
                id="password"
                type="password" 
                class="border border-gray-300 rounded p-2 w-full" 
                placeholder="Ingresa tu nueva contraseña"
            >
            <label name="password-error" class="text-red-500 hidden">
                La contraseña es obligatoria.
            </label>
            
            <label class="mt-2" for="password_confirm">Confirmar Contraseña</label>
            <input 
                name="password_confirm" 
                id="password_confirm"
                type="password" 
                class="border border-gray-300 rounded p-2 w-full" 
                placeholder="Confirma tu nueva contraseña"
            >
            <label name="password_confirm-error" class="text-red-500 hidden">
                Las contraseñas no coinciden.
            </label>
            
            <button 
                class="cursor-pointer bg-[#E3FFCD] p-4 rounded w-[15svw] self-center border border-emerald-500 hover:bg-emerald-500 hover:text-white hover:border-emerald-700 mt-4" 
                type="submit"
            >
                Actualizar Contraseña
            </button>
        </form>

    </div>
</div>

<script>
    function verifyForm(event) {
        event.preventDefault();

        const password = document.querySelector('input[name="password"]');
        const passwordError = document.querySelector('label[name="password-error"]');
        const confirm = document.querySelector('input[name="password_confirm"]');
        const confirmError = document.querySelector('label[name="password_confirm-error"]');
        
        let isValid = true;

        // Validar contraseña
        if (password.value === "") {
            password.classList.add("border-red-500");
            passwordError.classList.remove("hidden");
            passwordError.textContent = "La contraseña es obligatoria.";
            isValid = false;
        } else if (password.value.length < 8) {
            password.classList.add("border-red-500");
            passwordError.classList.remove("hidden");
            passwordError.textContent = "La contraseña debe tener al menos 8 caracteres.";
            isValid = false;
        } else {
            password.classList.remove("border-red-500");
            passwordError.classList.add("hidden");
        }

        // Validar confirmación
        if (confirm.value === "") {
            confirm.classList.add("border-red-500");
            confirmError.classList.remove("hidden");
            confirmError.textContent = "Debes confirmar la contraseña.";
            isValid = false;
        } else if (password.value !== confirm.value) {
            confirm.classList.add("border-red-500");
            confirmError.classList.remove("hidden");
            confirmError.textContent = "Las contraseñas no coinciden.";
            isValid = false;
        } else {
            confirm.classList.remove("border-red-500");
            confirmError.classList.add("hidden");
        }

        // Si todo es válido, enviar formulario
        if (isValid) {
            event.target.submit();
        }
    }
</script>