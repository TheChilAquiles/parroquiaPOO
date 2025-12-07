<main class="max-w-7xl mx-auto p-4 md:p-8 w-full">

 
    <!-- Sacraments Grid -->
    <section class="rounded-3xl bg-white shadow-lg border border-stone-200 p-6 md:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Bautizos -->
            <a href="<?= url('libros/seleccionar-tipo') ?>?tipo=1" 
               class="group flex flex-col items-center p-6 rounded-2xl border border-stone-200 bg-[#faf6f4] transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:border-[#8D7B68] hover:bg-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                     <svg class="w-32 h-32 text-[#8D7B68]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                </div>
                
                <div class="h-32 w-32 mb-6 flex items-center justify-center relative z-10">
                    <svg class="w-full h-full drop-shadow-md text-[#8D7B68]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                        <!-- Simplified path reusing the original Bautizos icon logic but applied cleaner -->
                         <path d="M256 0c-44 0-80 36-80 80 0 16 5 31 13 44l-49 25v171h232V149l-49-25c9-13 13-28 13-44 0-44-36-80-80-80zm0 32c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zM96 288v224h64v-96h128v96h64V288H96z"/>
                    </svg>
                </div>
                
                <h3 class="text-2xl font-bold text-[#5A4D41] mb-2 group-hover:text-[#8D7B68] transition-colors relative z-10">Bautizos</h3>
                <span class="text-stone-500 font-medium text-sm text-center relative z-10 group-hover:text-stone-600">Registro de nuevos miembros</span>
            </a>

            <!-- Confirmaciones -->
            <a href="<?= url('libros/seleccionar-tipo') ?>?tipo=2" 
               class="group flex flex-col items-center p-6 rounded-2xl border border-stone-200 bg-[#faf6f4] transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:border-[#8D7B68] hover:bg-white relative overflow-hidden">
                <div class="h-32 w-32 mb-6 flex items-center justify-center relative z-10">
                    <svg class="w-full h-full drop-shadow-md text-[#8D7B68]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                         <path d="M256 32a224 224 0 1 0 0 448 224 224 0 0 0 0-448zm0 50a174 174 0 1 1 0 348 174 174 0 0 1 0-348zm-20 70v100h-90v40h90v150h40V292h90v-40h-90V152h-40z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-[#5A4D41] mb-2 group-hover:text-[#8D7B68] transition-colors relative z-10">Confirmaciones</h3>
                <span class="text-stone-500 font-medium text-sm text-center relative z-10 group-hover:text-stone-600">Ratificación de la fe</span>
            </a>

            <!-- Defunciones -->
            <a href="<?= url('libros/seleccionar-tipo') ?>?tipo=3" 
               class="group flex flex-col items-center p-6 rounded-2xl border border-stone-200 bg-[#faf6f4] transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:border-[#8D7B68] hover:bg-white relative overflow-hidden">
                <div class="h-32 w-32 mb-6 flex items-center justify-center relative z-10">
                   <svg class="w-full h-full drop-shadow-md text-[#8D7B68]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6zm0-8H9V7h6v2z" opacity=".3"/> <!-- Tombstone shape like -->
                        <path d="M19 20H5V9a7 7 0 0 1 14 0v11zm-9-2h4v-2h-4v2zm0-4h4v-2h-4v2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-[#5A4D41] mb-2 group-hover:text-[#8D7B68] transition-colors relative z-10">Defunciones</h3>
                <span class="text-stone-500 font-medium text-sm text-center relative z-10 group-hover:text-stone-600">Registro de fallecimientos</span>
            </a>

            <!-- Matrimonios -->
            <a href="<?= url('libros/seleccionar-tipo') ?>?tipo=4" 
               class="group flex flex-col items-center p-6 rounded-2xl border border-stone-200 bg-[#faf6f4] transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:border-[#8D7B68] hover:bg-white relative overflow-hidden">
                <div class="h-32 w-32 mb-6 flex items-center justify-center relative z-10">
                    <svg class="w-full h-full drop-shadow-md text-[#8D7B68]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                         <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6zm-1-8c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-4 0c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zM7 11c0-1.1.9-2 2-2s2 .9 2 2v2h2v-2c0-1.1.9-2 2-2s2 .9 2 2v2h2v7H7v-7z"/>
                         <!-- Wedding rings equivalent or couple -->
                         <path d="M16 9h-2c0-1.66-1.34-3-3-3S8 7.34 8 9H6c0-2.76 2.24-5 5-5s5 2.24 5 5z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-[#5A4D41] mb-2 group-hover:text-[#8D7B68] transition-colors relative z-10">Matrimonios</h3>
                <span class="text-stone-500 font-medium text-sm text-center relative z-10 group-hover:text-stone-600">Uniones sagradas</span>
            </a>

        </div>
    </section>

</main>