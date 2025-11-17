/**
 * reportes.js - Módulo de Reportes
 * Gestiona el filtrado y búsqueda en la vista de reportes
 */

(function() {
    'use strict';

    // Constantes
    const ESTADOS_PAGADOS = ['pagado', 'completo', 'paid', 'complete'];
    const ESTADOS_PENDIENTES = ['pendiente', 'pending'];

    /**
     * Obtiene todas las filas de la tabla
     * @returns {Array} Array de elementos TR
     */
    function obtenerFilas() {
        return Array.from(document.querySelectorAll('#tableBody tr'));
    }

    /**
     * Verifica si un estado de pago está completado
     * @param {string} estadoPago
     * @returns {boolean}
     */
    function esPagoCompletado(estadoPago) {
        return ESTADOS_PAGADOS.includes(estadoPago.toLowerCase());
    }

    /**
     * Verifica si un estado de pago está pendiente
     * @param {string} estadoPago
     * @returns {boolean}
     */
    function esPagoPendiente(estadoPago) {
        return ESTADOS_PENDIENTES.includes(estadoPago.toLowerCase());
    }

    /**
     * Aplica los filtros de búsqueda y estado a las filas
     */
    function aplicarFiltros() {
        const searchInput = document.getElementById('searchInput');
        const query = (searchInput.value || '').toLowerCase().trim();
        const botonActivo = document.querySelector('.filter-button.active');
        const filtro = botonActivo ? botonActivo.dataset.filter : 'all';

        obtenerFilas().forEach(fila => {
            // Verificar coincidencia de búsqueda
            const texto = fila.textContent.toLowerCase();
            const coincideBusqueda = texto.includes(query);

            // Obtener estados de la fila
            const estadoPago = (fila.dataset.estadoPago || '').toLowerCase();
            const estadoRegistro = (fila.dataset.estadoRegistro || '').toLowerCase();

            // Verificar coincidencia de filtro
            let coincideFiltro = false;

            switch (filtro) {
                case 'all':
                    coincideFiltro = true;
                    break;
                case 'pagado':
                    coincideFiltro = esPagoCompletado(estadoPago);
                    break;
                case 'pendiente':
                    coincideFiltro = esPagoPendiente(estadoPago);
                    break;
                case 'activo':
                    coincideFiltro = (estadoRegistro === 'activo');
                    break;
                default:
                    coincideFiltro = true;
            }

            // Mostrar u ocultar fila
            fila.style.display = (coincideBusqueda && coincideFiltro) ? '' : 'none';
        });
    }

    /**
     * Configura los event listeners para los botones de filtro
     */
    function configurarBotonesFiltro() {
        const botonesFiltro = document.querySelectorAll('.filter-button');

        botonesFiltro.forEach(boton => {
            boton.addEventListener('click', function() {
                // Remover clase active de todos los botones
                botonesFiltro.forEach(b => {
                    b.classList.remove('active', 'bg-blue-500', 'text-white');
                    b.classList.add('text-slate-700');
                });

                // Agregar clase active al botón clickeado
                this.classList.add('active', 'bg-blue-500', 'text-white');
                this.classList.remove('text-slate-700');

                // Aplicar filtros
                aplicarFiltros();
            });
        });
    }

    /**
     * Configura el event listener para el campo de búsqueda
     */
    function configurarBusqueda() {
        const searchInput = document.getElementById('searchInput');

        if (searchInput) {
            searchInput.addEventListener('input', aplicarFiltros);
        }
    }

    /**
     * Configura los botones de acción (editar, eliminar)
     */
    function configurarBotonesAccion() {
        // Configurar botones de eliminar con confirmación
        const botonesEliminar = document.querySelectorAll('.btn-eliminar-reporte');

        botonesEliminar.forEach(boton => {
            boton.addEventListener('click', function(e) {
                const confirmar = confirm('¿Está seguro que desea eliminar este reporte?');
                if (!confirmar) {
                    e.preventDefault();
                }
            });
        });
    }

    /**
     * Muestra un mensaje de notificación
     * @param {string} mensaje
     * @param {string} tipo - 'success', 'error', 'warning', 'info'
     */
    function mostrarNotificacion(mensaje, tipo = 'info') {
        // Si existe SweetAlert2, usarlo
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: tipo === 'success' ? 'success' : tipo === 'error' ? 'error' : 'info',
                title: tipo === 'success' ? 'Éxito' : tipo === 'error' ? 'Error' : 'Información',
                text: mensaje,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            // Fallback a alert nativo
            alert(mensaje);
        }
    }

    /**
     * Muestra mensajes de sesión (success/error)
     */
    function mostrarMensajesSesion() {
        // Verificar si hay mensajes en el DOM
        const mensajeSuccess = document.querySelector('[data-mensaje-success]');
        const mensajeError = document.querySelector('[data-mensaje-error]');

        if (mensajeSuccess) {
            mostrarNotificacion(mensajeSuccess.dataset.mensajeSuccess, 'success');
        }

        if (mensajeError) {
            mostrarNotificacion(mensajeError.dataset.mensajeError, 'error');
        }
    }

    /**
     * Inicializa el botón de exportar PDF
     */
    function configurarExportarPDF() {
        const botonExportar = document.getElementById('btnExportarPDF');

        if (botonExportar) {
            botonExportar.addEventListener('click', function() {
                window.location.href = 'index.php?route=reportes/exportarPDF';
            });
        }
    }

    /**
     * Actualiza el contador de resultados visibles
     */
    function actualizarContadorResultados() {
        const contador = document.getElementById('contadorResultados');
        if (!contador) return;

        const filasVisibles = obtenerFilas().filter(fila => fila.style.display !== 'none');
        const totalFilas = obtenerFilas().length;

        contador.textContent = `Mostrando ${filasVisibles.length} de ${totalFilas} reportes`;
    }

    /**
     * Inicialización principal
     */
    function inicializar() {
        // Configurar búsqueda y filtros
        configurarBusqueda();
        configurarBotonesFiltro();
        configurarBotonesAccion();
        configurarExportarPDF();

        // Activar el botón "Todos" por defecto
        const botonTodos = document.querySelector('.filter-button[data-filter="all"]');
        if (botonTodos) {
            botonTodos.classList.add('active', 'bg-blue-500', 'text-white');
            botonTodos.classList.remove('text-slate-700');
        }

        // Aplicar filtros iniciales
        aplicarFiltros();

        // Mostrar mensajes de sesión
        mostrarMensajesSesion();
    }

    // Ejecutar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', inicializar);
    } else {
        inicializar();
    }

    // Exportar funciones públicas si es necesario
    window.ReportesModule = {
        aplicarFiltros,
        mostrarNotificacion
    };

})();
