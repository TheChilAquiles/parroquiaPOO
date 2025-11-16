<div class="flex flex-1 flex-col w-full h-full justify-center items-center">
    <h1 class="text-3xl my-2 font-bold">Registrarse</h1>
    <div class="p-10 rounded-md bg-white">

        <!-- ✅ Formulario de registro con URL limpia -->
        <form action="<?= url('registro/procesar') ?>" method="post" onsubmit="return verifyForm(event)" class="w-[20svw] flex flex-col">
            
            <label for="email">Correo electrónico</label>
            <input 
                name="email" 
                id="email"
                type="email" 
                class="border border-gray-300 rounded p-2 w-full <?php echo isset($_SESSION['error_email']) ? 'border-red-500' : ''; ?>" 
                placeholder="Ingresa Tu Correo electrónico" 
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
            >
            <label name="email-error" class="text-red-500 <?php echo isset($_SESSION['error_email']) ? '' : 'hidden'; ?>">
                <?php 
                    if (isset($_SESSION['error_email'])) {
                        echo htmlspecialchars($_SESSION['error_email'], ENT_QUOTES, 'UTF-8');
                    } else {
                        echo 'Corrije Este Campo';
                    }
                ?>
            </label>
            
            <label class="mt-2" for="password">Contraseña</label>
            <input 
                name="password" 
                id="password"
                type="password" 
                class="border border-gray-300 rounded p-2 w-full <?php echo isset($_SESSION['error_password']) ? 'border-red-500' : ''; ?>" 
                placeholder="Ingresa Tu Contraseña"
            >
            <label name="password-error" class="text-red-500 <?php echo isset($_SESSION['error_password']) ? '' : 'hidden'; ?>">
                <?php 
                    if (isset($_SESSION['error_password'])) {
                        echo htmlspecialchars($_SESSION['error_password'], ENT_QUOTES, 'UTF-8');
                    } else {
                        echo 'Corrije Este Campo';
                    }
                ?>
            </label>
            
            <label class="mt-2" for="password-confirm">Confirmar contraseña</label>
            <input 
                name="password-confirm" 
                id="password-confirm"
                type="password" 
                class="border border-gray-300 rounded p-2 w-full <?php echo isset($_SESSION['error_confirm']) ? 'border-red-500' : ''; ?>" 
                placeholder="Confirma Tu contraseña"
            >
            <label name="confirm-error" class="text-red-500 <?php echo isset($_SESSION['error_confirm']) ? '' : 'hidden'; ?>">
                <?php 
                    if (isset($_SESSION['error_confirm'])) {
                        echo htmlspecialchars($_SESSION['error_confirm'], ENT_QUOTES, 'UTF-8');
                    } else {
                        echo 'Corrije Este Campo';
                    }
                ?>
            </label>
            
            <button 
                class="cursor-pointer bg-[#E3FFCD] p-4 rounded w-[10svw] self-center border border-emerald-500 hover:bg-emerald-500 hover:text-white hover:border-emerald-700 mt-4" 
                type="submit"
            >
                Registrar
            </button>
        </form>

    </div>
</div>

<?php 
// Limpiar mensajes de error después de mostrarlos
if (isset($_SESSION['error_email'])) unset($_SESSION['error_email']);
if (isset($_SESSION['error_password'])) unset($_SESSION['error_password']);
if (isset($_SESSION['error_confirm'])) unset($_SESSION['error_confirm']);
?>

<script>
    function verifyForm(event) {
        event.preventDefault();

        const email = document.querySelector('input[name="email"]');
        const emailError = document.querySelector('label[name="email-error"]');
        
        // Validar correo electrónico
        if (email.value === "") {
            email.classList.add("border-red-500");
            emailError.classList.remove("hidden");
            emailError.textContent = "El correo electrónico es obligatorio.";
            return false;
        } else {
            email.classList.remove("border-red-500");
            emailError.classList.add("hidden");
            
            if (!email.value.includes("@") || !email.value.includes(".")) {
                email.classList.add("border-red-500");
                emailError.classList.remove("hidden");
                emailError.textContent = "El correo electrónico debe contener '@' y '.'";
                return false;
            } else {
                email.classList.remove("border-red-500");
                emailError.classList.add("hidden");

                if (email.value.includes(".")) {
                    const parts = email.value.split(".");
                    if (parts[parts.length - 1].length < 2) {
                        email.classList.add("border-red-500");
                        emailError.classList.remove("hidden");
                        emailError.textContent = "El dominio del correo electrónico debe tener al menos dos caracteres después del punto.";
                        return false;
                    }
                }
            }
        }

        // Validar contraseña
        const password = document.querySelector('input[name="password"]');
        const passwordError = document.querySelector('label[name="password-error"]');

        if (password.value === "") {
            password.classList.add("border-red-500");
            passwordError.classList.remove("hidden");
            passwordError.textContent = "La contraseña es obligatoria.";
            return false;
        } else {
            password.classList.remove("border-red-500");
            passwordError.classList.add("hidden");
        }

        // Validar confirmación de contraseña
        const passwordConfirm = document.querySelector('input[name="password-confirm"]');
        const confirmError = document.querySelector('label[name="confirm-error"]');

        if (passwordConfirm.value === "") {
            passwordConfirm.classList.add("border-red-500");
            confirmError.classList.remove("hidden");
            confirmError.textContent = "La confirmación de contraseña es obligatoria.";
            return false;
        } else {
            passwordConfirm.classList.remove("border-red-500");
            confirmError.classList.add("hidden");
        }

        // Verificar si las contraseñas coinciden
        if (password.value !== passwordConfirm.value) {
            password.classList.add("border-red-500");
            passwordConfirm.classList.add("border-red-500");
            confirmError.classList.remove("hidden");
            confirmError.textContent = "Las contraseñas no coinciden.";
            return false;
        } else {
            password.classList.remove("border-red-500");
            passwordConfirm.classList.remove("border-red-500");
            confirmError.classList.add("hidden");
        }

        // Si todo es válido, enviar formulario
        event.target.submit();
    }
</script>