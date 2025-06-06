<?php
namespace App\Config;

use Dotenv\Dotenv;

class Env
{
    private static array $cache = [];

    public static function get(string $key, ?string $default = null): ?string
    {
        if (!self::$cache) {
            $path = dirname(__DIR__, 2);
            if (file_exists($path . '/.env')) {
                $dotenv = Dotenv::createImmutable($path);
                $dotenv->safeLoad();
            }
            self::$cache = $_ENV + $_SERVER;
        }
        return self::$cache[$key] ?? $default;
    }
}
