import { getStatusMessage } from './utils.js';
import { escapeHtml } from '../../assets/utils.js';

const container = document.getElementById('bulletinContainer');
const jwt = localStorage.getItem('jwt');

if (!jwt) {
  container.innerHTML =
    '<p class="text-center text-red-500">Sesión no iniciada. Vuelva al inicio.</p>';
} else {
  fetch('/api/get_bulletin.php', {
    headers: { Authorization: `Bearer ${jwt}` }
  })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
      if (!ok) throw new Error(data.error || 'Error al obtener el boletín');
      renderBulletin(data);
    })
    .catch(err => {
      container.innerHTML =
        `<p class="text-center text-red-500">${escapeHtml(err.message)}</p>`;
    });
}

function renderBulletin(info) {
  const { center, student, subjects, status, signatories } = info;

  /* ---------- Encabezado ---------- */
  const header = /*html*/ `
    <header class="text-center mb-6">
      <h1 class="text-2xl font-bold">${escapeHtml(center.name)}</h1>
      <p class="text-sm">Código: ${escapeHtml(center.code)}</p>
      <p class="text-sm mb-4">${escapeHtml(center.address)}</p>

      <h2 class="text-xl font-semibold mt-4">Boletín de Calificaciones</h2>
      <p class="mt-2"><strong>Estudiante:</strong> ${escapeHtml(student.nombres)} ${escapeHtml(student.apellidos)}</p>
      <p><strong>ID SIGERD:</strong> ${escapeHtml(student.sigerd_id)}</p>
      <p><strong>Modalidad:</strong> ${escapeHtml(student.modalidad)}</p>
      <p><strong>Grado y Sección:</strong> ${escapeHtml(student.grado)} — ${escapeHtml(student.seccion)}</p>
    </header>
  `;

  /* ---------- Tabla de calificaciones ---------- */
  const rows = subjects.map(s => {
    const finalClass = s.final >= 70 ? 'text-green-600' : 'text-red-600';

    const periodCells = [1, 2, 3, 4].map(p => {
      const note = s.periodos[p] ?? '';
      if (note === '') return `<td class="border px-2 py-1 text-center"></td>`;
      const cls = note >= 70 ? 'text-green-600' : 'text-red-600';
      return `<td class="border px-2 py-1 text-center ${cls}">${note}</td>`;
    }).join('');

    return `
      <tr>
        <td class="border px-2 py-1">${escapeHtml(s.asignatura)}</td>
        ${periodCells}
        <td class="border px-2 py-1 text-center font-semibold ${finalClass}">
          ${s.final}
        </td>
      </tr>
    `;
  }).join('');

  const table = /*html*/ `
    <table class="w-full border-collapse mb-4">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-2 py-1">Asignatura</th>
          <th class="border px-2 py-1">P1</th>
          <th class="border px-2 py-1">P2</th>
          <th class="border px-2 py-1">P3</th>
          <th class="border px-2 py-1">P4</th>
          <th class="border px-2 py-1">Final</th>
        </tr>
      </thead>
      <tbody>${rows}</tbody>
    </table>
  `;

  /* ---------- Estado ---------- */
  const statusHtml =
    `<p class="text-center font-semibold mb-6">${getStatusMessage(status)}</p>`;

  /* ---------- Firmas ---------- */
  const footer = /*html*/ `
    <div class="flex justify-around mt-8 text-center">
      <div>
        ___________________________<br>
        ${escapeHtml(signatories.director)}<br>
        <span class="font-semibold">Directora</span>
      </div>
      <div>
        ___________________________<br>
        ${escapeHtml(signatories.registrar)}<br>
        <span class="font-semibold">Registro & Control Académico</span>
      </div>
    </div>
  `;

  container.innerHTML = header + table + statusHtml + footer;
}
