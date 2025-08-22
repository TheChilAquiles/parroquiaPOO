<!-- Contenedor principal -->
<div class="min-h-[500px] px-4 py-8 ">
    <div class="max-w-7xl mx-auto bg-red-500">

        <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center"> <?= $libroTipo . " " . $_POST['numero-libro'] ?> </h2>

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

<?= "Solae : ". $_POST['numero-libro'] ?>

<!-- Modal Tailwind -->
<div id="recordModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
        <h4 class="text-xl font-semibold mb-4 modal-title w-full text-center">Editar Registro</h4>




        <form id="recordForm" class="space-y-4" method="POST">


            <!-- inputs de navegacion  -->
            <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('DefinirTipolibro') ?>">
            <input type="hidden" name="<?= md5('tipo') ?>" value="<?= $tipo ?>">
            <input type="hidden" name="<?= md5('sub-action') ?>" value="<?= md5('RegistrosLibro') ?>">
            <input type="hidden" name="numero-libro" value="<?= $_POST['numero-libro'] ?>">
            <!-- Fin inputs de navegacion  -->



            <input type="hidden" name="id" id="id" />
            <input type="hidden" name="Doaction" id="Doaction" value="" />



            <div>
                <label for="fecha-evento" class="block font-medium">Fecha Evento</label>
                <input type="text" id="fecha-evento" name="fecha-evento" placeholder="Name" class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>




            <!-- <input type="hidden" name="save" id="save" value="" /> -->
            <div>
                <label for="name" class="block font-medium">Nombre del Bautizado</label>
                <input type="text" id="name" name="name" placeholder="Name" class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>





            <div>
                <label for="parroquia" class="block font-medium">Nombre de la Parroquia</label>
                <input type="text" id="parroquia" name="parroquia" placeholder="Parroquia" class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="fechaFallecimiento" class="block font-medium">Fecha del Evento</label>
                <input type="date" id="fechaFallecimiento" name="fechaFallecimiento" class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="lugarNacimiento" class="block font-medium">Lugar de Nacimiento</label>
                <input type="text" id="lugarNacimiento" name="lugarNacimiento" placeholder="Lugar de nacimiento" class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="age" class="block font-medium">Edad</label>
                <input type="number" id="age" name="age" placeholder="Edad" class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div>
                <label for="causaMuerte" class="block font-medium">Madrina</label>
                <input type="text" id="causaMuerte" name="causaMuerte" placeholder="Madrina" class="w-full mt-1 p-2 border border-gray-300 rounded">
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

                <button type="submit" class="bg-blue-100 text-black/50 px-4 py-2 rounded">Guardar</button>
                <button type="button" id="cerrarFormSacramentos" class="bg-red-100 px-4 py-2 rounded cursor-pointer">Cerrar</button>
            </div>
        </form>
    </div>
</div>






<!-- </div>  
            <div class="insert-post-ads1" style="margin-top:20px;">
            </div>
        </div> -->






<script>
    $(document).on('click', '#cerrarFormSacramentos', function() {
        $('#recordModal').addClass('hidden');
        $('#recordForm')[0].reset();
    });

    $(document).on('click', '#addRecord', function() {
        $('#recordModal').removeClass('hidden');
        $('#recordForm')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Adición Registro");
        $('#Doaction').val('addRecord');
        // $('#save').val('Adicionar');
    });

    $("#recordModal").on('submit', '#recordForm', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        //    $('#save').attr('disabled','disabled');
        // alert('fformData: ' + formData);
        $.ajax({
            url: "Controlador/ControladorAjax.php",
            method: "POST",
        data: formData + '&Tipo=<?php echo $tipo; ?>&Numero=<?php echo $_POST["numero-libro"]; ?>',
            success: function(data) {

                $('#recordForm')[0].reset();
                $('#recordModal').addClass('hidden');

                //  $('#save').attr('disabled', false);

                alert('completado');
                dataRecords.ajax.reload();

            }
        })
    });






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
            url: "Controlador/ControladorAjax.php",
            type: "POST",
            data: {
                Doaction: 'listRecords',

                Tipo: <?php echo json_encode($tipo); ?>,
                Numero: <?php echo json_encode($_POST['numero-libro']); ?>
            },
            dataType: "json"
        },
        columnDefs: [{
            // targets: [0, 6, 7],
            orderable: false,
        }, ],
        pageLength: 10

    });
</script>