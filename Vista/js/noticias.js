const modal = document.getElementById('noticiaModal');
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');
const noticiaForm = document.getElementById('noticiaForm');
const modalTitle = document.getElementById('modalTitle');
const noticiaId = document.getElementById('noticiaId');
const tituloInput = document.getElementById('titulo');
const descripcionInput = document.getElementById('descripcion');
const imagenInput = document.getElementById('imagen');
const imagenPreview = document.getElementById('imagenPreview');
const imagenActualInput = document.getElementById('imagenActual');
const editButtons = document.querySelectorAll('.open-edit-modal');
const actionInput = document.querySelector('input[name="<?= md5('action') ?>"]');


/**
 * Muestra el modal con una animación.
 */
function showModal() {
    modal.classList.remove('hidden', 'opacity-0');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.add('opacity-100');
    }, 10);
}

/**
 * Oculta el modal con una animación.
 */
function hideModal() {
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 300);
}

// Evento para abrir el modal para crear una nueva noticia
openModalBtn.addEventListener('click', () => {
    modalTitle.textContent = 'Crear Noticia';
    noticiaId.value = '';
    noticiaForm.reset();
    imagenPreview.classList.add('hidden');
    imagenActualInput.value = '';
    actionInput.value = '<?= md5('guardar') ?>';
    showModal();
});

// Evento para cerrar el modal
closeModalBtn.addEventListener('click', hideModal);

// Evento para abrir el modal para editar una noticia
editButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        const id = e.currentTarget.dataset.id;
        const titulo = e.currentTarget.dataset.titulo;
        const descripcion = e.currentTarget.dataset.descripcion;
        const imagen = e.currentTarget.dataset.imagen;

        modalTitle.textContent = 'Editar Noticia';
        noticiaId.value = id;
        tituloInput.value = titulo;
        descripcionInput.value = descripcion;
        imagenActualInput.value = imagen;
        actionInput.value = '<?= md5('guardar') ?>';
        
        // Muestra la imagen actual si existe
        if (imagen && imagen.length > 0) {
            imagenPreview.src = imagen;
            imagenPreview.classList.remove('hidden');
        } else {
            imagenPreview.classList.add('hidden');
        }
        showModal();
    });
});

// Evento para cerrar el modal al hacer clic fuera
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        hideModal();
    }
});

// Vista previa de la nueva imagen
imagenInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagenPreview.src = e.target.result;
            imagenPreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
