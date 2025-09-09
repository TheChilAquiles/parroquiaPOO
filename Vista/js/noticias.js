document.addEventListener('DOMContentLoaded', () => {

    // Variables para los modales
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const noticiaModal = document.getElementById('noticiaModal');
    const form = document.getElementById('noticiaForm');
    const modalTitle = document.getElementById('modalTitle');
    const noticiaIdInput = document.getElementById('noticiaId');
    const tituloInput = document.getElementById('titulo');
    const descripcionInput = document.getElementById('descripcion');
    const imagenPreview = document.getElementById('imagenPreview');
    const imagenActualInput = document.getElementById('imagenActual');
    const imagenInput = document.getElementById('imagen');

    // Obtener el campo de acción del formulario por su ID
    const actionInput = document.getElementById('actionInput');
    // const editActionHash = '<?= md5("editar") ?>'; // Nota: esta variable ya no se necesita

    // Variables para el modal de eliminación
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    let formToDelete = null;

    // Funciones para mostrar/ocultar modales
    const showModal = (modal) => {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('flex', 'opacity-100'); // Añadido 'flex' y 'opacity-100'
        }, 10);
    };

    const hideModal = (modal) => {
        modal.classList.remove('flex', 'opacity-100'); // Eliminado 'flex' y 'opacity-100'
        modal.classList.add('hidden', 'opacity-0');
    };
    
    // Función para restablecer el formulario
    const resetForm = () => {
        form.reset();
        noticiaIdInput.value = '';
        tituloInput.value = '';
        descripcionInput.value = '';
        imagenActualInput.value = '';
        imagenPreview.src = '';
        imagenPreview.classList.add('hidden');
        modalTitle.textContent = 'Crear Noticia';
    };

    // Manejar clic para abrir el modal de creación
    if (openModalBtn) {
        openModalBtn.addEventListener('click', () => {
            resetForm();
            showModal(noticiaModal);
        });
    }
    
    // Manejar clic para abrir el modal de edición
    document.querySelectorAll('.open-edit-modal').forEach(button => {
        button.addEventListener('click', (e) => {
            const { id, titulo, descripcion, imagen } = e.currentTarget.dataset;

            noticiaIdInput.value = id;
            tituloInput.value = titulo;
            descripcionInput.value = descripcion;
            imagenActualInput.value = imagen;
            modalTitle.textContent = 'Editar Noticia';

            // ✅ LÓGICA CORREGIDA PARA LA PREVISUALIZACIÓN DE LA IMAGEN
            if (imagen) {
                imagenPreview.src = imagen;
                imagenPreview.classList.remove('hidden');
            } else {
                imagenPreview.classList.add('hidden');
            }

            // Ya no es necesario cambiar el valor de actionInput en el cliente
            // El controlador ya maneja la lógica de crear/editar con el ID

            showModal(noticiaModal);
        });
    });

    // Manejar clic para cerrar el modal de creación/edición
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', () => hideModal(noticiaModal));
    }
    
    // Manejar la vista previa de la imagen
    if (imagenInput) {
        imagenInput.addEventListener('change', () => {
            const file = imagenInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagenPreview.src = e.target.result;
                    imagenPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                imagenPreview.src = '';
                imagenPreview.classList.add('hidden');
            }
        });
    }

    // Modal de confirmación para eliminar
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            formToDelete = e.currentTarget.closest('.delete-form');
            showModal(deleteConfirmationModal);
        });
    });

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => {
            if (formToDelete) {
                formToDelete.submit();
            }
            hideModal(deleteConfirmationModal);
        });
    }

    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => hideModal(deleteConfirmationModal));
    }

    // Cierre del modal con clic fuera
    window.addEventListener('click', (event) => {
        if (event.target === noticiaModal) {
            hideModal(noticiaModal);
        }
        if (event.target === deleteConfirmationModal) {
            hideModal(deleteConfirmationModal);
        }
    });
});