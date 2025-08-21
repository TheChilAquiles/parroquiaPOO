

	

console.log("Inicializando DataTable");


$(document).ready(function () {


    
    



    const dataRecords = new DataTable('#recordListing', {

        // processing: false,
        // serverSide: true,
        // serverMethod: 'post',
        // order: [],
        // language: {
        //     lengthMenu: "Mostrar _MENU_ registros",
        //     zeroRecords: "No se encontraron resultados",
        //     info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        //     infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
        //     infoFiltered: "(filtrado de un total de _MAX_ registros)",
        //     sSearch: "Buscar:",
        //     oPaginate: {
        //         sFirst: "Primero",
        //         sLast: "Último",
        //         sNext: "Siguiente",
        //         sPrevious: "Anterior"
        //     },
        //     sProcessing: "Procesando...",
        // },
        // ajax: {
        //     url: "Controlador/ControladorAjax.php",
        //     type: "POST",
        //     data: { Doaction: 'listRecords' },
        //     dataType: "json"
        // },
        // columnDefs: [
        //     {
        //         targets: [0, 6, 7],
        //         orderable: false,
        //     },
        // ],
        // pageLength: 10


    });










});



// $(document).ready(function () {

//     var dataRecords = $('#recordListing').DataTable({
//         processing: true,
//         serverSide: true,
//         serverMethod: 'post',
//         order: [],
//         language: {
//             lengthMenu: "Mostrar _MENU_ registros",
//             zeroRecords: "No se encontraron resultados",
//             info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
//             infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
//             infoFiltered: "(filtrado de un total de _MAX_ registros)",
//             sSearch: "Buscar:",
//             oPaginate: {
//                 sFirst: "Primero",
//                 sLast: "Último",
//                 sNext: "Siguiente",
//                 sPrevious: "Anterior"
//             },
//             sProcessing: "Procesando...",
//         },
//         ajax: {
//             url: "Controlador/ControladorAjax.php",
//             type: "POST",
//             data: { Doaction: 'listRecords' },
//             dataType: "json"
//         },
//         columnDefs: [
//             {
//                 targets: [0, 6, 7],
//                 orderable: false,
//             },
//         ],
//         pageLength: 10
//     });




//     // Abrir modal para nuevo registro
//     $('#addRecord').click(function () {
//         openModal();
//         $('#recordForm')[0].reset();
//         $('.modal-title').text("Adición Registro");
//         $('#Doaction').val('addRecord');
//         $('#save').val('Adicionar');
//     });

//     // Abrir modal para editar registro
//     $("#recordListing").on('click', '.update', function () {
//         var id = $(this).attr("id");
//         var Doaction = 'getRecord';
//         $.ajax({
//             url: 'Controlador/ControladorAjax.php',
//             method: "POST",
//             data: { id: id, Doaction: Doaction },
//             dataType: "json",
//             success: function (data) {
//                 console.log("Respuesta desde el servidor:", data);
//                 openModal();
//                 $('#id').val(data.id);
//                 // $('#name').val(data.name);
//                 // $('#parroquia').val(data.parroquia);
//                 // $('#fechaFallecimiento').val(data.fechaFallecimiento);
//                 // $('#lugarNacimiento').val(data.lugarNacimiento);
//                 // $('#age').val(data.age);
//                 // $('#causaMuerte').val(data.causaMuerte);
//                 // $('#hijoDe').val(data.hijoDe);
//                 // $('#estadoCivil').val(data.estadoCivil);
//                 // $('.modal-title').text("Editar Registro");
//                 // $('#Doaction').val('updateRecord');
//                 // $('#save').val('Guardar');
//             }
//         });
//     });

//     // Enviar formulario (guardar/actualizar)
//     $("#recordModal").on('submit', '#recordForm', function (event) {
//         event.preventDefault();
//         $('#save').attr('disabled', 'disabled');
//         var formData = $(this).serialize();
//         $.ajax({
//             url: "Controlador/ControladorAjax.php",
//             method: "POST",
//             data: formData,
//             success: function (data) {
//                 $('#recordForm')[0].reset();
//                 closeModal();
//                 $('#save').attr('disabled', false);
//                 dataRecords.ajax.reload();
//             }
//         });
//     });

//     // Eliminar registro
//     $("#recordListing").on('click', '.delete', function () {
//         var id = $(this).attr("id");
//         var Doaction = "deleteRecord";
//         if (confirm("¿Está seguro de que desea eliminar este registro?")) {
//             $.ajax({
//                 url: "Controlador/ControladorAjax.php",
//                 method: "POST",
//                 data: { id: id, Doaction: Doaction },
//                 success: function (data) {
//                     dataRecords.ajax.reload();
//                 }
//             });
//         } else {
//             return false;
//         }
//     });
// });
