/**
 * JavaScript para la vista pública de noticias (noticiaUsuario.php)
 *
 * Se encarga de las mejoras visuales y la interactividad básica
 * para los usuarios que no son administradores.
 */

document.addEventListener('DOMContentLoaded', function() {

    // 1. INICIALIZAR ANIMACIONES DE ENTRADA AL HACER SCROLL
    // Esta función hace que los elementos con la animación 'fadeInUp'
    // se activen solo cuando el usuario los ve en la pantalla.
    function initializeAnimations() {
        // Opciones para el observador: se activa cuando el 10% del elemento es visible.
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        // Creamos el observador.
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Si el elemento está en la pantalla...
                if (entry.isIntersecting) {
                    // ...activamos la animación.
                    entry.target.style.animationPlayState = 'running';
                    // Dejamos de observar este elemento para no repetir la animación.
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Le decimos al observador que vigile todas las tarjetas de noticias.
        document.querySelectorAll('article[style*="animation"]').forEach(el => {
            // Pausamos la animación por defecto hasta que sea visible.
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    }

    // 2. AÑADIR FUNCIONALIDAD AL BOTÓN "LEER MÁS"
    // Usamos delegación de eventos para que funcione con todas las tarjetas.
    function initializeReadMoreButtons() {
        document.body.addEventListener('click', function(e) {
            // Verificamos si el clic fue en un botón "Leer más".
            const readMoreButton = e.target.closest('.leer-mas-btn');
            
            if (readMoreButton) {
                // Obtenemos el ID de la noticia del artículo padre.
                const article = readMoreButton.closest('article');
                const noticiaId = article.dataset.id;

                if (noticiaId) {
                    // NOTA: Esto redirigirá a una nueva página.
                    // Necesitarás crear un archivo 'noticia.php' que reciba el ID
                    // y muestre el contenido completo de esa noticia.
                    window.location.href = `noticia.php?id=${noticiaId}`;
                } else {
                    console.error('No se encontró el data-id en el artículo.');
                }
            }
        });
    }

    // Ejecutar las funciones de inicialización.
    initializeAnimations();
    initializeReadMoreButtons();

});