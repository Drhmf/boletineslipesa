import { escapeHtml } from '../../assets/utils.js';

const jwt = localStorage.getItem('jwt');
if (!jwt) {
  alert('Sesión expirada. Inicie sesión de nuevo.');
  window.location.href = '/admin/index.php';
}

const selModalidad = document.getElementById('selModalidad');
const selGrado     = document.getElementById('selGrado');
const selSeccion   = document.getElementById('selSeccion');
const selAsignatura= document.getElementById('selAsignatura');
const selPeriodo   = document.getElementById('selPeriodo');
const btnCargar    = document.getElementById('btnCargar');
const tableSection = document.getElementById('tableSection');
const studentRows  = document.getElementById('studentRows');
const gradeForm    = document.getElementById('gradeForm');

let options = {};

function fetchOptions() {
  return fetch('/api/list_options.php', {
    headers: { Authorization: `Bearer ${jwt}` }
  })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
      if (!ok) throw new Error(data.error || 'No se pudieron cargar opciones');
      options = data;
      populateSelects();
    });
}

function populateSelects() {
  // Modalidades
  selModalidad.innerHTML = '<option value="">--Seleccione--</option>' +
    options.modalidades.map(m => `<option value="${m.id}">${escapeHtml(m.nombre)}</option>`).join('');

  // Events
  selModalidad.addEventListener('change', handleModalidadChange);
  selGrado.addEventListener('change', handleGradoChange);
  handleModalidadChange();
}

function handleModalidadChange() {
  const modId = parseInt(selModalidad.value);
  selGrado.innerHTML = '<option value="">--Seleccione--</option>';
  if (!modId) return;

  const grades = options.grados.filter(g => g.modalidad_id === modId);
  selGrado.innerHTML += grades.map(g => `<option value="${g.id}">${escapeHtml(g.nombre)}</option>`).join('');
  handleGradoChange();
}

function handleGradoChange() {
  const gradeId = parseInt(selGrado.value);
  selSeccion.innerHTML = '<option value="">--Seleccione--</option>';
  if (!gradeId) return;

  const secs = options.secciones.filter(s => s.grado_id === gradeId);
  selSeccion.innerHTML += secs.map(s => `<option value="${s.id}">${escapeHtml(s.nombre)}</option>`).join('');
}

// Asignaturas sin filtro adicional por ahora
function loadAsignaturas() {
  selAsignatura.innerHTML = '<option value="">--Seleccione--</option>' +
    options.asignaturas.map(a => `<option value="${a.id}">${escapeHtml(a.nombre)}</option>`).join('');
}

btnCargar.addEventListener('click', () => {
  const modId = selModalidad.value;
  const gradeId = selGrado.value;
  const secId = selSeccion.value;
  const subjId = selAsignatura.value;
  const period = selPeriodo.value;

  if (!modId || !gradeId || !secId || !subjId || !period) {
    alert('Complete todos los filtros.');
    return;
  }

  fetch(`/api/list_students.php?modality_id=${modId}&grade_id=${gradeId}&section_id=${secId}&subject_id=${subjId}&period=${period}`, {
    headers: { Authorization: `Bearer ${jwt}` }
  })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
      if (!ok) throw new Error(data.error || 'Error al listar estudiantes');
      renderStudents(data);
    })
    .catch(err => alert(err.message));
});

function renderStudents(students) {
  if (!students.length) {
    alert('No se encontraron estudiantes.');
    return;
  }
  studentRows.innerHTML = students.map(s => studentRowHtml(s)).join('');
  tableSection.classList.remove('hidden');
}

function studentRowHtml(s) {
  const makeInput = (name, val='') => 
    `<input type="number" min="0" max="100" step="0.01" name="${name}" value="${val ?? ''}"
            class="w-20 border rounded p-1 text-center text-sm">`;
  return `
    <tr>
      <td class="border px-1 py-0.5 text-sm">${escapeHtml(s.sigerd_id)}</td>
      <td class="border px-1 py-0.5 text-sm">${escapeHtml(s.nombres)} ${escapeHtml(s.apellidos)}</td>
      <td class="border px-1 py-0.5">${makeInput('c1', s.c1)}</td>
      <td class="border px-1 py-0.5">${makeInput('rp1', s.rp1)}</td>
      <td class="border px-1 py-0.5">${makeInput('c2', s.c2)}</td>
      <td class="border px-1 py-0.5">${makeInput('rp2', s.rp2)}</td>
      <td class="border px-1 py-0.5">${makeInput('c3', s.c3)}</td>
      <td class="border px-1 py-0.5">${makeInput('rp3', s.rp3)}</td>
      <td class="border px-1 py-0.5">${makeInput('c4', s.c4)}</td>
      <td class="border px-1 py-0.5">${makeInput('rp4', s.rp4)}</td>
    </tr>
  `;
}

gradeForm.addEventListener('submit', e => {
  e.preventDefault();

  const subjId = selAsignatura.value;
  const period = selPeriodo.value;

  const grades = Array.from(studentRows.children).map(tr => {
    const cells = tr.children;
    const sigerd = cells[0].textContent;
    const inputs = tr.querySelectorAll('input');
    const c = [inputs[0].value, inputs[2].value, inputs[4].value, inputs[6].value].map(v => v ? parseFloat(v) : null);
    const rp= [inputs[1].value, inputs[3].value, inputs[5].value, inputs[7].value].map(v => v ? parseFloat(v) : null);
    return { sigerd_id: sigerd, competencies: c, rp: rp };
  });

  fetch('/api/save_grades.php', {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${jwt}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ subject_id: subjId, period: period, grades: grades })
  })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
      if (!ok) throw new Error(data.error || 'Error al guardar calificaciones');
      alert('Calificaciones guardadas correctamente.');
    })
    .catch(err => alert(err.message));
});

fetchOptions().then(loadAsignaturas);
