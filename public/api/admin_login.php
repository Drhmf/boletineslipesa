<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Config\JWTService;
use App\Api\{send_json, get_json_input};
use App\Services\CaptchaService;

$input    = get_json_input();
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
$token    = $input['captcha_token'] ?? '';

if ($username === '' || $password === '' || $token === '') {
    send_json(['error' => 'Datos incompletos'], 400);
}

if (!CaptchaService::verify($token, 'admin_login')) {
    send_json(['error' => 'Captcha inválido'], 400);
}

$db   = Database::getConnection();
$stmt = $db->prepare('SELECT id, password_hash FROM admins WHERE username = :user');
$stmt->execute([':user' => $username]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($password, $admin['password_hash'])) {
    send_json(['error' => 'Credenciales inválidas'], 401);
}

$jwt = JWTService::generateToken([
    'sub'  => $admin['id'],
    'role' => 'admin',
]);

send_json(['token' => $jwt]);
