<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema PQR - Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.4s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .status-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script type="text/javascript">
        (function() {
            // 🟢 CLAVE PÚBLICA INTEGRADA 🟢
            emailjs.init('owC7KfZGuYHlyQwj1');
        })();
    </script>
</head>

<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">

   
    <main class="max-w-5xl mx-auto p-4 md:p-8 w-full">

        <section class="mb-6 md:mb-8 rounded-2xl bg-white p-6 md:p-8 shadow-lg border border-slate-200 hover-lift animate-slide-in">
            <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-4">
                <div class="flex flex-col md:flex-row items-center md:items-start text-center md:text-left space-y-4 md:space-y-0 md:space-x-6 w-full">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 shadow-lg flex-shrink-0">
                        <svg class="h-8 w-8 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <div class="mb-2 flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-3">
                            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Sistema PQR</h1>
                            <span class="inline-flex items-center rounded-full bg-gradient-to-r from-blue-50 to-indigo-50 px-3 py-1 text-xs font-semibold text-blue-700 border border-blue-200 text-center">
                                Peticiones, Quejas y Reclamos
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 font-medium">Complete el formulario para enviar su solicitud a la administración.</p>
                    </div>
                </div>

                <span class="inline-flex items-center space-x-1.5 rounded-full bg-gradient-to-r from-emerald-50 to-green-50 px-3 py-1.5 text-xs font-bold text-emerald-700 border-2 border-emerald-200 shadow-sm whitespace-nowrap">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 status-pulse"></span>
                    <span>Sistema Activo</span>
                </span>
            </div>
        </section>

        <section class="rounded-2xl bg-white shadow-lg border border-slate-200 overflow-hidden animate-slide-in">
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 md:px-8 md:py-6">
                <h2 class="text-base font-bold text-slate-800 uppercase tracking-wide">Formulario de Solicitud</h2>
                <p class="mt-1 text-xs text-slate-600">Todos los campos marcados con * son obligatorios</p>
            </div>

            <div class="p-5 md:p-8">
                <form id="pqrForm" class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-900">
                            Tipo de Solicitud <span class="text-rose-500">*</span>
                        </label>
                        <select id="tipoSolicitud" required
                            class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 shadow-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200">
                            <option value="">Seleccione una opción</option>
                            <option value="Petición">Petición</option>
                            <option value="Queja">Queja</option>
                            <option value="Reclamo">Reclamo</option>
                            <option value="Sugerencia">Sugerencia</option>
                            <option value="Felicitación">Felicitación</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-900">
                                Nombre Completo <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="nombre" required
                                placeholder="Ingrese su nombre completo"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-900">
                                Documento de Identidad <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="documento" required
                                placeholder="Número de documento"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-900">
                                Correo Electrónico <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="correo" required
                                placeholder="correo@ejemplo.com"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-slate-900">
                                Teléfono <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" id="telefono" required
                                placeholder="Número de contacto"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-900">
                            Dirección
                        </label>
                        <input type="text" id="direccion"
                            placeholder="Dirección completa (opcional)"
                            class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-900">
                            Asunto <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="asunto" required
                            placeholder="Resuma brevemente su solicitud"
                            class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-900">
                            Descripción <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="descripcion" required rows="6"
                            placeholder="Describa detalladamente su petición, queja o reclamo..."
                            class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-900 placeholder-slate-400 shadow-sm transition-all focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200 resize-none"></textarea>
                        <p class="text-xs text-slate-500">Mínimo 20 caracteres</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <label class="flex items-start space-x-3 cursor-pointer">
                            <input type="checkbox" id="terminos" required
                                class="mt-1 h-4 w-4 flex-shrink-0 rounded border-slate-300 text-rose-500 focus:ring-2 focus:ring-rose-200">
                            <span class="text-sm text-slate-700">
                                Acepto los <a href="#" class="font-bold text-rose-600 hover:text-rose-700 underline">términos y condiciones</a>
                                y autorizo el tratamiento de mis datos personales según la
                                <a href="#" class="font-bold text-rose-600 hover:text-rose-700 underline">política de privacidad</a>.
                            </span>
                        </label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 pt-4">
                        <button type="button" id="btnLimpiar"
                            class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 rounded-xl border-2 border-slate-300 bg-white px-6 py-3 text-sm font-bold text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:shadow-md hover:scale-105">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M4 7h16M10 11v6m4-6v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                            </svg>
                            <span>Limpiar</span>
                        </button>

                        <button type="submit" id="btnEnviar"
                            class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 px-8 py-3 text-sm font-bold text-black shadow-lg transition-all duration-200 hover:shadow-xl hover:scale-105">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Enviar PQR</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="mt-8 rounded-2xl bg-white from-slate-800 to-slate-900 p-6 shadow-lg animate-slide-in">
            <div class="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-black/10 flex-shrink-0">
                    <svg class="h-5 w-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-white text-black">Información de Respuesta</h3>
                    <p class="mt-1 text-xs text-black leading-relaxed">
                        Recibirá una respuesta a su correo electrónico en un plazo máximo de 15 días hábiles.
                        Conserve el número de radicado para realizar seguimiento a su solicitud.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 animate-fade-in">
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl border-2 border-slate-200 animate-slide-in">
            <div class="p-8 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-emerald-100 to-green-100 mb-4">
                    <svg class="h-8 w-8 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-slate-900 mb-2">¡PQR Enviado Exitosamente!</h3>
                <p class="text-sm text-slate-600 mb-4">Su solicitud ha sido registrada correctamente.</p>

                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 mb-6">
                    <p class="text-xs text-slate-500 mb-1">Número de Radicado</p>
                    <p id="numeroRadicado" class="text-lg font-bold text-slate-900 font-mono">PQR-2024-0000</p>
                </div>

                <button id="btnCerrarModal"
                    class="w-full rounded-xl bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-3 text-sm font-bold text-black shadow-lg transition-all hover:shadow-xl hover:scale-105">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <div id="toast" class="pointer-events-none fixed bottom-6 right-6 z-50 hidden animate-slide-in">
        <div class="rounded-xl bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-3 text-sm font-bold text-black shadow-2xl border border-slate-700">
            <div class="flex items-center space-x-3">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <span class="toast-message">Acción completada</span>
            </div>
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
            }, 3000);
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
            if (confirm('¿Está seguro que desea limpiar el formulario?')) {
                $('pqrForm').reset();
                showToast('Formulario limpiado');
            }
        });

        // Enviar formulario (INTEGRACIÓN EMAILJS)
        $('pqrForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Validaciones
            const descripcion = $('descripcion').value;
            if (descripcion.length < 20) {
                showToast('La descripción debe tener al menos 20 caracteres');
                return;
            }

            if (!$('terminos').checked) {
                showToast('Debe aceptar los términos y condiciones');
                return;
            }

            const btnEnviar = $('btnEnviar');
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Enviando...';

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
                showToast('PQR enviado y correo generado');

            } catch (error) {
                console.error('Error al enviar el correo:', error);
                showToast('Error al enviar. Intente nuevamente.');
            } finally {
                btnEnviar.disabled = false;
                btnEnviar.innerHTML = '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><span>Enviar PQR</span>';
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
</body>

</html>