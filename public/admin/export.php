<?php
require_once __DIR__ . '/../../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Exportar Boletines</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<header class="bg-indigo-700 text-white p-4">
  <h1 class="text-2xl font-bold">Exportar Boletines</h1>
</header>
<main class="p-6">
  <div class="mb-4">
    <input type="text" id="sigerd" placeholder="ID SIGERD"
           class="border rounded-md p-2 w-64">
    <button id="btnExport" class="bg-green-600 text-white px-4 py-2 rounded-md ml-2">
      Generar PDF
    </button>
  </div>
</main>

<script type="module">
const jwt = localStorage.getItem('jwt');
if (!jwt) { window.location.href='/admin/index.php'; }
document.getElementById('btnExport').addEventListener('click', () => {
  const id = document.getElementById('sigerd').value.trim();
  if(!id){alert('Ingrese ID');return;}
  fetch(`/api/export_bulletins.php?sigerd_id=${id}`,{headers:{Authorization:'Bearer '+jwt}})
    .then(r=>r.json().then(d=>({ok:r.ok,data:d})))
    .then(({ok,data})=>{
      if(!ok)throw new Error(data.error);
      window.open(data.url,'_blank');
    })
    .catch(err=>alert(err.message));
});
</script>
</body>
</html>
