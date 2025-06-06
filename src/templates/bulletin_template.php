<?php
/* Variables disponibles:
   $center, $student, $subjects, $status, $signatories */
?>
<style>
body{font-family: DejaVu Sans, sans-serif;font-size:12px}
table{width:100%;border-collapse:collapse}
th,td{border:1px solid #999;padding:4px;text-align:center}
</style>
<h2 style="text-align:center"><?= htmlspecialchars($center['name']) ?></h2>
<p style="text-align:center">Código: <?= $center['code'] ?> — <?= htmlspecialchars($center['address']) ?></p>
<h3 style="text-align:center">Boletín de Calificaciones</h3>
<p><strong>Estudiante:</strong> <?= htmlspecialchars($student['nombres']) ?> <?= htmlspecialchars($student['apellidos']) ?></p>
<p><strong>ID SIGERD:</strong> <?= $student['sigerd_id'] ?></p>
<p><strong>Modalidad:</strong> <?= htmlspecialchars($student['modalidad']) ?></p>
<p><strong>Grado y Sección:</strong> <?= $student['grado'] ?> — <?= $student['seccion'] ?></p>

<table>
  <thead>
    <tr>
      <th>Asignatura</th><th>P1</th><th>P2</th><th>P3</th><th>P4</th><th>Final</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($subjects as $s): ?>
    <tr>
      <td style="text-align:left"><?= htmlspecialchars($s['asignatura']) ?></td>
      <?php for($p=1;$p<=4;$p++):
            $note=$s['periodos'][$p]??''; ?>
        <td><?= $note ?></td>
      <?php endfor; ?>
      <td style="font-weight:bold;
        color:<?= $s['final']>=70?'green':'red' ?>"><?= $s['final'] ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p style="margin-top:10px"><strong>Estado:</strong> <?= $status ?></p>

<br><br>
<table style="border:none">
  <tr style="border:none">
    <td style="border:none;text-align:center">
      ___________________________<br>
      <?= htmlspecialchars($signatories['director']) ?><br>Directora
    </td>
    <td style="border:none;text-align:center">
      ___________________________<br>
      <?= htmlspecialchars($signatories['registrar']) ?><br>Registro &amp; Control Académico
    </td>
  </tr>
</table>
