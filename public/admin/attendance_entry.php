<?php
require_once __DIR__ . '/../../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Asistencia</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <header class="bg-indigo-700 text-white p-4">
    <h1 class="text-2xl font-bold">Registrar Asistencia</h1>
  </header>

  <main class="p-6" id="attendanceRoot">
    <section class="bg-white shadow rounded-xl p-4 mb-6">
      <h2 class="font-semibold mb-4">Filtros</h2>
      <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <div>
          <label class="block text-sm font-medium mb-1">Modalidad</label>
          <select id="selModalidad" class="w-full border rounded-md p-2"></select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Grado</label>
          <select id="selGrado" class="w-full border rounded-md p-2"></select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Sección</label>
          <select id="selSeccion" class="w-full border rounded-md p-2"></select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Asignatura</label>
          <select id="selAsignatura" class="w-full border rounded-md p-2"></select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Periodo</label>
          <select id="selPeriodo" class="w-full border rounded-md p-2">
            <option value="1">Periodo 1</option>
            <option value="2">Periodo 2</option>
            <option value="3">Periodo 3</option>
            <option value="4">Periodo 4</option>
          </select>
        </div>
      </div>
      <div class="mt-4">
        <button id="btnCargar" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
          Cargar Estudiantes
        </button>
      </div>
    </section>

    <section id="tableSection" class="hidden bg-white shadow rounded-xl p-4">
      <form id="attendanceForm">
        <div class="overflow-auto">
          <table class="min-w-full border-collapse">
            <thead>
              <tr class="bg-gray-200 text-sm">
                <th class="border px-2 py-1">SIGERD</th>
                <th class="border px-2 py-1">Nombre</th>
                <th class="border px-2 py-1">Asistencia (%)</th>
              </tr>
            </thead>
            <tbody id="studentRows"></tbody>
          </table>
        </div>
        <div class="mt-4 text-center">
          <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">
            Guardar Asistencias
          </button>
        </div>
      </form>
    </section>
  </main>

  <script type="module" src="/admin/assets/attendance_entry.js"></script>
</body>
</html>
