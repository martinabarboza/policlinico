<?php

class TutorModel extends Model
{
    /**
     * Busca por nombre, documento o teléfono; limita la lista a 50 coincidencias.
     *
     * @return list<array{id: int, nombre: string, documento: string, telefono: string|null, email: string|null}>
     */
    public function buscar(string $texto): array
    {
        $termino = '%' . $texto . '%';
        $stmt = $this->db->prepare(
            "SELECT
                id_Tutor AS id,
                nombre_Tutor AS nombre,
                documento_Tutor AS documento,
                telefono_Tutor AS telefono,
                email_Tutor AS email
             FROM TUTOR
             WHERE nombre_Tutor LIKE ?
                OR documento_Tutor LIKE ?
                OR telefono_Tutor LIKE ?
             ORDER BY nombre_Tutor ASC
             LIMIT 50"
        );
        $stmt->bind_param('sss', $termino, $termino, $termino);
        $stmt->execute();

        $tutores = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $tutores;
    }
}
