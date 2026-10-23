<h3 class="mb-4"><i class="bi bi-speedometer2"></i> Panel principal</h3>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card kpi-card bg-white p-3">
            <div class="text-muted small"><i class="bi bi-list-columns"></i> Cuentas activas</div>
            <div class="value"><?= (int) $totalCuentas ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card kpi-card bg-white p-3">
            <div class="text-muted small"><i class="bi bi-journal-plus"></i> Asientos registrados</div>
            <div class="value"><?= (int) $totalAsientos ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card kpi-card bg-white p-3">
            <div class="text-muted small"><i class="bi bi-bank"></i> Total activos</div>
            <div class="value">$<?= number_format($totalActivos, 2) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card kpi-card p-3 <?= $utilidadMes >= 0 ? 'bg-white' : 'bg-white' ?>">
            <div class="text-muted small"><i class="bi bi-graph-up"></i> Utilidad del mes</div>
            <div class="value <?= $utilidadMes >= 0 ? 'text-success' : 'text-danger' ?>">
                $<?= number_format($utilidadMes, 2) ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>Últimos asientos</strong>
                <a href="/asientos" class="small">Ver todos</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>#</th><th>Fecha</th><th>Concepto</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                    <?php if (empty($ultimosAsientos)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Todavía no hay asientos registrados.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($ultimosAsientos as $a): ?>
                        <tr>
                            <td><a href="/asientos/<?= $a['id'] ?>">#<?= $a['numero'] ?></a></td>
                            <td><?= htmlspecialchars($a['fecha']) ?></td>
                            <td><?= htmlspecialchars($a['concepto']) ?></td>
                            <td class="text-end">$<?= number_format($a['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-3">
            <strong class="mb-2"><i class="bi bi-lightning"></i> Accesos rápidos</strong>
            <a href="/asientos/nuevo" class="btn btn-primary mb-2"><i class="bi bi-plus-circle"></i> Nuevo asiento</a>
            <a href="/cuentas/nueva" class="btn btn-outline-secondary mb-2"><i class="bi bi-plus-circle"></i> Nueva cuenta</a>
            <a href="/reportes/balance" class="btn btn-outline-secondary"><i class="bi bi-bar-chart"></i> Balance de comprobación</a>
        </div>
    </div>
</div>
