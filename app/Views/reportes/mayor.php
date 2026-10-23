<h3 class="mb-4"><i class="bi bi-journal-bookmark"></i> Libro mayor</h3>

<form method="get" action="/reportes/mayor" class="row g-2 mb-3">
    <div class="col-auto">
        <label class="form-label small">Cuenta</label>
        <select name="cuenta_id" class="form-select" onchange="this.form.submit()">
            <option value="">Seleccioná una cuenta...</option>
            <?php foreach ($cuentas as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $cuentaId == $c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['codigo'] . ' - ' . $c['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<?php if ($cuentaId): ?>
<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Fecha</th><th>Asiento</th><th>Concepto</th><th class="text-end">Debe</th><th class="text-end">Haber</th><th class="text-end">Saldo</th></tr>
            </thead>
            <tbody>
            <?php foreach ($movimientos as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['fecha']) ?></td>
                    <td>#<?= $m['numero'] ?></td>
                    <td><?= htmlspecialchars($m['concepto']) ?></td>
                    <td class="text-end"><?= $m['debe'] > 0 ? '$' . number_format($m['debe'], 2) : '' ?></td>
                    <td class="text-end"><?= $m['haber'] > 0 ? '$' . number_format($m['haber'], 2) : '' ?></td>
                    <td class="text-end"><?= number_format($m['saldo_acumulado'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($movimientos)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Esta cuenta no tiene movimientos.</td></tr>
            <?php endif; ?>
            </tbody>
            <?php if (!empty($movimientos)): ?>
            <tfoot>
                <tr class="fw-bold"><td colspan="5">Saldo final</td><td class="text-end">$<?= number_format($saldo, 2) ?></td></tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php else: ?>
    <p class="text-muted">Elegí una cuenta para ver su movimiento.</p>
<?php endif; ?>
