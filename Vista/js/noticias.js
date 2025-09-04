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
    const editActionHash = '<?= md5("editar") ?>'; // Nota: esta variable se define en el archivo PHP

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
            modal.querySelector('div').classList.remove('scale-95');
            modal.querySelector('div').classList.add('scale-100');
        }, 10);
    };

    const hideModal = (modal) => {
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        modal.querySelector('div').classList.remove('scale-100');
        setTimeout(() => modal.classList.add('hidden'), 300);
    };

    // Manejar clic para abrir el modal de creación
    if (openModalBtn) {
        openModalBtn.addEventListener('click', () => {
            form.reset();
            noticiaIdInput.value = '';
            modalTitle.textContent = 'Crear Noticia';
            imagenPreview.classList.add('hidden');
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

            if (imagen) {
                imagenPreview.src = imagen;
                imagenPreview.classList.remove('hidden');
            } else {
                imagenPreview.classList.add('hidden');
            }

            // Ahora actualizamos el valor del campo usando su ID
            if (actionInput) {
                actionInput.value = editActionHash;
            }

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

    // Manejar clic para abrir el modal de confirmación de eliminación
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            formToDelete = e.currentTarget.closest('.delete-form');
            showModal(deleteConfirmationModal);
        });
    });

    // Manejar clic para confirmar la eliminación
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => {
            if (formToDelete) {
                formToDelete.submit();
            }
            hideModal(deleteConfirmationModal);
        });
    }

    // Manejar clic para cancelar la eliminación
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', () => hideModal(deleteConfirmationModal));
    }
});
