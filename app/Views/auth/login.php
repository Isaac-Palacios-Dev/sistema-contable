<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card mt-5">
            <div class="card-body p-4">
                <h4 class="mb-1 text-center"><i class="bi bi-journal-text"></i> Sistema Contable</h4>
                <p class="text-muted text-center mb-4">Iniciá sesión para continuar</p>
                <form method="post" action="/login">
                    <input type="hidden" name="csrf_token" value="<?= $this->csrfToken() ?? '' ?>">
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control" required autofocus value="admin@contable.local">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="clave" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
                <p class="text-muted small text-center mt-3 mb-0">
                    Usuario de prueba: <code>admin@contable.local</code> / <code>Admin123*</code>
                </p>
            </div>
        </div>
    </div>
</div>
