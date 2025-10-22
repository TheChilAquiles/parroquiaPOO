/**
 * @file noticiaAdministrador.js
 * @version 1.6
 * @author Samuel Bedoya
 * @brief Sistema de administración de noticias con AJAX y delegación de eventos
 * 
 * Maneja toda la interactividad del CRUD de noticias sin recargas de página,
 * utilizando peticiones asíncronas y patrones modernos de JavaScript.
 * 
 * @architecture
 * - Event Delegation: Un listener centralizado maneja todos los eventos
 * - AJAX Pattern: Comunicación asíncrona con el servidor
 * - Progressive Enhancement: Funciona sin JS pero mejora con él
 * 
 * @dependencies
 * - window.appConfig: Configuración global definida en el HTML
 * - Fetch API: Para peticiones HTTP asíncronas
 * - FormData API: Para envío de formularios con archivos
 * - FileReader API: Para vista previa de imágenes
 */

// ============================================================================
// PUNTO DE ENTRADA Y CONFIGURACIÓN
// ============================================================================
document.addEventListener("DOMContentLoaded", () => {
    
    // ========================================================================
    // CONFIGURACIÓN GLOBAL
    // Obtiene parámetros de configuración desde el objeto window.appConfig
    // definido en el HTML. Provee fallbacks seguros si no existe.
    // ========================================================================
    const { ajaxUrl, csrfToken } = window.appConfig || {
        ajaxUrl: "noticias.php",    // Endpoint por defecto para peticiones
        csrfToken: "",               // Token CSRF para seguridad futura
    };

    // ========================================================================
    // REFERENCIAS A ELEMENTOS DEL DOM
    // Se cachean las referencias para evitar búsquedas repetidas en el DOM
    // y mejorar el rendimiento general de la aplicación
    // ========================================================================
    const noticiaModal = document.getElementById("noticiaModal");
    const deleteModal = document.getElementById("deleteConfirmationModal");
    const loadingOverlay = document.getElementById("loadingOverlay");
    const noticiaForm = document.getElementById("noticiaForm");
    const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
    const feedbackContainer = document.getElementById("feedback-container");
    const imagenInput = document.getElementById("imagen");
    const imagenPreview = document.getElementById("imagenPreview");

    // ========================================================================
    // SISTEMA DE DELEGACIÓN DE EVENTOS
    // Un único listener en document maneja todos los clics de forma eficiente.
    // Esto es especialmente útil para elementos dinámicos y mejora el rendimiento.
    // ========================================================================
    /**
     * Delegación de eventos centralizada para todos los clics.
     * 
     * @pattern Event Delegation
     * @benefits
     * - Menor uso de memoria (un listener vs muchos)
     * - Funciona con elementos agregados dinámicamente
     * - Código más mantenible y organizado
     * - Mejor rendimiento en listas grandes
     */
    document.addEventListener("click", (e) => {
        const target = e.target;

        // Apertura de modal para crear noticia nueva
        if (target.closest("#openModalBtn")) {
            abrirModalCrear();
        }

        // Apertura de modal para editar noticia existente
        // Los data attributes del botón contienen la info de la noticia
        if (target.closest(".open-edit-modal")) {
            const button = target.closest(".open-edit-modal");
            abrirModalEditar(button.dataset);
        }

        // Apertura de confirmación de eliminación
        if (target.closest(".delete-btn")) {
            e.preventDefault(); // Prevenir envío del formulario padre
            const form = target.closest(".delete-form");
            const id = form.querySelector('input[name="id"]').value;
            abrirModalEliminar(id);
        }

        // Cierre de modales (clic en botón cerrar o en overlay oscuro)
        if (target.closest("#closeModalBtn") || target === noticiaModal) {
            cerrarModal(noticiaModal);
        }
        if (target.closest("#cancelDeleteBtn") || target === deleteModal) {
            cerrarModal(deleteModal);
        }
    });

    // ========================================================================
    // INICIALIZACIÓN DE LISTENERS DE FORMULARIOS
    // Eventos específicos que no se pueden manejar con delegación
    // ========================================================================
    noticiaForm.addEventListener("submit", handleGuardar);
    confirmDeleteBtn.addEventListener("click", handleEliminar);
    imagenInput.addEventListener("change", handleImagenPreview);

    // ========================================================================
    // HANDLERS DE OPERACIONES CRUD
    // Funciones asíncronas que manejan las operaciones principales
    // ========================================================================

    /**
     * Maneja el guardado (crear/actualizar) de una noticia vía AJAX.
     * 
     * @async
     * @param {Event} e - Evento de submit del formulario
     * 
     * @workflow
     * 1. Previene el envío tradicional del formulario
     * 2. Muestra overlay de carga para feedback visual
     * 3. Prepara y envía datos con FormData (soporta archivos)
     * 4. Procesa respuesta JSON del servidor
     * 5. Actualiza UI o muestra errores según resultado
     * 
     * @note El campo 'action' ya está definido como hidden en el formulario
     * @note FormData maneja automáticamente la codificación multipart
     */
    async function handleGuardar(e) {
        console.log("¡El botón de Guardar fue presionado!");
        e.preventDefault();
        mostrarLoading();

        // FormData extrae automáticamente todos los campos del formulario
        // incluyendo archivos, lo que facilita el envío multipart
        const formData = new FormData(noticiaForm);

        try {
            // Petición AJAX con Fetch API moderna
            const response = await fetch(ajaxUrl, {
                method: "POST",
                body: formData,
                headers: { 
                    "X-Requested-With": "XMLHttpRequest"  // Identifica petición AJAX en servidor
                },
            });

            // Parsea la respuesta JSON del servidor
            // Si el servidor no retorna JSON válido, lanzará excepción
            const data = await response.json();

            if (data.exito) {
                cerrarModal(noticiaModal);
                // Recarga para mostrar los cambios
                // TODO: Implementar actualización dinámica del DOM sin reload
                location.reload();
            } else {
                // Muestra error sin cerrar modal para permitir correcciones
                mostrarFeedback(
                    data.mensaje || "Ocurrió un error desconocido.",
                    "error"
                );
            }
        } catch (error) {
            console.error("Error al guardar:", error);
            mostrarFeedback("Error de conexión. Inténtalo de nuevo.", "error");
        } finally {
            // Se ejecuta siempre, sin importar el resultado
            ocultarLoading();
        }
    }

    /**
     * Maneja la confirmación y ejecución de eliminación de noticia.
     * 
     * @async
     * @note Realiza borrado lógico en la BD (no físico)
     * @note El ID se almacena en data attribute del botón de confirmación
     */
    async function handleEliminar() {
        const id = confirmDeleteBtn.dataset.id;
        if (!id) return; // Validación de seguridad

        mostrarLoading();

        // Construye FormData manualmente para operación de eliminación
        const formData = new FormData();
        formData.append("action", "eliminar");
        formData.append("id", id);
        if (csrfToken) formData.append("csrf_token", csrfToken);

        try {
            const response = await fetch(ajaxUrl, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });

            const data = await response.json();

            if (data.exito) {
                cerrarModal(deleteModal);
                location.reload(); // Recarga para actualizar el listado
            } else {
                cerrarModal(deleteModal);
                mostrarFeedback(
                    data.mensaje || "No se pudo eliminar la noticia.",
                    "error"
                );
            }
        } catch (error) {
            console.error("Error al eliminar:", error);
            mostrarFeedback("Error de conexión al eliminar.", "error");
        } finally {
            ocultarLoading();
        }
    }

    /**
     * Genera vista previa de imagen seleccionada antes de subir.
     * 
     * Usa FileReader API para leer el archivo localmente sin subirlo al servidor.
     * Esto mejora la UX mostrando la imagen antes de guardar.
     * 
     * @note Solo procesa el primer archivo si se seleccionan múltiples
     */
    function handleImagenPreview() {
        const file = imagenInput.files[0];
        if (file) {
            const reader = new FileReader();
            
            // Callback cuando la lectura del archivo se completa
            reader.onload = (e) => {
                imagenPreview.src = e.target.result; // Data URL de la imagen
                imagenPreview.classList.remove("hidden");
            };
            
            // Lee el archivo como Data URL (base64)
            reader.readAsDataURL(file);
        }
    }

    // ========================================================================
    // FUNCIONES DE GESTIÓN DE UI
    // Control de modales, overlays y notificaciones
    // ========================================================================

    /**
     * Abre un modal con animación de entrada suave.
     * 
     * Usa transiciones CSS para efectos visuales profesionales.
     * El setTimeout permite que las clases CSS se apliquen correctamente.
     * 
     * @param {HTMLElement} modal - Elemento modal a mostrar
     */
    function abrirModal(modal) {
        modal.classList.remove("hidden");
        // setTimeout permite que la transición CSS se ejecute correctamente
        setTimeout(() => {
            modal.classList.remove("opacity-0");
            modal.querySelector(".modal-content").classList.remove("scale-95");
        }, 10);
    }

    /**
     * Cierra un modal con animación de salida.
     * 
     * Primero anima la salida, luego oculta completamente el elemento.
     * 
     * @param {HTMLElement} modal - Elemento modal a ocultar
     */
    function cerrarModal(modal) {
        modal.querySelector(".modal-content").classList.add("scale-95");
        modal.classList.add("opacity-0");
        // Espera a que termine la animación antes de ocultar (300ms)
        setTimeout(() => modal.classList.add("hidden"), 300);
    }

    /**
     * Prepara y abre modal para crear nueva noticia.
     * 
     * Limpia el formulario y establece valores por defecto.
     */
    function abrirModalCrear() {
        resetFormulario();
        abrirModal(noticiaModal);
    }

    /**
     * Prepara y abre modal para editar noticia existente.
     * 
     * Pre-carga los datos de la noticia en el formulario desde
     * los data attributes del botón que disparó la acción.
     * 
     * @param {Object} data - Data attributes del botón editar
     * @param {string} data.id - ID de la noticia
     * @param {string} data.titulo - Título actual
     * @param {string} data.descripcion - Descripción actual
     * @param {string} data.imagen - URL de imagen actual
     */
    function abrirModalEditar(data) {
        resetFormulario();

        // Rellena campos del formulario con datos existentes
        document.getElementById("noticiaId").value = data.id;
        document.getElementById("titulo").value = data.titulo;
        document.getElementById("descripcion").value = data.descripcion;
        document.getElementById("imagenActual").value = data.imagen;

        // Actualiza título del modal para contexto visual
        document.getElementById("modalTitle").textContent = "Editar Noticia";

        // Muestra vista previa de imagen existente si hay una
        if (data.imagen) {
            imagenPreview.src = data.imagen;
            imagenPreview.classList.remove("hidden");
        }

        abrirModal(noticiaModal);
    }

    /**
     * Abre modal de confirmación de eliminación.
     * 
     * Almacena el ID en el botón de confirmación mediante data attribute
     * para uso posterior cuando el usuario confirme la acción.
     * 
     * @param {string|number} id - ID de la noticia a eliminar
     */
    function abrirModalEliminar(id) {
        confirmDeleteBtn.dataset.id = id;
        abrirModal(deleteModal);
    }

    /**
     * Resetea el formulario a su estado inicial limpio.
     * 
     * Limpia todos los campos, oculta previews y restaura valores por defecto.
     */
    function resetFormulario() {
        noticiaForm.reset();
        document.getElementById("noticiaId").value = "";
        document.getElementById("imagenActual").value = "";
        imagenPreview.classList.add("hidden");
        imagenPreview.src = "";
        document.getElementById("modalTitle").textContent = "Crear Nueva Noticia";
    }

    /**
     * Muestra overlay de carga durante operaciones asíncronas.
     * Provee feedback visual de que la aplicación está procesando.
     */
    function mostrarLoading() {
        loadingOverlay.classList.remove("hidden");
    }

    /**
     * Oculta overlay de carga cuando la operación termina.
     */
    function ocultarLoading() {
        loadingOverlay.classList.add("hidden");
    }

    /**
     * Muestra notificación toast flotante con feedback al usuario.
     * 
     * Crea dinámicamente un elemento de notificación con animaciones
     * de entrada y salida suaves. Se auto-destruye después de 4 segundos.
     * 
     * @param {string} mensaje - Texto a mostrar en la notificación
     * @param {string} tipo - Tipo de mensaje: "success" o "error"
     * 
     * @features
     * - Auto-dismiss después de 4 segundos
     * - Animación de entrada/salida suave con CSS transitions
     * - Apilable (múltiples notificaciones simultáneas)
     * - Limpieza automática del DOM al desaparecer
     */
    function mostrarFeedback(mensaje, tipo = "success") {
        // Clases de color según el tipo de mensaje
        const colorClasses =
            tipo === "success"
                ? "bg-green-50 text-green-800 border-green-400"
                : "bg-red-50 text-red-800 border-red-400";

        // Crea elemento de notificación con clases Tailwind
        const feedbackDiv = document.createElement("div");
        feedbackDiv.className = `p-4 mb-4 rounded-xl border-l-4 shadow-lg transform transition-all duration-300 translate-x-full opacity-0 ${colorClasses}`;
        feedbackDiv.innerHTML = `<p class="font-bold capitalize">${tipo}</p><p>${mensaje}</p>`;

        // Añade al contenedor (prepend para que aparezca arriba)
        feedbackContainer.prepend(feedbackDiv);

        // Animación de entrada usando requestAnimationFrame para sincronización
        requestAnimationFrame(() => {
            feedbackDiv.classList.remove("translate-x-full", "opacity-0");
        });

        // Auto-dismiss con animación de salida después de 4 segundos
        setTimeout(() => {
            feedbackDiv.classList.add("opacity-0", "translate-x-8");
            // Espera a que termine la animación antes de remover del DOM
            feedbackDiv.addEventListener("transitionend", () => feedbackDiv.remove());
        }, 4000);
    }
});