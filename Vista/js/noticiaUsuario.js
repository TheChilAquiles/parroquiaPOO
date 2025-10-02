/**
 * @file noticiaUsuario.js
 * @version 1.0
 * @brief Script de interactividad para la vista pública de noticias
 * 
 * Este archivo maneja la funcionalidad del lado del cliente para usuarios finales,
 * incluyendo animaciones de scroll, navegación y efectos visuales.
 * 
 * @dependency noticiaUsuario.php - Vista que consume este script
 * @features
 * - Animaciones lazy-load al hacer scroll (Intersection Observer API)
 * - Sistema de navegación para lectura completa de noticias
 * - Delegación de eventos para rendimiento optimizado
 */

// ============================================================================
// PUNTO DE ENTRADA PRINCIPAL
// Garantiza que el DOM esté completamente cargado antes de ejecutar scripts
// ============================================================================
document.addEventListener('DOMContentLoaded', function() {

    // ========================================================================
    // SISTEMA DE ANIMACIONES LAZY-LOAD
    // Implementa animaciones de entrada que se activan solo cuando el usuario
    // hace scroll y los elementos son visibles en viewport
    // ========================================================================
    /**
     * Inicializa el sistema de animaciones basado en visibilidad.
     * Utiliza Intersection Observer API para detectar cuándo un elemento
     * entra en el viewport y activa su animación de entrada.
     * 
     * @performance Las animaciones están pausadas por defecto y solo se activan
     *              cuando son necesarias, reduciendo el uso de recursos.
     */
    function initializeAnimations() {
        
        // Configuración del Intersection Observer
        const observerOptions = {
            threshold: 0.1,              // Se activa cuando el 10% del elemento es visible
            rootMargin: '0px 0px -50px 0px'  // Margen inferior para activación anticipada
        };

        // Creamos el observador con callback para manejar intersecciones
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                
                // Verificamos si el elemento está entrando en el viewport
                if (entry.isIntersecting) {
                    // Activamos la animación CSS cambiando el estado de reproducción
                    entry.target.style.animationPlayState = 'running';
                    
                    // Dejamos de observar para evitar re-animaciones innecesarias
                    // y liberar recursos del observador
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Seleccionamos todos los artículos que tienen animación CSS definida
        document.querySelectorAll('article[style*="animation"]').forEach(el => {
            // Pausamos la animación inicialmente para control manual
            el.style.animationPlayState = 'paused';
            
            // Registramos el elemento en el observador
            observer.observe(el);
        });
    }

    // ========================================================================
    // SISTEMA DE NAVEGACIÓN DE NOTICIAS
    // Maneja los clics en botones "Leer más" y redirige a la vista detallada
    // ========================================================================
    /**
     * Inicializa los botones de "Leer más" usando delegación de eventos.
     * 
     * @pattern Event Delegation - Un solo listener en body maneja todos los clics
     *          en lugar de múltiples listeners por botón, mejorando el rendimiento.
     * 
     * @note Requiere que cada article tenga un atributo data-id con el ID de la noticia
     * @note Requiere un archivo noticia.php que maneje la vista detallada
     */
    function initializeReadMoreButtons() {
        
        // Delegación de eventos: escuchamos clics en body en lugar de cada botón
        document.body.addEventListener('click', function(e) {
            
            // Buscamos si el clic fue en un botón "Leer más" o dentro de uno
            const readMoreButton = e.target.closest('.leer-mas-btn');
            
            // Si se encontró un botón válido, procesamos el clic
            if (readMoreButton) {
                
                // Navegamos hacia arriba en el DOM para encontrar el article padre
                const article = readMoreButton.closest('article');
                
                // Extraemos el ID de la noticia desde el data attribute
                const noticiaId = article.dataset.id;

                // Validamos que exista un ID antes de navegar
                if (noticiaId) {
                    // Redireccionamos a la página de detalle con el ID como parámetro
                    // IMPORTANTE: Debe existir un endpoint noticia.php que procese este ID
                    window.location.href = `noticia.php?id=${noticiaId}`;
                } else {
                    // Registramos error en consola para debugging
                    console.error('No se encontró el data-id en el artículo.');
                }
            }
        });
    }

    // ========================================================================
    // INICIALIZACIÓN DE MÓDULOS
    // Ejecuta todas las funciones de setup en el orden correcto
    // ========================================================================
    initializeAnimations();
    initializeReadMoreButtons();

});