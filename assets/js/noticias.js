/**
 * @file noticias.js
 * @version 2.0 - REFACTORIZADO PARA MVC
 * @author Samuel Bedoya
 * @brief Sistema de administración de noticias con AJAX + Arquitectura MVC
 *
 * CAMBIOS IMPORTANTES:
 * - ✅ Peticiones AJAX ahora van a rutas MVC (?route=noticias/crear)
 * - ✅ El controlador maneja la lógica y responde JSON
 * - ✅ Sin archivo ajaxNoticias.php separado
 * - ✅ Respeta la arquitectura Router → Controlador → Modelo
 *
 * @architecture
 * - Event Delegation: Un listener centralizado
 * - AJAX Pattern: Comunicación asíncrona con rutas MVC
 * - Progressive Enhancement: Funciona sin JS pero mejora con él
 */

// ============================================================================
// PUNTO DE ENTRADA
// ============================================================================
document.addEventListener("DOMContentLoaded", () => {
    // ========================================================================
    // REFERENCIAS A ELEMENTOS DEL DOM
    // ========================================================================
    const noticiaModal = document.getElementById("noticiaModal");
    const deleteModal = document.getElementById("deleteConfirmationModal");
    const loadingOverlay = document.getElementById("loadingOverlay");
    const noticiaForm = document.getElementById("noticiaForm");
    const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
    const cancelBtn = document.getElementById("cancelBtn");
    const feedbackContainer = document.getElementById("feedback-container");
    const imagenInput = document.getElementById("imagen");
    const imagenPreview = document.getElementById("imagenPreview");
    const imagenPlaceholder = document.getElementById("imagenPlaceholder");
    const verNoticiaModal = document.getElementById("verNoticiaModal"); // NUEVO
    const verTitulo = document.getElementById("verTitulo");
    const verImagen = document.getElementById("verImagen");
    const verDescripcion = document.getElementById("verDescripcion");
    const verFecha = document.getElementById("verFecha");

    // ========================================================================
    // SISTEMA DE DELEGACIÓN DE EVENTOS
    // ========================================================================
    document.addEventListener("click", (e) => {
        const target = e.target;

        // Abrir modal para crear noticia nueva
        if (target.closest("#openModalBtn")) {
            abrirModalCrear();
        }

        // Abrir modal para editar noticia existente
        if (target.closest(".open-edit-modal")) {
            const button = target.closest(".open-edit-modal");
            abrirModalEditar(button.dataset);
        }

        // Abrir confirmación de eliminación
        if (target.closest(".delete-btn")) {
            e.preventDefault();
            const id = target.closest(".delete-btn").dataset.id;
            abrirModalEliminar(id);
        }

        // LÓGICA NUEVA: Abrir modal de "Leer más" (para cualquier usuario)
        if (target.closest(".view-news-btn")) {
            const btn = target.closest(".view-news-btn");
            abrirModalVerNoticia(btn.dataset);
        }

        // LÓGICA NUEVA: Cerrar modal de visualización
        if (target.closest("#closeVerModalBtn") || target.closest("#closeVerModalBtnBottom") || target === verNoticiaModal) {
            cerrarModal(verNoticiaModal);
        }

        // Cerrar modales
        if (target.closest("#closeModalBtn") || target === noticiaModal) {
            cerrarModal(noticiaModal);
        }
        if (target.closest("#cancelDeleteBtn") || target === deleteModal) {
            cerrarModal(deleteModal);
        }
        if (target.closest("#cancelBtn")) {
            cerrarModal(noticiaModal);
        }
    });

    // ========================================================================
    // LISTENERS DE FORMULARIOS
    // ========================================================================
    if (noticiaForm) {
        noticiaForm.addEventListener("submit", handleGuardar);
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener("click", handleEliminar);
    }

    if (imagenInput) {
        imagenInput.addEventListener("change", handleImagenPreview);
    }

    // ========================================================================
    // HANDLERS DE OPERACIONES CRUD
    // ========================================================================

    /**
     * Maneja el guardado (crear/actualizar) de una noticia vía AJAX.
     *
     * 🔥 IMPORTANTE: Ahora envía a rutas MVC en lugar de ajaxNoticias.php
     *
     * @async
     * @param {Event} e - Evento de submit del formulario
     */
    async function handleGuardar(e) {
        e.preventDefault();
        console.log("💾 Guardando noticia...");

        mostrarLoading();

        const formData = new FormData(noticiaForm);
        const noticiaId = document.getElementById("noticiaId").value;

        // ✅ DETERMINAR RUTA MVC SEGÚN OPERACIÓN
        const route = noticiaId ? "noticias/actualizar" : "noticias/crear";
        const url = `?route=${route}`;

        console.log(`📡 Enviando a: ${url}`);
        console.log("📦 Datos:", {
            id: formData.get("id"),
            titulo: formData.get("titulo"),
            descripcion: formData.get("descripcion"),
            imagen: formData.get("imagen")?.name || "sin imagen",
        });

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest", // Identifica petición AJAX
                },
            });

            console.log("📨 Respuesta recibida:", response.status);

            // Verificar si la respuesta es JSON
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("La respuesta no es JSON. Verifica el controlador.");
            }

            const data = await response.json();
            console.log("✅ Datos JSON:", data);

            if (data.exito || data.status === "success") {
                mostrarFeedback(
                    data.mensaje || data.message || "Operación exitosa",
                    "success"
                );
                cerrarModal(noticiaModal);

                // Recargar después de 1 segundo para mostrar el mensaje
                setTimeout(() => location.reload(), 1000);
            } else {
                mostrarFeedback(
                    data.mensaje || data.message || "Ocurrió un error desconocido.",
                    "error"
                );
            }
        } catch (error) {
            console.error("❌ Error al guardar:", error);
            mostrarFeedback(
                "Error de conexión o el servidor no respondió correctamente. Revisa la consola.",
                "error"
            );
        } finally {
            ocultarLoading();
        }
    }

    /**
     * Maneja la confirmación y ejecución de eliminación de noticia.
     *
     * 🔥 IMPORTANTE: Envía a ?route=noticias/eliminar
     *
     * @async
     */
    async function handleEliminar() {
        const id = confirmDeleteBtn.dataset.id;
        if (!id) {
            console.error("❌ No se encontró el ID para eliminar");
            return;
        }

        console.log(`🗑️ Eliminando noticia ID: ${id}`);
        mostrarLoading();

        // ✅ CONSTRUIR FORMDATA PARA ENVIAR AL CONTROLADOR
        const formData = new FormData();
        formData.append("id", id);

        // ✅ RUTA MVC PARA ELIMINACIÓN
        const url = "?route=noticias/eliminar";

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });

            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("La respuesta no es JSON. Verifica el controlador.");
            }

            const data = await response.json();
            console.log("✅ Respuesta eliminación:", data);

            if (data.exito || data.status === "success") {
                mostrarFeedback(
                    data.mensaje || data.message || "Noticia eliminada correctamente",
                    "success"
                );
                cerrarModal(deleteModal);
                setTimeout(() => location.reload(), 1000);
            } else {
                cerrarModal(deleteModal);
                mostrarFeedback(
                    data.mensaje || data.message || "No se pudo eliminar la noticia.",
                    "error"
                );
            }
        } catch (error) {
            console.error("❌ Error al eliminar:", error);
            mostrarFeedback("Error de conexión al eliminar.", "error");
        } finally {
            ocultarLoading();
        }
    }

    /**
     * Genera vista previa de imagen seleccionada antes de subir.
     *
     * @note Solo procesa el primer archivo si se seleccionan múltiples
     */
    function handleImagenPreview() {
        const file = imagenInput.files[0];
        if (file) {
            // Validar tipo de archivo
            if (!file.type.match("image.*")) {
                mostrarFeedback("Por favor selecciona una imagen válida", "error");
                imagenInput.value = "";
                return;
            }

            // Validar tamaño (5MB)
            if (file.size > 5 * 1024 * 1024) {
                mostrarFeedback("La imagen no debe superar 5MB", "error");
                imagenInput.value = "";
                return;
            }

            const reader = new FileReader();

            reader.onload = (e) => {
                imagenPreview.src = e.target.result;
                imagenPreview.classList.remove("hidden");
                if (imagenPlaceholder) {
                    imagenPlaceholder.classList.add("hidden");
                }
            };

            reader.onerror = () => {
                mostrarFeedback("Error al leer la imagen", "error");
            };

            reader.readAsDataURL(file);
        }
    }

    // ========================================================================
    // FUNCIONES DE GESTIÓN DE UI
    // ========================================================================

    /**
     * Abre un modal con animación de entrada suave.
     */
    function abrirModal(modal) {
        if (!modal) return;

        modal.classList.remove("hidden");
        modal.classList.add("flex");

        setTimeout(() => {
            const content = modal.querySelector(".modal-content");
            if (content) {
                content.classList.remove("scale-95");
                content.classList.add("scale-100");
            }
        }, 10);
    }

    /**
     * Cierra un modal con animación de salida.
     */
    function cerrarModal(modal) {
        if (!modal) return;

        const content = modal.querySelector(".modal-content");
        if (content) {
            content.classList.remove("scale-100");
            content.classList.add("scale-95");
        }

        setTimeout(() => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }, 300);
    }

    /**
     * Prepara y abre modal para crear nueva noticia.
     */
    function abrirModalCrear() {
        resetFormulario();
        document.getElementById("modalTitle").textContent = "Crear Nueva Noticia";
        abrirModal(noticiaModal);
    }

    /**
     * Prepara y abre modal para editar noticia existente.
     *
     * @param {Object} data - Data attributes del botón editar
     */
    function abrirModalEditar(data) {
        resetFormulario();

        document.getElementById("noticiaId").value = data.id;
        document.getElementById("titulo").value = data.titulo;
        document.getElementById("descripcion").value = data.descripcion;
        document.getElementById("imagenActual").value = data.imagen;
        document.getElementById("modalTitle").textContent = "Editar Noticia";

        if (data.imagen) {
            imagenPreview.src = data.imagen;
            imagenPreview.classList.remove("hidden");
            if (imagenPlaceholder) {
                imagenPlaceholder.classList.add("hidden");
            }
        }

        abrirModal(noticiaModal);
    }

    /**
     * Abre modal de confirmación de eliminación.
     */
    function abrirModalEliminar(id) {
        if (!id) return;
        confirmDeleteBtn.dataset.id = id;
        abrirModal(deleteModal);
    }

    /**
     * Resetea el formulario a su estado inicial limpio.
     */
    function resetFormulario() {
        if (noticiaForm) {
            noticiaForm.reset();
        }
        document.getElementById("noticiaId").value = "";
        document.getElementById("imagenActual").value = "";
        imagenPreview.classList.add("hidden");
        imagenPreview.src = "";
        if (imagenPlaceholder) {
            imagenPlaceholder.classList.remove("hidden");
        }
    }

    /**
     * Abre el modal de lectura con la información completa
     * @param {Object} data - Dataset del botón con título, descripción, etc.
     */
    function abrirModalVerNoticia(data) {
        // Llenar los datos
        verTitulo.textContent = data.titulo;
        // Usamos textContent para evitar inyección HTML, pero style white-space: pre-wrap en CSS respeta los saltos de línea
        verDescripcion.textContent = data.descripcion; 
        verFecha.textContent = data.fecha;

        // Manejar imagen
        if (data.imagen && data.imagen.trim() !== "") {
            verImagen.src = data.imagen;
            verImagen.parentElement.classList.remove("hidden"); // Asegurar que se vea el header
        } else {
            // Si no hay imagen, podrías poner una por defecto o ocultar la imagen header
            verImagen.src = "ruta/a/imagen_por_defecto.jpg"; // Opcional
        }

        abrirModal(verNoticiaModal);
    }

    /**
     * Muestra overlay de carga durante operaciones asíncronas.
     */
    function mostrarLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.remove("hidden");
            loadingOverlay.classList.add("flex");
        }
    }

    /**
     * Oculta overlay de carga cuando la operación termina.
     */
    function ocultarLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.add("hidden");
            loadingOverlay.classList.remove("flex");
        }
    }

    /**
     * Muestra notificación toast flotante con feedback al usuario.
     *
     * @param {string} mensaje - Texto a mostrar en la notificación
     * @param {string} tipo - Tipo de mensaje: "success" o "error"
     */
    function mostrarFeedback(mensaje, tipo = "success") {
        if (!feedbackContainer) return;

        const colorClasses =
            tipo === "success"
                ? "bg-green-50 text-green-800 border-green-400"
                : "bg-red-50 text-red-800 border-red-400";

        const iconClass = tipo === "success" ? "check_circle" : "error";

        const feedbackDiv = document.createElement("div");
        feedbackDiv.className = `p-4 mb-4 rounded-xl border-l-4 shadow-lg transform transition-all duration-300 translate-x-full opacity-0 ${colorClasses}`;
        feedbackDiv.innerHTML = `
            <div class="flex items-center">
                <span class="material-icons mr-3">${iconClass}</span>
                <div>
                    <p class="font-bold capitalize">${tipo}</p>
                    <p>${mensaje}</p>
                </div>
            </div>
        `;

        feedbackContainer.prepend(feedbackDiv);

        requestAnimationFrame(() => {
            feedbackDiv.classList.remove("translate-x-full", "opacity-0");
        });

        setTimeout(() => {
            feedbackDiv.classList.add("opacity-0", "translate-x-8");
            feedbackDiv.addEventListener("transitionend", () => feedbackDiv.remove());
        }, 4000);
    }

    // ========================================================================
    // INICIALIZACIÓN DE ANIMACIONES (para cards)
    // ========================================================================
    function initializeAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px",
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = "running";
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('article[style*="animation"]').forEach((el) => {
            el.style.animationPlayState = "paused";
            observer.observe(el);
        });
    }

    // Inicializar animaciones
    initializeAnimations();

    console.log("✅ Sistema de noticias inicializado correctamente");
});
