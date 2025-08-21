<!-- Contenedor principal -->
<div class="min-h-[500px] px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">DATA TABLE DEL FORMULARIO DE BAUTIZO</h2>

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium"></h3>
            <button id="addRecord" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Agregar nuevo Bautizado
            </button>
        </div>

        <!-- Tabla -->
        <div class="overflow-auto">
            <table id="recordListing" class="min-w-full text-sm text-left text-gray-700 border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 border">#</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tailwind -->
<div id="recordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
        <h4 class="text-xl font-semibold mb-4 modal-title">Editar Registro</h4>
        <form id="recordForm" class="space-y-4">
            <input type="hidden" name="id" id="id" />
            <input type="hidden" name="Doaction" id="Doaction" value="" />

            <div>
                <label for="name" class="block font-medium">Nombre del Bautizado</label>
                <input type="text" id="name" name="name" placeholder="Name" required class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="parroquia" class="block font-medium">Nombre de la Parroquia</label>
                <input type="text" id="parroquia" name="parroquia" placeholder="Parroquia" required class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="fechaFallecimiento" class="block font-medium">Fecha del Evento</label>
                <input type="date" id="fechaFallecimiento" name="fechaFallecimiento" required class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="lugarNacimiento" class="block font-medium">Lugar de Nacimiento</label>
                <input type="text" id="lugarNacimiento" name="lugarNacimiento" placeholder="Lugar de nacimiento" required class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="age" class="block font-medium">Edad</label>
                <input type="number" id="age" name="age" placeholder="Edad" class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="causaMuerte" class="block font-medium">Madrina</label>
                <input type="text" id="causaMuerte" name="causaMuerte" placeholder="Madrina" required class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="hijoDe" class="block font-medium">Hijo de</label>
                <input type="text" id="hijoDe" name="hijoDe" placeholder="Hijo de" class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="estadoCivil" class="block font-medium">Estado Civil</label>
                <select id="estadoCivil" name="estadoCivil" class="w-full mt-1 p-2 border border-gray-300 rounded">
                    <option value="" disabled selected>Seleccione el Estado Civil</option>
                    <option value="Soltero">Soltero</option>
                    <option value="Casado">Casado</option>
                    <option value="Divorciado">Divorciado</option>
                    <option value="Viudo">Viudo</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded">Cerrar</button>
                <button type="submit" id="save" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>


<script>


    function openModal() {
        document.getElementById("recordModal").classList.remove("hidden");
    }

    function closeModal() {
        document.getElementById("recordModal").classList.add("hidden");
    }
</script>

<!-- </div>  
            <div class="insert-post-ads1" style="margin-top:20px;">
            </div>
        </div> -->



        <table id="myTable" class="display">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
        </tr>
        <tr>
            <td>Row 2 Data 1</td>
            <td>Row 2 Data 2</td>
        </tr>
    </tbody>
</table>

<script>
  const table = new DataTable('#recordListing' , {

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
            url: "Controlador/ControladorAjax.php",
            type: "POST",
            data: { Doaction: 'listRecords' },
            dataType: "json"
        },
        columnDefs: [
            {
                // targets: [0, 6, 7],
                orderable: false,
            },
        ],
        pageLength: 10

  });




</script>