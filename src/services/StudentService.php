<?php
namespace App\Services;

use App\Config\Database;
use PDO;

class StudentService
{
    public static function list(): array
    {
        $db = Database::getConnection();
        return $db->query(
            'SELECT e.sigerd_id, e.nombres, e.apellidos, e.fecha_nacimiento,
                    m.nombre AS modalidad, g.nombre AS grado, s.nombre AS seccion
             FROM estudiantes e
             JOIN modalidades m ON m.id = e.modalidad_id
             JOIN grados g      ON g.id = e.grado_id
             JOIN secciones s   ON s.id = e.seccion_id
             ORDER BY e.apellidos, e.nombres'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): void
    {
        $sql = 'INSERT INTO estudiantes
                    (sigerd_id, nombres, apellidos, fecha_nacimiento,
                     modalidad_id, grado_id, seccion_id)
                VALUES
                    (:sigerd, :nombres, :apellidos, :fecha,
                     :modalidad, :grado, :seccion)';
        Database::getConnection()->prepare($sql)->execute([
            ':sigerd'    => $data['sigerd_id'],
            ':nombres'   => $data['nombres'],
            ':apellidos' => $data['apellidos'],
            ':fecha'     => $data['fecha_nacimiento'],
            ':modalidad' => $data['modalidad_id'],
            ':grado'     => $data['grado_id'],
            ':seccion'   => $data['seccion_id'],
        ]);
    }

    public static function update(string $sigerdId, array $data): void
    {
        $sql = 'UPDATE estudiantes SET
                    nombres = :nombres, apellidos = :apellidos,
                    fecha_nacimiento = :fecha,
                    modalidad_id = :modalidad,
                    grado_id = :grado,
                    seccion_id = :seccion
                WHERE sigerd_id = :sigerd';
        Database::getConnection()->prepare($sql)->execute([
            ':nombres'   => $data['nombres'],
            ':apellidos' => $data['apellidos'],
            ':fecha'     => $data['fecha_nacimiento'],
            ':modalidad' => $data['modalidad_id'],
            ':grado'     => $data['grado_id'],
            ':seccion'   => $data['seccion_id'],
            ':sigerd'    => $sigerdId,
        ]);
    }

    public static function delete(string $sigerdId): void
    {
        Database::getConnection()->prepare(
            'DELETE FROM estudiantes WHERE sigerd_id = :id'
        )->execute([':id' => $sigerdId]);
    }
}
