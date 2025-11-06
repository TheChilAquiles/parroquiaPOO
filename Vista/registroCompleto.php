<div class="flex flex-col flex-1 w-full h-full justify-center items-center">
    <h1 class="text-3xl my-2 font-bold">Registrarse</h1>
    <div class="p-10 rounded-md h-80 border border-emerald-500 bg-emerald-50">

        <div class="w-[20svw] h-full flex flex-col items-between justify-center"> 

            <h2 class="text-2xl font-bold text-green-500">Registro Exitoso!</h2>
            <p class="mt-2 text-xl "> <?= $_POST['email'] ?> ,Ha completado Su Registro , Ya puedes Navegar , No olvides Confirmar Tu correo electronico</p>

            <form  method="POST" class="flex flex-col items-center justify-center">
                <input type="hidden" name="menu-item" value="Login" />
                <button class="cursor-pointer bg-[#E3FFCD] p-4 rounded w-[10svw] self-center border border-emerald-500 hover:bg-emerald-500 hover:text-white hover:border-emerald-700 mt-4" type="submit">Iniciar Sesión</button>
            </form>
         

        </div>


    </div>
</div>