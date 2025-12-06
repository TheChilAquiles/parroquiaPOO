<?php
// Determinar si es modo edición o nuevo registro
$hasData = isset($feligres) && !empty($feligres);
$modoEdicion = $hasData;

// Valores iniciales
$tipoDoc = $feligres['tipo_documento_id'] ?? '';
$numDoc = $feligres['numero_documento'] ?? '';
$primerNombre = $feligres['primer_nombre'] ?? '';
$segundoNombre = $feligres['segundo_nombre'] ?? '';
$primerApellido = $feligres['primer_apellido'] ?? '';
$segundoApellido = $feligres['segundo_apellido'] ?? '';
$fechaNacimiento = isset($feligres['fecha_nacimiento']) ? date('Y-m-d', strtotime($feligres['fecha_nacimiento'])) : '';
$telefono = $feligres['telefono'] ?? '';
$direccion = $feligres['direccion'] ?? '';
?>

<!-- Estilos Específicos -->
<style>
    .step-circle {
        transition: all 0.3s ease;
    }
    .step-line {
        transition: all 0.3s ease;
    }
    [x-cloak] { display: none !important; }
</style>

<main class="flex-1 relative bg-gradient-to-br from-[#D0B8A8] via-[#b5a394] to-[#ab876f] flex items-center justify-center py-10 min-h-screen px-4">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl animate-pulse"></div>
    <div class="absolute bottom-32 right-16 w-40 h-40 bg-[#8D7B68]/20 rounded-full blur-3xl animate-pulse delay-1000"></div>

    <div class="relative w-full max-w-3xl z-10" x-data="{ 
        step: 1,
        totalSteps: 3,
        isEdit: <?= $modoEdicion ? 'true' : 'false' ?>,
        
        // Form Data Models
        data: {
            primerNombre: '<?= htmlspecialchars($primerNombre) ?>',
            primerApellido: '<?= htmlspecialchars($primerApellido) ?>',
            fechaNacimiento: '<?= $fechaNacimiento ?>',
            tipoDocumento: '<?= $tipoDoc ?>',
            numeroDocumento: '<?= $numDoc ?>',
            telefono: '<?= htmlspecialchars($telefono) ?>',
            direccion: '<?= htmlspecialchars($direccion) ?>'
        },

        init() {
            // Si es edición, mostramos todo de una vez (no wizard)
            if(this.isEdit) this.step = 4;
        },

        validateStep(currentStep) {
            // STEP 1: Datos Básicos
            if(currentStep === 1) {
                if(!this.data.primerNombre || this.data.primerNombre.length < 3) { 
                    this.showError('El primer nombre debe tener al menos 3 letras.'); return false; 
                }
                if(!this.data.primerApellido || this.data.primerApellido.length < 3) { 
                    this.showError('El primer apellido debe tener al menos 3 letras.'); return false; 
                }
                if(!this.data.fechaNacimiento) { 
                    this.showError('La fecha de nacimiento es obligatoria.'); return false; 
                }
                
                const birthDate = new Date(this.data.fechaNacimiento);
                const today = new Date();
                if(birthDate > today) {
                    this.showError('La fecha de nacimiento no puede ser en el futuro.'); return false;
                }
                if(birthDate.getFullYear() < 1900) {
                     this.showError('Fecha de nacimiento no válida.'); return false;
                }
                return true;
            }

            // STEP 2: Identificación
            if(currentStep === 2) {
                if(!this.data.tipoDocumento) { 
                    this.showError('Selecciona el tipo de documento.'); return false; 
                }
                if(!this.data.numeroDocumento || this.data.numeroDocumento.length < 5) { 
                    this.showError('El número de documento debe tener al menos 5 dígitos.'); return false; 
                }
                if(!/^\d+$/.test(this.data.numeroDocumento)) {
                    this.showError('El número de documento solo debe contener números.'); return false;
                }
                return true;
            }

            // STEP 3: Contacto
            if(currentStep === 3) {
                if(this.data.telefono && !/^\d{7,10}$/.test(this.data.telefono.replace(/\s/g, ''))) {
                    this.showError('El teléfono debe tener entre 7 y 10 dígitos.'); return false;
                }
                if(!this.data.direccion || this.data.direccion.length < 5) { 
                    this.showError('Ingresa una dirección válida (ej: Calle 10 # 20-30).'); return false; 
                }
                return true;
            }
            return true;
        },

        showError(msg) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: msg,
                confirmButtonColor: '#ab876f'
            });
        },

        nextStep() {
            if(this.validateStep(this.step)) {
                if(this.step < this.totalSteps) {
                    this.step++;
                } else {
                   // Submit form
                   document.getElementById('profileForm').submit();
                }
            }
        }
    }">
        
        <!-- Header Section -->
        <div class="text-center mb-8 text-white">
            <h1 class="text-4xl font-bold mb-2 drop-shadow-md">
                <?= $modoEdicion ? 'Tu Perfil' : 'Completa tu Registro' ?>
            </h1>
            <p class="text-lg opacity-90 font-light">
                <?= $modoEdicion ? 'Mantén tu información actualizada' : 'Solo te tomará unos minutos' ?>
            </p>
        </div>

        <!-- Wizard Progress Bar (Solo nuevos) -->
        <div x-show="!isEdit" class="mb-8" x-transition>
            <div class="flex items-center justify-between relative max-w-xl mx-auto px-4">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-white/30 rounded-full -z-0"></div>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-white rounded-full -z-0 transition-all duration-500"
                     :style="'width: ' + ((step - 1) / (totalSteps - 1) * 100) + '%'"></div>

                <template x-for="i in totalSteps">
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 transform"
                             :class="step >= i ? 'bg-white text-[#ab876f] scale-110 shadow-lg' : 'bg-[#ab876f] text-white/70 border-2 border-white/30'">
                            <span x-text="i"></span>
                        </div>
                        <span class="mt-2 text-xs font-medium text-white/90" 
                              x-text="i===1 ? 'Básicos' : (i===2 ? 'Identidad' : 'Contacto')"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-6 md:p-10 border border-white/50">
            
            <form action="?route=perfil/actualizar" method="POST" id="profileForm" class="space-y-6">
                
                <!-- STEP 1: Datos Básicos -->
                <div x-show="step === 1 || isEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="mb-6 border-b border-gray-100 pb-2" x-show="isEdit">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="material-icons mr-2 text-[#ab876f]">person</span> Datos Básicos
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Primer Nombre <span class="text-red-500">*</span></label>
                            <input type="text" name="primerNombre" x-model="data.primerNombre" required
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Segundo Nombre</label>
                            <input type="text" name="segundoNombre" value="<?= htmlspecialchars($segundoNombre) ?>"
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Primer Apellido <span class="text-red-500">*</span></label>
                            <input type="text" name="primerApellido" x-model="data.primerApellido" required
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Segundo Apellido</label>
                            <input type="text" name="segundoApellido" value="<?= htmlspecialchars($segundoApellido) ?>"
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <div class="form-group md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Nacimiento <span class="text-red-500">*</span></label>
                            <input type="date" name="fechaNacimiento" x-model="data.fechaNacimiento" required
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Identidad -->
                <div x-show="step === 2 || isEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="mb-6 border-b border-gray-100 pb-2 mt-6" x-show="isEdit">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="material-icons mr-2 text-[#ab876f]">badge</span> Identificación
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo Documento <span class="text-red-500">*</span></label>
                            <select name="tipoDocumento" x-model="data.tipoDocumento" required
                                    class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                                <option value="">Seleccione...</option>
                                <option value="1">Cédula de Ciudadanía</option>
                                <option value="2">Tarjeta de Identidad</option>
                                <option value="3">Cédula de Extranjería</option>
                                <option value="4">Registro Civil</option>
                                <option value="5">Permiso Especial</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Número Documento <span class="text-red-500">*</span></label>
                            <input type="text" name="numeroDocumento" x-model="data.numeroDocumento" required
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Contacto -->
                <div x-show="step === 3 || isEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="mb-6 border-b border-gray-100 pb-2 mt-6" x-show="isEdit">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="material-icons mr-2 text-[#ab876f]">contact_mail</span> Contacto
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                            <input type="tel" name="telefono" x-model="data.telefono"
                                   placeholder="Ej: 300 123 4567"
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <div class="form-group md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="material-icons absolute left-3 top-3.5 text-gray-400">location_on</span>
                                <input type="text" name="direccion" x-model="data.direccion" required
                                       placeholder="Ej: Calle 123 # 45 - 67"
                                       class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3 pl-10">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between pt-8 border-t border-gray-100 mt-6">
                    <button type="button" x-show="step > 1 && !isEdit" @click="step--"
                            class="px-6 py-2.5 rounded-xl text-gray-600 font-semibold hover:bg-gray-100 transition flex items-center">
                        <span class="material-icons mr-1">arrow_back</span>
                        Atrás
                    </button>
                    
                    <div class="flex-1"></div>

                    <button type="button" @click="nextStep()"
                            class="bg-[#ab876f] text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-[#8D7B68] hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center">
                        <span x-text="step === 3 || isEdit ? 'Guardar Información' : 'Siguiente'"></span>
                        <span class="material-icons ml-2" x-text="step === 3 || isEdit ? 'save' : 'arrow_forward'"></span>
                    </button>
                </div>

            </form>
        </div>
        

    </div>
</main>
