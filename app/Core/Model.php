<?php

namespace App\Core;

use PDO;

/**
 * Modelo base con operaciones CRUD genéricas sobre PDO.
 * Cada modelo hijo solo define $tabla (y opcionalmente $llave).
 */
abstract class Model
{
    protected string $tabla;
    protected string $llave = 'id';
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::conectar();
    }

    public function todos(string $orden = ''): array
    {
        $sql = "SELECT * FROM {$this->tabla}";
        if ($orden !== '') {
            $sql .= " ORDER BY {$orden}";
        }
        return $this->db->query($sql)->fetchAll();
    }

    public function buscar($id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tabla} WHERE {$this->llave} = ?");
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function crear(array $datos): string
    {
        $columnas = array_keys($datos);
        $marcadores = array_map(fn($c) => ':' . $c, $columnas);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->tabla,
            implode(', ', $columnas),
            implode(', ', $marcadores)
        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute($datos);
        return $this->db->lastInsertId();
    }

    public function actualizar($id, array $datos): bool
    {
        $asignaciones = implode(', ', array_map(fn($c) => "{$c} = :{$c}", array_keys($datos)));
        $sql = "UPDATE {$this->tabla} SET {$asignaciones} WHERE {$this->llave} = :__id";
        $datos['__id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($datos);
    }

    public function eliminar($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->tabla} WHERE {$this->llave} = ?");
        return $stmt->execute([$id]);
    }
}
