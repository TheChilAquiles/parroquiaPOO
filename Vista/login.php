<main class="min-h-screen relative bg-gradient-to-br from-[#D0B8A8] via-[#b5a394] to-[#ab876f] flex items-center justify-center overflow-hidden py-10">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-[#8D7B68] rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-[#C8B6A6] rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>


                <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Bienvenido</h1>
                <p class="text-white/80">Ingresa a tu cuenta para continuar</p>
            </div>

    <div class="relative z-10 w-full max-w-md p-8 mx-4">
        <div class="bg-white/30 backdrop-blur-md border border-white/20 shadow-xl rounded-2xl p-8">
            


            <form action="<?= url('login/procesar') ?>" method="post" onsubmit="return verifyForm(event)" class="space-y-6">
                
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-white ml-1">Correo Electrónico</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons text-white/60 group-focus-within:text-white transition-colors">email</span>
                        </div>
                        <input 
                            name="email" 
                            id="email"
                            type="email" 
                            class="block w-full pl-10 pr-3 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all backdrop-blur-sm <?php echo isset($_SESSION['error_login']) ? 'border-red-400 ring-2 ring-red-400/50' : ''; ?>" 
                            placeholder="ejemplo@correo.com"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        >
                    </div>
                    <label name="email-error" class="text-red-200 text-xs ml-1 font-medium flex items-center gap-1 <?php echo isset($_SESSION['error_login']) ? '' : 'hidden'; ?>">
                        <span class="material-icons text-[14px]">error</span>
                        <?php 
                            if (isset($_SESSION['error_login'])) {
                                echo htmlspecialchars($_SESSION['error_login'], ENT_QUOTES, 'UTF-8');
                            } else {
                                echo 'Verifica este campo';
                            }
                        ?>
                    </label>
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-white ml-1">Contraseña</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-icons text-white/60 group-focus-within:text-white transition-colors">lock</span>
                        </div>
                        <input 
                            name="password" 
                            id="password"
                            type="password" 
                            class="block w-full pl-10 pr-3 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all backdrop-blur-sm <?php echo isset($_SESSION['error_login']) ? 'border-red-400 ring-2 ring-red-400/50' : ''; ?>" 
                            placeholder="••••••••"
                        >
                    </div>
                    <label name="password-error" class="text-red-200 text-xs ml-1 font-medium flex items-center gap-1 <?php echo isset($_SESSION['error_login']) ? '' : 'hidden'; ?>">
                        <span class="material-icons text-[14px]">error</span>
                        Credenciales incorrectas
                    </label>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-white/80 hover:text-white cursor-pointer select-none">
                        <input type="checkbox" class="mr-2 rounded border-white/30 bg-white/10 text-[#8D7B68] focus:ring-offset-0 focus:ring-white/50">
                        Recordarme
                    </label>
                    <a href="<?= url('olvido') ?>" class="text-white/80 hover:text-white font-medium transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button 
                    type="submit"
                    class="w-full py-3 px-4 bg-white/20 hover:bg-white/30 text-white font-bold rounded-xl border border-white/40 shadow-lg backdrop-blur-sm transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2"
                >
                    <span>Iniciar Sesión</span>
                    <span class="material-icons text-sm">arrow_forward</span>
                </button>

            </form>

            <div class="mt-8 text-center">
                <p class="text-white/70 text-sm">
                    ¿No tienes una cuenta? 
                    <a href="<?= url('registro') ?>" class="text-white font-bold hover:underline ml-1">
                        Regístrate aquí
                    </a>
                </p>
            </div>

        </div>
    </div>
</main>

<?php 
// Limpiar mensajes de error después de mostrarlos
if (isset($_SESSION['error_login'])) {
    unset($_SESSION['error_login']);
}
?>

<script>
    function verifyForm(event) {
        event.preventDefault();

        const email = document.querySelector('input[name="email"]');
        const emailError = document.querySelector('label[name="email-error"]');
        const password = document.querySelector('input[name="password"]');
        const passwordError = document.querySelector('label[name="password-error"]');
        
        let isValid = true;

        // Validar correo electrónico
        if (email.value === "") {
            email.classList.add("border-red-400", "ring-2", "ring-red-400/50");
            emailError.classList.remove("hidden");
            emailError.innerHTML = '<span class="material-icons text-[14px]">error</span> El correo es obligatorio';
            isValid = false;
        } else if (!email.value.includes("@") || !email.value.includes(".")) {
            email.classList.add("border-red-400", "ring-2", "ring-red-400/50");
            emailError.classList.remove("hidden");
            emailError.innerHTML = '<span class="material-icons text-[14px]">error</span> Correo inválido';
            isValid = false;
        } else {
            email.classList.remove("border-red-400", "ring-2", "ring-red-400/50");
            emailError.classList.add("hidden");
        }

        // Validar contraseña
        if (password.value === "") {
            password.classList.add("border-red-400", "ring-2", "ring-red-400/50");
            passwordError.classList.remove("hidden");
            passwordError.innerHTML = '<span class="material-icons text-[14px]">error</span> La contraseña es obligatoria';
            isValid = false;
        } else {
            password.classList.remove("border-red-400", "ring-2", "ring-red-400/50");
            passwordError.classList.add("hidden");
        }

        if (isValid) {
            event.target.submit();
        }
    }
</script>