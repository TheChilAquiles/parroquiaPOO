<?php

require_once __DIR__ . '/../Modelo/Conexion.php';
require_once __DIR__ . '/../Controlador/ControladorCertificados.php';

$pdo = Conexion::conectar();
$controller = new CertificadoController($pdo);
$certificados = $controller->index();


?>

<h1>Listado de Certificados</h1>
<a href="crear.php">Crear nuevo certificado</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Tipo</th>
        <th>Fecha Emisión</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($certificados as $c): ?>
    <tr>
        <td><?= $c['id'] ?></td>
        <td><?= $c['tipo_certificado'] ?></td>
        <td><?= $c['fecha_emision'] ?></td>
        <td><?= $c['estado'] ?></td>
        <td>
            <a href="editar.php?id=<?= $c['id'] ?>">Editar</a>
            <a href="eliminar.php?id=<?= $c['id'] ?>" onclick="return confirm('¿Seguro?')">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
