<?php
namespace App\Services;

use App\Config\Database;
use PDO;

class GradeService
{
    /**
     * Lista estudiantes y sus calificaciones (si existen) para un filtro dado.
     */
    public static function listStudents(
        int $modalityId,
        int $gradeId,
        int $sectionId,
        int $subjectId,
        int $period
    ): array {
        $db  = Database::getConnection();
        $sql = "
            SELECT
                e.sigerd_id,
                e.nombres,
                e.apellidos,
                MAX(CASE WHEN c.competencia_id = 1 THEN c.nota END)  AS c1,
                MAX(CASE WHEN c.competencia_id = 1 THEN c.rp_nota END) AS rp1,
                MAX(CASE WHEN c.competencia_id = 2 THEN c.nota END)  AS c2,
                MAX(CASE WHEN c.competencia_id = 2 THEN c.rp_nota END) AS rp2,
                MAX(CASE WHEN c.competencia_id = 3 THEN c.nota END)  AS c3,
                MAX(CASE WHEN c.competencia_id = 3 THEN c.rp_nota END) AS rp3,
                MAX(CASE WHEN c.competencia_id = 4 THEN c.nota END)  AS c4,
                MAX(CASE WHEN c.competencia_id = 4 THEN c.rp_nota END) AS rp4
            FROM estudiantes e
            LEFT JOIN calificaciones c
              ON  c.estudiante_id = e.sigerd_id
              AND c.asignatura_id = :subject_id
              AND c.periodo       = :period
            WHERE e.modalidad_id = :modality_id
              AND e.grado_id      = :grade_id
              AND e.seccion_id    = :section_id
            GROUP BY e.sigerd_id, e.nombres, e.apellidos
            ORDER BY e.apellidos, e.nombres
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':subject_id'  => $subjectId,
            ':period'      => $period,
            ':modality_id' => $modalityId,
            ':grade_id'    => $gradeId,
            ':section_id'  => $sectionId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Guarda calificaciones en lote. $grades es arreglo:
     * [
     *   ['sigerd_id' => '123', 'competencies' => [70,80,60,90], 'rp' => [null,null,75,null]],
     *   ...
     * ]
     */
    public static function saveGrades(
        int $subjectId,
        int $period,
        array $grades
    ): void {
        $db = Database::getConnection();
        $db->beginTransaction();

        $sql = "
            INSERT INTO calificaciones
                (estudiante_id, asignatura_id, competencia_id, periodo, nota, rp_nota)
            VALUES
                (:sid, :subject_id, :comp_id, :period, :nota, :rp_nota)
            ON DUPLICATE KEY UPDATE
                nota = VALUES(nota),
                rp_nota = VALUES(rp_nota)
        ";
        $stmt = $db->prepare($sql);

        foreach ($grades as $g) {
            $sid    = $g['sigerd_id'];
            $notes  = $g['competencies'];
            $rps    = $g['rp'] ?? [None, None, None, None];

            for ($i = 0; $i < 4; $i++) {
                $stmt->execute([
                    ':sid'         => $sid,
                    ':subject_id'  => $subjectId,
                    ':comp_id'     => $i + 1,
                    ':period'      => $period,
                    ':nota'        => $notes[$i] ?? null,
                    ':rp_nota'     => $rps[$i] ?? null,
                ]);
            }
        }

        $db->commit();
    }
}
