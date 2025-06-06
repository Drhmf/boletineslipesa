<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Api\{send_json, get_json_input};
use App\Api\{require_auth};
use App\Services\GradeService;

try {
    require_auth('admin');

    $input = get_json_input();
    $subjectId = (int)($input['subject_id'] ?? 0);
    $period    = (int)($input['period'] ?? 0);
    $grades    = $input['grades'] ?? [];

    if (!$subjectId || !$period || !is_array($grades)) {
        send_json(['error' => 'Datos incompletos'], 400);
    }

    GradeService::saveGrades($subjectId, $period, $grades);
    send_json(['ok' => true]);
} catch (\Throwable $th) {
    send_json(['error' => $th->getMessage()], 500);
}
