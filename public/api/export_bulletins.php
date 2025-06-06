<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Api\{send_json};
use App\Api\{require_auth};
use App\Services\BulletinService;
use App\Services\PDFService;

require_auth('admin');

$sigerd = $_GET['sigerd_id'] ?? '';
if (!$sigerd) {
    send_json(['error' => 'Falta sigerd_id'], 400);
}

try {
    $data  = BulletinService::getBulletin($sigerd);
    $html  = PDFService::generateBulletinHtml($data);
    $file  = PDFService::createPdf($html);
    $link  = '/download.php?file=' . basename($file);
    send_json(['url' => $link]);
} catch (\Throwable $th) {
    send_json(['error' => $th->getMessage()], 500);
}
