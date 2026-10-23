<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function mostrarLogin(): void
    {
        if (!empty($_SESSION['usuario_id'])) {
            $this->redirigir('/');
        }
        $this->vista('auth/login', ['titulo' => 'Iniciar sesión']);
    }

    public function login(): void
    {
        $this->validarCsrf();

        $correo = trim($_POST['correo'] ?? '');
        $clave = $_POST['clave'] ?? '';

        $usuarios = new Usuario();
        $usuario = $usuarios->verificarClave($correo, $clave);

        if (!$usuario) {
            $this->flash('error', 'Correo o contraseña incorrectos.');
            $this->redirigir('/login');
        }

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $this->redirigir('/');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirigir('/login');
    }
}
