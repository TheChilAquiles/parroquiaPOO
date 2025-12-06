<main class="min-h-[100dvh] relative bg-gradient-to-br from-[#D0B8A8] via-[#b5a394] to-[#ab876f] flex items-center justify-center overflow-hidden p-4">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-[#8D7B68] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-[#D0B8A8] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative z-10 w-full max-w-5xl bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="grid md:grid-cols-2">
            <!-- Lado Izquierdo - Branding/Descripción -->
            <div class="hidden md:flex flex-col justify-center p-6 lg:p-8 bg-gradient-to-br from-[#D0B8A8] to-[#8D7B68] text-gray-900 relative overflow-hidden">
                <!-- Elementos decorativos -->
                <div class="absolute top-10 right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-10 left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-white/30 backdrop-blur-sm rounded-xl mb-3 shadow-lg">
                        <span class="material-icons text-2xl text-gray-900">church</span>
                    </div>
                    
                    <h1 class="text-2xl lg:text-3xl font-bold mb-2 leading-tight">
                        Bienvenido de vuelta
                    </h1>
                    
                    <p class="text-sm text-gray-800 mb-4 leading-relaxed">
                        Accede a tu cuenta para gestionar tus sacramentos, certificados y mantenerte conectado.
                    </p>
                    
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-white/30 rounded-full flex items-center justify-center">
                                <span class="material-icons text-[10px] text-gray-900">check</span>
                            </div>
                            <span class="text-xs text-gray-800">Gestión de certificados en línea</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-white/30 rounded-full flex items-center justify-center">
                                <span class="material-icons text-[10px] text-gray-900">check</span>
                            </div>
                            <span class="text-xs text-gray-800">Consulta de sacramentos</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-white/30 rounded-full flex items-center justify-center">
                                <span class="material-icons text-[10px] text-gray-900">check</span>
                            </div>
                            <span class="text-xs text-gray-800">Pagos seguros y rápidos</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lado Derecho - Formulario -->
            <div class="flex flex-col justify-center p-6">
                <!-- Título móvil -->
                <div class="md:hidden text-center mb-4">
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-[#D0B8A8] to-[#8D7B68] rounded-xl mb-2">
                        <span class="material-icons text-white text-xl">church</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">Bienvenido</h2>
                    <p class="text-xs text-gray-600">Ingresa a tu cuenta</p>
                </div>

                <div class="hidden md:block mb-4">
                    <h2 class="text-xl font-bold text-gray-900 mb-1">Iniciar Sesión</h2>
                    <p class="text-xs text-gray-600">Ingresa tus credenciales para continuar</p>
                </div>

                <form action="<?= url('login/procesar') ?>" method="post" onsubmit="return verifyForm(event)" class="space-y-3">
                    
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-bold text-gray-800">Correo Electrónico</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-icons text-gray-500 text-lg group-focus-within:text-[#8D7B68] transition-all duration-300">email</span>
                            </div>
                            <input 
                                name="email" 
                                id="email"
                                type="email" 
                                class="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[#8D7B68] focus:border-[#8D7B68] focus:bg-white transition-all duration-300 shadow-sm hover:bg-white font-medium <?php echo isset($_SESSION['error_login']) ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''; ?>" 
                                placeholder="ejemplo@correo.com"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            >
                        </div>
                        <label name="email-error" class="text-red-600 text-xs font-bold flex items-center gap-1 <?php echo isset($_SESSION['error_login']) ? '' : 'hidden'; ?>">
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

                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-bold text-gray-800">Contraseña</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-icons text-gray-500 text-lg group-focus-within:text-[#8D7B68] transition-all duration-300">lock</span>
                            </div>
                            <input 
                                name="password" 
                                id="password"
                                type="password" 
                                class="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[#8D7B68] focus:border-[#8D7B68] focus:bg-white transition-all duration-300 shadow-sm hover:bg-white font-medium <?php echo isset($_SESSION['error_login']) ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''; ?>" 
                                placeholder="••••••••"
                            >
                        </div>
                        <label name="password-error" class="text-red-600 text-xs font-bold flex items-center gap-1 <?php echo isset($_SESSION['error_login']) ? '' : 'hidden'; ?>">
                            <span class="material-icons text-[14px]">error</span>
                            Credenciales incorrectas
                        </label>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center text-gray-700 hover:text-gray-900 cursor-pointer select-none transition-colors font-medium">
                            <input type="checkbox" class="mr-2 w-3.5 h-3.5 rounded border-gray-400 bg-gray-100 text-[#8D7B68] focus:ring-offset-0 focus:ring-[#8D7B68] transition-all">
                            <span>Recordarme</span>
                        </label>
                        <a href="<?= url('olvido') ?>" class="text-[#8D7B68] hover:text-[#6b5d4f] font-bold transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button 
                        type="submit"
                        class="w-full py-2.5 px-4 bg-gradient-to-r from-[#D0B8A8] to-[#8D7B68] hover:from-[#C8B6A6] hover:to-[#7a6a58] text-white font-bold text-sm rounded-lg shadow-lg transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 group"
                    >
                        <span>Iniciar Sesión</span>
                        <span class="material-icons text-base transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                    </button>

                </form>

                <div class="mt-6 text-center pt-4 border-t border-gray-200">
                    <p class="text-gray-700 text-xs font-medium">
                        ¿No tienes una cuenta? 
                        <a href="<?= url('registro') ?>" class="text-[#8D7B68] font-bold hover:text-[#6b5d4f] hover:underline ml-1 transition-all">
                            Regístrate aquí
                        </a>
                    </p>
                </div>
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

<style>
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-blob {
        animation: blob 7s infinite;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out;
    }
    
    /* Efecto de shake para errores */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .animate-shake {
        animation: shake 0.5s;
    }
</style>

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
            email.classList.add("border-red-300", "ring-2", "ring-red-300/60", "bg-red-500/10", "animate-shake");
            emailError.classList.remove("hidden");
            emailError.innerHTML = '<span class="material-icons text-[14px]">error</span> El correo es obligatorio';
            isValid = false;
            setTimeout(() => email.classList.remove("animate-shake"), 500);
        } else if (!email.value.includes("@") || !email.value.includes(".")) {
            email.classList.add("border-red-300", "ring-2", "ring-red-300/60", "bg-red-500/10", "animate-shake");
            emailError.classList.remove("hidden");
            emailError.innerHTML = '<span class="material-icons text-[14px]">error</span> Correo inválido';
            isValid = false;
            setTimeout(() => email.classList.remove("animate-shake"), 500);
        } else {
            email.classList.remove("border-red-300", "ring-2", "ring-red-300/60", "bg-red-500/10");
            emailError.classList.add("hidden");
        }

        // Validar contraseña
        if (password.value === "") {
            password.classList.add("border-red-300", "ring-2", "ring-red-300/60", "bg-red-500/10", "animate-shake");
            passwordError.classList.remove("hidden");
            passwordError.innerHTML = '<span class="material-icons text-[14px]">error</span> La contraseña es obligatoria';
            isValid = false;
            setTimeout(() => password.classList.remove("animate-shake"), 500);
        } else {
            password.classList.remove("border-red-300", "ring-2", "ring-red-300/60", "bg-red-500/10");
            passwordError.classList.add("hidden");
        }

        if (isValid) {
            event.target.submit();
        }
    }
</script>
