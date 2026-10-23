<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Asiento;
use App\Models\Cuenta;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->exigirSesion();

        $asientos = new Asiento();
        $cuentas = new Cuenta();

        $balance = $asientos->balanceComprobacion();
        $totalActivos = array_sum(array_map(
            fn($c) => $c['tipo'] === 'activo' ? $c['saldo'] : 0,
            $balance
        ));
        $totalPasivos = array_sum(array_map(
            fn($c) => $c['tipo'] === 'pasivo' ? $c['saldo'] : 0,
            $balance
        ));

        $hoy = date('Y-m-d');
        $inicioMes = date('Y-m-01');
        $resultadoMes = $asientos->estadoResultados($inicioMes, $hoy);

        $this->vista('dashboard/index', [
            'titulo' => 'Panel principal',
            'totalCuentas' => count($cuentas->activas()),
            'totalAsientos' => count($asientos->todos()),
            'totalActivos' => $totalActivos,
            'totalPasivos' => $totalPasivos,
            'utilidadMes' => $resultadoMes['utilidad'],
            'ultimosAsientos' => array_slice($asientos->listarConTotales(), 0, 6),
        ]);
    }
}
