<h3 class="mb-4"><i class="bi bi-bar-chart"></i> Balance de comprobación</h3>

<form method="get" action="/reportes/balance" class="row g-2 mb-3">
    <div class="col-auto">
        <label class="form-label small">Hasta la fecha</label>
        <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars($hasta) ?>">
    </div>
    <div class="col-auto align-self-end">
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr><th>Código</th><th>Cuenta</th><th>Tipo</th><th class="text-end">Debe</th><th class="text-end">Haber</th><th class="text-end">Saldo</th></tr>
            </thead>
            <tbody>
            <?php foreach ($filas as $f): ?>
                <tr>
                    <td><code><?= htmlspecialchars($f['codigo']) ?></code></td>
                    <td><?= htmlspecialchars($f['nombre']) ?></td>
                    <td><?= \App\Models\Cuenta::ETIQUETAS_TIPO[$f['tipo']] ?? $f['tipo'] ?></td>
                    <td class="text-end">$<?= number_format($f['total_debe'], 2) ?></td>
                    <td class="text-end">$<?= number_format($f['total_haber'], 2) ?></td>
                    <td class="text-end fw-bold"><?= $f['saldo'] < 0 ? '-$' . number_format(abs($f['saldo']), 2) : '$' . number_format($f['saldo'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3">Totales</td>
                    <td class="text-end">$<?= number_format($totales['debe'], 2) ?></td>
                    <td class="text-end">$<?= number_format($totales['haber'], 2) ?></td>
                    <td class="text-end">
                        <?= abs($totales['debe'] - $totales['haber']) < 0.01
                            ? '<span class="text-success">Cuadrado</span>'
                            : '<span class="text-danger">Descuadrado</span>' ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
