<?php include 'layout/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard Parroquial</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Resumen General</li>
    </ol>

    <!-- Tarjetas de Resumen -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?php echo $stats['sacramentos_total'] ?? 0; ?></div>
                            <div class="small">Sacramentos Totales</div>
                        </div>
                        <i class="fas fa-church fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="index.php?route=reportes/sacramentos">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0">$<?php echo number_format($stats['ingresos_mes'] ?? 0, 0); ?></div>
                            <div class="small">Ingresos del Mes</div>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="index.php?route=reportes/finanzas">Ver Finanzas</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?php echo $stats['feligreses_total'] ?? 0; ?></div>
                            <div class="small">Feligreses Registrados</div>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="index.php?route=feligreses">Ver Feligreses</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="h3 mb-0"><?php echo $stats['certificados_mes'] ?? 0; ?></div>
                            <div class="small">Certificados (Mes)</div>
                        </div>
                        <i class="fas fa-file-alt fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">Ver Historial</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico de Sacramentos (Simulado con Barras CSS) -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Sacramentos del Año
                </div>
                <div class="card-body">
                    <?php if (!empty($stats['desglose_sacramentos'])): ?>
                        <?php foreach ($stats['desglose_sacramentos'] as $sac): ?>
                            <?php 
                                $max = 100; // Valor base para cálculo de porcentaje (simplificado)
                                $porcentaje = ($sac['cantidad'] / $max) * 100; // Esto debería ser relativo al total real
                            ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><?php echo htmlspecialchars($sac['tipo']); ?></span>
                                    <span><?php echo $sac['cantidad']; ?></span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentaje; ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">No hay datos registrados este año.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabla de Ingresos por Concepto -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Ingresos por Concepto (Año Actual)
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($stats['ingresos_por_concepto'])): ?>
                                <?php foreach ($stats['ingresos_por_concepto'] as $ingreso): ?>
                                    <tr>
                                        <td><?php echo ucfirst($ingreso['tipo_concepto']); ?></td>
                                        <td class="text-end">$<?php echo number_format($ingreso['total'], 0); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No hay ingresos registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
