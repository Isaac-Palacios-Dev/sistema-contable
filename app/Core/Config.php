<?php

namespace App\Core;

/**
 * Acceso rápido a la configuración cargada en config/config.php,
 * sin tener que volver a leer el .env en cada archivo.
 */
class Config
{
    private static ?array $datos = null;

    private static function cargar(): array
    {
        if (self::$datos === null) {
            self::$datos = require __DIR__ . '/../../config/config.php';
        }
        return self::$datos;
    }

    public static function get(string $clave, $defecto = null)
    {
        $partes = explode('.', $clave);
        $valor = self::cargar();
        foreach ($partes as $parte) {
            if (!is_array($valor) || !array_key_exists($parte, $valor)) {
                return $defecto;
            }
            $valor = $valor[$parte];
        }
        return $valor;
    }

    public static function base(): string
    {
        return self::get('app.base', '');
    }
}
