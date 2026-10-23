<?php

/**
 * Carga el archivo .env (si existe) y devuelve la configuración del sistema.
 * No usa librerías externas: es un parser mínimo línea por línea.
 */

$rutaEnv = __DIR__ . '/../.env';

if (file_exists($rutaEnv)) {
    foreach (file($rutaEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
        $linea = trim($linea);
        if ($linea === '' || str_starts_with($linea, '#')) {
            continue;
        }
        [$clave, $valor] = array_pad(explode('=', $linea, 2), 2, '');
        putenv(trim($clave) . '=' . trim($valor));
    }
}

if (!function_exists('env')) {
    function env(string $clave, $defecto = null)
    {
        $valor = getenv($clave);
        return $valor === false ? $defecto : $valor;
    }
}

return [
    'app' => [
        'nombre' => env('APP_NOMBRE', 'Sistema Contable'),
        'base' => rtrim(env('APP_BASE', ''), '/'),
        'debug' => env('APP_DEBUG', 'true') === 'true',
    ],
    'db' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'name' => env('DB_NAME', 'sistema_contable'),
        'user' => env('DB_USER', 'root'),
        'pass' => env('DB_PASS', ''),
    ],
];
