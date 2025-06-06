<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Database;
use App\Config\JWTService;
use App\Api\{send_json, get_json_input};
use App\Services\CaptchaService;

$input  = get_json_input();
$sigerd = $input['sigerd_id'] ?? '';
$token  = $input['captcha_token'] ?? '';

if ($sigerd === '' || $token === '') {
    send_json(['error' => 'Datos incompletos'], 400);
}

if (!CaptchaService::verify($token, 'student_login')) {
    send_json(['error' => 'Captcha inválido'], 400);
}

$db   = Database::getConnection();
$stmt = $db->prepare('SELECT sigerd_id FROM estudiantes WHERE sigerd_id = :id');
$stmt->execute([':id' => $sigerd]);

if (!$stmt->fetch()) {
    send_json(['error' => 'Estudiante no encontrado'], 404);
}

$jwt = JWTService::generateToken([
    'sub'  => $sigerd,
    'role' => 'student',
]);

send_json(['token' => $jwt]);
