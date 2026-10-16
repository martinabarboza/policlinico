<?php

class PacienteModel extends Model
{
    /**
     * Busca por nombre o chip; limita la lista a 50 coincidencias.
     *
     * @return list<array{id: int, nombre: string, chip: string|null, tutor: string}>
     */
    public function buscar(string $texto): array
    {
        $termino = '%' . $texto . '%';
        $stmt = $this->db->prepare(
            "SELECT
                p.id_Paciente AS id,
                p.nombre_Paciente AS nombre,
                p.numchip_Paciente AS chip,
                t.nombre_Tutor AS tutor
             FROM PACIENTE p
             INNER JOIN TUTOR t ON t.id_Tutor = p.id_Tutor
             WHERE p.nombre_Paciente LIKE ?
                OR p.numchip_Paciente LIKE ?
             ORDER BY p.nombre_Paciente ASC
             LIMIT 50"
        );
        $stmt->bind_param('ss', $termino, $termino);
        $stmt->execute();

        $pacientes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $pacientes;
    }
}
