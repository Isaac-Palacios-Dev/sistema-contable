<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-journal-plus"></i> Asientos contables</h3>
    <a href="/asientos/nuevo" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nuevo asiento</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>#</th><th>Fecha</th><th>Concepto</th><th>Registrado por</th><th class="text-end">Total</th></tr></thead>
            <tbody>
            <?php foreach ($asientos as $a): ?>
                <tr>
                    <td><a href="/asientos/<?= $a['id'] ?>">#<?= $a['numero'] ?></a></td>
                    <td><?= htmlspecialchars($a['fecha']) ?></td>
                    <td><?= htmlspecialchars($a['concepto']) ?></td>
                    <td><?= htmlspecialchars($a['usuario_nombre']) ?></td>
                    <td class="text-end">$<?= number_format($a['total'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($asientos)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Todavía no hay asientos registrados.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
