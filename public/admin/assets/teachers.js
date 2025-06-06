import { fetchJSON, deleteReq } from './helpers.js';

const jwt = localStorage.getItem('jwt');
if (!jwt) { window.location.href = '/admin/index.php'; }

const tblBody = document.querySelector('#tblTeachers tbody');
const btnAdd  = document.getElementById('btnAdd');

function loadTeachers() {
  fetchJSON('/api/teachers.php', jwt).then(list => {
    tblBody.innerHTML = list.map(r => rowHtml(r)).join('');
  });
}

function rowHtml(t) {
  return `
    <tr>
      <td class="border px-1 py-0.5 text-sm">${t.nombres} ${t.apellidos}</td>
      <td class="border px-1 py-0.5 text-sm">${t.cedula}</td>
      <td class="border px-1 py-0.5 text-sm">${t.telefono ?? ''}</td>
      <td class="border px-1 py-0.5 text-sm">${t.asignatura}</td>
      <td class="border px-1 py-0.5 text-sm">${t.grado ?? '-'}</td>
      <td class="border px-1 py-0.5 text-sm">
        <button class="text-blue-600" data-edit="${t.id}">Editar</button>
        <button class="text-red-600 ml-2" data-del="${t.id}">Eliminar</button>
      </td>
    </tr>
  `;
}

tblBody.addEventListener('click', e => {
  const id = e.target.dataset.edit || e.target.dataset.del;
  if (!id) return;

  if (e.target.dataset.del) {
    if (confirm('¿Eliminar docente?')) {
      deleteReq(`/api/teachers.php?id=${id}`, jwt).then(loadTeachers);
    }
  } else {
    alert('Función editar pendiente.');
  }
});

btnAdd.addEventListener('click', () => {
  alert('Función crear docente pendiente.');
});

loadTeachers();
