<h3 class="mb-4"><i class="bi bi-journal-plus"></i> Nuevo asiento contable (#<?= $siguienteNumero ?>)</h3>

<div id="app-asiento" class="card p-4">
    <form method="post" action="/asientos" @submit="validarEnvio">
        <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?>">

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="form-control" required value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-8">
                <label class="form-label">Concepto</label>
                <input type="text" name="concepto" class="form-control" required maxlength="255"
                       placeholder="Ej. Compra de repuestos al contado">
            </div>
        </div>

        <table class="table align-middle">
            <thead>
                <tr><th>Cuenta</th><th style="width:160px">Debe</th><th style="width:160px">Haber</th><th style="width:50px"></th></tr>
            </thead>
            <tbody>
                <tr v-for="(linea, i) in lineas" :key="i">
                    <td>
                        <select :name="'cuenta_id[' + i + ']'" v-model="linea.cuenta_id" class="form-select" required>
                            <option value="">Seleccioná una cuenta...</option>
                            <?php foreach ($cuentas as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['codigo'] . ' - ' . $c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" :name="'debe[' + i + ']'" v-model.number="linea.debe"
                               class="form-control text-end" @input="linea.haber = linea.debe ? 0 : linea.haber">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" :name="'haber[' + i + ']'" v-model.number="linea.haber"
                               class="form-control text-end" @input="linea.debe = linea.haber ? 0 : linea.debe">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger" @click="quitarLinea(i)"
                                :disabled="lineas.length <= 2">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="fw-bold" :class="cuadra ? 'text-success' : 'text-danger'">
                    <td>Totales</td>
                    <td class="text-end">{{ totalDebe.toFixed(2) }}</td>
                    <td class="text-end">{{ totalHaber.toFixed(2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-outline-secondary btn-sm" @click="agregarLinea">
                <i class="bi bi-plus-lg"></i> Agregar línea
            </button>
            <span v-if="!cuadra" class="text-danger small">
                <i class="bi bi-exclamation-triangle"></i> El debe y el haber deben ser iguales para guardar.
            </span>
        </div>

        <hr>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" :disabled="!cuadra || totalDebe === 0">
                <i class="bi bi-check2"></i> Guardar asiento
            </button>
            <a href="/asientos" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
const { createApp } = Vue;
createApp({
    data() {
        return {
            lineas: [
                { cuenta_id: '', debe: null, haber: null },
                { cuenta_id: '', debe: null, haber: null },
            ],
        };
    },
    computed: {
        totalDebe() {
            return this.lineas.reduce((suma, l) => suma + (Number(l.debe) || 0), 0);
        },
        totalHaber() {
            return this.lineas.reduce((suma, l) => suma + (Number(l.haber) || 0), 0);
        },
        cuadra() {
            return Math.abs(this.totalDebe - this.totalHaber) < 0.01;
        },
    },
    methods: {
        agregarLinea() {
            this.lineas.push({ cuenta_id: '', debe: null, haber: null });
        },
        quitarLinea(i) {
            if (this.lineas.length > 2) this.lineas.splice(i, 1);
        },
        validarEnvio(evento) {
            if (!this.cuadra || this.totalDebe === 0) {
                evento.preventDefault();
            }
        },
    },
}).mount('#app-asiento');
</script>
