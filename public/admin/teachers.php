<?php
require_once __DIR__ . '/../../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Docentes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <header class="bg-indigo-700 text-white p-4">
    <h1 class="text-2xl font-bold">Gestión de Docentes</h1>
  </header>

  <main class="p-6">
    <div class="mb-4">
      <button id="btnAdd" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
        Nuevo Docente
      </button>
    </div>

    <div class="overflow-auto bg-white shadow rounded-xl">
      <table class="min-w-full border-collapse" id="tblTeachers">
        <thead class="bg-gray-200 text-sm">
          <tr>
            <th class="border px-2 py-1">Nombre</th>
            <th class="border px-2 py-1">Cédula</th>
            <th class="border px-2 py-1">Teléfono</th>
            <th class="border px-2 py-1">Asignatura</th>
            <th class="border px-2 py-1">Grado</th>
            <th class="border px-2 py-1"></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </main>

  <script type="module" src="/admin/assets/teachers.js"></script>
</body>
</html>
