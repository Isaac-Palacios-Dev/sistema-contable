<?php

namespace App\Core;

/**
 * Controlador base: trae helpers comunes a todos los controladores
 * (renderizar vistas, responder JSON, redirigir, exigir sesión iniciada).
 */
abstract class Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function vista(string $vista, array $datos = []): void
    {
        extract($datos);
        $rutaVista = __DIR__ . '/../Views/' . $vista . '.php';

        if (!file_exists($rutaVista)) {
            http_response_code(500);
            die("Vista no encontrada: {$vista}");
        }

        require __DIR__ . '/../Views/layout.php';
    }

    protected function json(array $datos, int $codigo = 200): void
    {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    protected function redirigir(string $ruta): void
    {
        header('Location: ' . Config::base() . $ruta);
        exit;
    }

    protected function exigirSesion(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            $this->flash('error', 'Iniciá sesión para continuar.');
            $this->redirigir('/login');
        }
    }

    public function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validarCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(419);
            die('Token de seguridad inválido. Volvé a cargar el formulario.');
        }
    }

    protected function flash(string $tipo, string $mensaje): void
    {
        $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    }
}
