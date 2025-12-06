


    <main class="max-w-4xl mx-auto p-4 md:p-8 w-full">

        <!-- Header Section -->
        <section class="mb-8 rounded-3xl bg-white p-6 md:p-8 shadow-lg border border-stone-200">
            <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-[#F4EBE7] border border-[#E6D5CC] flex-shrink-0">
                    <svg class="h-10 w-10 text-[#8D7B68]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <div class="flex-1 space-y-2">
                    <h1 class="text-3xl font-bold text-[#5A4D41] tracking-tight">Sistema PQRS</h1>
                    <p class="text-lg text-gray-600 font-medium leading-relaxed">
                        Sistema de Peticiones, Quejas, Reclamos y Sugerencias.
                        <span class="block text-gray-500 text-base">Estamos aquí para escucharle.</span>
                    </p>
                </div>

                <div class="hidden md:block">
                     <span class="inline-flex items-center space-x-2 rounded-full bg-[#F4EBE7] px-4 py-2 border border-[#E6D5CC]">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-sm font-bold text-[#8D7B68]">Sistema Activo</span>
                    </span>
                </div>
            </div>
        </section>

        <!-- Form Section -->
        <section class="rounded-3xl bg-white shadow-lg border border-stone-200 overflow-hidden">
            <div class="border-b border-stone-200 bg-[#F9F5F3] px-6 py-6 md:px-8 md:py-6">
                <h2 class="text-xl font-bold text-[#5A4D41] flex items-center gap-3">
                    <svg class="w-6 h-6 text-[#8D7B68]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Formulario de Solicitud
                </h2>
                <p class="mt-1 text-base text-gray-600">Los campos marcados con <span class="text-[#8D7B68] font-bold">*</span> son obligatorios.</p>
            </div>

            <div class="p-6 md:p-8 bg-white">
                <form id="pqrForm" class="space-y-8">
                    
                    <!-- Tipo de Solicitud -->
                    <div class="bg-[#F9F5F3] p-6 rounded-2xl border border-stone-200">
                        <label class="block text-lg font-bold text-[#5A4D41] mb-3">
                            ¿Qué desea realizar? <span class="text-[#8D7B68]">*</span>
                        </label>
                        <div class="relative">
                            <select id="tipoSolicitud" required
                                class="w-full appearance-none rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 shadow-sm transition-all focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC] cursor-pointer">
                                <option value="" class="text-gray-400">Seleccione una opción...</option>
                                <option value="Petición">Realizar una Petición</option>
                                <option value="Queja">Presentar una Queja</option>
                                <option value="Reclamo">Hacer un Reclamo</option>
                                <option value="Sugerencia">Enviar una Sugerencia</option>
                                <option value="Felicitación">Enviar una Felicitación</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-[#8D7B68]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-lg font-bold text-[#5A4D41]">
                                Su Nombre Completo <span class="text-[#8D7B68]">*</span>
                            </label>
                            <input type="text" id="nombre" required
                                placeholder="Escriba su nombre aquí"
                                class="w-full rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 placeholder-gray-400 shadow-sm focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC]">
                        </div>

                        <div class="space-y-3">
                            <label class="block text-lg font-bold text-[#5A4D41]">
                                Documento de Identidad <span class="text-[#8D7B68]">*</span>
                            </label>
                            <input type="text" id="documento" required
                                placeholder="Número de cédula"
                                class="w-full rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 placeholder-gray-400 shadow-sm focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="block text-lg font-bold text-[#5A4D41]">
                                Correo Electrónico <span class="text-[#8D7B68]">*</span>
                            </label>
                            <input type="email" id="correo" required
                                placeholder="correo@ejemplo.com"
                                class="w-full rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 placeholder-gray-400 shadow-sm focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC]">
                            <p class="text-sm text-gray-500 font-medium">Aquí enviaremos la respuesta.</p>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-lg font-bold text-[#5A4D41]">
                                Teléfono / Celular <span class="text-[#8D7B68]">*</span>
                            </label>
                            <input type="tel" id="telefono" required
                                placeholder="Número de contacto"
                                class="w-full rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 placeholder-gray-400 shadow-sm focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC]">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-lg font-bold text-[#5A4D41]">
                            Dirección de Residencia <span class="text-gray-400 font-normal text-base">(Opcional)</span>
                        </label>
                        <input type="text" id="direccion"
                            placeholder="Ej: Calle 10 # 5-20"
                            class="w-full rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 placeholder-gray-400 shadow-sm focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC]">
                    </div>

                    <div class="space-y-3">
                        <label class="block text-lg font-bold text-[#5A4D41]">
                            Asunto Principal <span class="text-[#8D7B68]">*</span>
                        </label>
                        <input type="text" id="asunto" required
                            placeholder="Ej: Solicitud de partida de bautismo"
                            class="w-full rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 placeholder-gray-400 shadow-sm focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC]">
                    </div>

                    <div class="space-y-3">
                        <label class="block text-lg font-bold text-[#5A4D41]">
                            Descripción Detallada <span class="text-[#8D7B68]">*</span>
                        </label>
                        <textarea id="descripcion" required rows="6"
                            placeholder="Por favor escriba aquí su solicitud con el mayor detalle posible..."
                            class="w-full rounded-xl border border-stone-300 bg-white px-5 py-4 text-lg font-medium text-gray-800 placeholder-gray-400 shadow-sm focus:border-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC] resize-y"></textarea>
                        <p class="text-sm text-gray-500 font-medium bg-[#F9F5F3] p-2 rounded-lg border border-stone-200 inline-block">
                            📝 Por favor use al menos 20 caracteres para describir su solicitud.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-[#F9F5F3] border border-stone-200 p-6">
                        <label class="flex items-start space-x-4 cursor-pointer">
                            <div class="relative flex items-start">
                                <input type="checkbox" id="terminos" required
                                class="h-6 w-6 rounded border-2 border-[#8D7B68] text-[#8D7B68] focus:ring-4 focus:ring-[#E6D5CC] cursor-pointer">
                            </div>
                            <span class="text-base text-gray-700 font-medium pt-0.5">
                                Entiendo y acepto los <a href="#" class="font-bold text-[#8D7B68] underline focus:ring-2 focus:ring-offset-2 focus:ring-[#D0B8A8] rounded px-1">términos y condiciones</a>.
                                Autorizo el uso de mis datos para este trámite.
                            </span>
                        </label>
                    </div>

                    <div class="flex flex-col-reverse md:flex-row items-center justify-between gap-6 pt-6 border-t border-stone-200">
                        <button type="button" id="btnLimpiar"
                            class="w-full md:w-auto inline-flex items-center justify-center space-x-3 rounded-xl border border-stone-300 bg-white px-8 py-4 text-base font-bold text-gray-700 shadow-sm transition-all hover:bg-[#F9F5F3] hover:border-[#8D7B68] hover:scale-[1.02] focus:ring-4 focus:ring-[#E6D5CC]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 7h16M10 11v6m4-6v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                            </svg>
                            <span>Borrar Todo</span>
                        </button>

                        <button type="submit" id="btnEnviar"
                            class="w-full md:w-auto inline-flex items-center justify-center space-x-3 rounded-xl bg-gradient-to-r from-[#D0B8A8] to-[#8D7B68] px-10 py-5 text-lg font-bold text-white shadow-lg transition-transform hover:from-[#C8B6A6] hover:to-[#7a6a58] hover:scale-[1.02] active:scale-95 focus:ring-4 focus:ring-[#E6D5CC]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>ENVIAR SOLICITUD</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Info Footer -->
        <section class="mt-8 rounded-3xl bg-gradient-to-r from-[#8D7B68] to-[#6b5d4f] p-8 shadow-xl">
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 flex-shrink-0">
                    <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-xl font-bold text-white">Información Importante</h3>
                    <p class="text-lg text-[#F4EBE7] leading-relaxed font-medium">
                        Recibirá una respuesta oficial a su correo electrónico en un plazo máximo de <span class="text-white font-bold underline">15 días hábiles</span>.
                        <br>Por favor, guarde el número de radicado que aparecerá al enviar este formulario.
                    </p>
                </div>
            </div>
        </section>

    <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 animate-fade-in">
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg rounded-3xl bg-white shadow-2xl border-2 border-slate-200 animate-slide-in">
            <div class="p-10 text-center">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 border-2 border-emerald-200 mb-6">
                    <svg class="h-12 w-12 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h3 class="text-3xl font-bold text-slate-900 mb-3">¡PQRS Enviado!</h3>
                <p class="text-xl text-slate-600 mb-6">Su solicitud ha sido registrada correctamente.</p>

                <div class="rounded-2xl bg-slate-50 border-2 border-slate-200 p-6 mb-8">
                    <p class="text-base text-slate-500 mb-2 font-bold uppercase tracking-wider">Número de Radicado</p>
                    <p id="numeroRadicado" class="text-3xl font-extrabold text-slate-900 font-mono tracking-widest">PQRS-2024-0000</p>
                </div>

                <button id="btnCerrarModal"
                    class="w-full rounded-xl bg-slate-800 px-8 py-4 text-xl font-bold text-white shadow-lg transition-all hover:bg-slate-900 hover:scale-[1.02]">
                    Cerrar Ventana
                </button>
            </div>
        </div>
    </div>

    <div id="toast" class="pointer-events-none fixed bottom-6 right-6 z-50 hidden animate-slide-in">
        <div class="rounded-xl bg-slate-800 px-6 py-4 text-base font-bold text-white shadow-2xl border-2 border-slate-700 flex items-center space-x-4">
            <svg class="h-6 w-6 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            <span class="toast-message">Acción completada</span>
        </div>
    </div>

    <script>
        const $ = (id) => document.getElementById(id);

        // Función para mostrar toast
        function showToast(message) {
            const toast = $('toast');
            const messageElement = toast.querySelector('.toast-message');
            messageElement.textContent = message;

            toast.classList.remove('hidden');

            clearTimeout(showToast._timeout);
            showToast._timeout = setTimeout(() => {
                toast.classList.add('hidden');
            }, 4000); // Increased timeout for better reading time
        }

        // Función para generar número de radicado
        function generarRadicado() {
            const fecha = new Date();
            const año = fecha.getFullYear();
            const numero = Math.floor(Math.random() * 9999) + 1;
            return `PQR-${año}-${numero.toString().padStart(4, '0')}`;
        }

        // Limpiar formulario
        $('btnLimpiar').addEventListener('click', () => {
            if (confirm('¿Está seguro que desea borrar todo el contenido del formulario?')) {
                $('pqrForm').reset();
                showToast('Formulario limpiado correctamente');
            }
        });

        // Enviar formulario (INTEGRACIÓN EMAILJS)
        $('pqrForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Validaciones
            const descripcion = $('descripcion').value;
            if (descripcion.length < 20) {
                showToast('Por favor escriba una descripción más detallada (mínimo 20 letras)');
                $('descripcion').focus();
                return;
            }

            if (!$('terminos').checked) {
                showToast('Debe aceptar los términos y condiciones para continuar');
                $('terminos').focus();
                return; // Redundant but safe
            }

            const btnEnviar = $('btnEnviar');
            const originalContent = btnEnviar.innerHTML;
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<div class="flex items-center justify-center gap-3"><svg class="animate-spin h-6 w-6 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Enviando...</span></div>';

            // Recopilar datos para EmailJS
            const templateParams = {
                tipo: $('tipoSolicitud').value,
                nombre: $('nombre').value,
                documento: $('documento').value,
                correo: $('correo').value,
                telefono: $('telefono').value,
                direccion: $('direccion').value || 'No especificada',
                asunto: $('asunto').value,
                descripcion: descripcion,
                radicado: generarRadicado()
            };

            try {
                // 🟢 SERVICE ID Y TEMPLATE ID INTEGRADOS 🟢
                await emailjs.send('service_rbd6x1w', 'template_asoiufa', templateParams);

                // Mostrar modal de éxito
                $('numeroRadicado').textContent = templateParams.radicado;
                $('modal').classList.remove('hidden');
                $('modal').classList.add('flex');

                // Limpiar formulario
                $('pqrForm').reset();
                // showToast('PQR enviado y correo generado'); // Modal is enough

            } catch (error) {
                console.error('Error al enviar el correo:', error);
                showToast('Hubo un error al enviar. Por favor intente nuevamente.');
            } finally {
                btnEnviar.disabled = false;
                btnEnviar.innerHTML = originalContent;
            }
        });

        // Cerrar modal
        $('btnCerrarModal').addEventListener('click', () => {
            $('modal').classList.add('hidden');
            $('modal').classList.remove('flex');
        });

        // Cerrar modal al hacer clic fuera
        $('modal').addEventListener('click', (e) => {
            if (e.target === $('modal')) {
                $('modal').classList.add('hidden');
                $('modal').classList.remove('flex');
            }
        });
    </script>
