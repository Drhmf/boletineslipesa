<?php
namespace App\Services;

use App\Config\Database;
use App\Config\Constants;
use PDO;

/**
 * Servicio para armar el boletín de un estudiante.
 */
class BulletinService
{
    /**
     * @throws \RuntimeException si el estudiante no existe.
     */
    public static function getBulletin(string $estudianteId): array
    {
        $db = Database::getConnection();

        /* ---------- Datos del estudiante ---------- */
        $stmt = $db->prepare(
            'SELECT e.sigerd_id,
                    e.nombres,
                    e.apellidos,
                    g.nombre    AS grado,
                    s.nombre    AS seccion,
                    m.nombre    AS modalidad
             FROM   estudiantes e
             JOIN   grados      g ON g.id = e.grado_id
             JOIN   secciones   s ON s.id = e.seccion_id
             JOIN   modalidades m ON m.id = e.modalidad_id
             WHERE  e.sigerd_id = :id'
        );
        $stmt->execute([':id' => $estudianteId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            throw new \RuntimeException('Estudiante no encontrado');
        }

        /* ---------- Calificaciones ---------- */
        $stmt = $db->prepare(
            'SELECT a.id AS asignatura_id,
                    a.nombre AS asignatura,
                    c.periodo,
                    ROUND(AVG(
                        CASE
                            WHEN c.rp_nota IS NOT NULL
                                THEN GREATEST(c.nota, c.rp_nota)
                            ELSE c.nota
                        END
                    ), 2) AS nota
             FROM   calificaciones c
             JOIN   asignaturas a ON a.id = c.asignatura_id
             WHERE  c.estudiante_id = :id
             GROUP  BY a.id, c.periodo
             ORDER  BY a.nombre, c.periodo'
        );
        $stmt->execute([':id' => $estudianteId]);

        $subjects = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sid = $row['asignatura_id'];
            if (!isset($subjects[$sid])) {
                $subjects[$sid] = [
                    'asignatura' => $row['asignatura'],
                    'periodos'   => [],
                    'final'      => null,
                ];
            }
            $subjects[$sid]['periodos'][(int)$row['periodo']] = (float)$row['nota'];
        }

        /* Promedio final por asignatura */
        foreach ($subjects as &$sub) {
            $cnt = count($sub['periodos']);
            $sub['final'] = $cnt ? round(array_sum($sub['periodos']) / $cnt, 2) : null;
        }
        unset($sub);

        /* Filtrar asignaturas sin nota y re-indexar */
        $subjects = array_values(array_filter($subjects, fn($s) => $s['final'] !== null));

        /* Conteo de reprobadas */
        $failed = array_reduce(
            $subjects,
            fn($carry, $s) => $carry + (($s['final'] < Constants::MIN_PASS_GRADE) ? 1 : 0),
            0
        );

        $status = match (true) {
            $failed >= 3                    => 'Reprobado: repite el grado',
            $failed >= 1 && $failed <= 2    => 'Promovido con asignaturas pendientes',
            default                         => 'Aprobado',
        };

        return [
            'center'          => [
                'name'    => Constants::CENTER_NAME,
                'code'    => Constants::CENTER_CODE,
                'address' => Constants::CENTER_ADDRESS,
            ],
            'student'         => $student,
            'subjects'        => $subjects,
            'failed_subjects' => $failed,
            'status'          => $status,
            'signatories'     => [
                'director'  => Constants::DIRECTOR_NAME,
                'registrar' => Constants::REGISTRAR_NAME,
            ],
        ];
    }
}
