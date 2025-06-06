<?php
namespace App\Api;

use App\Config\JWTService;

/**
 * Verifica JWT.  Si $role !== null, chequea que el usuario tenga ese rol.
 * Retorna el payload decodificado.
 */
function require_auth(string $role = null): array
{
    $headers = getallheaders();
    $auth    = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (!preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
        send_json(['error' => 'Token no provisto'], 401);
    }

    $jwt     = $matches[1];
    $payload = JWTService::validateToken($jwt);

    if ($payload === null) {
        send_json(['error' => 'Token inválido o expirado'], 401);
    }

    if ($role !== null && (($payload['role'] ?? null) !== $role)) {
        send_json(['error' => 'Sin permisos suficientes'], 403);
    }

    return $payload;
}
