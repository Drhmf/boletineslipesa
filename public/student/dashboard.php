<?php
require_once __DIR__ . '/../../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Boletín Estudiantil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="/student/assets/print.css" media="print">
</head>
<body class="bg-gray-100 py-8">
  <main id="bulletinContainer"
        class="max-w-4xl mx-auto bg-white shadow-lg rounded-2xl p-8">
    <p class="text-center text-gray-500">Cargando boletín...</p>
  </main>

  <script type="module" src="/student/assets/bulletin.js"></script>
</body>
</html>
