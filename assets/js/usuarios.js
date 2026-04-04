/**
 * @file usuarios.js
 * @brief Sistema de administración de usuarios con AJAX + Arquitectura MVC
 */

document.addEventListener("DOMContentLoaded", () => {
    // ========================================================================
    // REFERENCIAS A ELEMENTOS DEL DOM
    // ========================================================================
    const usuarioModal = document.getElementById("usuarioModal");
    const deleteModal = document.getElementById("deleteConfirmationModal");
    const loadingOverlay = document.getElementById("loadingOverlay");
    const usuarioForm = document.getElementById("usuarioForm");
    const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
    const feedbackContainer = document.getElementById("feedback-container");

    // ========================================================================
    // SISTEMA DE DELEGACIÓN DE EVENTOS
    // ========================================================================
    document.addEventListener("click", (e) => {
        const target = e.target;

        // Abrir modal para crear usuario
        if (target.closest("#openModalBtn")) {
            abrirModalCrear();
        }

        // Abrir modal para editar usuario existente
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

        // Cerrar modales
        if (target.closest("#closeModalBtn") || target.closest("#cancelBtn") || target === usuarioModal) {
            cerrarModal(usuarioModal);
        }
        if (target.closest("#cancelDeleteBtn") || target === deleteModal) {
            cerrarModal(deleteModal);
        }
    });

    // ========================================================================
    // LISTENERS DE FORMULARIOS
    // ========================================================================
    if (usuarioForm) {
        usuarioForm.addEventListener("submit", handleGuardar);
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener("click", handleEliminar);
    }

    // ========================================================================
    // HANDLERS DE OPERACIONES CRUD
    // ========================================================================

    async function handleGuardar(e) {
        e.preventDefault();
        console.log("💾 Guardando usuario...");

        mostrarLoading();

        const formData = new FormData(usuarioForm);
        const usuarioId = document.getElementById("usuarioId").value;

        // Determinar ruta MVC según operación
        const route = usuarioId ? "usuarios/actualizar" : "usuarios/crear";
        const url = `?route=${route}`;

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("La respuesta no es JSON. Verifica el controlador.");
            }

            const data = await response.json();

            if (data.exito || data.status === "success") {
                mostrarFeedback(data.mensaje || data.message || "Operación exitosa", "success");
                cerrarModal(usuarioModal);
                setTimeout(() => location.reload(), 1000);
            } else {
                mostrarFeedback(data.mensaje || data.message || "Ocurrió un error.", "error");
            }
        } catch (error) {
            console.error("❌ Error al guardar:", error);
            mostrarFeedback("Error de conexión o el servidor no respondió correctamente.", "error");
        } finally {
            ocultarLoading();
        }
    }

    async function handleEliminar() {
        const id = confirmDeleteBtn.dataset.id;
        if (!id) return;

        mostrarLoading();

        const formData = new FormData();
        formData.append("id", id);
        const url = "?route=usuarios/eliminar";

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });

            const data = await response.json();

            if (data.exito || data.status === "success") {
                mostrarFeedback(data.mensaje || data.message || "Usuario eliminado", "success");
                cerrarModal(deleteModal);
                setTimeout(() => location.reload(), 1000);
            } else {
                cerrarModal(deleteModal);
                mostrarFeedback(data.mensaje || data.message || "No se pudo eliminar.", "error");
            }
        } catch (error) {
            console.error("❌ Error al eliminar:", error);
            mostrarFeedback("Error de conexión al eliminar.", "error");
        } finally {
            ocultarLoading();
        }
    }

    // ========================================================================
    // FUNCIONES DE GESTIÓN DE UI Y MODALES
    // ========================================================================

    function abrirModal(modal) {
        if (!modal) return;
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    }

    function cerrarModal(modal) {
        if (!modal) return;
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }

    function abrirModalCrear() {
        if (usuarioForm) usuarioForm.reset();
        document.getElementById("usuarioId").value = "";
        document.getElementById("modalTitle").textContent = "Crear Nuevo Usuario";
        
        // Al crear, la contraseña es OBLIGATORIA
        document.getElementById("password").setAttribute("required", "required");
        document.getElementById("pwdHint").classList.add("hidden");

        abrirModal(usuarioModal);
    }

    function abrirModalEditar(data) {
        if (usuarioForm) usuarioForm.reset();

        document.getElementById("usuarioId").value = data.id;
        document.getElementById("email").value = data.email;
        document.getElementById("usuario_rol_id").value = data.rol; // Selecciona el rol automáticamente
        document.getElementById("modalTitle").textContent = "Editar Usuario";

        // Al editar, la contraseña NO es obligatoria (para no forzar a cambiarla)
        document.getElementById("password").removeAttribute("required");
        document.getElementById("pwdHint").classList.remove("hidden");

        abrirModal(usuarioModal);
    }

    function abrirModalEliminar(id) {
        if (!id) return;
        confirmDeleteBtn.dataset.id = id;
        abrirModal(deleteModal);
    }

    function mostrarLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.remove("hidden");
            loadingOverlay.classList.add("flex");
        }
    }

    function ocultarLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.add("hidden");
            loadingOverlay.classList.remove("flex");
        }
    }

    function mostrarFeedback(mensaje, tipo = "success") {
        if (!feedbackContainer) return;

        const colorClasses = tipo === "success"
            ? "bg-green-50 text-green-800 border-green-400"
            : "bg-red-50 text-red-800 border-red-400";
        
        // Usamos Boxicons ya que tu vista los usa
        const iconClass = tipo === "success" ? "bx-check-circle" : "bx-error-circle";

        const feedbackDiv = document.createElement("div");
        feedbackDiv.className = `p-4 mb-4 rounded-xl border-l-4 shadow-lg flex items-center ${colorClasses}`;
        feedbackDiv.innerHTML = `
            <i class='bx ${iconClass} text-2xl mr-3'></i>
            <div>
                <p class="font-bold capitalize">${tipo}</p>
                <p>${mensaje}</p>
            </div>
        `;

        feedbackContainer.prepend(feedbackDiv);
        setTimeout(() => feedbackDiv.remove(), 4000);
    }
});