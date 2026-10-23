<?php
$totalDebe = array_sum(array_column($asiento['lineas'], 'debe'));
$totalHaber = array_sum(array_column($asiento['lineas'], 'haber'));
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-journal-text"></i> Asiento #<?= $asiento['numero'] ?></h3>
    <a href="/asientos" class="btn btn-outline-secondary btn-sm">Volver</a>
</div>

<div class="card p-3 mb-3">
    <div class="row">
        <div class="col-md-4"><strong>Fecha:</strong> <?= htmlspecialchars($asiento['fecha']) ?></div>
        <div class="col-md-8"><strong>Concepto:</strong> <?= htmlspecialchars($asiento['concepto']) ?></div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Cuenta</th><th class="text-end">Debe</th><th class="text-end">Haber</th></tr></thead>
            <tbody>
            <?php foreach ($asiento['lineas'] as $l): ?>
                <tr>
                    <td><code><?= htmlspecialchars($l['codigo']) ?></code> <?= htmlspecialchars($l['cuenta_nombre']) ?></td>
                    <td class="text-end"><?= $l['debe'] > 0 ? '$' . number_format($l['debe'], 2) : '' ?></td>
                    <td class="text-end"><?= $l['haber'] > 0 ? '$' . number_format($l['haber'], 2) : '' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td>Totales</td>
                    <td class="text-end">$<?= number_format($totalDebe, 2) ?></td>
                    <td class="text-end">$<?= number_format($totalHaber, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
