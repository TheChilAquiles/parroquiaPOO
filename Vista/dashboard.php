<?php

$servidor="localhost";
$user="root";
$password="";
$db="parroquia";

try{
    $conexion=new PDO("mysql:host=$servidor;dbname=$db",$user,$password);

    // ===== Usuarios =====
    $sentencia=$conexion->prepare("SELECT COUNT(id) FROM usuarios");
    $sentencia->execute();
    $id_Usuarios=$sentencia->fetchColumn();

    $sentencia=$conexion->prepare("SELECT COUNT(id) FROM usuario_roles");
    $sentencia->execute();
    $id_Roles=$sentencia->fetchColumn();

    $sentencia=$conexion->prepare("SELECT COUNT(id) FROM feligreses");
    $sentencia->execute();
    $id_Feligreses=$sentencia->fetchColumn();

    // ===== Libros =====
    $sentencia=$conexion->prepare("SELECT COUNT(id) FROM libros");
    $sentencia->execute();
    $id=$sentencia->fetchColumn();

    $sentencia=$conexion->prepare("SELECT COUNT(libro_tipo_id) FROM libros");
    $sentencia->execute();
    $tipo=$sentencia->fetchColumn();

    $sentencia=$conexion->prepare("SELECT COUNT(numero) FROM libros");
    $sentencia->execute();
    $numero=$sentencia->fetchColumn();

    $sentencia=$conexion->prepare("SELECT COUNT(id) FROM documento_tipos");
    $sentencia->execute();
    $id_Documento=$sentencia->fetchColumn();

    $sentencia=$conexion->prepare("SELECT COUNT(tipo) FROM documento_tipos");
    $sentencia->execute();
    $tipo_Documento=$sentencia->fetchColumn();


}
catch (Exception $e) {
    echo $e->getMessage();
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="Vista/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="row">
        <!-- GRAFICO USUARIOS -->
        <div class="col-md-6"> 
            <canvas id="graficoUsuarios"></canvas>
        </div>

        <!-- GRAFICO LIBROS -->
        <div class="col-md-6">
            <canvas id="graficoLibros"></canvas>
        </div>

        <!-- GRAFICO DOCUMENTO -->
        <div class='col-md-6'>
            <canvas id="graficoDocumento"></canvas>
        </div>
    </div>

    <script>
        // ====== Gráfico de Usuarios ======
        const ctxUsuarios = document.getElementById('graficoUsuarios');
        new Chart(ctxUsuarios, {
            type: 'bar',
            data: {
                labels: ['id Usuarios','id Roles','id Feligreses'],
                datasets: [{
                    label: 'Informacion de id',
                    data: [<?php echo $id_Usuarios;?>, <?php echo $id_Roles;?>, <?php echo $id_Feligreses;?> ],
                    backgroundColor: ['#36A2EB','#FF6384','#4BC0C0'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // ====== Gráfico de Libros ======
        const ctxLibros = document.getElementById('graficoLibros');
        new Chart(ctxLibros, {
            type: 'pie',
            data: {
                labels: ['id','tipo','numero'],
                datasets: [{
                    label: 'Libros',
                    data: [<?php echo $id;?>, <?php echo $tipo;?>, <?php echo $numero;?> ],
                    backgroundColor: ['#FFCE56','#9966FF','#FF9F40'],
                    borderWidth: 1
                }]
            }
        });

        // ====== Gráfico de Usuarios ======
        const ctxDocumento = document.getElementById('graficoDocumento');
        new Chart(ctxDocumento, {
            type: 'line',
            data: {
                labels: ['id','email','rol'],
                datasets: [{
                    label: 'Docuemtos',
                    data: [<?php echo $tipo_Documento;?>, <?php echo $id_Documento;?> ],
                    backgroundColor: ['#36A2EB','#FF6384','#4BC0C0'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
