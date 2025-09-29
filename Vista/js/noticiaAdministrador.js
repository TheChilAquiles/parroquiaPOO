/**
 * SISTEMA DE ADMINISTRACIÓN DE NOTICIAS - VERSIÓN MODERNA CON AJAX
 * JavaScript para manejo de la interfaz con delegación de eventos.
 * Adaptado para la vista con Tailwind CSS.
 */

document.addEventListener("DOMContentLoaded", () => {
    // --- CONFIGURACIÓN Y CONSTANTES ---
    // Se asume que 'window.appConfig' está definido en el HTML.
    const { ajaxUrl, csrfToken } = window.appConfig || {
        ajaxUrl: "noticias.php",
        csrfToken: "",
    };

    // CAMBIO: IDs y selectores actualizados para coincidir con la vista de Tailwind.
    const noticiaModal = document.getElementById("noticiaModal");
    const deleteModal = document.getElementById("deleteConfirmationModal");
    const loadingOverlay = document.getElementById("loadingOverlay");
    const noticiaForm = document.getElementById("noticiaForm");
    const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
    const feedbackContainer = document.getElementById("feedback-container");
    const imagenInput = document.getElementById("imagen");
    const imagenPreview = document.getElementById("imagenPreview");

    // --- DELEGACIÓN DE EVENTOS ---
    // Un único listener para manejar todos los clics de forma eficiente.
    document.addEventListener("click", (e) => {
        const target = e.target;

        // Botón para abrir modal de "Crear Noticia"
        if (target.closest("#openModalBtn")) {
            abrirModalCrear();
        }

        // Botón para editar una noticia
        if (target.closest(".open-edit-modal")) {
            const button = target.closest(".open-edit-modal");
            abrirModalEditar(button.dataset);
        }

        // Botón para abrir confirmación de eliminación
        if (target.closest(".delete-btn")) {
            e.preventDefault(); // Prevenir el envío del formulario anidado
            const form = target.closest(".delete-form");
            const id = form.querySelector('input[name="id"]').value;
            abrirModalEliminar(id);
        }

        // Botones para cerrar los modales
        if (target.closest("#closeModalBtn") || target === noticiaModal) {
            cerrarModal(noticiaModal);
        }
        if (target.closest("#cancelDeleteBtn") || target === deleteModal) {
            cerrarModal(deleteModal);
        }
    });

    // --- MANEJO DE FORMULARIOS Y EVENTOS ---
    noticiaForm.addEventListener("submit", handleGuardar);
    confirmDeleteBtn.addEventListener("click", handleEliminar);
    // MEJORA: Listener para la vista previa de la imagen.
    imagenInput.addEventListener("change", handleImagenPreview);

    // --- FUNCIONES LÓGICAS (LOS "HANDLERS") ---

    /**
     * Maneja el envío del formulario para crear o editar una noticia vía AJAX.
     */
    async function handleGuardar(e) {
        console.log("¡El botón de Guardar fue presionado!"); // <-- AÑADE ESTA LÍNEA
        e.preventDefault();
        mostrarLoading();

        const formData = new FormData(noticiaForm);
        // La acción 'guardar' ya está en un campo oculto del formulario.

        try {
            const response = await fetch(ajaxUrl, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });

            // Si el backend no responde JSON, esto dará error.
            const data = await response.json();

            if (data.exito) {
                cerrarModal(noticiaModal);
                // En lugar de recargar, podrías actualizar el DOM dinámicamente.
                // Por ahora, recargar es la forma más simple y segura.
                location.reload();
            } else {
                // Muestra el error dentro del modal para que el usuario pueda corregir.
                mostrarFeedback(
                    data.mensaje || "Ocurrió un error desconocido.",
                    "error"
                );
            }
        } catch (error) {
            console.error("Error al guardar:", error);
            mostrarFeedback("Error de conexión. Inténtalo de nuevo.", "error");
        } finally {
            ocultarLoading();
        }
    }

    /**
     * Maneja la confirmación de eliminación de una noticia vía AJAX.
     */
    async function handleEliminar() {
        const id = confirmDeleteBtn.dataset.id;
        if (!id) return;

        mostrarLoading();

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
                location.reload(); // Recargamos para ver la lista actualizada.
            } else {
                cerrarModal(deleteModal); // Cerramos el modal de confirmación
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
     * Muestra una vista previa de la imagen seleccionada.
     */
    function handleImagenPreview() {
        const file = imagenInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagenPreview.src = e.target.result;
                imagenPreview.classList.remove("hidden");
            };
            reader.readAsDataURL(file);
        }
    }

    // --- FUNCIONES DE UI (MODALES, FEEDBACK, LOADING) ---

    function abrirModal(modal) {
        modal.classList.remove("hidden");
        setTimeout(() => {
            modal.classList.remove("opacity-0");
            modal.querySelector(".modal-content").classList.remove("scale-95");
        }, 10);
    }

    function cerrarModal(modal) {
        modal.querySelector(".modal-content").classList.add("scale-95");
        modal.classList.add("opacity-0");
        setTimeout(() => modal.classList.add("hidden"), 300); // Duración de la transición
    }

    function abrirModalCrear() {
        resetFormulario();
        abrirModal(noticiaModal);
    }

    // CAMBIO: Función para rellenar el formulario antes de abrir el modal de edición.
    function abrirModalEditar(data) {
        resetFormulario();

        // Rellenar el formulario con los datos del botón
        document.getElementById("noticiaId").value = data.id;
        document.getElementById("titulo").value = data.titulo;
        document.getElementById("descripcion").value = data.descripcion;
        document.getElementById("imagenActual").value = data.imagen;

        // Actualizar UI del modal
        document.getElementById("modalTitle").textContent = "Editar Noticia";

        if (data.imagen) {
            imagenPreview.src = data.imagen;
            imagenPreview.classList.remove("hidden");
        }

        abrirModal(noticiaModal);
    }

    function abrirModalEliminar(id) {
        confirmDeleteBtn.dataset.id = id;
        abrirModal(deleteModal);
    }

    function resetFormulario() {
        noticiaForm.reset();
        document.getElementById("noticiaId").value = "";
        document.getElementById("imagenActual").value = "";
        imagenPreview.classList.add("hidden");
        imagenPreview.src = "";
        document.getElementById("modalTitle").textContent = "Crear Nueva Noticia";
    }

    function mostrarLoading() {
        loadingOverlay.classList.remove("hidden");
    }

    function ocultarLoading() {
        loadingOverlay.classList.add("hidden");
    }

    /**
     * Muestra una alerta flotante de feedback (éxito o error).
     */
    function mostrarFeedback(mensaje, tipo = "success") {
        const colorClasses =
            tipo === "success"
                ? "bg-green-50 text-green-800 border-green-400"
                : "bg-red-50 text-red-800 border-red-400";

        const feedbackDiv = document.createElement("div");
        feedbackDiv.className = `p-4 mb-4 rounded-xl border-l-4 shadow-lg transform transition-all duration-300 translate-x-full opacity-0 ${colorClasses}`;
        feedbackDiv.innerHTML = `<p class="font-bold capitalize">${tipo}</p><p>${mensaje}</p>`;

        feedbackContainer.prepend(feedbackDiv);

        // Animación de entrada
        requestAnimationFrame(() => {
            feedbackDiv.classList.remove("translate-x-full", "opacity-0");
        });

        // Desaparece después de 4 segundos
        setTimeout(() => {
            feedbackDiv.classList.add("opacity-0", "translate-x-8");
            feedbackDiv.addEventListener("transitionend", () => feedbackDiv.remove());
        }, 4000);
    }
});
