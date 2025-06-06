<?php
$file = sys_get_temp_dir() . '/' . basename($_GET['file'] ?? '');
if (!is_file($file)) {
    http_response_code(404);
    echo 'Archivo no encontrado';
    exit;
}
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="boletin.pdf"');
readfile($file);
unlink($file);
