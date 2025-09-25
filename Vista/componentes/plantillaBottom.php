</div>

<footer class="bg-[#D0B8A8] h-full">
    <div>
        <h3>Redes Sociales</h3>
        <div>
            hola
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        const rutaCompleta = window.location.pathname || '';
        const ruta = rutaCompleta.toLowerCase();
        const archivo = ruta.substring(ruta.lastIndexOf('/') + 1);

        console.log('DEBUG - ruta completa:', rutaCompleta);
        console.log('DEBUG - ruta lower:', ruta);
        console.log('DEBUG - archivo actual:', archivo);

        const archivosExcluidos = ['pagos.php', 'reportes.php', 'dashboard.php'];

        // condición de exclusión: si el nombre del archivo está en la lista
        // o si la ruta contiene "/vista/" seguido del archivo (por tu estructura)
        const estaExcluido = archivosExcluidos.includes(archivo)
            || archivosExcluidos.some(f => ruta.includes('/vista/' + f))
            || archivosExcluidos.some(f => ruta.endsWith('/' + f));

        console.log('DEBUG - estaExcluido:', estaExcluido);

        // si Toast no está definido, evitamos que lance un error
        if (typeof Toast === 'undefined') {
            console.warn('WARNING - Toast no está definido. No se mostrará alerta.');
            return;
        }

        if (!estaExcluido) {
            // Mostrar alerta
            Toast.fire({
                position: 'top',
                text: '¡tlabaja , tu tiene que tlabaja!',
                icon: 'info',
                title: '¡Que Hacel Vago!',
                timer: 7000
            });
            console.log('DEBUG - Toast mostrado');
        } else {
            console.log('DEBUG - Toast bloqueado para esta ruta.');
        }
    } catch (err) {
        console.error('ERROR en el control del toast:', err);
    }
});
</script>



<!-- <script type="module" src="vista/js/ajax.js"></script> -->
</body>
</html>
