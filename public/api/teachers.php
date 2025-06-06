<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Api\{send_json, get_json_input};
use App\Api\{require_auth};
use App\Services\TeacherService;

require_auth('admin');
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            send_json(TeacherService::list());
            break;
        case 'POST':
            $data = get_json_input();
            TeacherService::create($data);
            send_json(['ok' => true], 201);
            break;
        case 'PUT':
            parse_str(file_get_contents('php://input'), $putData);
            $id = (int)($putData['id'] ?? 0);
            if (!$id) send_json(['error' => 'ID requerido'], 400);
            TeacherService::update($id, $putData);
            send_json(['ok' => true]);
            break;
        case 'DELETE':
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) send_json(['error' => 'ID requerido'], 400);
            TeacherService::delete($id);
            send_json(['ok' => true]);
            break;
        default:
            send_json(['error' => 'Método no permitido'], 405);
    }
} catch (\Throwable $th) {
    send_json(['error' => $th->getMessage()], 500);
}
