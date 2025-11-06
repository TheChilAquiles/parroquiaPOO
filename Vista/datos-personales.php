<div class="w-full bg-emerald-500 text-xl font-bold p-2 hidden" id="Datos">Completa tus datos Para Continuar</div>
<div class="flex flex-col flex-1 w-full h-full justify-center items-center">
    <h1 class="text-3xl my-2 font-bold">Datos Personales</h1>


    <div class="p-10 rounded-md bg-white w-[60svw]">



        <form method="POST" onsubmit="verifyFeligres(event)" class=" flex justify-center   space-x-1 ">

            <!-- <label for="">Nombre</label>
        <input type="text" class="border border-gray-300 rounded p-2 mb-4 w-full" placeholder="Ingresa Tu Primer Nombre" requited> -->
            <div class="flex-1" >
                <label for="">Tipo Documento</label>
                <select onblur="cambioTipo()" id="tipoDocumento" name="tipoDocumento" class="border border-gray-300 rounded p-2 w-full">
                    <option value="" disabled <?= empty($_POST['tipoDocumento']) ? 'selected' : '' ?>>Selecciona Tipo Documento</option>
                    <option value="1" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == 'Cédula') ? 'selected' : '' ?>>Cédula Ciudadania</option>
                    <option value="2" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == 'Tarjeta Identidad') ? 'selected' : '' ?>>Tarjeta Identidad</option>
                    <option value="3" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == 'Cedula Extranjera') ? 'selected' : '' ?>>Cedula Extranjera</option>
                    <option value="4" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == 'Registro Civil') ? 'selected' : '' ?>>Registro Civil</option>
                    <option value="5" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == 'Permiso Especial') ? 'selected' : '' ?>>Permiso Especial</option>
                    <option value="6" <?= (isset($_POST['tipoDocumento']) && $_POST['tipoDocumento'] == 'Numero Identificación Tributaria') ? 'selected' : '' ?>>Numero Identificación Tributaria</option>

                </select>
                <label name="tipoDocumento-error" class="text-red-500 hidden">Corrije Este Campo </label>
            </div>

            <div class="flex-1">
                <label for="">Numero Documento</label>
                <input onblur="cambioDoc()" name="numeroDocumento" id="numeroDocumento" type="text" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu Numero de Documento" value="<?php if (isset($_POST['numeroDocumento'])) echo $_POST['numeroDocumento']; ?>">
                <label name="numeroDocumento-error" class="text-red-500 hidden">Corrije Este Campo </label>
            </div>

            <input type="hidden" name="Buscar" value="Buscar">

            <div class="mt-6 h-full space-x-2">
                <button class="cursor-pointer ml-3 w-10 h-10 bg-lime-50 border border-lime-500 p-2 rounded " type="submit"><svg class="w-full" viewBox="0 0 24 24" data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color"><path style="fill:none;stroke:#2ca9bc;stroke-linecap:round;stroke-linejoin:round;stroke-width:2" d="m21 21-6-6"/><circle cx="10" cy="10" r="7" style="fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:2"/></svg></button>
            </div>




        </form>

        <span id="mensajeFeligres" class="hidden text-gray-500">Feligres No Encontrado Completa los Datos</span>

        <form method="POST" onsubmit="verifyDatos(event)" id="formDatos">

            <div class="flex flex-col space-x-2">

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
                        <input type="text" name="primerApellido" id="primerApellido" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu Primer Apellido" value="<?php if (isset($_POST['primerApellido'])) echo $_POST['primerApellido']; ?>">
                        <label name="primerApellido-error" class="text-red-500 hidden">Corrije Este Campo </label>
                    </div>

                    <div>
                        <label for="">Segundo Apellido</label>
                        <input type="text" name="segundoApellido" id="segundoApellido" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu Primer Nombre" value="<?php if (isset($_POST['segundoApellido'])) echo $_POST['segundoApellido']; ?>">
                        <label name="segundoApellido-error" class="text-red-500 hidden">Corrije Este Campo </label>
                    </div>

                </div>
            </div>

            <div class="flex space-x-2">
                <div>
                    <label for="">Fecha de Nacimiento</label>
                    <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="border border-gray-300 rounded p-2 w-full" value="<?php if (isset($_POST['fechaNacimiento'])) echo $_POST['fechaNacimiento']; ?>">
                    <label name="fechaNacimiento-error" class="text-red-500 hidden">Corrije Este Campo </label>
                </div>

                <div>
                    <label for="">Telefono</label>
                    <input type="text" name="telefono" id="telefono" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu Telefono" value="<?php if (isset($_POST['telefono'])) echo $_POST['telefono']; ?>">
                    <label name="telefono-error" class="text-red-500 hidden">Corrije Este Campo </label>
                </div>
            </div>
            <div>
                <label for="">Direccion</label>
                <input type="text" name="direccion" id="direccion" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu Direccion" value="<?php if (isset($_POST['direccion'])) echo $_POST['direccion']; ?>">
                <label name="direccion-error" class="text-red-500 hidden">Corrije Este Campo </label>
            </div>






            <input type="hidden" name="tipoDocumento" id="hiddenTipo" value="<?php if (isset($_POST['tipoDocumento'])) echo $_POST['tipoDocumento']; ?>">
            <input type="hidden" name="numeroDocumento" id="hiddenDoc" value="<?php if (isset($_POST['numeroDocumento'])) echo $_POST['numeroDocumento']; ?>">
            <input type="hidden" name="Añadir" value="Añadir">

            <button class="cursor-pointer bg-[#E3FFCD] p-4 rounded w-[10svw] self-center border border-emerald-500 hover:bg-emerald-500 hover:text-white hover:border-emerald-700 mt-4" type="submit">Entrar</button>

        </form>

    </div>


</div>

<script>
    function verifyDatos(event) {

        event.preventDefault();

        const primerNombre = document.querySelector('input[name="primerNombre"]');
        if (primerNombre.value === "") {
            primerNombre.classList.add("border-red-500");
            const primerNombreError = document.querySelector('label[name="primerNombre-error"]');
            primerNombreError.classList.remove("hidden");
            primerNombreError.textContent = "El primer nombre es obligatorio.";
            return false;
        } else {
            primerNombre.classList.remove("border-red-500");
            const primerNombreError = document.querySelector('label[name="primerNombre-error"]');
            primerNombreError.classList.add("hidden");
        }


        const primerApellido = document.querySelector('input[name="primerApellido"]');
        if (primerApellido.value === "") {
            primerApellido.classList.add("border-red-500");
            const primerApellidoError = document.querySelector('label[name="primerApellido-error"]');
            primerApellidoError.classList.remove("hidden");
            primerApellidoError.textContent = "El primer apellido es obligatorio.";
            return false;
        } else {
            primerApellido.classList.remove("border-red-500");
            const primerApellidoError = document.querySelector('label[name="primerApellido-error"]');
            primerApellidoError.classList.add("hidden");
        }

        const fechaNacimiento = document.querySelector('input[name="fechaNacimiento"]');
        if (fechaNacimiento.value === "") {
            fechaNacimiento.classList.add("border-red-500");
            const fechaNacimientoError = document.querySelector('label[name="fechaNacimiento-error"]');
            fechaNacimientoError.classList.remove("hidden");
            fechaNacimientoError.textContent = "La fecha de nacimiento es obligatoria.";
            return false;
        } else {
            fechaNacimiento.classList.remove("border-red-500");
            const fechaNacimientoError = document.querySelector('label[name="fechaNacimiento-error"]');
            fechaNacimientoError.classList.add("hidden");
        }






        verifyFeligres(event)

        // Similar validation for other fields...

    }










    function verifyFeligres(event) {

        event.preventDefault();

        const tipoDocumento = document.querySelector('select[name="tipoDocumento"]');

        if (tipoDocumento.value === "") {
            tipoDocumento.classList.add("border-red-500");
            const tipoDocumentoError = document.querySelector('label[name="tipoDocumento-error"]');
            tipoDocumentoError.classList.remove("hidden");
            tipoDocumentoError.textContent = "El tipo de documento es obligatorio.";
            return false;
        } else {
            tipoDocumento.classList.remove("border-red-500");
            const tipoDocumentoError = document.querySelector('label[name="tipoDocumento-error"]');
            tipoDocumentoError.classList.add("hidden");
        }

        const numeroDocumento = document.querySelector('input[name="numeroDocumento"]');
        if (numeroDocumento.value === "") {
            numeroDocumento.classList.add("border-red-500");
            const numeroDocumentoError = document.querySelector('label[name="numeroDocumento-error"]');
            numeroDocumentoError.classList.remove("hidden");
            numeroDocumentoError.textContent = "El número de documento es obligatorio.";
            return false;
        } else {
            numeroDocumento.classList.remove("border-red-500");
            const numeroDocumentoError = document.querySelector('label[name="numeroDocumento-error"]');
            numeroDocumentoError.classList.add("hidden");
        }

        event.target.submit();

    }







    function cambioTipo() {
        const tipoDocumento = document.getElementById('tipoDocumento');
        const hiddenTipo = document.getElementById('hiddenTipo');

        if (tipoDocumento && hiddenTipo) {
            hiddenTipo.value = tipoDocumento.value;
        }
    }


    function cambioDoc() {
        const numeroDocumento = document.getElementById('numeroDocumento');
        const hiddenDoc = document.getElementById('hiddenDoc');
        if (numeroDocumento && hiddenDoc) {
            hiddenDoc.value = numeroDocumento.value;
        }
    }


    function mostarBaner() {
        const Datos = document.getElementById('Datos');

        if (Datos) {
            Datos.classList.remove('hidden');
        }
    }

    // function verifyFeligres() {
    //     const form = document.querySelector('form'); // o usa un ID si hay varios formularios
    //     if (form) {
    //         form.submit();
    //     } else {
    //         console.warn("Formulario no encontrado");
    //     }
    // }

    function errorFeligres() {


        const mensajeFeligres = document.getElementById('mensajeFeligres');
        if (mensajeFeligres) {
            mensajeFeligres.classList.remove('hidden');
        } else {
            console.warn("Mensaje de feligres no encontrado");
        }


        // formDatos = document.getElementById('formDatos');



        // if (formDatos) {
        //     formDatos.classList.remove('hidden');
        // } else {
        //     console.warn("Formulario no encontrado");
        // }


    }


    function saludar() {
        alert('sasas');
    }
</script>

