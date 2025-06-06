/**
 * Devuelve etiqueta HTML según el estado global del estudiante.
 */
export function getStatusMessage(status) {
  switch (status) {
    case 'Reprobado: repite el grado':
      return 'Estado: <span class="text-red-600 font-bold">Reprobado — repite el grado</span>';
    case 'Promovido con asignaturas pendientes':
      return 'Estado: <span class="text-yellow-600 font-bold">Promovido con asignaturas pendientes</span>';
    default:
      return 'Estado: <span class="text-green-600 font-bold">Aprobado</span>';
  }
}
