<?php

namespace App\Core;

/**
 * Router sencillo: mapea "método HTTP + ruta" a [Controlador, acción].
 * Soporta parámetros con llaves, por ejemplo: /cuentas/{id}/editar
 */
class Router
{
    private array $rutas = [];

    public function get(string $ruta, string $controlador, string $accion): void
    {
        $this->agregar('GET', $ruta, $controlador, $accion);
    }

    public function post(string $ruta, string $controlador, string $accion): void
    {
        $this->agregar('POST', $ruta, $controlador, $accion);
    }

    private function agregar(string $metodo, string $ruta, string $controlador, string $accion): void
    {
        $this->rutas[] = compact('metodo', 'ruta', 'controlador', 'accion');
    }

    public function resolver(string $metodo, string $uriPath): void
    {
        foreach ($this->rutas as $r) {
            if ($r['metodo'] !== $metodo) {
                continue;
            }

            $patron = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $r['ruta']);
            $patron = '#^' . $patron . '$#';

            if (preg_match($patron, $uriPath, $coincidencias)) {
                array_shift($coincidencias);
                $this->despachar($r['controlador'], $r['accion'], $coincidencias);
                return;
            }
        }

        http_response_code(404);
        require __DIR__ . '/../Views/errores/404.php';
    }

    private function despachar(string $nombreControlador, string $accion, array $parametros): void
    {
        $clase = 'App\\Controllers\\' . $nombreControlador;

        if (!class_exists($clase)) {
            http_response_code(500);
            echo "Controlador no encontrado: {$clase}";
            return;
        }

        $controlador = new $clase();

        if (!method_exists($controlador, $accion)) {
            http_response_code(500);
            echo "Acción no encontrada: {$accion}";
            return;
        }

        call_user_func_array([$controlador, $accion], $parametros);
    }
}
