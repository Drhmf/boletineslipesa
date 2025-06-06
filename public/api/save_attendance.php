<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Api\{send_json, get_json_input};
use App\Api\{require_auth};
use App\Services\AttendanceService;

require_auth('admin');

$input     = get_json_input();
$subjectId = (int)($input['subject_id'] ?? 0);
$period    = (int)($input['period'] ?? 0);
$records   = $input['records'] ?? [];

if (!$subjectId || !$period || !is_array($records)) {
    send_json(['error' => 'Datos incompletos'], 400);
}

try {
    AttendanceService::save($subjectId, $period, $records);
    send_json(['ok' => true]);
} catch (\Throwable $th) {
    send_json(['error' => $th->getMessage()], 500);
}
