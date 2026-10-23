<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Conexión única (singleton) a la base de datos vía PDO.
 * Lee la configuración desde config/config.php, que a su vez lee el .env.
 */
class Database
{
    private static ?PDO $conexion = null;

    public static function conectar(): PDO
    {
        if (self::$conexion === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $db['host'],
                $db['port'],
                $db['name']
            );

            try {
                self::$conexion = new PDO($dsn, $db['user'], $db['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                die(
                    '<div style="font-family:sans-serif;padding:2rem;color:#b00">'
                    . '<h2>No se pudo conectar a la base de datos</h2>'
                    . '<p>Revisa que MySQL esté corriendo en Laragon y que el archivo .env tenga los datos correctos.</p>'
                    . '<p><small>' . htmlspecialchars($e->getMessage()) . '</small></p>'
                    . '</div>'
                );
            }
        }

        return self::$conexion;
    }
}
