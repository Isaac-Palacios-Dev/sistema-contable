<?php

namespace App\Models;

use App\Core\Model;

class Cuenta extends Model
{
    protected string $tabla = 'cuentas';

    public const TIPOS = ['activo', 'pasivo', 'capital', 'ingreso', 'gasto'];
    public const NATURALEZAS = ['deudora', 'acreedora'];

    public const ETIQUETAS_TIPO = [
        'activo' => 'Activo',
        'pasivo' => 'Pasivo',
        'capital' => 'Capital',
        'ingreso' => 'Ingreso',
        'gasto' => 'Gasto',
    ];

    public function activas(): array
    {
        return $this->db
            ->query('SELECT * FROM cuentas WHERE activa = 1 ORDER BY codigo')
            ->fetchAll();
    }

    public function codigoExiste(string $codigo, ?int $ignorarId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM cuentas WHERE codigo = ?';
        $parametros = [$codigo];
        if ($ignorarId !== null) {
            $sql .= ' AND id != ?';
            $parametros[] = $ignorarId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function tieneMovimientos(int $cuentaId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM asiento_detalles WHERE cuenta_id = ?');
        $stmt->execute([$cuentaId]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
