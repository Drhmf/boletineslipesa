<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Api\{send_json};
use App\Api\{require_auth};
use App\Services\BulletinService;

try {
    $payload = require_auth('student');      // sólo estudiantes
    $sigerd  = $payload['sub'] ?? '';

    if ($sigerd === '') {
        send_json(['error' => 'Token sin identificador de estudiante'], 400);
    }

    $bulletin = BulletinService::getBulletin($sigerd);
    send_json($bulletin);
} catch (\Throwable $th) {
    send_json(['error' => $th->getMessage()], 500);
}
