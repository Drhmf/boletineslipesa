<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Api\{send_json};
use App\Api\{require_auth};
use App\Config\Database;

require_auth('admin');

$db = Database::getConnection();

$options = [
    'modalidades' => $db->query('SELECT id, nombre FROM modalidades')->fetchAll(PDO::FETCH_ASSOC),
    'grados'      => $db->query('SELECT id, nombre, modalidad_id FROM grados')->fetchAll(PDO::FETCH_ASSOC),
    'secciones'   => $db->query('SELECT id, nombre, grado_id FROM secciones')->fetchAll(PDO::FETCH_ASSOC),
    'asignaturas' => $db->query('SELECT id, nombre, modalidad_id FROM asignaturas')->fetchAll(PDO::FETCH_ASSOC),
];

send_json($options);
