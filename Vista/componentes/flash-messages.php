<?php
/**
 * Componente Global de Notificaciones Flash
 * Detecta variables de sesión y muestra alertas usando SweetAlert2
 */

$alertType = null;
$alertMessage = '';
$alertTitle = '';
$alertIcon = '';

if (isset($_SESSION['error'])) {
    $alertType = 'error';
    $alertMessage = $_SESSION['error'];
    $alertTitle = '¡Error!';
    $alertIcon = 'error';
    unset($_SESSION['error']);
} elseif (isset($_SESSION['warning'])) {
    $alertType = 'warning';
    $alertMessage = $_SESSION['warning'];
    $alertTitle = 'Atención';
    $alertIcon = 'warning';
    unset($_SESSION['warning']);
} elseif (isset($_SESSION['success'])) {
    $alertType = 'success';
    $alertMessage = $_SESSION['success'];
    $alertTitle = '¡Éxito!';
    $alertIcon = 'success';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['info'])) {
    $alertType = 'info';
    $alertMessage = $_SESSION['info'];
    $alertTitle = 'Información';
    $alertIcon = 'info';
    unset($_SESSION['info']);
}

if ($alertType): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?= $alertIcon ?>',
                title: '<?= $alertTitle ?>',
                text: '<?= htmlspecialchars($alertMessage, ENT_QUOTES, 'UTF-8') ?>',
                confirmButtonColor: '#ab876f',
                timer: <?= ($alertType === 'success' || $alertType === 'info') ? 3000 : 'null' ?>,
                timerProgressBar: <?= ($alertType === 'success' || $alertType === 'info') ? 'true' : 'false' ?>
            });
        });
    </script>
<?php endif; ?>
