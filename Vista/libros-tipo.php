<!-- Contenedor principal con ancho máximo de 7xl, padding responsive y ancho completo -->
<main class="max-w-7xl mx-auto p-4 md:p-8 w-full">

    <!-- SECCIÓN DE SACRAMENTOS: Tarjeta principal que contiene el grid de sacramentos -->
    <!-- Bordes redondeados grandes, fondo blanco, sombra, borde delgado y padding responsive -->
    <section class="rounded-3xl bg-white shadow-lg border border-stone-200 p-6 md:p-8">
        
        <!-- Grid responsive: 1 columna en móvil, 2 en tablet (md), 4 en desktop (lg) -->
        <!-- gap-6 genera espaciado de 1.5rem (24px) entre elementos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- ========== TARJETA 1: BAUTIZOS ========== -->
            <!-- Enlace dinámico con PHP que construye la URL y agrega parámetro tipo=1 -->
            <!-- <?= url('libros/seleccionar-tipo') ?> es sintaxis PHP corta para echo -->
            <a href="<?= url('libros/seleccionar-tipo') ?>?tipo=1" 
               class="group flex flex-col items-center p-6 rounded-2xl border border-stone-200 bg-[#faf6f4] transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:border-[#8D7B68] hover:bg-white relative overflow-hidden">
                <!-- 
                Clases explicadas:
                - group: Permite aplicar estilos hover a elementos hijos usando group-hover:
                - flex flex-col: Flexbox con dirección vertical (columna)
                - items-center: Centra elementos horizontalmente en el eje transversal
                - p-6: Padding de 1.5rem (24px) en todos los lados
                - rounded-2xl: Bordes muy redondeados (16px)
                - border border-stone-200: Borde sólido de 1px en color gris piedra claro
                - bg-[#faf6f4]: Color de fondo personalizado (beige muy claro)
                - transition-all duration-300: Transición suave de 300ms para TODAS las propiedades
                - hover:scale-[1.03]: Al pasar mouse, escala elemento al 103% (efecto zoom sutil)
                - hover:shadow-xl: Al hover, aplica sombra extra grande
                - hover:border-[#8D7B68]: Al hover, cambia color del borde a café
                - hover:bg-white: Al hover, fondo se vuelve blanco puro
                - relative: Posicionamiento relativo (para posicionar hijos absolutos)
                - overflow-hidden: Oculta cualquier contenido que se salga del contenedor
                -->
                
                <!-- Icono decorativo de fondo (marca de agua) -->
                <!-- Posicionado en esquina superior derecha, muy transparente -->
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <!-- 
                    - absolute: Posicionamiento absoluto respecto al padre relativo
                    - top-0 right-0: Pegado a esquina superior derecha
                    - p-4: Padding de 1rem (16px)
                    - opacity-10: Opacidad del 10% (casi invisible)
                    - group-hover:opacity-20: Al hacer hover en el padre .group, opacidad sube a 20%
                    - transition-opacity: Transición suave solo para la opacidad
                    -->
                    
                    <!-- SVG: Círculo simple como marca de agua decorativa -->
                    <svg class="w-32 h-32 text-[#8D7B68]" fill="currentColor" viewBox="0 0 24 24">
                        <!-- Path que dibuja un círculo con borde (anillo) -->
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                    </svg>
                </div>
                
                <!-- Contenedor del icono principal del sacramento -->
                <div class="h-32 w-32 mb-6 flex items-center justify-center relative z-10">
                    <!-- 
                    - h-32 w-32: Altura y ancho de 8rem (128px) - contenedor cuadrado
                    - mb-6: Margen inferior de 1.5rem (24px)
                    - flex items-center justify-center: Centra contenido vertical y horizontalmente
                    - relative z-10: Posición relativa con z-index 10 (aparece sobre la marca de agua)
                    -->
                    
                    <!-- SVG del icono de Bautizos: Representa una persona siendo bautizada -->
                    <svg class="w-full h-full drop-shadow-md text-[#8D7B68]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                        <!-- 
                        - w-full h-full: Ocupa 100% del contenedor padre (128px)
                        - drop-shadow-md: Sombra paralela mediana para dar profundidad
                        - text-[#8D7B68]: Color café oscuro (el fill="currentColor" toma este color)
                        - viewBox="0 0 512 512": Sistema de coordenadas SVG de 512x512
                        -->
                        
                        <!-- Path simplificado que dibuja una figura humana con agua/iglesia -->
                        <!-- Este path dibuja la silueta de una persona con elementos arquitectónicos -->
                        <path d="M256 0c-44 0-80 36-80 80 0 16 5 31 13 44l-49 25v171h232V149l-49-25c9-13 13-28 13-44 0-44-36-80-80-80zm0 32c26.5 0 48 21.5 48 48s-21.5 48-48 48-48-21.5-48-48 21.5-48 48-48zM96 288v224h64v-96h128v96h64V288H96z"/>
                    </svg>
                </div>
                
                <!-- Título del sacramento -->
                <h3 class="text-2xl font-bold text-[#5A4D41] mb-2 group-hover:text-[#8D7B68] transition-colors relative z-10">Bautizos</h3>
                <!-- 
                - text-2xl: Tamaño de fuente 1.5rem (24px)
                - font-bold: Peso de fuente negrita (700)
                - text-[#5A4D41]: Color café oscuro
                - mb-2: Margen inferior de 0.5rem (8px)
                - group-hover:text-[#8D7B68]: Al hover del padre, cambia a café más claro
                - transition-colors: Transición suave solo para colores
                - relative z-10: Se mantiene sobre la marca de agua
                -->
                
                <!-- Descripción corta del sacramento -->
                <span class="text-stone-500 font-medium text-sm text-center relative z-10 group-hover:text-stone-600">Registro de nuevos miembros</span>
                <!-- 
                - text-stone-500: Color gris medio de la paleta "stone"
                - font-medium: Peso de fuente medio (500)
                - text-sm: Tamaño pequeño 0.875rem (14px)
                - text-center: Texto centrado
                - group-hover:text-stone-600: Al hover, color más oscuro
                -->
            </a>

            <!-- ========== TARJETA 2: CONFIRMACIONES ========== -->
            <!-- URL con parámetro tipo=2 para identificar el sacramento de confirmación -->
            <a href="<?= url('libros/seleccionar-tipo') ?>?tipo=2" 
               class="group flex flex-col items-center p-6 rounded-2xl border border-stone-200 bg-[#faf6f4] transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:border-[#8D7B68] hover:bg-white relative overflow-hidden">
                <!-- Misma estructura que Bautizos, clases idénticas -->
                
                <!-- Contenedor del icono (sin marca de agua en esta tarjeta) -->
                <div class="h-32 w-32 mb-6 flex items-center justify-center relative z-10">
                    
                    <!-- SVG del icono de Confirmaciones: Cruz dentro de un círculo -->
                    <svg class="w-full h-full drop-shadow-md text-[#8D7B68]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                        <!-- Path que dibuja un círculo con una cruz en el centro -->
                        <!-- Simboliza la confirmación del Espíritu Santo -->
                        <path d="M256 32a224 224 0 1 0 0 448 224 224 0 0 0 0-448zm0 50a174 174 0 1 1 0 348 174 174 0 0 1 0-348zm-20 70v100h-90v40h90v150h40V292h90v-40h-90V152h-40z"/>
                        <!-- 
                        El path dibuja:
                        1. Círculo exterior (a224 224 = radio de 224 unidades)
                        2. Círculo interior más pequeño (a174 174)
                        3. Cruz centrada con brazos horizontales y verticales
                        -->
                    </svg>
                </div>
                
                <h3 class="text-2xl font-bold text-[#5A4D41] mb-2 group-hover:text-[#8D7B68] transition-colors relative z-10">Confirmaciones</h3>
                <span class="text-stone-500 font-medium text-sm text-center relative z-10 group-hover:text-stone-600">Ratificación de la fe</span>
            </a>

            <!-- ========== TARJETA 3: DEFUNCIONES ========== -->
            <!-- URL con parámetro tipo=3 para registro de fallecimientos -->
            <a href="<?= url('libros/seleccionar-tipo') ?>?tipo=3" 
               class="group flex flex-col items-center p-6 rounded-2xl border border-stone-200 bg-[#faf6f4] transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:border-[#8D7B68] hover:bg-white relative overflow-hidden">
                
                <div class="h-32 w-32 mb-6 flex items-center justify-center relative z-10">
                    
                    <!-- SVG del icono de Defunciones: Forma de lápida/tumba -->
                    <svg class="w-full h-full drop-shadow-md text-[#8D7B68]" viewBox="0 0 24 24" fill="currentColor">
                        <!-- Primera capa: Forma de fondo con opacidad reducida -->
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6zm0-8H9V7h6v2z" opacity=".3"/>
                        <!-- opacity=".3": 30% de opacidad para crear profundidad -->
                        
                        <!-- Segunda capa: Forma principal de lápida con detalles -->
                        <path d="M19 20H5V9a7 7 0 0 1 14 0v11zm-9-2h4v-2h-4v2zm0-4h4v-2h-4v2z"/>
                        <!-- Dibuja rectángulo con parte superior redondeada (arco de 7 unidades de radio) -->
                    </svg>
                </div>
                
                <h3 class="text-2xl font-bold text-[#5A4D41] mb-2 group-hover:text-[#8D7B68] transition-colors relative z-10">Defunciones</h3>
                <span class="text-stone-500 font-medium text-sm text-center relative z-10 group-hover:text-stone-600">Registro de fallecimientos</span>
            </a>

            <!-- ========== TARJETA 4: MATRIMONIOS ========== -->
            <!-- URL con parámetro tipo=4 para registro de matrimonios -->
            <a href="<?= url('libros/seleccionar-tipo') ?>?tipo=4" 
               class="group flex flex-col items-center p-6 rounded-2xl border border-stone-200 bg-[#faf6f4] transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:border-[#8D7B68] hover:bg-white relative overflow-hidden">
                
                <div class="h-32 w-32 mb-6 flex items-center justify-center relative z-10">
                    
                    <!-- SVG del icono de Matrimonios: Pareja/anillos de boda -->
                    <svg class="w-full h-full drop-shadow-md text-[#8D7B68]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <!-- Primera capa: Estructura principal representando dos personas -->
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6zm-1-8c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-4 0c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zM7 11c0-1.1.9-2 2-2s2 .9 2 2v2h2v-2c0-1.1.9-2 2-2s2 .9 2 2v2h2v7H7v-7z"/>
                        <!-- Dibuja figuras circulares (cabezas) y rectángulos (cuerpos) representando pareja -->
                        
                        <!-- Segunda capa: Arco superior simbolizando unión/bendición -->
                        <path d="M16 9h-2c0-1.66-1.34-3-3-3S8 7.34 8 9H6c0-2.76 2.24-5 5-5s5 2.24 5 5z"/>
                        <!-- Arco semicircular que une las dos figuras -->
                    </svg>
                </div>
                
                <h3 class="text-2xl font-bold text-[#5A4D41] mb-2 group-hover:text-[#8D7B68] transition-colors relative z-10">Matrimonios</h3>
                <span class="text-stone-500 font-medium text-sm text-center relative z-10 group-hover:text-stone-600">Uniones sagradas</span>
            </a>

        </div>
        <!-- Fin del grid de sacramentos -->
    </section>
    <!-- Fin de la sección de sacramentos -->

</main>
<!-- Fin del contenedor principal -->