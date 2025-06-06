<?php
namespace App\Services;

use App\Config\Database;
use PDO;

class DashboardService
{
    /**
     * Devuelve estadísticas globales para el dashboard administrativo.
     */
    public static function getStats(): array
    {
        $db = Database::getConnection();

        /* ---------- Totales simples ---------- */
        $totalStudents  = (int)$db->query('SELECT COUNT(*) FROM estudiantes')->fetchColumn();
        $totalSubjects  = (int)$db->query('SELECT COUNT(*) FROM asignaturas')->fetchColumn();

        /* ---------- Aprobados / Reprobados ---------- */
        $passFail = $db->query(
            "SELECT
                SUM(CASE WHEN t.final >= 70 THEN 1 ELSE 0 END) AS pass_count,
                SUM(CASE WHEN t.final  < 70 THEN 1 ELSE 0 END) AS fail_count
             FROM (
                SELECT estudiante_id,
                       asignatura_id,
                       ROUND(AVG(CASE
                             WHEN rp_nota IS NOT NULL
                                 THEN GREATEST(nota, rp_nota)
                             ELSE nota END), 2) AS final
                FROM   calificaciones
                GROUP  BY estudiante_id, asignatura_id
             ) t"
        )->fetch(PDO::FETCH_ASSOC);

        /* ---------- Estudiantes por grado ---------- */
        $byGrade = $db->query(
            "SELECT g.nombre AS grade, COUNT(*) AS total
             FROM   estudiantes e
             JOIN   grados g ON g.id = e.grado_id
             GROUP  BY g.nombre
             ORDER  BY g.nombre"
        )->fetchAll(PDO::FETCH_ASSOC);

        /* ---------- Promedio por periodo ---------- */
        $avgByPeriod = $db->query(
            "SELECT periodo,
                    ROUND(AVG(CASE
                          WHEN rp_nota IS NOT NULL
                             THEN GREATEST(nota, rp_nota)
                          ELSE nota END), 2) AS avg_grade
             FROM   calificaciones
             GROUP  BY periodo
             ORDER  BY periodo"
        )->fetchAll(PDO::FETCH_ASSOC);

        return [
            'totals' => [
                'students' => $totalStudents,
                'subjects' => $totalSubjects,
            ],
            'pass_fail'      => $passFail,
            'students_grade' => $byGrade,
            'avg_period'     => $avgByPeriod,
        ];
    }
}
