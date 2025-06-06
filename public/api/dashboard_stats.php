<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Api\{send_json};
use App\Api\{require_auth};
use App\Services\DashboardService;

require_auth('admin');

try {
    $stats = DashboardService::getStats();
    send_json($stats);
} catch (\Throwable $th) {
    send_json(['error' => $th->getMessage()], 500);
}
