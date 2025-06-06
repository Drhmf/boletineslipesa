<?php
namespace App\Config;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    private const ALGORITHM  = 'HS256';
    private const TTL        = 60 * 60 * 4; // 4 horas

    private static function secret(): string
    {
        return Env::get('JWT_SECRET', 'Hector402#');
    }

    public static function generateToken(array $payload): string
    {
        $issuedAt   = time();
        $expiration = $issuedAt + self::TTL;

        $tokenPayload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expiration,
        ]);

        return JWT::encode($tokenPayload, self::secret(), self::ALGORITHM);
    }

    public static function validateToken(string $jwt): ?array
    {
        try {
            $decoded = JWT::decode($jwt, new Key(self::secret(), self::ALGORITHM));
            return (array) $decoded;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
