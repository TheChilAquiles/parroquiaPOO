<?php include_once __DIR__ . '/componentes/plantillaTop.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Gestión de Sacramentos</h1>
            <p class="text-gray-600 mt-2"><?= htmlspecialchars($libroTipo ?? 'Desconocido') . " " . htmlspecialchars($numeroLibro ?? '') ?></p>
        </div>

        <button id="addRecord"
            class="px-6 py-3 bg-[#D0B8A8] text-white rounded-lg shadow-md hover:bg-[#ab876f] transition duration-200 font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Agregar nuevo Bautizado
        </button>
    </div>

    <!-- Tabla de Sacramentos (DataTables) -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#F5F0EB] to-[#E8DFD5] border-b border-[#DFD3C3]">
            <h2 class="text-xl font-semibold text-gray-800">Registro de Sacramentos</h2>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="recordListing" class="table-auto w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider"></th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tipo Sacramento</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Participantes</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Lugar</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>

                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>




<!-- Modal para agregar/editar sacramento -->
<div id="recordModal" class="modal fixed inset-0  flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header del Modal -->
        <div class="px-6 py-4 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f] border-b border-[#8B6F47]">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white modal-title">Editar Registro</h2>
                <button type="button" id="cerrarFormSacramentos" class="text-white hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-white text-sm mt-2">Complete los datos del sacramento</p>
        </div>

        <!-- Formulario -->
        <form id="recordForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            <div class="overflow-y-auto flex-1 p-6">
                <div class="space-y-5">




                    <!-- inputs de navegacion  -->
                    <input type="hidden" name="Tipo" value="<?= $tipo ?>">
                    <input type="hidden" name="Numero" value="<?= $numeroLibro ?>">
                    <!-- Fin inputs de navegacion  -->

                    <input type="hidden" name="id" id="id" />
                    <input type="hidden" name="Doaction" id="Doaction" value="" />



                    <!-- inicio campos Inputs  -->

                    <div id="Form1">
                        <div>
                            <div class=" text-center text-lg font-bold my-4">
                                Fecha Evento
                            </div>
                            <label for="fecha-evento" class="block font-medium">Fecha Evento</label>
                            <input type="date" id="fecha-evento" name="fecha-evento" placeholder="Fecha" class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C4A68A] focus:border-transparent outline-none transition">
                        </div>
                    </div>

                    <div id="Form2" class="hidden py-4">

                        <div class="border border-[#D0B8A8] rounded-lg p-4">

                            <div class="text-center text-lg font-bold my-2 text-gray-800">
                                Participantes del Sacramento
                            </div>

                            <ul id="contenedor-integrantes" class="my-4">

                                <li id="integranteVacio">
                                    <div class="bg-gray-100 border border-gray-300 rounded p-2 mb-2 mx-1 flex justify-center items-center">
                                        <span class="font-bold text-gray-500"> --- Sin participantes --- </span>
                                    </div>
                                </li>

                                <!-- Aquí se mostrarán los integrantes -->
                            </ul>

                            <button type="button" id="btnAbrirModalParticipante"
                                class="w-full bg-[#D0B8A8] hover:bg-[#ab876f] text-white rounded-lg font-bold py-3 cursor-pointer transition duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Añadir Participante
                            </button>
                        </div>

                    </div>


                    <!-- Eliminado todo el contenido del formulario de añadir participante que ahora estará en el mini-modal -->

                </div> <!-- Cierre de space-y-5 -->
            </div> <!-- Cierre de overflow-y-auto -->

            <!-- Botones fijos en la parte inferior -->
            <div class="flex flex-wrap gap-3 px-6 py-4 border-t border-gray-200 bg-white">
                <button type="button" id="Anterior"
                    class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 font-medium hidden">
                    ← Anterior
                </button>
                <button type="button" id="Siguiente"
                    class="flex-1 px-6 py-2.5 bg-[#D0B8A8] text-white rounded-lg hover:bg-[#ab876f] transition duration-200 font-medium">
                    Siguiente →
                </button>
                <button type="submit" id="Guardar"
                    class="flex-1 bg-[#D0B8A8] hover:bg-[#ab876f] text-white px-6 py-2.5 rounded-lg shadow-md font-semibold transition duration-200 hidden">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Mini-Modal para añadir participante -->
<div id="miniModalParticipante" class="modal fixed inset-0  flex items-center justify-center z-[60] hidden">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
        <div class="px-6 py-4 bg-gradient-to-r from-[#D0B8A8] to-[#ab876f]">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Añadir Participante</h3>
                <button type="button" id="cerrarMiniModal" class="text-white hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="overflow-y-auto flex-1 p-6">
            <div class="space-y-5">

                <div id="feligresNoExiste" class="text-center text-orange-700 font-medium bg-orange-50 border border-orange-200 rounded-lg p-3 hidden shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Feligrés no encontrado. Por favor, escriba sus nombres manualmente.</span>
                </div>

                <div class="border border-gray-200 rounded-xl p-5 shadow-sm bg-white">

                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 text-lg">1. Identificación</h4>

                    <div class="flex flex-wrap md:flex-nowrap gap-3 items-end mb-6 bg-[#F9F6F4] p-4 rounded-lg border border-[#E6D5CC]">
                        <div class="flex-1 min-w-[150px]">
                            <label for="tipo-doc" class="block text-sm font-bold text-gray-700 mb-1">Tipo Documento</label>
                            <select id="tipo-doc" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-2 focus:ring-[#C4A68A] outline-none bg-white transition">
                                <option value="" disabled selected>-- Selecciona --</option>
                                <option value="1">Cedula Ciudadania</option>
                                <option value="2">Tarjeta Identidad</option>
                                <option value="3">Cedula Extranjeria</option>
                                <option value="4">Registro Civil</option>
                                <option value="5">Permiso Especial</option>
                                <option value="6">NIT</option>
                            </select>
                        </div>

                        <div class="flex-1 min-w-[150px]">
                            <label for="numero-doc" class="block text-sm font-bold text-gray-700 mb-1">Número</label>
                            <input type="text" id="numero-doc" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-2 focus:ring-[#C4A68A] outline-none bg-white transition"
                                placeholder="Ej: 1002345678" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        <button type="button" id="BuscarUser" class="p-2.5 px-4 rounded-lg bg-[#ab876f] hover:bg-[#8D7B68] text-white flex items-center justify-center transition shadow-md h-[46px]" title="Autocompletar datos si ya existe">
                            <svg class="w-5 h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="font-semibold hidden md:inline">Buscar</span>
                        </button>
                    </div>

                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2 text-lg">2. Información Personal</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="primerNombre" class="block text-sm font-medium mb-1 text-gray-700">Primer Nombre *</label>
                            <input type="text" id="primerNombre" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-2 focus:ring-[#C4A68A] outline-none transition" placeholder="Primer Nombre">
                        </div>
                        <div>
                            <label for="segundoNombre" class="block text-sm font-medium mb-1 text-gray-700">Segundo Nombre</label>
                            <input type="text" id="segundoNombre" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-2 focus:ring-[#C4A68A] outline-none transition" placeholder="Segundo Nombre">
                        </div>
                        <div>
                            <label for="primerApellido" class="block text-sm font-medium mb-1 text-gray-700">Primer Apellido *</label>
                            <input type="text" id="primerApellido" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-2 focus:ring-[#C4A68A] outline-none transition" placeholder="Primer Apellido">
                        </div>
                        <div>
                            <label for="segundoApellido" class="block text-sm font-medium mb-1 text-gray-700">Segundo Apellido</label>
                            <input type="text" id="segundoApellido" class="border border-gray-300 rounded-lg p-2.5 w-full focus:ring-2 focus:ring-[#C4A68A] outline-none transition" placeholder="Segundo Apellido">
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <label for="rolParticipante" class="block text-sm font-bold text-[#ab876f] mb-2">Rol en el Sacramento *</label>
                        <select id="rolParticipante" class="border border-[#D0B8A8] rounded-lg p-3 w-full focus:ring-2 focus:ring-[#C4A68A] outline-none bg-[#F9F6F4] text-gray-800 font-medium">
                            <option value="" disabled selected>-- Selecciona un Rol --</option>
                            <?php
                            if ($tipo == 1) {
                                echo '<option value="10">Abuelo</option>';
                                echo '<option value="11">Abuela</option>';
                                echo '<option value="1">Bautizado</option>';
                            } elseif ($tipo == 2) {
                                echo '<option value="2">Confirmando</option>';
                            } elseif ($tipo == 3) {
                                echo '<option value="3">Difunto</option>';
                            } elseif ($tipo == 4) {
                                echo '<option value="4">Esposo</option>';
                                echo '<option value="5">Esposa</option>';
                                echo '<option value="12">Esposo Padrino</option>';
                                echo '<option value="13">Esposo Madrina</option>';
                                echo '<option value="14">Esposa Padrino</option>';
                                echo '<option value="15">Esposa Madrina</option>';
                            }
                            ?>
                            <option value="6">Padre</option>
                            <option value="7">Madre</option>
                            <?php if ($tipo !== 3 && $tipo !== 4) {
                                echo '<option value="8">Padrino</option>';
                                echo '<option value="9">Madrina</option>';
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
            <button type="button" id="cancelarParticipante" class="flex-1 px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium shadow-sm">
                Cancelar
            </button>
            <button type="button" id="AddNew" class="flex-1 px-4 py-3 bg-[#D0B8A8] hover:bg-[#ab876f] text-white rounded-lg font-bold transition duration-200 shadow-md">
                Añadir a la Lista
            </button>
        </div>
    </div>
</div>




<!-- </div>  
            <div class="insert-post-ads1" style="margin-top:20px;">
            </div>
        </div> -->





<script>
    // ============================================================================
    // VARIABLES GLOBALES
    // ============================================================================

    // Definir roles obligatorios según el tipo de sacramento
    const rolesObligatorios = (() => {
        const tipo = <?php echo json_encode($tipo); ?>;

        switch (tipo) {
            case 1: // Bautizos
                return ['Bautizado', 'Padrino', 'Madrina'];
            case 2: // Confirmaciones
                return ['Confirmando', 'Padrino', 'Madrina'];
            case 3: // Defunciones
                return ['Difunto'];
            case 4: // Matrimonios
                return ['Esposo', 'Esposa', 'Esposo Padrino', 'Esposa Padrino', 'Testigo 1', 'Testigo 2'];
            default:
                return [];
        }
    })();

    // Mapa de IDs a nombres de roles (consolidado, usado en múltiples lugares)
    const roles = {
        1: 'Bautizado',
        2: 'Confirmando',
        3: 'Difunto',
        4: 'Esposo',
        5: 'Esposa',
        6: 'Padre',
        7: 'Madre',
        8: 'Padrino',
        9: 'Madrina',
        10: 'Abuelo',
        11: 'Abuela'
    };

    // ============================================================================
    // EVENT LISTENERS
    // ============================================================================

    // ============================================================================
    // MANEJO DEL MINI-MODAL
    // ============================================================================

    // Abrir mini-modal
    $(document).on('click', '#btnAbrirModalParticipante', function() {
        $('#miniModalParticipante').removeClass('hidden');
        actualizarOpcionesRol(); // Actualizar roles disponibles
    });

    // Cerrar mini-modal (solo cerrar, sin guardar)
    $(document).on('click', '#cerrarMiniModal, #cancelarParticipante', function() {
        cerrarMiniModal();
    });

    // Intentar agregar participante al hacer clic en "Añadir"
    $(document).on('click', '#AddNew', function() {
        agregarParticipante();
    });

    // Cerrar mini-modal al hacer clic fuera
    $(document).on('click', '#miniModalParticipante', function(e) {
        if (e.target.id === 'miniModalParticipante') {
            cerrarMiniModal();
        }
    });

    // ============================================================================
    // FUNCIÓN PARA BUSCAR FELIGRÉS POR DOCUMENTO
    // ============================================================================
    $(document).on('click', '#BuscarUser', function() {
        const tipoDoc = $('#tipo-doc').val();
        const numeroDoc = $('#numero-doc').val();

        // Validar que haya escrito algo antes de buscar
        if (!tipoDoc || !numeroDoc) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, selecciona el tipo de documento y escribe el número antes de buscar.',
                confirmButtonColor: '#D0B8A8'
            });
            return;
        }

        const btn = $(this);
        const iconOriginal = btn.html();
        // Cambiar el ícono por un texto de carga temporal
        btn.prop('disabled', true).html('<span class="text-white font-bold">...</span>');

        $.ajax({
            url: '?route=sacramentos/buscar-usuario',
            method: 'POST',
            data: {
                tipoDoc: tipoDoc,
                numeroDoc: numeroDoc
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Si lo encuentra, llenamos los campos automáticamente
                    $('#primerNombre').val(response.data.primer_nombre).removeClass('border-red-500');
                    $('#segundoNombre').val(response.data.segundo_nombre || '');
                    $('#primerApellido').val(response.data.primer_apellido).removeClass('border-red-500');
                    $('#segundoApellido').val(response.data.segundo_apellido || '');

                    // Ocultamos el mensaje de que no existe
                    $('#feligresNoExiste').addClass('hidden');

                    Swal.fire({
                        icon: 'success',
                        title: 'Feligrés encontrado',
                        text: 'Datos autocompletados',
                        showConfirmButton: false,
                        timer: 1500 // Se cierra solo en 1.5 segundos
                    });
                } else {
                    // Si no existe, limpiamos los campos para que los llene manualmente
                    $('#primerNombre').val('');
                    $('#segundoNombre').val('');
                    $('#primerApellido').val('');
                    $('#segundoApellido').val('');

                    // Mostramos la alerta naranja
                    $('#feligresNoExiste').removeClass('hidden');
                }
            },
            error: function(xhr) {
                console.error("Error en la búsqueda:", xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de servidor',
                    text: 'Hubo un problema de conexión al intentar buscar el feligrés.'
                });
            },
            complete: function() {
                // Restaurar el botón a su estado normal
                btn.prop('disabled', false).html(iconOriginal);
            }
        });
    });

    // Función para cerrar mini-modal y limpiar campos
    function cerrarMiniModal() {
        $('#miniModalParticipante').addClass('hidden');
        $('#tipo-doc').val('').removeClass('border-red-500');
        $('#numero-doc').val('').removeClass('border-red-500');
        $('#primerNombre').val('').removeClass('border-red-500');
        $('#segundoNombre').val('').removeClass('border-red-500');
        $('#primerApellido').val('').removeClass('border-red-500');
        $('#segundoApellido').val('').removeClass('border-red-500');
        $('#rolParticipante').val('').removeClass('border-red-500');
        $('#feligresNoExiste').addClass('hidden');
    }

    // Función para resaltar campos obligatorios faltantes
    function resaltarCampo(selector) {
        $(selector).addClass('border-red-500 animate-pulse');
        setTimeout(() => {
            $(selector).removeClass('animate-pulse');
        }, 2000);
    }

    // Función principal para agregar participante a la lista provisional
    function agregarParticipante() {
        const rolParticipante = document.getElementById('rolParticipante').value.trim();
        const tipoDoc = document.getElementById('tipo-doc').value.trim();
        const numeroDoc = document.getElementById('numero-doc').value.trim();

        const primerNombre = document.getElementById('primerNombre').value.trim();
        const segundoNombre = document.getElementById('segundoNombre').value.trim();
        const primerApellido = document.getElementById('primerApellido').value.trim();
        const segundoApellido = document.getElementById('segundoApellido').value.trim();

        // 1. Validaciones de campos obligatorios
        // Nota: El número de documento puede no ser obligatorio si es un menor sin documento aún,
        // pero generalmente se pide. Asumiremos obligatorio si se selecciona tipo de documento.
        if (!rolParticipante || !primerNombre || !primerApellido) {
            if (!primerNombre) resaltarCampo('#primerNombre');
            if (!primerApellido) resaltarCampo('#primerApellido');
            if (!rolParticipante) resaltarCampo('#rolParticipante');

            // Si hay tipo de documento pero no número, o viceversa
            if ((tipoDoc && !numeroDoc) || (!tipoDoc && numeroDoc)) {
                resaltarCampo('#tipo-doc');
                resaltarCampo('#numero-doc');
            }

            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, complete los campos obligatorios (Nombre, Apellido, Rol).',
                confirmButtonColor: '#D0B8A8'
            });
            return false;
        }

        // 2. Validar duplicados de Documento (si se ingresó documento)
        if (tipoDoc && numeroDoc) {
            const existeDoc = Array.from(document.querySelectorAll('#contenedor-integrantes li')).some(li => {
                const tipo = li.querySelector('input[name$="[tipoDoc]"]')?.value;
                const numero = li.querySelector('input[name$="[numeroDoc]"]')?.value;
                return tipo == tipoDoc && numero == numeroDoc;
            });

            if (existeDoc) {
                resaltarCampo('#tipo-doc');
                resaltarCampo('#numero-doc');
                Swal.fire({
                    icon: 'warning',
                    title: 'Documento duplicado',
                    text: 'Ya hay un participante con ese documento en la lista.',
                    confirmButtonColor: '#D0B8A8'
                });
                return false;
            }
        }

        // 3. Validar duplicados de Rol (para roles únicos)
        /* Nota: La función actualizarOpcionesRol() ya deshabilita opciones, 
           pero validamos por seguridad del backend manual */
        const inputs = Array.from(document.querySelectorAll('#contenedor-integrantes input[name$="[rolParticipante]"]'));
        const inputEncontrado = inputs.find(input => input.value === rolParticipante);

        // Roles que permiten multiples personas (ej: abuelos, padrinos si son varios)
        // Pero roles únicos como 'Bautizado', 'Padre', 'Madre' deben validarse.
        const rolesUnicos = ['1', '2', '3', '4', '5', '6', '7']; // IDs como strings

        if (inputEncontrado && rolesUnicos.includes(rolParticipante)) {
            resaltarCampo('#rolParticipante');
            const select = document.getElementById('rolParticipante');
            const textoSeleccionado = select.options[select.selectedIndex].text;

            Swal.fire({
                icon: 'warning',
                title: 'Rol duplicado',
                text: `El rol "${textoSeleccionado}" ya ha sido añadido.`,
                confirmButtonColor: '#D0B8A8'
            });
            return false;
        }

        // 4. Agregar a la lista
        contador++;

        // Eliminar mensaje de "Vacio" si existe
        if ($('#integranteVacio').length) {
            $('#integranteVacio').remove();
        }

        const tiposDocs = {
            1: "Cedula Ciudadania",
            2: "Tarjeta Identidad",
            3: "Cedula Extranjeria",
            4: "Registro Civil",
            5: "Permiso Especial",
            6: "Numero Identificación Tributaria"
        };

        // Colores para visualización
        const colores = {
            1: 'bg-blue-50 text-blue-700 border-blue-200',
            2: 'bg-red-50 text-red-700 border-red-200',
            3: 'bg-violet-50 text-violet-700 border-violet-200',
            4: 'bg-yellow-50 text-yellow-700 border-yellow-200',
            5: 'bg-pink-50 text-pink-700 border-pink-200',
            6: 'bg-indigo-50 text-indigo-700 border-indigo-200',
            7: 'bg-lime-50 text-lime-700 border-lime-200',
            8: 'bg-cyan-50 text-cyan-700 border-cyan-200',
            9: 'bg-emerald-50 text-emerald-700 border-emerald-200',
            10: 'bg-violet-50 text-violet-700 border-violet-200',
            11: 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200'
        };

        const grupoRol = roles[rolParticipante] || 'Desconocido';
        const claseColor = colores[rolParticipante] || 'bg-gray-50 text-gray-700 border-gray-200';
        const tipDocTexto = tiposDocs[tipoDoc] || '';
        const docTexto = (tipoDoc && numeroDoc) ? `${tipDocTexto} - ${numeroDoc}` : 'Sin Documento';
        const nombreCompleto = [primerNombre, segundoNombre, primerApellido, segundoApellido].filter(Boolean).join(' ');

        const li = document.createElement('li');
        li.innerHTML = `
            <div class="bg-white border border-gray-200 rounded-lg mb-2 mx-1 p-3 flex justify-between items-center shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                     <span class="font-bold px-3 py-1 rounded-full text-xs border ${claseColor} ">${grupoRol}</span>
                     <div class="flex flex-col">
                        <span class="font-semibold text-gray-800">${nombreCompleto}</span>
                        <span class="text-xs text-gray-500">${docTexto}</span>
                     </div>
                </div>
                
                <input type="hidden" name="integrantes[${contador}][rolParticipante]" value="${rolParticipante}">
                <input type="hidden" name="integrantes[${contador}][tipoDoc]" value="${tipoDoc}">
                <input type="hidden" name="integrantes[${contador}][numeroDoc]" value="${numeroDoc}">
                <input type="hidden" name="integrantes[${contador}][primerNombre]" value="${primerNombre}">
                <input type="hidden" name="integrantes[${contador}][segundoNombre]" value="${segundoNombre}">
                <input type="hidden" name="integrantes[${contador}][primerApellido]" value="${primerApellido}">
                <input type="hidden" name="integrantes[${contador}][segundoApellido]" value="${segundoApellido}">

                <button type="button" class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition" onclick="eliminarIntegrante(this)" title="Eliminar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        `;

        document.getElementById('contenedor-integrantes').appendChild(li);

        // Actualizar opciones disponibles en el select
        actualizarOpcionesRol();

        // Mostrar mensaje de éxito
        Swal.fire({
            icon: 'success',
            title: 'Participante añadido',
            showConfirmButton: false,
            timer: 1500
        });

        // CERRAR EL MODAL AL FINALIZAR
        cerrarMiniModal();

        return true;
    }

    function eliminarIntegrante(boton) {
        boton.closest('li').remove();
        // No decrementamos contador para evitar colisiones de índices si se borra uno intermedio.
        // Solo verificamos si quedó vacío.

        if ($('#contenedor-integrantes li').length === 0) {
            $('#contenedor-integrantes').html(`
                <li id="integranteVacio">
                    <div class="bg-gray-100 border border-gray-300 rounded p-2 mb-2 mx-1 flex justify-center items-center">
                        <span class="font-bold text-gray-500"> --- Sin participantes --- </span>
                    </div>
                </li>
            `);
        }

        // Actualizar opciones disponibles después de eliminar
        actualizarOpcionesRol();

        Swal.fire({
            icon: 'info',
            title: 'Participante eliminado',
            showConfirmButton: false,
            timer: 1500
        });
    }



    var form = 1; // empezamos en el primer formulario
    var totalForms = 2; // cantidad total de formularios

    function mostrarFormulario(index) {
        // Oculta todos los formularios
        $('[id^=Form]').addClass('hidden');

        // Muestra solo el actual
        $('#Form' + index).removeClass('hidden');

        // Control de botones
        if (index === 1) {
            $('#Anterior').addClass('hidden');
            $('#Siguiente').removeClass('hidden');
            $('#Guardar').addClass('hidden');
        } else if (index === totalForms) {
            $('#Anterior').removeClass('hidden');
            $('#Siguiente').addClass('hidden');
            $('#Guardar').removeClass('hidden');
        } else {
            $('#Anterior').removeClass('hidden');
            $('#Siguiente').removeClass('hidden');
            $('#Guardar').addClass('hidden');
        }
    }

    $(document).on('click', '#Siguiente', function() {
        // Validar fecha antes de avanzar del Form1
        if (form === 1) {
            const fechaEvento = $('#fecha-evento').val();
            if (!fechaEvento || fechaEvento.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha requerida',
                    text: 'Por favor, ingrese la fecha del evento antes de continuar',
                    confirmButtonColor: '#D0B8A8'
                });
                $('#fecha-evento').addClass('border-red-500 animate-pulse');
                setTimeout(() => {
                    $('#fecha-evento').removeClass('animate-pulse');
                }, 2000);
                return;
            } else {
                $('#fecha-evento').removeClass('border-red-500');
            }
        }

        if (form < totalForms) form++;
        mostrarFormulario(form);
    });

    $(document).on('click', '#Anterior', function() {
        if (form > 1) form--;
        mostrarFormulario(form);
    });


    $(document).on('click', '#cerrarFormSacramentos', function() {
        $('#recordModal').addClass('hidden');
        $('#feligresNoExiste').addClass('hidden');
        $('#recordForm')[0].reset();

        const contenedor = document.getElementById('contenedor-integrantes');
        contenedor.innerHTML = "";

        const li = document.createElement('li');
        li.id = "integranteVacio";
        li.innerHTML = `
        <div class="bg-gray-100 border border-gray-300 rounded p-2 mb-2 mx-1 flex justify-center items-center">
            <span class="font-bold"> --- Vacio --- </span>
        </div>
    `;

        console.log("Antes de agregar", contenedor.innerHTML);
        contenedor.appendChild(li);
        console.log("Después de agregar", contenedor.innerHTML);


        mostrarFormulario(1);

    });

    $(document).on('click', '#addRecord', function() {
        $('#recordModal').removeClass('hidden');
        $('#recordForm')[0].reset();
        $('#id').val('');
        $('#contenedor-integrantes').html(`
            <li id="integranteVacio">
                <div class="bg-gray-100 border border-gray-300 rounded p-2 mb-2 mx-1 flex justify-center items-center">
                    <span class="font-bold"> --- Vacio --- </span>
                </div>
            </li>
        `);
        contador = 0;
        $('.modal-title').html("<i class='fa fa-plus'></i> Añadir Sacramento En <?php echo $libroTipo  . " " . $numeroLibro ?>  ");
        $('#Doaction').val('addRecord');
        mostrarFormulario(1);
    });

    // Event handler para editar sacramento
    $(document).on('click', '.btn-editar-sacramento', function() {
        const sacramentoId = $(this).data('sacramento-id');
        cargarSacramentoParaEditar(sacramentoId);
    });

    function cargarSacramentoParaEditar(sacramentoId) {
        $.ajax({
            url: '<?= url('sacramentos/obtener') ?>',
            type: 'POST',
            data: {
                sacramento_id: sacramentoId
            },
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: 'Cargando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.close();

                if (response.success) {
                    // Abrir modal
                    $('#recordModal').removeClass('hidden');
                    $('#recordForm')[0].reset();

                    // Llenar datos básicos
                    $('#id').val(sacramentoId);
                    $('#fecha-evento').val(response.data.fecha_generacion);
                    $('#Doaction').val('editRecord');

                    // Actualizar título
                    $('.modal-title').html("✏️ Editar Sacramento");

                    // Cargar participantes
                    cargarParticipantesEdicion(response.data.participantes);

                    // Ir al segundo formulario (participantes)
                    mostrarFormulario(2);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar el sacramento'
                });
                console.error('Error:', xhr);
            }
        });
    }

    function cargarParticipantesEdicion(participantes) {
        const contenedor = $('#contenedor-integrantes');
        contenedor.html('');
        contador = 0;

        if (participantes && participantes.length > 0) {
            participantes.forEach(function(p) {
                contador++;

                const colores = {
                    1: 'bg-blue-50',
                    2: 'bg-red-50',
                    3: 'bg-violet-50',
                    4: 'bg-yellow-50',
                    5: 'bg-pink-50',
                    6: 'bg-indigo-50',
                    7: 'bg-lime-50',
                    8: 'bg-cyan-50',
                    9: 'bg-emerald-50',
                    10: 'bg-violet-50',
                    11: 'bg-fuchsia-50'
                };

                const li = document.createElement('li');
                li.innerHTML = `
                    <div class="bg-gray-100 border border-gray-300 rounded mb-2 mx-1 flex justify-between items-center">
                        <span class="font-bold p-2 ${colores[p.rol_id] || 'bg-gray-50'}">${p.rol}</span>
                        <span class="font-medium">${p.tipo_documento} - ${p.numero_documento}</span>
                        <span class="font-medium">${p.nombre}</span>
                        <input type="hidden" name="integrantes[${contador}][rolParticipante]" value="${p.rol_id}">
                        <input type="hidden" name="integrantes[${contador}][tipoDoc]" value="${p.tipo_documento_id}">
                        <input type="hidden" name="integrantes[${contador}][numeroDoc]" value="${p.numero_documento}">
                        <input type="hidden" name="integrantes[${contador}][primerNombre]" value="${p.primer_nombre}">
                        <input type="hidden" name="integrantes[${contador}][segundoNombre]" value="${p.segundo_nombre || ''}">
                        <input type="hidden" name="integrantes[${contador}][primerApellido]" value="${p.primer_apellido}">
                        <input type="hidden" name="integrantes[${contador}][segundoApellido]" value="${p.segundo_apellido || ''}">
                        <button type="button" class="eliminar" onclick="eliminarIntegrante(this)">X</button>
                    </div>
                `;
                contenedor.append(li);
            });
        } else {
            contenedor.html(`
                <li id="integranteVacio">
                    <div class="bg-gray-100 border border-gray-300 rounded p-2 mb-2 mx-1 flex justify-center items-center">
                        <span class="font-bold"> --- Vacio --- </span>
                    </div>
                </li>
            `);
        }
    }

    const table = new DataTable('#recordListing', {
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        order: [],
        language: {
            lengthMenu: "Mostrar _MENU_ registros",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            sSearch: "Buscar: ",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            },
            sProcessing: "Procesando...",
        },
        ajax: {
            url: "?route=sacramentos/listar",
            type: "POST",
            data: {
                tipo: <?php echo json_encode($tipo); ?>,
                numero: <?php echo json_encode($numeroLibro); ?>
            },
            dataType: "json"
        },
        columns: [{
                className: 'details-control',
                orderable: false,
                data: null,
                defaultContent: '<span class="toggle-icon pl-2">➕</span>',
                width: "10px"
            },
            {
                data: 'tipo_sacramento',
                title: 'Tipo Sacramento'
            },
            {
                data: 'participante_principal',
                title: 'Participante(s) Principal(es)',
                render: function(data, type, row) {
                    if (!data) {
                        return '<span class="text-gray-400 italic">Sin datos</span>';
                    }

                    // Si es matrimonio (array con dos personas)
                    if (Array.isArray(data)) {
                        return `
                            <div class="space-y-1">
                                ${data.map(p => `
                                    <div class="flex items-center gap-2 bg-gradient-to-r from-[#F5F0EB] to-[#E8DFD5] p-2 rounded-lg border-l-4 border-[#D0B8A8]">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-[#D0B8A8] flex items-center justify-center text-white font-bold">
                                                ${p.nombre.charAt(0)}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-gray-800 truncate">${p.nombre}</div>
                                            <div class="text-xs text-gray-600">
                                                <span class="font-medium">${p.rol}</span>
                                                ${p.tipo_documento && p.numero_documento ?
                                                    ` • ${p.tipo_documento}: ${p.numero_documento}` : ''}
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }

                    // Para otros sacramentos (una sola persona)
                    return `
                        <div class="flex items-center gap-3 bg-gradient-to-r from-[#F5F0EB] to-[#E8DFD5] p-3 rounded-lg border-l-4 border-[#D0B8A8]">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-[#D0B8A8] flex items-center justify-center text-white font-bold text-lg">
                                    ${data.nombre.charAt(0)}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-800 text-sm">${data.nombre}</div>
                                <div class="text-xs text-gray-600 mt-1">
                                    <span class="inline-block bg-[#ab876f] text-white px-2 py-0.5 rounded text-xs font-medium">
                                        ${data.rol}
                                    </span>
                                    ${data.tipo_documento && data.numero_documento ?
                                        `<span class="ml-2">${data.tipo_documento}: ${data.numero_documento}</span>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                }
            },
            {
                data: 'fecha_generacion',
                title: 'Fecha',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('es-ES') : '';
                }
            },
            {
                data: 'lugar',
                title: 'Lugar',
                defaultContent: 'N/A'
            },
            {
                data: null,
                title: 'Acciones',
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `<div class="flex gap-2 justify-center">
                                <button class="btn-editar-sacramento bg-[#E8DFD5] hover:bg-[#DFD3C3] text-[#ab876f] px-3 py-1.5 rounded-lg text-sm font-medium transition duration-200"
                                        data-sacramento-id="${row.id}"
                                        title="Editar este sacramento">
                                    ✏️ Editar
                                </button>
                                <button class="btn-generar-certificado bg-[#D0B8A8] hover:bg-[#ab876f] text-white px-3 py-1.5 rounded-lg text-sm font-medium transition duration-200"
                                        data-sacramento-id="${row.id}"
                                        data-tipo="${row.tipo_sacramento}"
                                        title="Generar certificado de este sacramento">
                                    📄 Certificado
                                </button>
                            </div>`;
                }
            }
        ],
        pageLength: 10
    });

    $('#recordListing tbody').on('click', 'td.details-control', function() {
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        const icon = tr.find('span.toggle-icon');

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            icon.text('➕');
        } else {
            const sacramentoId = row.data().id;

            $.ajax({
                url: '<?= url('sacramentos/participantes') ?>',
                type: 'POST',
                data: {
                    sacramento_id: sacramentoId
                },
                dataType: 'json',
                success: function(data) {
                    let html = '';
                    if (data.length > 0) {
                        html = `
                            <div class="bg-gradient-to-r from-[#F5F0EB] to-white p-4 rounded-lg border border-[#DFD3C3]">
                                <h4 class="text-sm font-bold text-[#ab876f] mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Todos los Participantes (${data.length})
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        `;

                        // Colores por rol
                        const coloresRol = {
                            'Bautizado': 'bg-blue-100 text-blue-800 border-blue-300',
                            'Confirmando': 'bg-purple-100 text-purple-800 border-purple-300',
                            'Difunto': 'bg-gray-100 text-gray-800 border-gray-300',
                            'Esposo': 'bg-yellow-100 text-yellow-800 border-yellow-300',
                            'Esposa': 'bg-pink-100 text-pink-800 border-pink-300',
                            'Padre': 'bg-indigo-100 text-indigo-800 border-indigo-300',
                            'Madre': 'bg-rose-100 text-rose-800 border-rose-300',
                            'Padrino': 'bg-cyan-100 text-cyan-800 border-cyan-300',
                            'Madrina': 'bg-emerald-100 text-emerald-800 border-emerald-300',
                            'Abuelo': 'bg-violet-100 text-violet-800 border-violet-300',
                            'Abuela': 'bg-fuchsia-100 text-fuchsia-800 border-fuchsia-300'
                        };

                        data.forEach(p => {
                            const colorClasses = coloresRol[p.rol] || 'bg-gray-100 text-gray-800 border-gray-300';

                            html += `
                                <div class="flex items-center gap-3 bg-white p-3 rounded-lg border ${colorClasses.split(' ')[2]} shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full ${colorClasses.split(' ')[0]} flex items-center justify-center font-bold ${colorClasses.split(' ')[1]}">
                                            ${p.nombre.charAt(0)}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 text-sm truncate">${p.nombre}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-block ${colorClasses} px-2 py-0.5 rounded-full text-xs font-medium border">
                                                ${p.rol}
                                            </span>
                                            ${p.tipo_documento && p.numero_documento ?
                                                `<span class="text-xs text-gray-500">${p.tipo_documento}: ${p.numero_documento}</span>`
                                                : ''}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        html += `
                                </div>
                            </div>
                        `;
                    } else {
                        html = '<div class="p-4 text-center text-gray-500 italic">No hay participantes adicionales</div>';
                    }

                    row.child(html).show();
                    tr.addClass('shown');
                    icon.text('➖');
                },
                error: function() {
                    row.child('<div class="p-4 text-center text-red-500">Error al cargar participantes</div>').show();
                    tr.addClass('shown');
                    icon.text('➖');
                }
            });
        }
    });

    // Event handler para botón de generar certificado
    $(document).on('click', '.btn-generar-certificado', function() {
        const sacramentoId = $(this).data('sacramento-id');
        const tipo = $(this).data('tipo');

        // Confirmación antes de generar
        Swal.fire({
            title: '¿Solicitar certificado?',
            html: `
                <p>Se creará una solicitud de certificado para este <strong>${tipo}</strong>.</p>
                <p class="text-sm text-gray-600 mt-2">
                    <em>El certificado quedará en estado "Pendiente de Pago".<br>
                    Deberá registrar el pago en la siguiente pantalla para generarlo.</em>
                </p>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#D0B8A8',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, solicitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir al controlador para crear la solicitud y seguir el flujo estándar
                window.location.href = `<?= url('certificados/generar') ?>?sacramento_id=${sacramentoId}`;
            }
        });
    });









    $(document).on('submit', '#recordForm', function(event) {
        event.preventDefault();




        let rolesActuales = [];

        // Usar 'roles' global definido al inicio del script

        const libroTipo = <?= json_encode($libroTipo) ?>;



        $('input[name^="integrantes"][name$="[rolParticipante]"]').each(function() {
            let rol = roles[$(this).val().trim()] || 'Desconocido';;
            if (rol !== '') {
                rolesActuales.push(rol);
            }
        });





        // Verificar que cada rol obligatorio esté presente
        let faltantes = rolesObligatorios.filter(rol => !rolesActuales.includes(rol));

        if (faltantes.length > 0) {
            // Usamos Swal.fire en lugar de Toast para mostrar listas de errores
            Swal.fire({
                icon: "warning",
                title: "Faltan Participantes",
                html: `
                    <p class="mb-3 text-gray-700">Para poder guardar este sacramento, debes agregar a las siguientes personas:</p>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 inline-block text-left w-full">
                        <ul class="list-disc list-inside text-orange-800 font-semibold space-y-1">
                            <li>${faltantes.join('</li><li>')}</li>
                        </ul>
                    </div>
                `,
                confirmButtonColor: '#D0B8A8',
                confirmButtonText: 'Entendido'
            });

            return; // No se envía el formulario
        }


        var formData = $(this).serialize();

        $.ajax({
            url: "?route=sacramentos/crear",
            method: "POST",
            data: formData,
            dataType: "json", // Expect JSON explicitly
            beforeSend: function() {
                $('#Guardar').prop('disabled', true).text('Guardando...');
            },
            success: function(response) {
                if (response.success) {
                    $('#recordForm')[0].reset();
                    $('#recordModal').addClass('hidden');
                    $('#feligresNoExiste').addClass('hidden');

                    // Recargar tabla con delay para asegurar que el registro se guardó
                    setTimeout(() => {
                        table.ajax.reload(null, false);
                    }, 300);

                    Swal.fire({
                        icon: 'success',
                        title: 'Completado',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', {
                    xhr,
                    status,
                    error
                });

                let mensaje = 'Error al guardar el sacramento';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: mensaje
                });
            },
            complete: function() {
                $('#Guardar').prop('disabled', false).text('Guardar');
            }
        })



    });
    // ============================================================================
    // FUNCIONES AUXILIARES
    // ============================================================================

    /**
     * Actualiza las opciones del select de roles
     * Deshabilita los roles que ya han sido seleccionados (si son únicos)
     */
    function actualizarOpcionesRol() {
        const rolesSeleccionados = [];

        // Obtener roles ya presentes en la lista
        $('#contenedor-integrantes li').each(function() {
            const rolInput = $(this).find('input[name$="[rolParticipante]"]');
            if (rolInput.length > 0) {
                rolesSeleccionados.push(parseInt(rolInput.val()));
            }
        });

        // Roles que solo pueden aparecer una vez
        // 1=Bautizado, 2=Confirmando, 3=Difunto, 4=Esposo, 5=Esposa, 
        // 6=Padre, 7=Madre, 8=Padrino, 9=Madrina 
        const rolesUnicos = [1, 2, 3, 4, 5, 6, 7];

        $('#rolParticipante option').each(function() {
            const val = parseInt($(this).val());

            if (rolesSeleccionados.includes(val) && rolesUnicos.includes(val)) {
                $(this).prop('disabled', true);
                if ($('#rolParticipante').val() == val) {
                    $('#rolParticipante').val('');
                }
            } else {
                $(this).prop('disabled', false);
            }
        });
    }
</script>

<?php include_once __DIR__ . '/componentes/plantillaBottom.php'; ?>