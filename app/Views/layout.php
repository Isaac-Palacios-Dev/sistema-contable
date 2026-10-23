<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titulo ?? 'Sistema Contable') ?> · Sistema Contable</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .navbar-brand { font-weight: 600; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .kpi-card { border-radius: .75rem; }
        .kpi-card .value { font-size: 1.75rem; font-weight: 700; }
        table.table thead { background-color: #eef1f6; }
        .sidebar-link.active { background-color: #e7eefc; font-weight: 600; }
    </style>
</head>
<body>

<?php if (!empty($_SESSION['usuario_id'])): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/"><i class="bi bi-journal-text"></i> Sistema Contable</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/"><i class="bi bi-speedometer2"></i> Panel</a></li>
                <li class="nav-item"><a class="nav-link" href="/cuentas"><i class="bi bi-list-columns"></i> Cuentas</a></li>
                <li class="nav-item"><a class="nav-link" href="/asientos"><i class="bi bi-journal-plus"></i> Asientos</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bar-chart"></i> Reportes
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/reportes/balance">Balance de comprobación</a></li>
                        <li><a class="dropdown-item" href="/reportes/mayor">Libro mayor</a></li>
                        <li><a class="dropdown-item" href="/reportes/resultados">Estado de resultados</a></li>
                    </ul>
                </li>
            </ul>
            <span class="navbar-text me-3">
                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?>
            </span>
            <a href="/logout" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a>
        </div>
    </div>
</nav>
<?php endif; ?>

<main class="container-fluid py-4">
    <?php if (!empty($_SESSION['flash'])): ?>
        <?php $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
        <div class="alert alert-<?= $f['tipo'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($f['mensaje']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php require $rutaVista; ?>
</main>

<footer class="text-center text-muted small py-3">
    Sistema Contable &middot; Proyecto académico &middot; <?= date('Y') ?>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.4.21/vue.global.prod.min.js"></script>
</body>
</html>
