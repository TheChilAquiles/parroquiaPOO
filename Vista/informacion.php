<main class="flex-1 mx-auto max-w-5xl rounded-xl bg-white p-6 shadow-md">
    <header class="mb-5 flex items-center justify-between border-b border-gray-200 pb-4">
        <div class="flex items-center">
            <div class="flex flex-col">
                <span class="text-sm text-gray-600">PARROQUIA</span>
                <span class="text-lg font-bold text-gray-900">SAN FRANCISCO DE ASIS</span>
                <a href=""></a>
            </div>
        </div>
        <div class="cursor-pointer text-gray-500">
            <span class="material-icons"></span>
        </div>
    </header>

    <section class="mb-5 rounded-lg bg-gray-50 p-5">
        <h2 class="mb-1 text-xl font-bold text-gray-800">Historia</h2>
        <p class="mb-2 text-xs font-semibold text-gray-500">Published date</p>
        <p class="text-sm text-gray-600">Desde hace aproximadamente 27 años, fue construida una capilla en la vereda en ese entonces "San José - Bosa" atendida por los sacerdotes de las parroquias maría inmaculada y 
            Santa María de Cana. Mediante el decreto N° 358, del 21 de marzo de 2015, el obispo de la diócesis de Soacha, Monseñor Daniel Caro Borda. </p>
    </section>

    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Grupos Parroquiales</h2>
            <a href="#" class="cursor-pointer text-gray-500">
                <span class="material-icons"></span>
            </a>
        </div>

        <article class="group-item cursor-pointer mb-2 flex items-center justify-between rounded-lg p-4 transition duration-200 hover:bg-gray-100" onclick="openModal('legion-de-maria')">
            <div class="flex items-start">
                <img class="mr-4 h-16 w-16 rounded-lg object-cover" src="https://i.pinimg.com/originals/90/c9/30/90c930833a634c3ee26925ebdded25cf.png" alt="Logo Legión de María">
                <div class="flex flex-col">
                    <h3 class="mb-1 text-base font-semibold text-gray-900">Legion de Maria</h3>
                    <p class="mb-1 text-sm text-gray-600">El principal propósito de la Legión de María es dar gloria a Dios mediante la santificación de sus miembros ...</p>
                    <p class="mt-1 flex items-center text-xs text-gray-500">
                        <span class="material-icons mr-1 text-base">access_time</span>
                        <span>sábados • 4 pm</span>
                    </p>
                </div>
            </div>
            <span class="material-icons text-gray-500">Ver Más </span>
        </article>

        <article class="group-item cursor-pointer mb-2 flex items-center justify-between rounded-lg p-4 transition duration-200 hover:bg-gray-100" onclick="openModal('monaguillos')">
            <div class="flex items-start">
                <img class="mr-4 h-16 w-16 rounded-lg object-cover" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMc6kqE_oMj4Rpwazy6dHq8iLnjb_7hLPcXg&s" alt="Logo Monaguillos">
                <div class="flex flex-col">
                    <h3 class="mb-1 text-base font-semibold text-gray-900">Monaguillos</h3>
                    <p class="mb-1 text-sm text-gray-600">Viven su fe y servir a la comunidad parroquial, ayudando al sacerdote y/o el diácono en la Misa, las bendiciones, las estaciones de la cruz, y otros ritos de la iglesia...</p>
                    <p class="mt-1 flex items-center text-xs text-gray-500">
                        <span class="material-icons mr-1 text-base">access_time</span>
                        <span>viernes • 4 pm</span>
                    </p>
                </div>
            </div>
            <span class="material-icons text-gray-500">Ver Más</span>
        </article>

        <article class="group-item cursor-pointer mb-2 flex items-center justify-between rounded-lg p-4 transition duration-200 hover:bg-gray-100" onclick="openModal('lectores')">
            <div class="flex items-start">
                <img class="mr-4 h-16 w-16 rounded-lg object-cover" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0cLzgMrZVQLhFurSO7MqGfUeBGaPWEt6eqA&s" alt="Logo Lectores">
                <div class="flex flex-col">
                    <h3 class="mb-1 text-base font-semibold text-gray-900">Lectores</h3>
                    <p class="mb-1 text-sm text-gray-600">comunicar la palabra de Dios a la comunidad y acercarla a ella, para que se convierta en un ejemplo de vida y guía.</p>
                    <p class="mt-1 flex items-center text-xs text-gray-500">
                        <span class="material-icons mr-1 text-base">access_time</span>
                        <span>viernes • 4:30pm</span>
                    </p>
                </div>
            </div>
            <span class="material-icons text-gray-500">Ver Más</span>
        </article>
    </section>
</main>

    <div id="modal-container" class="fixed inset-0 hidden items-center justify-center bg-gray-800/40 bg-opacity-75 z-50">
        <div id="modal-content" class="relative max-w-lg w-full bg-white rounded-lg p-6 shadow-xl">
            <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                <span class="material-icons">close</span>
            </button>
            <h2 id="modal-title" class="text-2xl font-bold mb-4"></h2>
            <p id="modal-text" class="text-gray-700"></p>
        </div>
    </div>


<script>
    // Datos de ejemplo para las modales
    const modalData = {
        'legion-de-maria': {
            title: 'Legión de María',
            text: 'La Legión de María es una asociación de laicos de la Iglesia Católica que, con aprobación eclesiástica, está bajo la poderosa dirección de María Inmaculada, Mediadora de todas las Gracias. Su objetivo principal es la gloria de Dios a través de la santificación de sus miembros por la oración y la cooperación activa en la obra de la Virgen y de la Iglesia. Participan en el apostolado de la Iglesia a través del contacto directo con las personas, especialmente los más necesitados. Se reúnen una vez a la semana para orar y planear sus actividades apostólicas.'
        },
        'monaguillos': {
            title: 'Monaguillos',
            text: 'Los monaguillos son niños y jóvenes que sirven al sacerdote en el altar durante las celebraciones litúrgicas, especialmente en la Misa. Su servicio es una forma de vivir su fe y de servir a la comunidad parroquial. Entre sus tareas se encuentran ayudar al sacerdote o diácono, encender las velas, llevar el incensario, preparar el altar y participar activamente en la liturgia.'
        },
        'lectores': {
            title: 'Lectores',
            text: 'Los lectores son miembros de la comunidad que proclaman la Palabra de Dios en la liturgia, comunicando las lecturas bíblicas, excepto el Evangelio. Su rol es vital para que la Palabra de Dios sea escuchada y entendida por la asamblea. Para ser lector, se requiere preparación y una buena dicción para transmitir el mensaje de forma clara y respetuosa.'
        }
    };

    function openModal(itemKey) {
        const modalContainer = document.getElementById('modal-container');
        const modalTitle = document.getElementById('modal-title');
        const modalText = document.getElementById('modal-text');

        // Obtener los datos del item
        const data = modalData[itemKey];
        if (data) {
            modalTitle.textContent = data.title;
            modalText.textContent = data.text;
            // Remover la clase hidden para mostrar la modal
            modalContainer.classList.remove('hidden');
            modalContainer.classList.add('flex');
        }
    }

    function closeModal() {
        const modalContainer = document.getElementById('modal-container');
        // Agregar la clase hidden para ocultar la modal
        modalContainer.classList.add('hidden');
        modalContainer.classList.remove('flex');
    }
</script>





