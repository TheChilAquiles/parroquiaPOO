<?php include 'layout/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Reporte Financiero</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?route=reportes">Dashboard</a></li>
        <li class="breadcrumb-item active">Finanzas</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filtros
        </div>
        <div class="card-body">
            <form method="GET" action="index.php" class="row g-3">
                <input type="hidden" name="route" value="reportes/finanzas">
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
            Transacciones
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Estado</th>
                        <th>Transacción ID</th>
                        <th class="text-end">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($datos)): ?>
                        <?php $total = 0; ?>
                        <?php foreach ($datos as $row): ?>
                            <?php $total += $row['valor']; ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_pago'])); ?></td>
                                <td><?php echo ucfirst($row['tipo_concepto']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo ($row['estado'] == 'aprobado' || $row['estado'] == 'completado') ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($row['estado']); ?>
                                    </span>
                                </td>
                                <td><?php echo $row['transaction_id'] ?? '-'; ?></td>
                                <td class="text-end">$<?php echo number_format($row['valor'], 0); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-dark">
                            <td colspan="5" class="text-end"><strong>Total</strong></td>
                            <td class="text-end"><strong>$<?php echo number_format($total, 0); ?></strong></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron registros en este rango de fechas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
