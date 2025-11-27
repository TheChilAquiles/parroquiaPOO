<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<main class="min-h-screen relative bg-gradient-to-br from-[#D0B8A8] via-[#b5a394] to-[#ab876f] flex items-center justify-center overflow-hidden py-10">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute top-20 left-10 floating-animation">
        <div class="w-32 h-32 bg-white/10 rounded-full glass-effect"></div>
    </div>
    <div class="absolute bottom-32 right-16 floating-animation" style="animation-delay: -2s">
        <div class="w-24 h-24 bg-white/10 rounded-full glass-effect"></div>
    </div>

    <div class="relative w-full max-w-4xl px-4 z-10">
        <!-- Header -->
        <div class="text-center mb-8 text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-2 drop-shadow-lg">Perfil</h1>
            <p class="text-lg opacity-90">Ayúdanos a encontrarte o regístrate en nuestra comunidad</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-5 min-h-[600px]">
                
                <!-- Left Side: Search/Info -->
                <div class="md:col-span-2 bg-gradient-to-br from-[#ab876f] to-[#8a6a55] p-8 text-white flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full opacity-10">
                        <span class="material-icons text-[200px] absolute -top-10 -left-10">church</span>
                    </div>
                    
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold mb-6 flex items-center">
                            <span class="material-icons mr-2">search</span>
                            Buscar Feligrés
                        </h2>
                        <p class="mb-6 opacity-90 text-sm">
                            Si ya has participado en nuestra parroquia, es posible que tus datos ya estén registrados. 
                            Ingresa tu documento para verificar.
                        </p>

                        <form method="POST" action="?route=perfil/buscar" onsubmit="return validateSearch(event)" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-1 opacity-80">Tipo Documento</label>
                                <div class="relative">
                                    <select name="tipoDocumento" id="searchTipoDoc" class="w-full bg-white/20 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:bg-white/30 transition appearance-none cursor-pointer">
                                        <option value="" class="text-gray-800">Seleccionar...</option>
                                        <option value="1" class="text-gray-800" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == '1') ? 'selected' : '' ?>>Cédula Ciudadanía</option>
                                        <option value="2" class="text-gray-800" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == '2') ? 'selected' : '' ?>>Tarjeta Identidad</option>
                                        <option value="3" class="text-gray-800" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == '3') ? 'selected' : '' ?>>Cédula Extranjería</option>
                                        <option value="4" class="text-gray-800" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == '4') ? 'selected' : '' ?>>Registro Civil</option>
                                        <option value="5" class="text-gray-800" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == '5') ? 'selected' : '' ?>>Permiso Especial</option>
                                    </select>
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                        <span class="material-icons text-white">expand_more</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-1 opacity-80">Número Documento</label>
                                <div class="relative">
                                    <input type="text" name="numeroDocumento" id="searchNumDoc" 
                                           value="<?= $_POST['numeroDocumento'] ?? '' ?>"
                                           class="w-full bg-white/20 border border-white/30 rounded-xl px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:bg-white/30 transition"
                                           placeholder="Ej: 1020304050">
                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <span class="material-icons text-white opacity-70">badge</span>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="Buscar" value="Buscar">
                            
                            <button type="submit" class="w-full bg-white text-[#ab876f] font-bold py-3 rounded-xl hover:bg-gray-100 transition shadow-lg flex items-center justify-center group">
                                <span>Buscar</span>
                                <span class="material-icons ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </button>
                        </form>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="mt-4 p-3 bg-red-500/20 border border-red-500/50 rounded-xl text-sm flex items-start animate-fade-in">
                                <span class="material-icons text-red-200 text-lg mr-2 mt-0.5">error_outline</span>
                                <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right Side: Personal Data Form -->
                <div class="md:col-span-3 p-8 bg-gray-50 overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Datos Personales</h2>
                        <?php if (isset($_SESSION['feligres_temporal'])): ?>
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full flex items-center">
                                <span class="material-icons text-sm mr-1">check_circle</span>
                                Encontrado
                            </span>
                        <?php else: ?>
                            <span class="bg-gray-200 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">
                                Nuevo Registro
                            </span>
                        <?php endif; ?>
                    </div>

                    <form method="POST" action="?route=perfil/actualizar" id="formDatos" onsubmit="return validateData(event)" class="space-y-5">
                        
                        <!-- Hidden fields to persist search data -->
                        <input type="hidden" name="tipoDocumento" id="hiddenTipo" value="<?= $_POST['tipoDocumento'] ?? ($_SESSION['feligres_temporal']['tipo_documento'] ?? '') ?>">
                        <input type="hidden" name="numeroDocumento" id="hiddenDoc" value="<?= $_POST['numeroDocumento'] ?? ($_SESSION['feligres_temporal']['numero_documento'] ?? '') ?>">
                        <input type="hidden" name="Añadir" value="Añadir">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Nombres -->
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Primer Nombre <span class="text-red-500">*</span></label>
                                <input type="text" name="primerNombre" id="primerNombre" 
                                       value="<?= $_POST['primerNombre'] ?? ($_SESSION['feligres_temporal']['primer_nombre'] ?? '') ?>"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition bg-white"
                                       placeholder="Juan">
                                <p class="text-red-500 text-xs mt-1 hidden" id="error-primerNombre">Campo requerido</p>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Segundo Nombre</label>
                                <input type="text" name="segundoNombre" 
                                       value="<?= $_POST['segundoNombre'] ?? ($_SESSION['feligres_temporal']['segundo_nombre'] ?? '') ?>"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition bg-white"
                                       placeholder="Carlos">
                            </div>

                            <!-- Apellidos -->
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Primer Apellido <span class="text-red-500">*</span></label>
                                <input type="text" name="primerApellido" id="primerApellido" 
                                       value="<?= $_POST['primerApellido'] ?? ($_SESSION['feligres_temporal']['primer_apellido'] ?? '') ?>"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition bg-white"
                                       placeholder="Pérez">
                                <p class="text-red-500 text-xs mt-1 hidden" id="error-primerApellido">Campo requerido</p>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Segundo Apellido</label>
                                <input type="text" name="segundoApellido" 
                                       value="<?= $_POST['segundoApellido'] ?? ($_SESSION['feligres_temporal']['segundo_apellido'] ?? '') ?>"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition bg-white"
                                       placeholder="Gómez">
                            </div>

                            <!-- Contacto -->
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Nacimiento <span class="text-red-500">*</span></label>
                                <input type="date" name="fechaNacimiento" id="fechaNacimiento" 
                                       value="<?= $_POST['fechaNacimiento'] ?? ($_SESSION['feligres_temporal']['fecha_nacimiento'] ?? '') ?>"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition bg-white">
                                <p class="text-red-500 text-xs mt-1 hidden" id="error-fechaNacimiento">Campo requerido</p>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                                <div class="relative">
                                    <input type="tel" name="telefono" 
                                           value="<?= $_POST['telefono'] ?? ($_SESSION['feligres_temporal']['telefono'] ?? '') ?>"
                                           class="w-full border-gray-300 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] pl-10 transition bg-white"
                                           placeholder="300 123 4567">
                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                        <span class="material-icons text-gray-400 text-sm">phone</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="direccion" id="direccion" 
                                       value="<?= $_POST['direccion'] ?? ($_SESSION['feligres_temporal']['direccion'] ?? '') ?>"
                                       class="w-full border-gray-300 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] pl-10 transition bg-white"
                                       placeholder="Calle 123 # 45 - 67">
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                    <span class="material-icons text-gray-400 text-sm">location_on</span>
                                </div>
                            </div>
                            <p class="text-red-500 text-xs mt-1 hidden" id="error-direccion">Campo requerido</p>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] text-white font-bold py-4 rounded-xl hover:shadow-lg hover:scale-[1.01] transition duration-300 flex items-center justify-center">
                                <span class="material-icons mr-2">save</span>
                                Guardar y Continuar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Sincronizar campos de búsqueda con hidden fields
    const searchTipo = document.getElementById('searchTipoDoc');
    const searchDoc = document.getElementById('searchNumDoc');
    const hiddenTipo = document.getElementById('hiddenTipo');
    const hiddenDoc = document.getElementById('hiddenDoc');

    if(searchTipo) {
        searchTipo.addEventListener('change', (e) => {
            if(hiddenTipo) hiddenTipo.value = e.target.value;
        });
    }
    if(searchDoc) {
        searchDoc.addEventListener('input', (e) => {
            if(hiddenDoc) hiddenDoc.value = e.target.value;
        });
    }

    function validateSearch(e) {
        const tipo = document.getElementById('searchTipoDoc').value;
        const doc = document.getElementById('searchNumDoc').value;
        
        if(!tipo || !doc) {
            e.preventDefault();
            alert('Por favor ingresa el tipo y número de documento para buscar.');
            return false;
        }
        return true;
    }

    function validateData(e) {
        let isValid = true;
        const requiredFields = ['primerNombre', 'primerApellido', 'fechaNacimiento', 'direccion'];
        
        // Validar que se haya buscado primero (o que los hidden tengan valor)
        if(!hiddenTipo.value || !hiddenDoc.value) {
            e.preventDefault();
            alert('Por favor realiza la búsqueda de tu documento primero (panel izquierdo).');
            return false;
        }

        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            const error = document.getElementById('error-' + field);
            
            if (!input.value.trim()) {
                input.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                error.classList.remove('hidden');
                isValid = false;
            } else {
                input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                error.classList.add('hidden');
            }
        });

        if (!isValid) {
            e.preventDefault();
            // Shake effect or toast could be added here
        }
        return isValid;
    }
</script>

<style>
    .glass-effect {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    .floating-animation {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
