<?php

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * Asientos contables: cabecera + líneas en partida doble.
 * Aquí vive la lógica de negocio contable (mayor, balance, estado de resultados).
 */
class Asiento extends Model
{
    protected string $tabla = 'asientos';

    public function siguienteNumero(): int
    {
        $max = (int) $this->db->query('SELECT COALESCE(MAX(numero), 0) FROM asientos')->fetchColumn();
        return $max + 1;
    }

    /**
     * Crea el asiento y sus líneas dentro de una transacción.
     * $lineas = [['cuenta_id' => int, 'debe' => float, 'haber' => float], ...]
     * Lanza una excepción si debe != haber (no deja asientos descuadrados).
     */
    public function crearConLineas(array $cabecera, array $lineas): int
    {
        $totalDebe = array_sum(array_column($lineas, 'debe'));
        $totalHaber = array_sum(array_column($lineas, 'haber'));

        if (round($totalDebe, 2) !== round($totalHaber, 2)) {
            throw new \InvalidArgumentException(
                "El asiento no cuadra: debe {$totalDebe}, haber {$totalHaber}."
            );
        }
        if ($totalDebe == 0) {
            throw new \InvalidArgumentException('El asiento no puede tener todas las líneas en cero.');
        }

        $this->db->beginTransaction();
        try {
            $idAsiento = $this->crear($cabecera);

            $stmt = $this->db->prepare(
                'INSERT INTO asiento_detalles (asiento_id, cuenta_id, debe, haber) VALUES (?, ?, ?, ?)'
            );
            foreach ($lineas as $linea) {
                if ((float) $linea['debe'] == 0 && (float) $linea['haber'] == 0) {
                    continue;
                }
                $stmt->execute([$idAsiento, $linea['cuenta_id'], $linea['debe'], $linea['haber']]);
            }

            $this->db->commit();
            return (int) $idAsiento;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function conLineas(int $id): ?array
    {
        $asiento = $this->buscar($id);
        if (!$asiento) {
            return null;
        }

        $stmt = $this->db->prepare(
            'SELECT d.*, c.codigo, c.nombre AS cuenta_nombre
             FROM asiento_detalles d
             JOIN cuentas c ON c.id = d.cuenta_id
             WHERE d.asiento_id = ?
             ORDER BY d.id'
        );
        $stmt->execute([$id]);
        $asiento['lineas'] = $stmt->fetchAll();

        return $asiento;
    }

    public function listarConTotales(): array
    {
        return $this->db->query(
            'SELECT a.*, u.nombre AS usuario_nombre,
                    COALESCE(SUM(d.debe), 0) AS total
             FROM asientos a
             JOIN usuarios u ON u.id = a.usuario_id
             LEFT JOIN asiento_detalles d ON d.asiento_id = a.id
             GROUP BY a.id
             ORDER BY a.fecha DESC, a.numero DESC'
        )->fetchAll();
    }

    /** Libro mayor de una cuenta: movimientos y saldo acumulado. */
    public function mayorDeCuenta(int $cuentaId): array
    {
        $stmt = $this->db->prepare(
            'SELECT a.fecha, a.numero, a.concepto, d.debe, d.haber
             FROM asiento_detalles d
             JOIN asientos a ON a.id = d.asiento_id
             WHERE d.cuenta_id = ?
             ORDER BY a.fecha, a.numero'
        );
        $stmt->execute([$cuentaId]);
        return $stmt->fetchAll();
    }

    /** Balance de comprobación: suma de debe/haber y saldo por cuenta, a una fecha de corte. */
    public function balanceComprobacion(?string $hasta = null): array
    {
        // Los movimientos se filtran por fecha en una subconsulta antes de unirlos
        // a cuentas; así una cuenta sin movimientos en el rango sale en 0, en vez
        // de arrastrar movimientos posteriores a la fecha de corte.
        $sql = 'SELECT c.id, c.codigo, c.nombre, c.tipo, c.naturaleza,
                       COALESCE(SUM(m.debe), 0) AS total_debe,
                       COALESCE(SUM(m.haber), 0) AS total_haber
                FROM cuentas c
                LEFT JOIN (
                    SELECT d.cuenta_id, d.debe, d.haber
                    FROM asiento_detalles d
                    JOIN asientos a ON a.id = d.asiento_id'
                    . ($hasta ? ' WHERE a.fecha <= ?' : '') . '
                ) m ON m.cuenta_id = c.id
                GROUP BY c.id
                ORDER BY c.codigo';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($hasta ? [$hasta] : []);
        $filas = $stmt->fetchAll();

        foreach ($filas as &$fila) {
            $saldo = $fila['naturaleza'] === 'deudora'
                ? $fila['total_debe'] - $fila['total_haber']
                : $fila['total_haber'] - $fila['total_debe'];
            $fila['saldo'] = $saldo;
        }

        return $filas;
    }

    /** Estado de resultados simplificado: ingresos - gastos, en un rango de fechas. */
    public function estadoResultados(string $desde, string $hasta): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.codigo, c.nombre, c.tipo,
                    COALESCE(SUM(d.debe), 0) AS total_debe,
                    COALESCE(SUM(d.haber), 0) AS total_haber
             FROM cuentas c
             JOIN asiento_detalles d ON d.cuenta_id = c.id
             JOIN asientos a ON a.id = d.asiento_id
             WHERE c.tipo IN ('ingreso', 'gasto') AND a.fecha BETWEEN ? AND ?
             GROUP BY c.id
             ORDER BY c.tipo, c.codigo"
        );
        $stmt->execute([$desde, $hasta]);
        $filas = $stmt->fetchAll();

        $ingresos = [];
        $gastos = [];
        $totalIngresos = 0;
        $totalGastos = 0;

        foreach ($filas as $fila) {
            if ($fila['tipo'] === 'ingreso') {
                $monto = $fila['total_haber'] - $fila['total_debe'];
                $ingresos[] = ['codigo' => $fila['codigo'], 'nombre' => $fila['nombre'], 'monto' => $monto];
                $totalIngresos += $monto;
            } else {
                $monto = $fila['total_debe'] - $fila['total_haber'];
                $gastos[] = ['codigo' => $fila['codigo'], 'nombre' => $fila['nombre'], 'monto' => $monto];
                $totalGastos += $monto;
            }
        }

        return [
            'ingresos' => $ingresos,
            'gastos' => $gastos,
            'total_ingresos' => $totalIngresos,
            'total_gastos' => $totalGastos,
            'utilidad' => $totalIngresos - $totalGastos,
        ];
    }
}
