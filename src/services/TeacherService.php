<?php
namespace App\Services;

use App\Config\Database;
use PDO;

class TeacherService
{
    public static function list(): array
    {
        $db = Database::getConnection();
        return $db->query(
            'SELECT d.id, d.nombres, d.apellidos, d.cedula, d.telefono,
                    a.nombre AS asignatura, g.nombre AS grado
             FROM docentes d
             JOIN asignaturas a ON a.id = d.asignatura_id
             LEFT JOIN grados g ON g.id = d.grado_id
             ORDER BY d.apellidos, d.nombres'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): void
    {
        $sql = 'INSERT INTO docentes
                    (nombres, apellidos, cedula, telefono, asignatura_id, grado_id)
                VALUES
                    (:nombres, :apellidos, :cedula, :telefono, :asignatura, :grado)';
        Database::getConnection()->prepare($sql)->execute([
            ':nombres'    => $data['nombres'],
            ':apellidos'  => $data['apellidos'],
            ':cedula'     => $data['cedula'],
            ':telefono'   => $data['telefono'],
            ':asignatura' => $data['asignatura_id'],
            ':grado'      => $data['grado_id'] ?: null,
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $sql = 'UPDATE docentes SET
                    nombres = :nombres, apellidos = :apellidos,
                    cedula = :cedula, telefono = :telefono,
                    asignatura_id = :asignatura, grado_id = :grado
                WHERE id = :id';
        Database::getConnection()->prepare($sql)->execute([
            ':nombres'    => $data['nombres'],
            ':apellidos'  => $data['apellidos'],
            ':cedula'     => $data['cedula'],
            ':telefono'   => $data['telefono'],
            ':asignatura' => $data['asignatura_id'],
            ':grado'      => $data['grado_id'] ?: null,
            ':id'         => $id,
        ]);
    }

    public static function delete(int $id): void
    {
        Database::getConnection()->prepare(
            'DELETE FROM docentes WHERE id = :id'
        )->execute([':id' => $id]);
    }
}
