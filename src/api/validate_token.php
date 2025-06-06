<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Api\{send_json};
use App\Api\{require_auth};

/* Devuelve el payload del JWT (útil para debug). */
$payload = require_auth();   // acepta cualquier rol
send_json($payload);
