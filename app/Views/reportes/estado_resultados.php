<h3 class="mb-4"><i class="bi bi-graph-up"></i> Estado de resultados</h3>

<form method="get" action="/reportes/resultados" class="row g-2 mb-3">
    <div class="col-auto">
        <label class="form-label small">Desde</label>
        <input type="date" name="desde" class="form-control" value="<?= htmlspecialchars($desde) ?>">
    </div>
    <div class="col-auto">
        <label class="form-label small">Hasta</label>
        <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars($hasta) ?>">
    </div>
    <div class="col-auto align-self-end">
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </div>
</form>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header bg-white"><strong>Ingresos</strong></div>
            <ul class="list-group list-group-flush">
                <?php foreach ($resultado['ingresos'] as $i): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><?= htmlspecialchars($i['nombre']) ?></span>
                        <span>$<?= number_format($i['monto'], 2) ?></span>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($resultado['ingresos'])): ?>
                    <li class="list-group-item text-muted">Sin ingresos en el período.</li>
                <?php endif; ?>
                <li class="list-group-item d-flex justify-content-between fw-bold bg-light">
                    <span>Total ingresos</span><span>$<?= number_format($resultado['total_ingresos'], 2) ?></span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header bg-white"><strong>Gastos</strong></div>
            <ul class="list-group list-group-flush">
                <?php foreach ($resultado['gastos'] as $g): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><?= htmlspecialchars($g['nombre']) ?></span>
                        <span>$<?= number_format($g['monto'], 2) ?></span>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($resultado['gastos'])): ?>
                    <li class="list-group-item text-muted">Sin gastos en el período.</li>
                <?php endif; ?>
                <li class="list-group-item d-flex justify-content-between fw-bold bg-light">
                    <span>Total gastos</span><span>$<?= number_format($resultado['total_gastos'], 2) ?></span>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Utilidad del período</h5>
        <h4 class="mb-0 <?= $resultado['utilidad'] >= 0 ? 'text-success' : 'text-danger' ?>">
            $<?= number_format($resultado['utilidad'], 2) ?>
        </h4>
    </div>
</div>
