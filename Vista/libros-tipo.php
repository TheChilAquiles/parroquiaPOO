<div class="bg-red-500 flex flex-1 justify-center items-center p-4">


    <div class="grid grid-cols-5 bg-white w-full h-full p-4 rounded-md gap-2">


        <form class="" method="POST">

        <input type="hidden" name="<?= md5('action') ?>" value="<?= md5('DefinirLibro') ?>">


        <svg width="800px" height="800px" viewBox="0 0 24 24" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"><title/><path d="M19,20H5V9a7,7,0,0,1,7-7h0a7,7,0,0,1,7,7Z" style="fill:#b3c1c9"/><path d="M5,18H19a2,2,0,0,1,2,2v2a0,0,0,0,1,0,0H3a0,0,0,0,1,0,0V20A2,2,0,0,1,5,18Z" style="fill:#738394"/><rect height="2" style="fill:#fff" width="8" x="8" y="8"/><rect height="2" style="fill:#fff" width="8" x="8" y="12"/></svg>




            <button type="submit" class="cursor-pointer relative flex justify-between items-center bg-red-500 p-2 rounded-md">
                <svg class="w-60" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 8v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z" fill="#16a085" />
                    <path d="M3 7v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V7z" fill="#ecf0f1" />
                    <path d="M3 6v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V6z" fill="#bdc3c7" />
                    <path d="M3 5v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V5z" fill="#ecf0f1" />
                    <path d="M5 1a2 2 0 0 0-2 2v18a2 2 0 0 0 2 2h2v-1H5.5a1.5 1.5 0 0 1 0-3H19a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#16a085" />
                    <path d="M8 1v18h11a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#1abc9c" />
                </svg>
                <span class=" absolute inset-x-0 top-6 text-white font-bold text-2xl text-center ">Parroquia</span>
                <span class=" absolute inset-x-0 top-17 text-white font-bold text-8xl text-center ">1</span>
            </button>


        </form>




        <div class="relative flex justify-between items-center bg-red-500 p-2 rounded-md">
            <svg class="w-60" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 8v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8z" fill="#c1c1c1" />
                <path d="M3 7v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V7z" fill="#ecf0f1" />
                <path d="M3 6v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V6z" fill="#bdc3c7" />
                <path d="M3 5v13c0 1.1.895 2 2 2h14c1.105 0 2-.9 2-2V5z" fill="#ecf0f1" />
                <path d="M5 1a2 2 0 0 0-2 2v18a2 2 0 0 0 2 2h2v-1H5.5a1.5 1.5 0 0 1 0-3H19a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#606060" />
                <path d="M8 1v18h11a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2z" fill="#c1c1c1" />
            </svg>

            <span class=" absolute inset-x-0 top-6 text-white font-bold text-2xl text-center ">Añadir</span>
            <span class=" absolute inset-x-0 top-17 text-white font-bold text-8xl text-center ">+</span>

        </div>




    </div>






</div>