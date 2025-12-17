<?php
namespace Dao;

class EstudiantesCC extends Table
{
    public static function getEstudiantes(
        string $partialNombre = "",
        string $especialidad = "",
        string $orderBy = "",
        bool $orderDescending = false,
        int $page = 0,
        int $itemsPerPage = 10
    ) {
        $sql = "SELECT id_estudiante, nombre, apellido, edad, especialidad FROM EstudianteCienciasComputacionales";
        $sqlCount = "SELECT COUNT(*) as count FROM EstudianteCienciasComputacionales";
        $conditions = [];
        $params = [];

        if ($partialNombre !== "") {
            $conditions[] = "nombre LIKE :nombre";
            $params["nombre"] = "%$partialNombre%";
        }

        if ($especialidad !== "") {
            $conditions[] = "especialidad = :especialidad";
            $params["especialidad"] = $especialidad;
        }

        if ($conditions) {
            $where = " WHERE " . implode(" AND ", $conditions);
            $sql .= $where;
            $sqlCount .= $where;
        }

        if (in_array($orderBy, ["nombre", "apellido", "edad", "especialidad"])) {
            $sql .= " ORDER BY $orderBy";
            if ($orderDescending) $sql .= " DESC";
        }

        $total = self::obtenerUnRegistro($sqlCount, $params)["count"];
        $sql .= " LIMIT " . ($page * $itemsPerPage) . ", $itemsPerPage";

        return [
            "estudiantes" => self::obtenerRegistros($sql, $params),
            "total" => $total
        ];
    }

    public static function getById(int $id)
    {
        return self::obtenerUnRegistro(
            "SELECT * FROM EstudianteCienciasComputacionales WHERE id_estudiante = :id",
            ["id" => $id]
        );
    }

    public static function insert(string $nombre, string $apellido, int $edad, string $especialidad)
    {
        return self::executeNonQuery(
            "INSERT INTO EstudianteCienciasComputacionales 
             (nombre, apellido, edad, especialidad)
             VALUES (:nombre, :apellido, :edad, :especialidad)",
            compact("nombre", "apellido", "edad", "especialidad")
        );
    }

    public static function update(int $id, string $nombre, string $apellido, int $edad, string $especialidad)
    {
        return self::executeNonQuery(
            "UPDATE EstudianteCienciasComputacionales
             SET nombre=:nombre, apellido=:apellido, edad=:edad, especialidad=:especialidad
             WHERE id_estudiante=:id",
            compact("id", "nombre", "apellido", "edad", "especialidad")
        );
    }

    public static function delete(int $id)
    {
        return self::executeNonQuery(
            "DELETE FROM EstudianteCienciasComputacionales WHERE id_estudiante=:id",
            ["id"=>$id]
        );
    }
}
