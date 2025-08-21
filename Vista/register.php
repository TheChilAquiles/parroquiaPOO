<div class="flex flex-col w-full h-full justify-center items-center">
    <h1 class="text-3xl my-2 font-bold">Registrarse</h1>
    <div class="p-10 rounded-md bg-white">


        <form action="" method="post" onsubmit="return verifyForm(event)" class="w-[20svw] flex flex-col ">
            <!-- <label for="">Nombre</label>
            <input type="text" class="border border-gray-300 rounded p-2 mb-4 w-full" placeholder="Ingresa Tu Primer Nombre" requited> -->
            <label for="">Correo electrónico</label>
            <input name="email" type="email" class="border border-gray-300 rounded p-2 w-full" placeholder="Ingresa Tu  Correo electrónico" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" >
            <label name="email-error" class="text-red-500 hidden">Corrije Este Campo </label>
            <label class="mt-2" for="">Contraseña</label>
            <input name="password" type="password" class="border border-gray-300 rounded p-2  w-full" placeholder="Ingresa Tu  Contraseña" Value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" >
            <label name="password-error" class="text-red-500 hidden">Corrije Este Campo </label>
            <label class="mt-2" for="">Confirmar contraseña</label>
            <input name="password-confirm" type="password" class="border border-gray-300 rounded p-2  w-full" placeholder="Confirma Tu contraseña"  Value="<?php if (isset($_POST['password-confirm'])) echo $_POST['password-confirm']; ?>" >
            <label name="confirm-error" class="text-red-500 hidden">Corrije Este Campo </label>
            <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('registro') ?>">
            <button class="cursor-pointer bg-[#E3FFCD] p-4 rounded w-[10svw] self-center border border-emerald-500 hover:bg-emerald-500 hover:text-white hover:border-emerald-700 mt-4" type="submit">Registar</button>
        </form>

    </div>
</div>

<script>
    function verifyForm(event) {

        event.preventDefault();

        const email = document.querySelector('input[name="email"]');
        // Validar el campo de correo electrónico
        // Verificar si el campo está vacío
        if (email.value === "") {
            email.classList.add("border-red-500");
            const emailError = document.querySelector('label[name="email-error"]');
            emailError.classList.remove("hidden");
            emailError.textContent = "El correo electrónico es obligatorio.";
            return false;
        } else {
            email.classList.remove("border-red-500");
            const emailError = document.querySelector('label[name="email-error"]');
            emailError.classList.add("hidden");
            // Verificar si el correo electrónico contiene '@' y '.'
            if (!email.value.includes("@") || !email.value.includes(".")) {

                email.classList.add("border-red-500");
                emailError.classList.remove("hidden");
                emailError.textContent = "El correo electrónico debe contener '@' y '.'";

                return false;
            } else {
                email.classList.remove("border-red-500");
                emailError.classList.add("hidden");

                if (email.value.includes(".")) {
                    const parts = email.value.split(".");
                    if (parts[parts.length - 1].length < 2) {
                        email.classList.add("border-red-500");
                        emailError.classList.remove("hidden");
                        emailError.textContent = "El dominio del correo electrónico debe tener al menos dos caracteres después del punto.";
                        return false;
                    }
                }
            }






        }
        // Validar el campo de contraseña
        const password = document.querySelector('input[name="password"]');

        if (password.value === "") {
            password.classList.add("border-red-500");
            const passwordError = document.querySelector('label[name="password-error"]');
            passwordError.classList.remove("hidden");
            passwordError.textContent = "La contraseña es obligatoria.";
            return false;
        } else {
            password.classList.remove("border-red-500");
            const passwordError = document.querySelector('label[name="password-error"]');
            passwordError.classList.add("hidden");
        }

        const passwordConfirm = document.querySelector('input[name="password-confirm"]');

        // Validar el campo de confirmación de contraseña
        if (passwordConfirm.value === "") {
            passwordConfirm.classList.add("border-red-500");
            const confirmError = document.querySelector('label[name="confirm-error"]');
            confirmError.classList.remove("hidden");
            confirmError.textContent = "La confirmación de contraseña es obligatoria.";
            return false;
        } else {
            passwordConfirm.classList.remove("border-red-500");
            const confirmError = document.querySelector('label[name="confirm-error"]');
            confirmError.classList.add("hidden");
        }

        // Verificar si las contraseñas coinciden
        if (password.value !== passwordConfirm.value) {
            password.classList.add("border-red-500");
            passwordConfirm.classList.add("border-red-500");
            const confirmError = document.querySelector('label[name="confirm-error"]');
            confirmError.classList.remove("hidden");
            confirmError.textContent = "Las contraseñas no coinciden.";
            return false;
        } else {
            password.classList.remove("border-red-500");
            passwordConfirm.classList.remove("border-red-500");
            const confirmError = document.querySelector('label[name="confirm-error"]');
            confirmError.classList.add("hidden");
        }
        // Si todos los campos son válidos, enviar el formulario
        event.target.submit();

    }

    function existEmail(){
        const email = document.querySelector('input[name="email"]');
        email.classList.add("border-red-500");
        const emailError = document.querySelector('label[name="email-error"]');
        emailError.classList.remove("hidden");
        emailError.textContent = "El correo electrónico ya existe.";
    }
</script>