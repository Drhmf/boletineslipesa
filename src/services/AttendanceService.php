<?php
namespace App\Services;

use App\Config\Database;
use PDO;

class AttendanceService
{
    public static function list(
        int $modalityId, int $gradeId, int $sectionId, int $subjectId, int $period
    ): array {
        $db = Database::getConnection();
        $sql = '
            SELECT e.sigerd_id,
                   e.nombres,
                   e.apellidos,
                   a.porcentaje
            FROM   estudiantes e
            LEFT JOIN asistencias a
              ON  a.estudiante_id = e.sigerd_id
              AND a.asignatura_id = :subject
              AND a.periodo       = :period
            WHERE  e.modalidad_id = :modality
              AND  e.grado_id     = :grade
              AND  e.seccion_id   = :section
            ORDER BY e.apellidos, e.nombres';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':subject'  => $subjectId,
            ':period'   => $period,
            ':modality' => $modalityId,
            ':grade'    => $gradeId,
            ':section'  => $sectionId,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function save(int $subjectId, int $period, array $records): void
    {
        $db = Database::getConnection();
        $db->beginTransaction();
        $sql = '
            INSERT INTO asistencias
                (estudiante_id, asignatura_id, periodo, porcentaje)
            VALUES
                (:sid, :subject, :period, :percent)
            ON DUPLICATE KEY UPDATE
                porcentaje = VALUES(porcentaje)';
        $stmt = $db->prepare($sql);

        foreach ($records as $r) {
            $stmt->execute([
                ':sid'     => $r['sigerd_id'],
                ':subject' => $subjectId,
                ':period'  => $period,
                ':percent' => $r['porcentaje'],
            ]);
        }
        $db->commit();
    }
}
