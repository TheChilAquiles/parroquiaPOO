<main class="min-h-[100dvh] relative bg-gradient-to-br from-[#D0B8A8] via-[#b5a394] to-[#ab876f] flex items-center justify-center overflow-hidden p-3 md:p-4">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#8D7B68] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-[#D0B8A8] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative z-10 w-full max-w-4xl bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="grid md:grid-cols-2">
            <!-- Lado Izquierdo - Branding/Descripción -->
            <div class="hidden md:flex flex-col justify-center p-5 lg:p-6 bg-gradient-to-br from-[#D0B8A8] to-[#8D7B68] text-gray-900 relative overflow-hidden">
                <!-- Elementos decorativos -->
                <div class="absolute top-10 right-10 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-white/30 backdrop-blur-sm rounded-xl mb-3 shadow-lg">
                        <span class="material-icons text-xl text-gray-900">person_add</span>
                    </div>
                    
                    <h1 class="text-xl lg:text-2xl font-bold mb-2 leading-tight">
                        Únete a nuestra comunidad
                    </h1>
                    
                    <p class="text-xs text-gray-800 mb-4 leading-relaxed">
                        Crea tu cuenta y accede a todos los servicios parroquiales de forma digital y segura.
                    </p>
                    
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-white/30 rounded-full flex items-center justify-center">
                                <span class="material-icons text-[10px] text-gray-900">check</span>
                            </div>
                            <span class="text-xs text-gray-800">Solicita certificados digitales</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-white/30 rounded-full flex items-center justify-center">
                                <span class="material-icons text-[10px] text-gray-900">check</span>
                            </div>
                            <span class="text-xs text-gray-800">Realiza pagos en línea</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-white/30 rounded-full flex items-center justify-center">
                                <span class="material-icons text-[10px] text-gray-900">check</span>
                            </div>
                            <span class="text-xs text-gray-800">Consulta tu historial sacramental</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lado Derecho - Formulario -->
            <div class="flex flex-col justify-center p-5 md:p-6">
                <!-- Título móvil -->
                <div class="md:hidden text-center mb-3">
                    <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-[#D0B8A8] to-[#8D7B68] rounded-xl mb-2">
                        <span class="material-icons text-white text-lg">person_add</span>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-0.5">Crear Cuenta</h2>
                    <p class="text-[10px] text-gray-600">Únete a nuestra comunidad</p>
                </div>

                <div class="hidden md:block mb-3">
                    <h2 class="text-lg font-bold text-gray-900 mb-0.5">Crear Cuenta</h2>
                    <p class="text-xs text-gray-600">Completa el formulario para registrarte</p>
                </div>

                <form action="<?= url('registro/procesar') ?>" method="post" onsubmit="return verifyForm(event)" class="space-y-2">
                    
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-bold text-gray-800">Correo Electrónico</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-icons text-gray-500 text-sm group-focus-within:text-[#8D7B68] transition-all duration-300">email</span>
                            </div>
                            <input 
                                name="email" 
                                id="email"
                                type="email" 
                                class="block w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[#8D7B68] focus:border-[#8D7B68] focus:bg-white transition-all duration-300 shadow-sm hover:bg-white font-medium <?php echo isset($_SESSION['error_email']) ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''; ?>" 
                                placeholder="ejemplo@correo.com"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            >
                        </div>
                        <label name="email-error" class="text-red-600 text-[10px] font-bold flex items-center gap-1 <?php echo isset($_SESSION['error_email']) ? '' : 'hidden'; ?>">
                            <span class="material-icons text-[12px]">error</span>
                            <?php echo isset($_SESSION['error_email']) ? htmlspecialchars($_SESSION['error_email'], ENT_QUOTES, 'UTF-8') : 'Verifica este campo'; ?>
                        </label>
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-bold text-gray-800">Contraseña</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-icons text-gray-500 text-sm group-focus-within:text-[#8D7B68] transition-all duration-300">lock</span>
                            </div>
                            <input 
                                name="password" 
                                id="password"
                                type="password" 
                                class="block w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[#8D7B68] focus:border-[#8D7B68] focus:bg-white transition-all duration-300 shadow-sm hover:bg-white font-medium <?php echo isset($_SESSION['error_password']) ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''; ?>" 
                                placeholder="••••••••"
                                oninput="checkPasswordStrength(this.value)"
                            >
                        </div>
                        <!-- Indicador de fuerza de contraseña -->
                        <div id="password-strength" class="hidden">
                            <div class="flex gap-1 mb-0.5 mt-1">
                                <div class="h-1 flex-1 rounded-full bg-gray-200" id="strength-bar-1"></div>
                                <div class="h-1 flex-1 rounded-full bg-gray-200" id="strength-bar-2"></div>
                                <div class="h-1 flex-1 rounded-full bg-gray-200" id="strength-bar-3"></div>
                                <div class="h-1 flex-1 rounded-full bg-gray-200" id="strength-bar-4"></div>
                            </div>
                            <p id="strength-text" class="text-[10px] text-gray-600 font-semibold"></p>
                        </div>
                        <label name="password-error" class="text-red-600 text-[10px] font-bold flex items-center gap-1 <?php echo isset($_SESSION['error_password']) ? '' : 'hidden'; ?>">
                            <span class="material-icons text-[12px]">error</span>
                            <?php echo isset($_SESSION['error_password']) ? htmlspecialchars($_SESSION['error_password'], ENT_QUOTES, 'UTF-8') : 'Verifica este campo'; ?>
                        </label>
                    </div>

                    <div class="space-y-1">
                        <label for="password-confirm" class="block text-xs font-bold text-gray-800">Confirmar Contraseña</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="material-icons text-gray-500 text-sm group-focus-within:text-[#8D7B68] transition-all duration-300">lock_reset</span>
                            </div>
                            <input 
                                name="password-confirm" 
                                id="password-confirm"
                                type="password" 
                                class="block w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-[#8D7B68] focus:border-[#8D7B68] focus:bg-white transition-all duration-300 shadow-sm hover:bg-white font-medium <?php echo isset($_SESSION['error_confirm']) ? 'border-red-500 ring-1 ring-red-500 bg-red-50' : ''; ?>" 
                                placeholder="••••••••"
                            >
                        </div>
                        <label name="confirm-error" class="text-red-600 text-[10px] font-bold flex items-center gap-1 <?php echo isset($_SESSION['error_confirm']) ? '' : 'hidden'; ?>">
                            <span class="material-icons text-[12px]">error</span>
                            <?php echo isset($_SESSION['error_confirm']) ? htmlspecialchars($_SESSION['error_confirm'], ENT_QUOTES, 'UTF-8') : 'Verifica este campo'; ?>
                        </label>
                    </div>

                    <button 
                        type="submit"
                        class="w-full py-2 px-4 bg-gradient-to-r from-[#D0B8A8] to-[#8D7B68] hover:from-[#C8B6A6] hover:to-[#7a6a58] text-white font-bold text-xs rounded-lg shadow-lg transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 group mt-2"
                    >
                        <span>Registrarse</span>
                        <span class="material-icons text-sm transition-transform duration-300 group-hover:translate-x-1">person_add</span>
                    </button>

                </form>

                <div class="mt-4 text-center pt-3 border-t border-gray-200">
                    <p class="text-gray-700 text-xs font-medium">
                        ¿Ya tienes una cuenta? 
                        <a href="<?= url('login') ?>" class="text-[#8D7B68] font-bold hover:text-[#6b5d4f] hover:underline ml-1 transition-all">
                            Inicia Sesión
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php 
// Limpiar mensajes de error después de mostrarlos
if (isset($_SESSION['error_email'])) unset($_SESSION['error_email']);
if (isset($_SESSION['error_password'])) unset($_SESSION['error_password']);
if (isset($_SESSION['error_confirm'])) unset($_SESSION['error_confirm']);
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
    function checkPasswordStrength(password) {
        const strengthIndicator = document.getElementById('password-strength');
        const strengthText = document.getElementById('strength-text');
        const bars = [
            document.getElementById('strength-bar-1'),
            document.getElementById('strength-bar-2'),
            document.getElementById('strength-bar-3'),
            document.getElementById('strength-bar-4')
        ];
        
        if (password.length === 0) {
            strengthIndicator.classList.add('hidden');
            return;
        }
        
        strengthIndicator.classList.remove('hidden');
        
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        // Reset all bars
        bars.forEach(bar => {
            bar.className = 'h-1 flex-1 rounded-full bg-white/20';
        });
        
        // Color bars based on strength
        const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-400'];
        const texts = ['Muy débil', 'Débil', 'Buena', 'Fuerte'];
        
        for (let i = 0; i < strength; i++) {
            bars[i].className = `h-1 flex-1 rounded-full ${colors[strength - 1]} transition-all duration-300`;
        }
        
        strengthText.textContent = texts[strength - 1] || 'Muy débil';
    }

    function verifyForm(event) {
        event.preventDefault();

        const email = document.querySelector('input[name="email"]');
        const emailError = document.querySelector('label[name="email-error"]');
        const password = document.querySelector('input[name="password"]');
        const passwordError = document.querySelector('label[name="password-error"]');
        const confirm = document.querySelector('input[name="password-confirm"]');
        const confirmError = document.querySelector('label[name="confirm-error"]');
        
        let isValid = true;

        // Validar correo
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

        // Validar confirmación
        if (confirm.value === "") {
            confirm.classList.add("border-red-300", "ring-2", "ring-red-300/60", "bg-red-500/10", "animate-shake");
            confirmError.classList.remove("hidden");
            confirmError.innerHTML = '<span class="material-icons text-[14px]">error</span> Confirma tu contraseña';
            isValid = false;
            setTimeout(() => confirm.classList.remove("animate-shake"), 500);
        } else if (password.value !== confirm.value) {
            confirm.classList.add("border-red-300", "ring-2", "ring-red-300/60", "bg-red-500/10", "animate-shake");
            confirmError.classList.remove("hidden");
            confirmError.innerHTML = '<span class="material-icons text-[14px]">error</span> Las contraseñas no coinciden';
            isValid = false;
            setTimeout(() => confirm.classList.remove("animate-shake"), 500);
        } else {
            confirm.classList.remove("border-red-300", "ring-2", "ring-red-300/60", "bg-red-500/10");
            confirmError.classList.add("hidden");
        }

        if (isValid) {
            event.target.submit();
        }
    }
</script>

