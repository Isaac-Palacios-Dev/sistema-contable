<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Asiento;
use App\Models\Cuenta;

class AsientoController extends Controller
{
    public function index(): void
    {
        $this->exigirSesion();
        $asientos = new Asiento();

        $this->vista('asientos/index', [
            'titulo' => 'Asientos contables',
            'asientos' => $asientos->listarConTotales(),
        ]);
    }

    public function ver(string $id): void
    {
        $this->exigirSesion();
        $asientos = new Asiento();
        $asiento = $asientos->conLineas((int) $id);

        if (!$asiento) {
            $this->flash('error', 'Asiento no encontrado.');
            $this->redirigir('/asientos');
        }

        $this->vista('asientos/show', ['titulo' => 'Asiento #' . $asiento['numero'], 'asiento' => $asiento]);
    }

    public function crearForm(): void
    {
        $this->exigirSesion();
        $asientos = new Asiento();
        $cuentas = new Cuenta();

        $this->vista('asientos/form', [
            'titulo' => 'Nuevo asiento contable',
            'cuentas' => $cuentas->activas(),
            'siguienteNumero' => $asientos->siguienteNumero(),
        ]);
    }

    public function crear(): void
    {
        $this->exigirSesion();
        $this->validarCsrf();

        $fecha = $_POST['fecha'] ?? '';
        $concepto = trim($_POST['concepto'] ?? '');
        $cuentaIds = $_POST['cuenta_id'] ?? [];
        $debes = $_POST['debe'] ?? [];
        $haberes = $_POST['haber'] ?? [];

        if ($fecha === '' || $concepto === '') {
            $this->flash('error', 'La fecha y el concepto son obligatorios.');
            $this->redirigir('/asientos/nuevo');
        }

        $lineas = [];
        foreach ($cuentaIds as $i => $cuentaId) {
            if ($cuentaId === '') {
                continue;
            }
            $lineas[] = [
                'cuenta_id' => (int) $cuentaId,
                'debe' => (float) ($debes[$i] ?? 0),
                'haber' => (float) ($haberes[$i] ?? 0),
            ];
        }

        if (count($lineas) < 2) {
            $this->flash('error', 'Un asiento necesita al menos dos líneas (una de debe y una de haber).');
            $this->redirigir('/asientos/nuevo');
        }

        $asientos = new Asiento();

        try {
            $asientos->crearConLineas([
                'numero' => $asientos->siguienteNumero(),
                'fecha' => $fecha,
                'concepto' => $concepto,
                'usuario_id' => $_SESSION['usuario_id'],
            ], $lineas);
        } catch (\InvalidArgumentException $e) {
            $this->flash('error', $e->getMessage());
            $this->redirigir('/asientos/nuevo');
        }

        $this->flash('exito', 'Asiento registrado correctamente.');
        $this->redirigir('/asientos');
    }
}
