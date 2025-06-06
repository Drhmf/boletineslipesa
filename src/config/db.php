<?php
// Conexión a la BD (PDO)
namespace App\Config;

use PDO;
use PDOException;
use App\Config\Env;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = Env::get('DB_HOST', 'localhost');
            $db   = Env::get('DB_NAME', 'boletines');
            $user = Env::get('DB_USER', 'root');
            $pass = Env::get('DB_PASS', '');
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                die(json_encode([
                    'error'   => 'No se pudo conectar a la base de datos',
                    'detalle' => $e->getMessage(),
                ]));
            }
        }

        return self::$instance;
    }
}
