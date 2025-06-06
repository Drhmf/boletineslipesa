const jwt = localStorage.getItem('jwt');
if (!jwt) {
  alert('Sesión expirada. Inicie sesión de nuevo.');
  window.location.href = '/admin/index.php';
}

fetch('/api/dashboard_stats.php', {
  headers: { Authorization: `Bearer ${jwt}` }
})
  .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
  .then(({ ok, data }) => {
    if (!ok) throw new Error(data.error || 'Error al obtener estadísticas');
    renderCharts(data);
  })
  .catch(err => {
    console.error(err);
    alert(err.message);
  });

function renderCharts(stats) {
  /* ---------- Pie: pass / fail ---------- */
  const pf = stats.pass_fail;
  new Chart(document.getElementById('piePassFail'), {
    type: 'pie',
    data: {
      labels: ['Aprobadas', 'Reprobadas'],
      datasets: [{
        data: [pf.pass_count, pf.fail_count],
      }]
    }
  });

  /* ---------- Bar: students per grade ---------- */
  const grades = stats.students_grade;
  new Chart(document.getElementById('barStudentsGrade'), {
    type: 'bar',
    data: {
      labels: grades.map(g => g.grade),
      datasets: [{
        label: 'Estudiantes',
        data: grades.map(g => g.total),
      }]
    }
  });

  /* ---------- Line: avg per period ---------- */
  const avg = stats.avg_period;
  new Chart(document.getElementById('lineAvgPeriod'), {
    type: 'line',
    data: {
      labels: avg.map(a => `P${a.periodo ?? a.period}`),
      datasets: [{
        label: 'Promedio',
        data: avg.map(a => a.avg_grade),
        tension: 0.3
      }]
    }
  });
}
