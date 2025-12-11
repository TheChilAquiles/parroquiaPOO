<?php
// Determine if it's edit mode or new record mode
// Check if $feligres variable exists and is not empty
$hasData = isset($feligres) && !empty($feligres);
// Set edit mode flag based on data existence
$modoEdicion = $hasData;

// Initial values - use null coalescing operator (??) to provide default empty string if data doesn't exist
// This prevents errors when accessing non-existent array keys
$tipoDoc = $feligres['tipo_documento_id'] ?? '';
$numDoc = $feligres['numero_documento'] ?? '';
$primerNombre = $feligres['primer_nombre'] ?? '';
$segundoNombre = $feligres['segundo_nombre'] ?? '';
$primerApellido = $feligres['primer_apellido'] ?? '';
$segundoApellido = $feligres['segundo_apellido'] ?? '';
// Format birth date to Y-m-d format if it exists, otherwise empty string
$fechaNacimiento = isset($feligres['fecha_nacimiento']) ? date('Y-m-d', strtotime($feligres['fecha_nacimiento'])) : '';
$telefono = $feligres['telefono'] ?? '';
$direccion = $feligres['direccion'] ?? '';
?>

<!-- Specific Styles for this page -->
<style>
    /* Step circle transition effect - smooth animation for wizard progress */
    .step-circle {
        transition: all 0.3s ease;
    }
    /* Step line transition effect - for progress bar animation */
    .step-line {
        transition: all 0.3s ease;
    }
    /* Hide elements with x-cloak attribute (Alpine.js feature to prevent flash) */
    [x-cloak] { display: none !important; }
</style>

<!-- Main container with gradient background and flex layout -->
<main class="flex-1 relative bg-gradient-to-br from-[#D0B8A8] via-[#b5a394] to-[#ab876f] flex items-center justify-center py-10 min-h-screen px-4">
    <!-- Background Elements - decorative overlays -->
    <!-- Dark overlay for better text readability -->
    <div class="absolute inset-0 bg-black/10"></div>
    <!-- Animated blur circle (top left) - creates depth -->
    <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl animate-pulse"></div>
    <!-- Animated blur circle (bottom right) - delayed animation -->
    <div class="absolute bottom-32 right-16 w-40 h-40 bg-[#8D7B68]/20 rounded-full blur-3xl animate-pulse delay-1000"></div>

    <!-- Main form container with Alpine.js reactive data -->
    <!-- x-data initializes Alpine.js component with reactive state (ENCAPSULATION: data is contained within this component) -->
    <div class="relative w-full max-w-3xl z-10" x-data="{ 
        step: 1,  // Current wizard step (starts at 1)
        totalSteps: 3,  // Total number of steps in wizard
        isEdit: <?= $modoEdicion ? 'true' : 'false' ?>,  // Edit mode flag from PHP
        
        // Form Data Models (ENCAPSULATION: all form data in one object)
        data: {
            // Escape HTML special characters to prevent XSS attacks (security best practice)
            primerNombre: '<?= htmlspecialchars($primerNombre) ?>',
            primerApellido: '<?= htmlspecialchars($primerApellido) ?>',
            fechaNacimiento: '<?= $fechaNacimiento ?>',
            tipoDocumento: '<?= $tipoDoc ?>',
            numeroDocumento: '<?= $numDoc ?>',
            telefono: '<?= htmlspecialchars($telefono) ?>',
            direccion: '<?= htmlspecialchars($direccion) ?>'
        },

        // Initialization method - runs when component is created (LIFECYCLE METHOD)
        init() {
            // If edit mode, skip wizard and show all fields (step 4 = all visible)
            if(this.isEdit) this.step = 4;
        },

        // Validation method for each step (METHOD: performs specific task)
        // Parameter: currentStep - the step number to validate
        // Returns: boolean - true if valid, false if invalid
        validateStep(currentStep) {
            // STEP 1: Basic Data Validation
            if(currentStep === 1) {
                // Check first name exists and has minimum 3 characters
                if(!this.data.primerNombre || this.data.primerNombre.length < 3) { 
                    this.showError('El primer nombre debe tener al menos 3 letras.'); 
                    return false; 
                }
                // Check first last name exists and has minimum 3 characters
                if(!this.data.primerApellido || this.data.primerApellido.length < 3) { 
                    this.showError('El primer apellido debe tener al menos 3 letras.'); 
                    return false; 
                }
                // Check birth date is provided
                if(!this.data.fechaNacimiento) { 
                    this.showError('La fecha de nacimiento es obligatoria.'); 
                    return false; 
                }
                
                // Create Date object from birth date string
                const birthDate = new Date(this.data.fechaNacimiento);
                const today = new Date();
                // Validate birth date is not in the future
                if(birthDate > today) {
                    this.showError('La fecha de nacimiento no puede ser en el futuro.'); 
                    return false;
                }
                // Validate birth date is not before year 1900 (reasonable range)
                if(birthDate.getFullYear() < 1900) {
                     this.showError('Fecha de nacimiento no válida.'); 
                     return false;
                }
                return true;
            }

            // STEP 2: Identification Validation
            if(currentStep === 2) {
                // Check document type is selected
                if(!this.data.tipoDocumento) { 
                    this.showError('Selecciona el tipo de documento.'); 
                    return false; 
                }
                // Check document number exists and has minimum 5 digits
                if(!this.data.numeroDocumento || this.data.numeroDocumento.length < 5) { 
                    this.showError('El número de documento debe tener al menos 5 dígitos.'); 
                    return false; 
                }
                // Validate document number contains only digits using regex
                // ^\d+$ means: start(^) + one or more digits(\d+) + end($)
                if(!/^\d+$/.test(this.data.numeroDocumento)) {
                    this.showError('El número de documento solo debe contener números.'); 
                    return false;
                }
                return true;
            }

            // STEP 3: Contact Information Validation
            if(currentStep === 3) {
                // If phone is provided, validate it has 7-10 digits
                // Remove spaces and check with regex: ^\d{7,10}$ means 7 to 10 digits
                if(this.data.telefono && !/^\d{7,10}$/.test(this.data.telefono.replace(/\s/g, ''))) {
                    this.showError('El teléfono debe tener entre 7 y 10 dígitos.'); 
                    return false;
                }
                // Address is required and must have minimum 5 characters
                if(!this.data.direccion || this.data.direccion.length < 5) { 
                    this.showError('Ingresa una dirección válida (ej: Calle 10 # 20-30).'); 
                    return false; 
                }
                return true;
            }
            // If no specific validation for step, return true (valid)
            return true;
        },

        // Method to show error messages using SweetAlert2 library
        // Parameter: msg - error message string to display
        showError(msg) {
            // Display modal alert with warning icon and custom color
            Swal.fire({
                icon: 'warning',  // Warning icon
                title: 'Atención',  // Alert title
                text: msg,  // Error message from parameter
                confirmButtonColor: '#ab876f'  // Custom button color matching theme
            });
        },

        // Method to navigate to next step or submit form
        nextStep() {
            // Validate current step before proceeding
            if(this.validateStep(this.step)) {
                // If not on last step, move to next step
                if(this.step < this.totalSteps) {
                    this.step++;  // Increment step counter
                } else {
                   // On last step: submit the form
                   // Get form element by ID and call submit method
                   document.getElementById('profileForm').submit();
                }
            }
        }
    }">
        
        <!-- Header Section - title and description -->
        <div class="text-center mb-8 text-white">
            <!-- Dynamic title based on edit mode -->
            <h1 class="text-4xl font-bold mb-2 drop-shadow-md">
                <?= $modoEdicion ? 'Tu Perfil' : 'Completa tu Registro' ?>
            </h1>
            <!-- Dynamic description based on edit mode -->
            <p class="text-lg opacity-90 font-light">
                <?= $modoEdicion ? 'Mantén tu información actualizada' : 'Solo te tomará unos minutos' ?>
            </p>
        </div>

        <!-- Wizard Progress Bar (Only for new registrations) -->
        <!-- x-show hides element when condition is false (reactive visibility) -->
        <!-- x-transition adds smooth fade animation -->
        <div x-show="!isEdit" class="mb-8" x-transition>
            <!-- Progress bar container with relative positioning for overlays -->
            <div class="flex items-center justify-between relative max-w-xl mx-auto px-4">
                <!-- Background line (gray/inactive state) -->
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-white/30 rounded-full -z-0"></div>
                <!-- Active progress line (white) - width changes based on current step -->
                <!-- :style binds inline style dynamically - calculates percentage based on progress -->
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-white rounded-full -z-0 transition-all duration-500"
                     :style="'width: ' + ((step - 1) / (totalSteps - 1) * 100) + '%'"></div>

                <!-- Loop through steps using Alpine.js template (ITERATION) -->
                <template x-for="i in totalSteps">
                    <!-- Step indicator container -->
                    <div class="relative z-10 flex flex-col items-center">
                        <!-- Step circle - changes appearance based on current step -->
                        <!-- :class dynamically adds classes based on condition (conditional styling) -->
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 transform"
                             :class="step >= i ? 'bg-white text-[#ab876f] scale-110 shadow-lg' : 'bg-[#ab876f] text-white/70 border-2 border-white/30'">
                            <!-- Display step number -->
                            <span x-text="i"></span>
                        </div>
                        <!-- Step label below circle -->
                        <!-- x-text dynamically sets text content based on step number -->
                        <span class="mt-2 text-xs font-medium text-white/90" 
                              x-text="i===1 ? 'Básicos' : (i===2 ? 'Identidad' : 'Contacto')"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Main Form Card with glassmorphism effect (backdrop-blur) -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-6 md:p-10 border border-white/50">
            
            <!-- HTML Form element - submits data to server -->
            <!-- action: server endpoint to process form -->
            <!-- method: POST sends data in request body (more secure than GET) -->
            <form action="?route=perfil/actualizar" method="POST" id="profileForm" class="space-y-6">
                
                <!-- STEP 1: Basic Data Section -->
                <!-- Visible on step 1 OR in edit mode (edit mode shows all sections) -->
                <!-- x-transition adds smooth entrance animation -->
                <div x-show="step === 1 || isEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <!-- Section header (only visible in edit mode) -->
                    <div class="mb-6 border-b border-gray-100 pb-2" x-show="isEdit">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <!-- Material icon for person -->
                            <span class="material-icons mr-2 text-[#ab876f]">person</span> Datos Básicos
                        </h2>
                    </div>

                    <!-- Grid layout - 1 column on mobile, 2 columns on desktop (md breakpoint) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- First Name field -->
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Primer Nombre <span class="text-red-500">*</span>
                            </label>
                            <!-- x-model creates two-way data binding with Alpine.js data -->
                            <!-- Changes in input update data.primerNombre and vice versa (BINDING) -->
                            <input type="text" name="primerNombre" x-model="data.primerNombre" required
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <!-- Second Name field (optional) -->
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Segundo Nombre</label>
                            <!-- Value from PHP variable (no Alpine binding as it's optional) -->
                            <input type="text" name="segundoNombre" value="<?= htmlspecialchars($segundoNombre) ?>"
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <!-- First Last Name field -->
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Primer Apellido <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="primerApellido" x-model="data.primerApellido" required
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <!-- Second Last Name field (optional) -->
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Segundo Apellido</label>
                            <input type="text" name="segundoApellido" value="<?= htmlspecialchars($segundoApellido) ?>"
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <!-- Birth Date field (spans 2 columns on desktop) -->
                        <div class="form-group md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Fecha Nacimiento <span class="text-red-500">*</span>
                            </label>
                            <!-- Date input type provides native date picker -->
                            <input type="date" name="fechaNacimiento" x-model="data.fechaNacimiento" required
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Identification Section -->
                <!-- Visible on step 2 OR in edit mode -->
                <div x-show="step === 2 || isEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <!-- Section header (only in edit mode, with margin top for spacing) -->
                    <div class="mb-6 border-b border-gray-100 pb-2 mt-6" x-show="isEdit">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <!-- Material icon for badge/ID -->
                            <span class="material-icons mr-2 text-[#ab876f]">badge</span> Identificación
                        </h2>
                    </div>

                    <!-- Grid layout for document fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Document Type dropdown -->
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Tipo Documento <span class="text-red-500">*</span>
                            </label>
                            <!-- Select element with options for different document types -->
                            <select name="tipoDocumento" x-model="data.tipoDocumento" required
                                    class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                                <!-- Empty option as placeholder -->
                                <option value="">Seleccione...</option>
                                <!-- Document type options with numeric values (database IDs) -->
                                <option value="1">Cédula de Ciudadanía</option>
                                <option value="2">Tarjeta de Identidad</option>
                                <option value="3">Cédula de Extranjería</option>
                                <option value="4">Registro Civil</option>
                                <option value="5">Permiso Especial</option>
                            </select>
                        </div>
                        <!-- Document Number field -->
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Número Documento <span class="text-red-500">*</span>
                            </label>
                            <!-- Text input for document number (validated to be numeric) -->
                            <input type="text" name="numeroDocumento" x-model="data.numeroDocumento" maxlength="12" required
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Contact Information Section -->
                <!-- Visible on step 3 OR in edit mode -->
                <div x-show="step === 3 || isEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <!-- Section header (only in edit mode) -->
                    <div class="mb-6 border-b border-gray-100 pb-2 mt-6" x-show="isEdit">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <!-- Material icon for contact/mail -->
                            <span class="material-icons mr-2 text-[#ab876f]">contact_mail</span> Contacto
                        </h2>
                    </div>

                    <!-- Grid layout for contact fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Phone field -->
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                            <!-- Tel input type for phone numbers (provides mobile keyboard optimization) -->
                            <input type="tel" name="telefono" x-model="data.telefono" maxlength="15"
                                   placeholder="Ej: 300 123 4567"
                                   class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3">
                        </div>
                        <!-- Address field (spans full width on desktop) -->
                        <div class="form-group md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Dirección <span class="text-red-500">*</span>
                            </label>
                            <!-- Relative positioning container for icon inside input -->
                            <div class="relative">
                                <!-- Location icon positioned absolutely inside input -->
                                <span class="material-icons absolute left-3 top-3.5 text-gray-400">location_on</span>
                                <!-- Input with left padding to accommodate icon (pl-10) -->
                                <input type="text" name="direccion" x-model="data.direccion" maxlength="40" required 
                                       placeholder="Ej: Calle 123 # 45 - 67"
                                       class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-[#ab876f] focus:border-[#ab876f] transition p-3 pl-10">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons Section -->
                <div class="flex items-center justify-between pt-8 border-t border-gray-100 mt-6">
                    <!-- Back button (only visible when not on first step and not in edit mode) -->
                    <!-- @click is Alpine.js event handler (same as addEventListener) -->
                    <button type="button" x-show="step > 1 && !isEdit" @click="step--"
                            class="px-6 py-2.5 rounded-xl text-gray-600 font-semibold hover:bg-gray-100 transition flex items-center">
                        <!-- Back arrow icon -->
                        <span class="material-icons mr-1">arrow_back</span>
                        Atrás
                    </button>
                    
                    <!-- Spacer to push next button to the right -->
                    <div class="flex-1"></div>

                    <!-- Next/Save button -->
                    <!-- Calls nextStep method which handles validation and navigation/submission -->
                    <button type="button" @click="nextStep()"
                            class="bg-[#ab876f] text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-[#8D7B68] hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center">
                        <!-- Dynamic button text based on step -->
                        <!-- x-text sets element's text content reactively -->
                        <span x-text="step === 3 || isEdit ? 'Guardar Información' : 'Siguiente'"></span>
                        <!-- Dynamic icon based on step (save icon on last step, forward arrow otherwise) -->
                        <span class="material-icons ml-2" x-text="step === 3 || isEdit ? 'save' : 'arrow_forward'"></span>
                    </button>
                </div>

            </form>
        </div>
        

    </div>
</main>