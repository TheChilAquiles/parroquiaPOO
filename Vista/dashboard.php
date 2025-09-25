<?php
// --- CONEXIÓN ---
$servidor = "localhost";
$user = "root";
$password = "";
$db = "parroquia";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$db;charset=utf8", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Usuarios
    $id_Usuarios = (int) $conexion->query("SELECT COUNT(id) FROM usuarios")->fetchColumn();
    $id_Roles = (int) $conexion->query("SELECT COUNT(id) FROM usuario_roles")->fetchColumn();
    $id_Feligreses = (int) $conexion->query("SELECT COUNT(id) FROM feligreses")->fetchColumn();

    // Libros
    $id_Libros = (int) $conexion->query("SELECT COUNT(id) FROM libros")->fetchColumn();
    $tipo_Libros = (int) $conexion->query("SELECT COUNT(libro_tipo_id) FROM libros")->fetchColumn();
    $numero_Libros = (int) $conexion->query("SELECT COUNT(numero) FROM libros")->fetchColumn();

    // Documentos
    $id_Documento = (int) $conexion->query("SELECT COUNT(id) FROM documento_tipos")->fetchColumn();
    $tipo_Documento = (int) $conexion->query("SELECT COUNT(tipo) FROM documento_tipos")->fetchColumn();

    // Reportes
    $id_Reportes = (int) $conexion->query("SELECT COUNT(id) FROM reportes")->fetchColumn();
    $categorias_Reportes = (int) $conexion->query("SELECT COUNT(DISTINCT categoria) FROM reportes")->fetchColumn();

    // Pagos
    $id_Pagos = (int) $conexion->query("SELECT COUNT(id) FROM pagos")->fetchColumn();
    $pagos_Completos = (int) $conexion->query("SELECT COUNT(*) FROM pagos WHERE estado='completo'")->fetchColumn();
    $pagos_Cancelados = (int) $conexion->query("SELECT COUNT(*) FROM pagos WHERE estado='cancelado'")->fetchColumn();

    // Contactos (si existe la tabla)
    try {
        $id_Contactos = (int) $conexion->query("SELECT COUNT(id) FROM contactos")->fetchColumn();
    } catch (Exception $e) {
        $id_Contactos = 0;
    }

} catch (Exception $e) {
    echo "<h1 style='color:red'>Error de conexión: " . $e->getMessage() . "</h1>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Parroquia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="p-8 text-center">
        <header class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800">📊 Dashboard Parroquia</h1>
            <p class="text-gray-500 mt-1">Estadísticas generales en tiempo real.</p>
        </header>

        <!-- TARJETAS -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
            <div class="bg-white p-4 rounded-xl shadow-md">
                <h2 class="text-sm font-semibold text-gray-500">👥 Usuarios</h2>
                <p class="text-3xl font-bold text-gray-800 mt-2"><?= $id_Usuarios ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <h2 class="text-sm font-semibold text-gray-500">📚 Libros</h2>
                <p class="text-3xl font-bold text-gray-800 mt-2"><?= $id_Libros ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <h2 class="text-sm font-semibold text-gray-500">📄 Documentos</h2>
                <p class="text-3xl font-bold text-gray-800 mt-2"><?= $id_Documento ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <h2 class="text-sm font-semibold text-gray-500">📝 Reportes</h2>
                <p class="text-3xl font-bold text-gray-800 mt-2"><?= $id_Reportes ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <h2 class="text-sm font-semibold text-gray-500">💰 Pagos</h2>
                <p class="text-3xl font-bold text-gray-800 mt-2"><?= $id_Pagos ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md">
                <h2 class="text-sm font-semibold text-gray-500">📞 Contactos</h2>
                <p class="text-3xl font-bold text-gray-800 mt-2"><?= $id_Contactos ?></p>
            </div>
        </div>

        <!-- GRÁFICOS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded-xl shadow-md"><canvas id="graficoUsuarios" height="180"></canvas></div>
            <div class="bg-white p-4 rounded-xl shadow-md"><canvas id="graficoLibros" height="180"></canvas></div>
            <div class="bg-white p-4 rounded-xl shadow-md"><canvas id="graficoDocumento" height="180"></canvas></div>
            <div class="bg-white p-4 rounded-xl shadow-md"><canvas id="graficoReportes" height="180"></canvas></div>
            <div class="bg-white p-4 rounded-xl shadow-md"><canvas id="graficoPagos" height="180"></canvas></div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        new Chart(document.getElementById('graficoUsuarios'),{
            type:'bar',
            data:{labels:['Usuarios','Roles','Feligreses'],
                datasets:[{data:[<?= $id_Usuarios ?>,<?= $id_Roles ?>,<?= $id_Feligreses ?>],
                backgroundColor:['#36A2EB','#FF6384','#4BC0C0'],borderRadius:4}]},
            options:{plugins:{title:{display:true,text:'Usuarios'},legend:{display:false}},responsive:true,maintainAspectRatio:false}
        });

        new Chart(document.getElementById('graficoLibros'),{
            type:'pie',
            data:{labels:['ID','Tipo','Número'],
                datasets:[{data:[<?= $id_Libros ?>,<?= $tipo_Libros ?>,<?= $numero_Libros ?>],
                backgroundColor:['#FFCE56','#9966FF','#FF9F40']}]},
            options:{plugins:{title:{display:true,text:'Libros'}},responsive:true,maintainAspectRatio:false}
        });

        new Chart(document.getElementById('graficoDocumento'),{
            type:'doughnut',
            data:{labels:['Tipo Documento','ID Documento'],
                datasets:[{data:[<?= $tipo_Documento ?>,<?= $id_Documento ?>],
                backgroundColor:['#36A2EB','#FF6384']}]},
            options:{plugins:{title:{display:true,text:'Documentos'}},responsive:true,maintainAspectRatio:false}
        });

        new Chart(document.getElementById('graficoReportes'),{
            type:'bar',
            data:{labels:['Total Reportes','Categorías'],
                datasets:[{data:[<?= $id_Reportes ?>,<?= $categorias_Reportes ?>],
                backgroundColor:['#8E44AD','#BB8FCE'],borderRadius:4}]},
            options:{plugins:{title:{display:true,text:'Reportes'},legend:{display:false}},responsive:true,maintainAspectRatio:false}
        });

        new Chart(document.getElementById('graficoPagos'),{
            type:'pie',
            data:{labels:['Completos','Cancelados','Total'],
                datasets:[{data:[<?= $pagos_Completos ?>,<?= $pagos_Cancelados ?>,<?= $id_Pagos ?>],
                backgroundColor:['#27AE60','#E74C3C','#F1C40F']}]},
            options:{plugins:{title:{display:true,text:'Pagos'}},responsive:true,maintainAspectRatio:false}
        });
    </script>
</body>
</html>