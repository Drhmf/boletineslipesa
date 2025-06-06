<?php
// Borra el JWT del navegador y redirige al inicio.
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cerrar Sesión</title>
  <script>
    localStorage.removeItem('jwt');
    window.location.href = '/';
  </script>
</head>
<body></body>
</html>
