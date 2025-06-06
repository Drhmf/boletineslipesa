<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Api\{send_json};
use App\Api\{require_auth};
use App\Services\AttendanceService;

require_auth('admin');

$modalityId = (int)($_GET['modality_id'] ?? 0);
$gradeId    = (int)($_GET['grade_id'] ?? 0);
$sectionId  = (int)($_GET['section_id'] ?? 0);
$subjectId  = (int)($_GET['subject_id'] ?? 0);
$period     = (int)($_GET['period'] ?? 0);

if (!$modalityId || !$gradeId || !$sectionId || !$subjectId || !$period) {
    send_json(['error' => 'Parámetros incompletos'], 400);
}

try {
    $list = AttendanceService::list($modalityId, $gradeId, $sectionId, $subjectId, $period);
    send_json($list);
} catch (\Throwable $th) {
    send_json(['error' => $th->getMessage()], 500);
}
