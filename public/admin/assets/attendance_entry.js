import { fetchJSON, postJSON } from './helpers.js';

const jwt = localStorage.getItem('jwt');
if (!jwt) { window.location.href = '/admin/index.php'; }

const selModalidad = document.getElementById('selModalidad');
const selGrado     = document.getElementById('selGrado');
const selSeccion   = document.getElementById('selSeccion');
const selAsignatura= document.getElementById('selAsignatura');
const selPeriodo   = document.getElementById('selPeriodo');
const btnCargar    = document.getElementById('btnCargar');
const tableSection = document.getElementById('tableSection');
const studentRows  = document.getElementById('studentRows');
const attendanceForm = document.getElementById('attendanceForm');

let options = {};

function fetchOptions() {
  return fetchJSON('/api/list_options.php', jwt).then(d => {
    options = d;
    populateSelects();
  });
}

function populateSelects() {
  selModalidad.innerHTML = '<option value="">--Seleccione--</option>' +
    options.modalidades.map(m => `<option value="${m.id}">${m.nombre}</option>`).join('');
  selModalidad.addEventListener('change', handleModalidadChange);
  selGrado.addEventListener('change', handleGradoChange);
  handleModalidadChange();
}

function handleModalidadChange() {
  const modId = parseInt(selModalidad.value);
  selGrado.innerHTML = '<option value="">--Seleccione--</option>';
  if (!modId) return;
  const grades = options.grados.filter(g => g.modalidad_id === modId);
  selGrado.innerHTML += grades.map(g => `<option value="${g.id}">${g.nombre}</option>`).join('');
  handleGradoChange();
}

function handleGradoChange() {
  const gradeId = parseInt(selGrado.value);
  selSeccion.innerHTML = '<option value="">--Seleccione--</option>';
  if (!gradeId) return;
  const secs = options.secciones.filter(s => s.grado_id === gradeId);
  selSeccion.innerHTML += secs.map(s => `<option value="${s.id}">${s.nombre}</option>`).join('');
}

function loadAsignaturas() {
  selAsignatura.innerHTML = '<option value="">--Seleccione--</option>' +
    options.asignaturas.map(a => `<option value="${a.id}">${a.nombre}</option>`).join('');
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

  fetchJSON(`/api/attendance_list.php?modality_id=${modId}&grade_id=${gradeId}&section_id=${secId}&subject_id=${subjId}&period=${period}`, jwt)
    .then(renderList)
    .catch(err => alert(err.message));
});

function renderList(list) {
  if (!list.length) { alert('Sin estudiantes'); return; }
  studentRows.innerHTML = list.map(l => `
    <tr>
      <td class="border px-1 py-0.5 text-sm">${l.sigerd_id}</td>
      <td class="border px-1 py-0.5 text-sm">${l.nombres} ${l.apellidos}</td>
      <td class="border px-1 py-0.5 text-sm">
        <input type="number" min="0" max="100" step="0.01" value="${l.porcentaje ?? ''}"
               class="w-24 border rounded p-1 text-center" />
      </td>
    </tr>
  `).join('');
  tableSection.classList.remove('hidden');
}

attendanceForm.addEventListener('submit', e => {
  e.preventDefault();
  const subjId = selAsignatura.value;
  const period = selPeriodo.value;
  const records = Array.from(studentRows.children).map(tr => ({
    sigerd_id: tr.children[0].textContent,
    porcentaje: parseFloat(tr.children[2].querySelector('input').value)
  }));
  postJSON('/api/save_attendance.php', { subject_id: subjId, period: period, records }, jwt)
    .then(() => alert('Asistencias guardadas'))
    .catch(err => alert(err.message));
});

fetchOptions().then(loadAsignaturas);
