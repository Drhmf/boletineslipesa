import { fetchJSON, postJSON, putJSON, deleteReq } from './helpers.js';
import { escapeHtml } from '../../assets/utils.js';

const jwt = localStorage.getItem('jwt');
if (!jwt) { window.location.href = '/admin/index.php'; }

const tblBody = document.querySelector('#tblStudents tbody');
const btnAdd  = document.getElementById('btnAdd');

function loadStudents() {
  fetchJSON('/api/students.php', jwt).then(list => {
    tblBody.innerHTML = list.map(r => rowHtml(r)).join('');
  });
}

function rowHtml(s) {
  return `
    <tr>
      <td class="border px-1 py-0.5 text-sm">${escapeHtml(s.sigerd_id)}</td>
      <td class="border px-1 py-0.5 text-sm">${escapeHtml(s.nombres)} ${escapeHtml(s.apellidos)}</td>
      <td class="border px-1 py-0.5 text-sm">${escapeHtml(s.modalidad)}</td>
      <td class="border px-1 py-0.5 text-sm">${escapeHtml(s.grado)}</td>
      <td class="border px-1 py-0.5 text-sm">${escapeHtml(s.seccion)}</td>
      <td class="border px-1 py-0.5 text-sm">
        <button class="text-blue-600" data-edit="${s.sigerd_id}">Editar</button>
        <button class="text-red-600 ml-2" data-del="${s.sigerd_id}">Eliminar</button>
      </td>
    </tr>
  `;
}

tblBody.addEventListener('click', e => {
  const id = e.target.dataset.edit || e.target.dataset.del;
  if (!id) return;

  if (e.target.dataset.del) {
    if (confirm('¿Eliminar estudiante?')) {
      deleteReq(`/api/students.php?sigerd_id=${id}`, jwt).then(loadStudents);
    }
  } else {
    alert('Función editar pendiente.'); // Se puede ampliar luego
  }
});

btnAdd.addEventListener('click', () => {
  alert('Función crear estudiante pendiente.'); // Placeholder
});

loadStudents();
