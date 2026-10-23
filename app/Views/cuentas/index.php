<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="bi bi-list-columns"></i> Catálogo de cuentas</h3>
    <a href="/cuentas/nueva" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nueva cuenta</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Código</th><th>Nombre</th><th>Tipo</th><th>Naturaleza</th><th>Estado</th><th class="text-end">Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($cuentas as $c): ?>
                <tr>
                    <td><code><?= htmlspecialchars($c['codigo']) ?></code></td>
                    <td><?= htmlspecialchars($c['nombre']) ?></td>
                    <td><span class="badge text-bg-light border"><?= \App\Models\Cuenta::ETIQUETAS_TIPO[$c['tipo']] ?? $c['tipo'] ?></span></td>
                    <td><?= $c['naturaleza'] === 'deudora' ? 'Deudora' : 'Acreedora' ?></td>
                    <td>
                        <?php if ($c['activa']): ?>
                            <span class="badge text-bg-success">Activa</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Inactiva</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="/cuentas/<?= $c['id'] ?>/editar" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if ($c['activa']): ?>
                        <form method="post" action="/cuentas/<?= $c['id'] ?>/desactivar" class="d-inline"
                              onsubmit="return confirm('¿Desactivar esta cuenta? No se puede deshacer desde aquí.')">
                            <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-slash-circle"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($cuentas)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No hay cuentas registradas todavía.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
