<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cuenta;

class CuentaController extends Controller
{
    public function index(): void
    {
        $this->exigirSesion();
        $cuentas = new Cuenta();

        $this->vista('cuentas/index', [
            'titulo' => 'Catálogo de cuentas',
            'cuentas' => $cuentas->todos('codigo'),
        ]);
    }

    public function crearForm(): void
    {
        $this->exigirSesion();
        $this->vista('cuentas/form', [
            'titulo' => 'Nueva cuenta',
            'cuenta' => null,
            'accion' => '/cuentas',
        ]);
    }

    public function crear(): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        [$datos, $errores] = $this->validar($_POST);
        $cuentas = new Cuenta();

        if ($cuentas->codigoExiste($datos['codigo'])) {
            $errores[] = 'Ya existe una cuenta con ese código.';
        }

        if ($errores) {
            $this->flash('error', implode(' ', $errores));
            $this->redirigir('/cuentas/nueva');
        }

        $cuentas->crear($datos);
        $this->flash('exito', 'Cuenta creada correctamente.');
        $this->redirigir('/cuentas');
    }

    public function editarForm(string $id): void
    {
        $this->exigirSesion();
        $cuentas = new Cuenta();
        $cuenta = $cuentas->buscar((int) $id);

        if (!$cuenta) {
            $this->flash('error', 'Cuenta no encontrada.');
            $this->redirigir('/cuentas');
        }

        $this->vista('cuentas/form', [
            'titulo' => 'Editar cuenta',
            'cuenta' => $cuenta,
            'accion' => '/cuentas/' . $id,
        ]);
    }

    public function actualizar(string $id): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        [$datos, $errores] = $this->validar($_POST);
        $cuentas = new Cuenta();

        if ($cuentas->codigoExiste($datos['codigo'], (int) $id)) {
            $errores[] = 'Ya existe otra cuenta con ese código.';
        }

        if ($errores) {
            $this->flash('error', implode(' ', $errores));
            $this->redirigir('/cuentas/' . $id . '/editar');
        }

        $cuentas->actualizar((int) $id, $datos);
        $this->flash('exito', 'Cuenta actualizada correctamente.');
        $this->redirigir('/cuentas');
    }

    public function desactivar(string $id): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        $cuentas = new Cuenta();
        $cuentas->actualizar((int) $id, ['activa' => 0]);
        $this->flash('exito', 'Cuenta desactivada.');
        $this->redirigir('/cuentas');
    }

    private function validar(array $datos): array
    {
        $errores = [];

        $codigo = trim($datos['codigo'] ?? '');
        $nombre = trim($datos['nombre'] ?? '');
        $tipo = $datos['tipo'] ?? '';
        $naturaleza = $datos['naturaleza'] ?? '';

        if ($codigo === '') {
            $errores[] = 'El código es obligatorio.';
        }
        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio.';
        }
        if (!in_array($tipo, Cuenta::TIPOS, true)) {
            $errores[] = 'Seleccioná un tipo de cuenta válido.';
        }
        if (!in_array($naturaleza, Cuenta::NATURALEZAS, true)) {
            $errores[] = 'Seleccioná una naturaleza válida.';
        }

        return [
            ['codigo' => $codigo, 'nombre' => $nombre, 'tipo' => $tipo, 'naturaleza' => $naturaleza, 'activa' => 1],
            $errores,
        ];
    }
}
