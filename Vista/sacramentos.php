<!-- Contenedor principal -->
<div class="min-h-[500px] px-4 py-8 flex-1 ">
    <div class="max-w-7xl mx-auto bg-white/60 rounded p-5">

        <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center"> <?= htmlspecialchars($libroTipo ?? 'Desconocido') . " " . htmlspecialchars($numeroLibro ?? '') ?> </h2>

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
                        <th></th> 
                        <th>Participante Principal</th>
                        <th>Tipo Documento</th>
                        <th>Numero Doc</th>
                        <th>Fecha</th>
                        <th>Certificado</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>
        </div>




    </div>
</div>




<!-- Modal Tailwind -->
<div id="recordModal" class="modal fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 max-h-[80svh] ">
        <h4 class="text-xl font-semibold mb-4 modal-title w-full text-center">Editar Registro</h4>




        <form id="recordForm" class="space-y-4" method="POST">




            <!-- inputs de navegacion  -->
            <input type="hidden" name="tipo" value="<?= $tipo ?>">
            <input type="hidden" name="numero" value="<?= $numeroLibro ?>">
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
                    <input type="date" id="fecha-evento" name="fecha-evento" placeholder="Fecha" class="w-full mt-1 p-2 border border-gray-300 rounded">
                </div>
            </div>

            <div id="Form2" class="hidden py-4">

                <div class="border border-blue-400 rounded mt-4">

                    <div class=" text-center text-lg font-bold my-1">
                        Participantes
                    </div>



                    <ul id="contenedor-integrantes">

                        <li id="integranteVacio">
                            <div class="bg-gray-100 border border-gray-300 rounded p-2 mb-2 mx-1 flex justify-center items-center">
                                <span class="font-bold"> --- Vacio --- </span>
                            </div>
                        </li>

                        <!-- Aquí se mostrarán los integrantes -->
                    </ul>
                </div>

                <hr class="my-4 border-t-2 border-gray-300">

                <div class="border border-emerald-400 p-2 rounded mb-4">


                    <div class=" text-center text-lg font-bold my-1">
                        Añadir Participante
                    </div>


                    <div class="mx-7">


                        <div class="flex space-x-2 mt-4 justify-center items-end">


                            <div>
                                <label for="">Tipo Documento</label>

                                <select placeholder="Selecciona un Documento" class="border border-gray-300 rounded  w-full placeholder:text-gray-100 placeholder:text-center p-2" name="tipo-doc" id="tipo-doc">
                                    <option class="text-center" value="" disabled selected>-- Selecciona un Documento --</option>
                                    <option value="1">Cedula Ciudadania</option>
                                    <option value="2">Tarjeta Identidad</option>
                                    <option value="3">Cedula Extranjeria</option>
                                    <option value="4">Registro Civil</option>
                                    <option value="5">Permiso Especial</option>
                                    <option value="6">Numero Identificación Tributaria</option>

                                </select>

                                <label name="primerNombre-error" class="text-red-500 hidden">Corrije Este Campo </label>
                            </div>


                            <div class="flex">

                                <div>
                                    <label for="">Numero De Documento</label>
                                    <input class="border border-gray-300 rounded  w-full placeholder:text-center p-2" type="text" name="numero-doc" id="numero-doc" placeholder="Numero de Documento" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    <label name="segundoNombre-error" class="text-red-500 hidden">Corrije Este Campo </label>
                                </div>

                            </div>


                            <div id="BuscarUser" class="p-2 rounded bg-green-500">



                                <svg class="w-6" viewBox="0 0 64 64" id="Layer_1" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                    <style>
                                        .st2 {
                                            fill: #d2f0ea
                                        }
                                    </style>
                                    <path class="st2" d="m37.24 40.04 2.87-2.94c2.38-3.05 3.67-6.89 3.82-11.06.33-9.51-7.81-17.67-18-18-9.94-.32-18 8.06-18 18s8.06 18 18 18c4.28 0 8.22-1.5 11.31-4" />
                                    <circle cx="25.93" cy="26.04" r="13.76" style="fill:#fff" />
                                    <path class="st2" d="M16.16 27.04c-.55 0-1-.45-1-1 0-5.94 4.83-10.76 10.76-10.76.55 0 1 .45 1 1s-.45 1-1 1c-4.83 0-8.76 3.93-8.76 8.76 0 .56-.45 1-1 1" />
                                    <path d="M54.46 48.03 42.33 37.96l-.68.68-1.54-1.54-2.87 2.94 1.51 1.51-.68.68 10.08 12.12a4.49 4.49 0 0 0 6.62.31 4.5 4.5 0 0 0-.31-6.63" style="fill:#7c64bd" />
                                    <path d="M25.93 39.81c-7.59 0-13.76-6.17-13.76-13.76v11.59c3.3 3.92 8.24 6.41 13.76 6.41 4.29 0 8.22-1.5 11.31-4.01l2.45-2.51V26.04c0 7.59-6.17 13.77-13.76 13.77" style="fill:#b4e6dd" />
                                </svg>




                            </div>

                        </div>




                    </div>

                    <div class="text-center text-orange-500 font-bold bg-orange-100  rounded rounded-orange-500 my-2 mx-1 hidden" id="feligresNoExiste">
                        <span>feligres no existe ingresa sus datos : </span>
                    </div>


                    <div class="flex space-x-2 mt-4 justify-center">
                        <div>
                            <label for="">Primer Nombre</label>
                            <input type="text" name="primerNombre" id="primerNombre" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu Primer Nombre" value="<?php if (isset($_POST['primerNombre'])) echo $_POST['primerNombre']; ?>">
                            <label name="primerNombre-error" class="text-red-500 hidden">Corrije Este Campo </label>
                        </div>

                        <div>
                            <label for="">Segundo Nombre</label>
                            <input type="text" name="segundoNombre" id="segundoNombre" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu segundo Nombre" value="<?php if (isset($_POST['segundoNombre'])) echo $_POST['segundoNombre']; ?>">
                            <label name="segundoNombre-error" class="text-red-500 hidden">Corrije Este Campo </label>
                        </div>

                    </div>





                    <div class="flex space-x-2 mt-4 justify-center">

                        <div>
                            <label for="">Primer Apellido</label>
                            <input type="text" name="primerApellido" id="primerApellido" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu Primer Nombre" value="<?php if (isset($_POST['primerApellido'])) echo $_POST['primerApellido']; ?>">
                            <label name="primerApellido-error" class="text-red-500 hidden">Corrije Este Campo </label>
                        </div>

                        <div>
                            <label for="">Segundo Apellido</label>
                            <input type="text" name="segundoApellido" id="segundoApellido" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu segundo Apellido" value="<?php if (isset($_POST['segundoApellido'])) echo $_POST['segundoApellido']; ?>">
                            <label name="segundoApellido-error" class="text-red-500 hidden">Corrije Este Campo </label>
                        </div>

                    </div>



                    <div class="mx-7">
                        <label for="rolParticipante" class="block font-medium">Rol</label>
                        <select class="border border-gray-300 rounded p-2 w-full" name="rolParticipante" id="rolParticipante">
                            <option class="text-center" value="" disabled selected>-- Selecciona un Rol --</option>

                            <?php

                            if ($tipo == 1) {
                                echo '<option value="10">Abuelo</option>';
                                echo '<option value="11">Abuela</option>';
                                echo '<option value="1">Bautizado</option>';


                                echo  '<script> const rolesObligatorios = [ "Bautizado", "Padrino" , "Madrina" ] </script>';
                            } elseif ($tipo == 2) {

                                echo '<option value="2">Confirmando</option>';

                                echo  '<script> const rolesObligatorios = [ "Confirmando", "Padrino" , "Madrina" ] </script>';
                            } elseif ($tipo == 3) {

                                echo '<option value="3">Difunto</option>';

                                echo  '<script> const rolesObligatorios = [ "Confirmando", "Padrino" , "Madrina" ] </script>';
                            } elseif ($tipo == 4) {

                                echo '<option value="4">Esposo</option>';
                                echo '<option value="5">Esposa</option>';

                                echo '<option value="12">Esposo Padrino</option>';
                                echo '<option value="13">Esposo Madrina</option>';
                                echo '<option value="14">Esposa Padrino</option>';
                                echo '<option value="15">Esposa Madrina</option>';


                                echo  '<script> const rolesObligatorios = [ "Esposo", "Esposa" , "Esposo Padrino" , "Esposo Madrina" , "Esposa Padrino" , "Esposa Madrina" ] </script>';
                            }
                            ?>

                            <option value="6">Padre</option>
                            <option value="7">Madre</option>

                            <?php if ($tipo !== 3 &&  $tipo !== 4) {

                                echo ' <option value="8">Padrino</option>';
                                echo '<option value="9">Madrina</option>';
                            }; ?>
                        </select>
                    </div>


                    <div id="AddNew" class="w-full bg-gray-200 rounded text-center font-bold py-1 cursor-pointer my-3">+ Añadir Participante</div>







                </div>



                <!-- <div id="Form2" class="hidden">
                <div class="flex space-x-2 w-full justify-between ">
                    <select class="border border-gray-300 rounded  w-full" name="TipDoc" id="TipDoc">
                        <option value="CC">CC</option>
                        <option value="CC">Ti</option>
                    </select>


                    <input class="border border-gray-300 rounded  w-full" type="text" name="numero-doc" id="numero-doc" placeholder="Numero de Documento">


                    <div class="p-2 rounded bg-green-500">
                        <svg class="w-10" viewBox="0 0 48 48" version="1" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#616161">
                                <path d="m2 9.174 31.99 2.828-2.829 12.02 12.021-2.828 2.828z" />
                                <circle cx="20" cy="20" r="16" />
                            </g>
                            <path fill="#37474F" d="m32.448 35.34 2.828-2.828 8.698 8.697-2.829 2.828z" />
                            <circle fill="#64B5F6" cx="20" cy="20" r="13" />
                            <path fill="#BBDEFB" d="M26.9 14.2c-1.7-2-4.2-3.2-6.9-3.2s-5.2 1.2-6.9 3.2c-.4.4-.3 1.1.1 1.4.4.4 1.1.3 1.4-.1C16 13.9 17.9 13 20 13s4 .9 5.4 2.5c.2.2.5.4.8.4.2 0 .5-.1.6-.2.4-.4.4-1.1.1-1.5" />
                        </svg>
                    </div>

                </div>



                <div class="flex space-x-2">

                    <div>
                        <label for="">Primer Nombre</label>
                        <input type="text" name="primerNombre" id="primerNombre" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu Primer Nombre" value="<?php if (isset($_POST['primerNombre'])) echo $_POST['primerNombre']; ?>">
                        <label name="primerNombre-error" class="text-red-500 hidden">Corrije Este Campo </label>
                    </div>

                    <div>
                        <label for="">Segundo Nombre</label>
                        <input type="text" name="segundoNombre" id="segundoNombre" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu segundo Nombre" value="<?php if (isset($_POST['segundoNombre'])) echo $_POST['segundoNombre']; ?>">
                        <label name="segundoNombre-error" class="text-red-500 hidden">Corrije Este Campo </label>
                    </div>

                </div>



                <div class="flex space-x-2">

                    <div>
                        <label for="">Primer Apellido</label>
                        <input type="text" name="primerNombre" id="primerNombre" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu Primer Nombre" value="<?php if (isset($_POST['primerNombre'])) echo $_POST['primerNombre']; ?>">
                        <label name="primerNombre-error" class="text-red-500 hidden">Corrije Este Campo </label>
                    </div>

                    <div>
                        <label for="">Segundo Apellido</label>
                        <input type="text" name="segundoNombre" id="segundoNombre" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu segundo Nombre" value="<?php if (isset($_POST['segundoNombre'])) echo $_POST['segundoNombre']; ?>">
                        <label name="segundoNombre-error" class="text-red-500 hidden">Corrije Este Campo </label>
                    </div>

                </div>
            </div> -->



            </div>


            <div class="flex justify-end gap-2 pt-4">

                <button type="button" id="Anterior" class="bg-blue-100 px-4 py-2 rounded cursor-pointer hidden">Anterior</button>
                <button type="button" id="Siguiente" class="bg-blue-100 px-4 py-2 rounded cursor-pointer">Siguiente</button>
                <button type="submit" id="Guardar" class="bg-blue-100 text-black/50 px-4 py-2 rounded hidden">Guardar</button>

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
    $(document).on('click', '#BuscarUser', function() {

        alert('Buscar Usuario');

        $numeroDoc = document.getElementById('numero-doc').value.trim();
        $tipoDoc = document.getElementById('tipo-doc').value.trim();



        if (!$tipoDoc) {
            document.getElementById('tipo-doc').classList.add('border-red-500');
            alert('Por favor, ingresa el tipo de documento.');
            return;
        } else {
            document.getElementById('tipo-doc').classList.remove('border-red-500');
        }

        if (!$numeroDoc) {
            document.getElementById('numero-doc').classList.add('border-red-500');
            alert('Por favor, ingresa el número de documento.');
            return;
        } else {
            document.getElementById('numero-doc').classList.remove('border-red-500');
        }





        $.ajax({
            url: "?route=sacramentos/buscar-usuario",
            method: "POST",
            dataType: "json",
            data: {
                Doaction: 'buscarUsuario',
                numeroDoc: $numeroDoc,
                tipoDoc: $tipoDoc,
                tipo: <?php echo json_encode($tipo); ?>,
                numero: <?php echo json_encode($numeroLibro); ?>
            },
            success: function(usuario) {

                if (usuario.status === 'success' && usuario.data) {
                    // Rellenar los campos del formulario con los datos del usuario
                    $('#primerNombre').val(usuario.data.primer_nombre || '');
                    $('#segundoNombre').val(usuario.data.segundo_nombre || '');
                    $('#primerApellido').val(usuario.data.primer_apellido || '');
                    $('#segundoApellido').val(usuario.data.segundo_apellido || '');
                    // Agrega más campos según sea necesario

                    $('#feligresNoExiste').addClass('hidden');



                    $('#rolParticipante').addClass('border-orange-300 bg-orange-50 animate-pulse text-orange-600');
                    setTimeout(
                        function() {
                            $('#rolParticipante').removeClass('animate-pulse bg-orange-50 border-orange-300 text-orange-600');
                        }, 5000);




                    // alert('Usuario encontrado: ' + usuario.primer_nombre);



                } else {
                    alert('No se encontró el usuario con el número de documento proporcionado.');
                    $('#feligresNoExiste').removeClass('hidden');



                    resaltarCampo('#primerNombre');
                    resaltarCampo('#primerApellido');





                }





            },

            error: function(e) {
                alert('Error al buscar el usuario.' + e.responseText);
                console.log('Error al buscar el usuario.', e);
            }
        });



    });

    $(document).on('click', '#AddNew', function() {
        agregarIntegrante();
        $('#feligresNoExiste').addClass('hidden');
        resetVacio(contador);
    });


    let contador = 0;

    function resetVacio(con) {
        if (con > 0) {
            document.getElementById('integranteVacio').classList.add('hidden');;
        } else {
            document.getElementById('integranteVacio').classList.remove('hidden');;
        }
    }


    function resaltarCampo(idCampo) {

        $(idCampo).addClass('border-orange-300 bg-orange-50 animate-pulse text-orange-600');
        setTimeout(function() {
            $(idCampo).removeClass('animate-pulse bg-orange-50 border-orange-300 text-orange-600');
        }, 5000);

    }



    function agregarIntegrante() {

        const rolParticipante = document.getElementById('rolParticipante').value.trim();
        const tipoDoc = document.getElementById('tipo-doc').value.trim();
        const numeroDoc = document.getElementById('numero-doc').value.trim();



        const primerNombre = document.getElementById('primerNombre').value.trim();
        const segundoNombre = document.getElementById('segundoNombre').value.trim();
        const primerApellido = document.getElementById('primerApellido').value.trim();
        const segundoApellido = document.getElementById('segundoApellido').value.trim();


        if (!tipoDoc || !numeroDoc || !rolParticipante || !primerNombre || !primerApellido) {

            resaltarCampo('#primerNombre');
            resaltarCampo('#primerApellido');
            resaltarCampo('#segundoNombre');
            resaltarCampo('#segundoApellido');
            resaltarCampo('#tipo-doc');
            resaltarCampo('#numero-doc');
            resaltarCampo('#rolParticipante');




            $('#rolParticipante').addClass('border-orange-300 bg-orange-50 animate-pulse text-orange-600');
            setTimeout(
                function() {
                    $('#rolParticipante').removeClass('animate-pulse bg-orange-50 border-orange-300 text-orange-600');
                }, 5000);







            Toast.fire({
                icon: "warning",
                title: "Inserta Datos Para Continuar"
            });



            return;


        }

        const inputs = Array.from(document.querySelectorAll('#contenedor-integrantes input[name$="[rolParticipante]"]'));

        const inputEncontrado = inputs.find(input => input.value === rolParticipante);


        const existe = !!inputEncontrado; // true o false

        const existedoc = Array.from(document.querySelectorAll('#contenedor-integrantes li')).some(li => {
            const tipo = li.querySelector('input[name$="[tipoDoc]"]')?.value;
            const numero = li.querySelector('input[name$="[numeroDoc]"]')?.value;
            return tipo == tipoDoc && numero == numeroDoc;
        });

        if (existedoc) {

            resaltarCampo('#tipo-doc');
            resaltarCampo('#numero-doc');


            alert('ya hay un participanteas con ese Documento. ');

            return;
        }


        if (existe) {
            const idDelInput = inputEncontrado ? inputEncontrado.id : null;

            resaltarCampo('#rolParticipante');

            const select = document.getElementById('rolParticipante');
            const textoSeleccionado = select.options[select.selectedIndex].text;

            resaltarCampo('#' + textoSeleccionado + '-rol');

            alert('Este ROL ya ha sido añadido.' + textoSeleccionado);
            return;
        }


        contador++;


        const tiposDocs = {
            1: "Cedula Ciudadania",
            2: "Tarjeta Identidad",
            3: "Cedula Extranjeria",
            4: "Registro Civil",
            5: "Permiso Especial",
            6: "Numero Identificación Tributaria"
        };


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

        const grupoRol = roles[rolParticipante] || 'Desconocido';
        const grupoColor = colores[rolParticipante] || 'Desconocido';

        const tipDoc = tiposDocs[tipoDoc] || 'Desconocido';

        const li = document.createElement('li');
        li.innerHTML = `


        <div class="bg-gray-100 border border-gray-300 rounded mb-2 mx-1 flex justify-between items-center">
        
        <span class="font-bold p-2  ${grupoColor} " id="${grupoRol}-rol" >${grupoRol}</span>

        <span class="font-medium">  ${tipDoc} - ${numeroDoc}  </span>
        <span class="font-medium">  ${[primerNombre, segundoNombre, primerApellido, segundoApellido].filter(Boolean).join(' ')}  </span>
   
          <input type="hidden" name="integrantes[${contador}][rolParticipante]" value="${rolParticipante}">
          <input type="hidden" name="integrantes[${contador}][tipoDoc]" value="${tipoDoc}">
          <input type="hidden" name="integrantes[${contador}][numeroDoc]" value="${numeroDoc}">
          <input type="hidden" name="integrantes[${contador}][primerNombre]" value="${primerNombre}">
          <input type="hidden" name="integrantes[${contador}][segundoNombre]" value="${segundoNombre}">
          <input type="hidden" name="integrantes[${contador}][primerApellido]" value="${primerApellido}">
          <input type="hidden" name="integrantes[${contador}][segundoApellido]" value="${segundoApellido}">



          <button type="button" class="eliminar" onclick="eliminarIntegrante(this)">X</button>
          </div>

      `;

        document.getElementById('contenedor-integrantes').appendChild(li);

        // Limpiar los campos después de añadir
        document.getElementById('tipo-doc').value = '';
        document.getElementById('numero-doc').value = '';
        document.getElementById('primerNombre').value = '';
        document.getElementById('segundoNombre').value = '';
        document.getElementById('primerApellido').value = '';
        document.getElementById('segundoApellido').value = '';
        document.getElementById('rolParticipante').value = '';


    }

    function eliminarIntegrante(boton) {
        boton.closest('li').remove();
        contador--;
        resetVacio(contador);
    }


    function eliminarTodos() {
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
        $('.modal-title').html("<i class='fa fa-plus'></i> Añadir Sacramento En <?php echo $libroTipo  . " " . $numeroLibro ?>  ");
        $('#Doaction').val('addRecord');
        // $('#save').val('Adicionar');
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
                data: 'participante_principal',
                title: 'Participante Principal'
            },
            {
                data: 'tipo_documento',
                title: 'Tipo Documento' // Te recomiendo poner título para que se vea bien
            },
            {
                data: 'numero_documento',
                title: 'Número Documento' // Lo mismo aquí
            },
            {
                data: 'fecha_generacion',
                title: 'Fecha'
            },
            {
                data: 'tiene_certificado',
                title: 'Certificado'
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
            const sacramentoId = row.data().sacramento_id;

            $.ajax({
                url: '?route=sacramentos/participantes',
                type: 'POST',
                data: {
                    sacramento_id: sacramentoId
                },
                dataType: 'json',
                success: function(data) {
                    let html = '';
                    if (data.length > 0) {
                        html = '<ul style="margin:0;padding-left:15px">';
                        data.forEach(p => {
                            html += `<li><strong>${p.rol}</strong>: ${p.nombre}</li>`;
                        });
                        html += '</ul>';
                    } else {
                        html = '<em>No hay participantes adicionales</em>';
                    }

                    row.child(html).show();
                    tr.addClass('shown');
                    icon.text('➖');
                },
                error: function() {
                    row.child('<em>Error al cargar participantes</em>').show();
                    tr.addClass('shown');
                    icon.text('➖');
                }
            });
        }
    });












    $("#recordModal").on('submit', '#recordForm', function(event) {
        event.preventDefault();




        let rolesActuales = [];


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


        // Aqui vamos Rusbelll :D 
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



            Toast.fire({
                icon: "warning",
                title: "\n Faltan los siguientes roles obligatorios:\n- " + faltantes.join("\n- "),
                timer: 100000,
            });


            alert("Faltan los siguientes roles obligatorios:\n- " + faltantes.join("\n- "));



            return; // No se envía el formulario
        }



        var formData = $(this).serialize();
        //  $('#Guardar').attr('disabled','disabled');
        // alert('fformData: ' + formData);




        $.ajax({
            url: "?route=sacramentos/crear",
            method: "POST",
            data: formData + '&Tipo=<?php echo $tipo; ?>&Numero=<?php echo $numeroLibro; ?>',
            success: function(data) {

                $('#recordForm')[0].reset();
                $('#recordModal').addClass('hidden');
                $('#feligresNoExiste').addClass('hidden');


                //  $('#save').attr('disabled', false);

                table.ajax.reload();
                alert('completado');

            }
        })



    });
</script>