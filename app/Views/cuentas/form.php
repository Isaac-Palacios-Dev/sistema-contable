<div class="row justify-content-center">
    <div class="col-lg-6">
        <h3 class="mb-4"><i class="bi bi-list-columns"></i> <?= htmlspecialchars($titulo) ?></h3>
        <div class="card p-4">
            <form method="post" action="<?= htmlspecialchars($accion) ?>">
                <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">

                <div class="mb-3">
                    <label class="form-label">Código</label>
                    <input type="text" name="codigo" class="form-control" required maxlength="20"
                           value="<?= htmlspecialchars($cuenta['codigo'] ?? '') ?>" placeholder="Ej. 1101">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required maxlength="150"
                           value="<?= htmlspecialchars($cuenta['nombre'] ?? '') ?>" placeholder="Ej. Caja">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de cuenta</label>
                    <select name="tipo" class="form-select" required>
                        <option value="">Seleccioná...</option>
                        <?php foreach (\App\Models\Cuenta::ETIQUETAS_TIPO as $valor => $etiqueta): ?>
                            <option value="<?= $valor ?>" <?= (($cuenta['tipo'] ?? '') === $valor) ? 'selected' : '' ?>>
                                <?= $etiqueta ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Naturaleza</label>
                    <select name="naturaleza" class="form-select" required>
                        <option value="">Seleccioná...</option>
                        <option value="deudora" <?= (($cuenta['naturaleza'] ?? '') === 'deudora') ? 'selected' : '' ?>>Deudora (activos y gastos)</option>
                        <option value="acreedora" <?= (($cuenta['naturaleza'] ?? '') === 'acreedora') ? 'selected' : '' ?>>Acreedora (pasivos, capital e ingresos)</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Guardar</button>
                    <a href="/cuentas" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
