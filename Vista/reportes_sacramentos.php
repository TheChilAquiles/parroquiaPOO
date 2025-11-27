<?php include 'layout/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Reporte de Sacramentos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?route=reportes">Dashboard</a></li>
        <li class="breadcrumb-item active">Sacramentos</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filtros
        </div>
        <div class="card-body">
            <form method="GET" action="index.php" class="row g-3">
                <input type="hidden" name="route" value="reportes/sacramentos">
                <div class="col-md-4">
                    <label for="inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="inicio" name="inicio" value="<?php echo $_GET['inicio'] ?? date('Y-m-01'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fin" name="fin" value="<?php echo $_GET['fin'] ?? date('Y-m-t'); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Resultados
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Fecha Generación</th>
                        <th>Acta</th>
                        <th>Folio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($datos)): ?>
                        <?php foreach ($datos as $row): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['tipo']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['fecha_generacion'])); ?></td>
                                <td><?php echo $row['acta']; ?></td>
                                <td><?php echo $row['folio']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron registros en este rango de fechas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
