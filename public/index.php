<?php

/**
 * Controlador frontal (front controller). Todas las peticiones pasan por aquí.
 * Registra un autoloader simple (sin Composer) y despacha al router.
 */

spl_autoload_register(function (string $clase) {
    $prefijo = 'App\\';
    if (!str_starts_with($clase, $prefijo)) {
        return;
    }
    $rutaRelativa = str_replace('\\', '/', substr($clase, strlen($prefijo)));
    $archivo = __DIR__ . '/../app/' . $rutaRelativa . '.php';
    if (file_exists($archivo)) {
        require $archivo;
    }
});

use App\Core\Router;

$config = require __DIR__ . '/../config/config.php';
if ($config['app']['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// Las sesiones se guardan dentro del propio proyecto, no en la carpeta temporal
// global del servidor: evita depender de permisos ajenos al proyecto.
$rutaSesiones = __DIR__ . '/../storage/sessions';
if (!is_dir($rutaSesiones)) {
    mkdir($rutaSesiones, 0775, true);
}
session_save_path($rutaSesiones);

$router = new Router();

$router->get('/login', 'AuthController', 'mostrarLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/logout', 'AuthController', 'logout');

$router->get('/', 'DashboardController', 'index');

$router->get('/cuentas', 'CuentaController', 'index');
$router->get('/cuentas/nueva', 'CuentaController', 'crearForm');
$router->post('/cuentas', 'CuentaController', 'crear');
$router->get('/cuentas/{id}/editar', 'CuentaController', 'editarForm');
$router->post('/cuentas/{id}', 'CuentaController', 'actualizar');
$router->post('/cuentas/{id}/desactivar', 'CuentaController', 'desactivar');

$router->get('/asientos', 'AsientoController', 'index');
$router->get('/asientos/nuevo', 'AsientoController', 'crearForm');
$router->post('/asientos', 'AsientoController', 'crear');
$router->get('/asientos/{id}', 'AsientoController', 'ver');

$router->get('/reportes/balance', 'ReporteController', 'balanceComprobacion');
$router->get('/reportes/mayor', 'ReporteController', 'mayor');
$router->get('/reportes/resultados', 'ReporteController', 'estadoResultados');

// Quita la base del proyecto (si se sirve desde una subcarpeta) y el query string.
$base = $config['app']['base'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
if ($base !== '' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}
if ($uri === '' ) {
    $uri = '/';
}
$uri = rtrim($uri, '/');
if ($uri === '') {
    $uri = '/';
}

$router->resolver($_SERVER['REQUEST_METHOD'], $uri);
