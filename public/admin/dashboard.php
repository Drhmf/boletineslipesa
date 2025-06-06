<?php
require_once __DIR__ . '/../../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard • Boletines</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
  <header class="bg-indigo-700 text-white p-4">
    <h1 class="text-2xl font-bold">Panel de Control — Boletines</h1>
  </header>

  <main class="flex-grow p-6" id="dashRoot">
    <div class="grid gap-6 grid-cols-1 md:grid-cols-2">
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-2">Aprobados vs Reprobados</h2>
        <canvas id="piePassFail" height="200"></canvas>
      </div>

      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-2">Estudiantes por Grado</h2>
        <canvas id="barStudentsGrade" height="200"></canvas>
      </div>

      <div class="bg-white rounded-xl shadow p-4 md:col-span-2">
        <h2 class="font-semibold mb-2">Promedio de Calificaciones por Periodo</h2>
        <canvas id="lineAvgPeriod" height="120"></canvas>
      </div>
    </div>

    <div class="mt-8 text-center">
      <a href="/admin/grade_entry.php"
         class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
        Registrar Calificaciones
      </a>
    </div>
  </main>

  <script type="module" src="/admin/assets/dashboard.js"></script>
</body>
</html>
