<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Asiento;
use App\Models\Cuenta;

class ReporteController extends Controller
{
    public function balanceComprobacion(): void
    {
        $this->exigirSesion();
        $hasta = $_GET['hasta'] ?? date('Y-m-d');

        $asientos = new Asiento();
        $filas = $asientos->balanceComprobacion($hasta);

        $totales = [
            'debe' => array_sum(array_column($filas, 'total_debe')),
            'haber' => array_sum(array_column($filas, 'total_haber')),
        ];

        $this->vista('reportes/balance_comprobacion', [
            'titulo' => 'Balance de comprobación',
            'filas' => $filas,
            'totales' => $totales,
            'hasta' => $hasta,
        ]);
    }

    public function mayor(): void
    {
        $this->exigirSesion();
        $cuentas = new Cuenta();
        $asientos = new Asiento();

        $cuentaId = isset($_GET['cuenta_id']) ? (int) $_GET['cuenta_id'] : null;
        $movimientos = [];
        $saldo = 0;

        if ($cuentaId) {
            $movimientos = $asientos->mayorDeCuenta($cuentaId);
            $cuentaActual = $cuentas->buscar($cuentaId);
            $acumulado = 0;
            foreach ($movimientos as &$mov) {
                $signo = $cuentaActual['naturaleza'] === 'deudora' ? 1 : -1;
                $acumulado += $signo * ($mov['debe'] - $mov['haber']);
                $mov['saldo_acumulado'] = $acumulado;
            }
            unset($mov);
            $saldo = $acumulado;
        }

        $this->vista('reportes/mayor', [
            'titulo' => 'Libro mayor',
            'cuentas' => $cuentas->activas(),
            'cuentaId' => $cuentaId,
            'movimientos' => $movimientos,
            'saldo' => $saldo,
        ]);
    }

    public function estadoResultados(): void
    {
        $this->exigirSesion();
        $desde = $_GET['desde'] ?? date('Y-m-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-d');

        $asientos = new Asiento();
        $resultado = $asientos->estadoResultados($desde, $hasta);

        $this->vista('reportes/estado_resultados', [
            'titulo' => 'Estado de resultados',
            'desde' => $desde,
            'hasta' => $hasta,
            'resultado' => $resultado,
        ]);
    }
}
